<?php
class DatabaseAction extends Action {
    public function index(){
        $data=$this->_post('query');
        if(!empty($data)){
            $data=queryFilter($data);
            $type="query";
        }
        $M= M('Backup');
        $select=$M->where($data)->join()->order();
        R('Comm/Comm/page',array($select,null,'null'));
    }
    public function backup(){
    	$br='\n';
    	$n=1;					//卷标
    	$signle=true;			//分表
    	$volume=true;			//是否分卷
    	$volume_size=2000000;	//分卷大小，默认2M
        $path = $this->GeneratePath();
        $result = array();
        $this->config=null;
        $this->config['volumes']=array();
        $this->config['path']=$path;
        $this->config['signle']=$signle;
        $this->config['volume']=$volume;
        $this->config['volume_size']=$volume_size;

        //Generate Database sql statement;
        $result .= $this->GenerateDatabaseSQL();

        //Generate Table sql statement;
        $result .= $this->GenerateTableSQL();

        //Generate View sql statement;
        $result .= $this->GenerateViewSQL();

        //Generate Producure sql statement;
        $result .= $this->GenerateProducureSQL();

        //Generate Tigger sql statement;
        $result .= $this->GenerateTriggerSQL();

        $this->writeFile($dbName,$result,true);

        $this->addRecord();
    }

    public function writeFile($file,&$data,$write){
    	if(empty($data)){return;}
    	$len=strlen($data);
    	if($len>=$size && $this->config['volume'] || $this->config['signle'] || $write){
            if($this->config['volume']){
    			$volume=$this->config['volumes'][$file];
    			$size=$this->config['volume_size'];
    			if($len >= $size){
    				if(empty($volume))
    					$volume=1;
                    else
                        $volume="_".$volume;
    			}
    			
	    	}
	    	$filename=$this->config['path'].'/'.$file.''.$volume.'.sql';
	    	file_put_contents ($filename, $data);
	    	$data=null;
	    	if($this->config['volume']){
                $this->config['volumes'][$file]=$this->config['volumes'][$file]+1;
            }
        }
    }

    public function addRecord(){
    	$M=M('backup');
    	$M->backup_time=get_time();
    	$M->path=$this->config['path'];
    	$M->desc=session('username');
    	$M->add();
    	$this->success('备份成功', U('Database/index'));
    }
    /**
     * @description 获取当前数据库的所有表名。
     * @static
     * @return array
     */
    public function getTables($type="BASE TABLE"){
        $dbName = C('DB_NAME');
        $sql = "SHOW FULL TABLES FROM `$dbName` WHERE Table_Type = '$type'";
        $col = "Tables_in_".$dbName;
        $data = M()->query($sql);

        foreach ($data as $row){
            $result[] = $row[$col];
        }
        return $result;
    }

    public function getColumns($table){
        $M = new Model();
        $data = $M->query("SHOW COLUMNS FROM `{$table}`;");
        $fields = array();
        foreach ($data as $k=>&$v){
            $fields[] = "`{$v['Field']}`";
        }
        return $fields;
    }
 	
 	public function GenerateDatabaseSQL(){
        $result = '';
        $dbName = C('DB_NAME');
        $data = M()->query("SHOW CREATE DATABASE `{$dbName}`");
        $result .= $this->dropSql($dbName,"DATABASE");
        $result .= $data[0]["Create Database"];
        //if($this->config['signle']){
        	$this->writeFile($dbName,$result);
        
    }

    public function GenerateTableSQL(){
        $result = '';
        $tables=$this->getTables();
        foreach ($tables as $table){
        	//$result = array();
            $data = M()->query("SHOW CREATE TABLE `{$table}`");
        	$result .= $this->dropSql($table,"TABLE").PHP_EOL;
        	$result .= $data[0]["Create Table"].';'.PHP_EOL;
        	$this->GenerateDataSQL($table,$result);
        	$this->writeFile($table,$result);
        }
        return $result;
    }

    /**
     * @description 导出SQL数据，但不包含表创建代码。
     * @static
     * @return string
     */
    public function GenerateDataSQL($table,&$result){
        $M = new Model();
        $result .= "TRUNCATE TABLE `{$table}`;".PHP_EOL;
        $data = $M->query("SHOW COLUMNS FROM `{$table}`;");
        $fields = array();
        foreach ($data as $k=>&$v){
            $fields[] = "`{$v['Field']}`";
        }
        $fields = implode($fields,",");
        $size=$this->config['volume_size'];
        $data=$M->query("select * from `{$table}`");
        foreach ($data as $k=>&$v){
            $values = array();
            foreach($v as $key=>$value)
            {
                if(is_numeric($value)){
                    $values[]=$value;
                }else if(is_null($value)){
                    $values[]='NULL';
                }else{
                    $values[]="'".addslashes($value)."'";
                }
            }
            $result .= "INSERT INTO `{$table}`($fields) VALUES (".implode(',',$values).");".PHP_EOL;
        	if(strlen($result)>=$size && $this->config['volume']){
        		$this->writeFile($table,$result);
        	}
        }
    }

    public function GenerateViewSQL(){
        $tables=$this->getTables('VIEW');
        foreach ($tables as $table){
            $data = M()->query("SHOW CREATE VIEW `{$table}`");
        	$sql = $data[0]["Create View"];
        	$result .= $this->dropSql($table,"VIEW");
        	$result .= $sql.';';
        	$this->writeFile('VIEW'.($this->config['signle']?'_'.$table:''),$result);
        }
        $this->writeFile('VIEW',$result);
        return $result;
    }

    public function GenerateProducureSQL(){
        $dbName = C('DB_NAME');
        $sql="SHOW PROCEDURE status where Db='$dbName';";
        $data=M()->query($sql);
        foreach ($data as $row){
            $create = M()->query("SHOW CREATE PROCEDURE `".$row['Name'].'`');
            $result .= $this->dropSql($row['Name'],"PROCEDURE");
            $result .= $create[0]['Create Procedure'].';';
            $this->writeFile('PROCEDURE'.($this->config['signle']?'_'.$row['Name']:''),$result);
        }
        $this->writeFile('PROCEDURE',$result);
        return $result;
    }

    public function GenerateTriggerSQL(){
        $dbName = C('DB_NAME');
        $sql="SHOW TRIGGERS FROM `$dbName`";
        $data=M()->query($sql);
        foreach ($data as $row){
            $trigger = $row['Trigger'];
            $result .= $this->dropSql($trigger,"TRIGGER");
            $result .= M()->query("SHOW CREATE TRIGGER `".$trigger.'`');
            $result .= $row['SQL Original Statement'].';';
            $this->writeFile('TRIGGER'.($this->config['signle']?'_'.$trigger:''),$result);
        }
        $this->writeFile('TRIGGER',$result);
        return $result;    
    }

    public function dropSql($name,$type){
        return "DROP $type IF EXISTS `$name`;";
    }

    
    public function GeneratePath($baseDir="./backup"){
    	$this->mkDir($baseDir);
    	$dbName = C('DB_NAME');
    	$time_now=time();
    	$stamp = date('YmdHi',$time_now);
    	$this->config['time']=date('Y-m-d H:i:s',$time_now);

    	$path = $baseDir.(substr($baseDir,strlen($baseDir)-1,1)==='/'?'':'/').$dbName.'_dump'.$stamp;
    	$this->mkDir($path);
    	return $path;
    }

    public function mkDir($path){
    	if(!file_exists($path)){
    	    mkdir($path);
    	}
    }
}