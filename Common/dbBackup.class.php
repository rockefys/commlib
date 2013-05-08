<?php
class dbBackup
{
    

    public backup(){
        $result = array();

        //Generate Table sql statement;
        $type="TABLE";
        $tables=getTables();
        foreach ($tables as $table){
            //Generate table structure sql statement;
            $result[] = BuildStructure($table); 
            //Generate table data sql statement;
            $result[] = BuildData($table);
        }

        //Generate View sql statement;
        $type="VIEW";
        $tables=getTables($type);
        foreach ($tables as $table){
            $result[] = BuildStructure($table);
        }

        //Generate Producure sql statement;
        $result[] = GenerateProducureSQL();

        //Generate Tigger sql statement;
        $result[] = GenerateTriggerSQL();
    }
    /**
     * @description 获取当前数据库的所有表名。
     * @static
     * @return array
     */
    static protected function getTables($type="BASE TABLE"){
        $dbName = C('DB_NAME');
        $sql = "SHOW FULL TABLES FROM `$dbName` WHERE Table_Type = '$type'");

        $col = "Tables_in_".$dbName;
        $data = M()->query($sql);

        foreach ($data as $row){
            $result[] = $row[$col];
        }
        return $result;
    }
 
    /**
     * @description 导出SQL数据，但不包含表创建代码。
     * @static
     * @return string
     */
    static public function GenerateDataSQL($table)
    {
        $M = new Model();
        $result[]= "TRUNCATE TABLE {$table};";
        $data = $M->query("SHOW COLUMNS FROM {$table}");
        $fields = array();
        foreach ($data as $k=>&$v){
            $fields[] = "`{$v['Field']}`";
        }
        $fields = implode($fields,",");

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
            $result[] = "INSERT INTO `{$table}`($fields) VALUES (".implode(',',$values).");";
        }
        return $result;
    }
 
    static protected function GenerateStructureSQL($table,$type="TABLE")
    {
        $result = array();
        $data = M()->query("SHOW CREATE $type `{$table}`");
        $sql = $data[0]["Create $type"];
        $result[] = dropSql($table,$type);
        $result[] = $sql.';';
        return $result;
    }

    static protected function GenerateProducureSQL(){
        $result = array();
        $sql="SELECT ROUTINE_NAME,ROUTINE_DEFINITION FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_SCHEMA='$dbName'";
        $data=M()->query($sql);
        foreach ($data as $row){
            $result[] = dropSql($row['ROUTINE_NAME'],"ROUTINE")
            $result[] = $row['ROUTINE_DEFINITION'].';';
        }
        return $result;
    }

    static protected function GenerateTriggerSQL(){
        $result = array();
        $sql="SELECT TRIGGER_NAME FROM INFORMATION_SCHEMA.TRIGGERS WHERE TRIGGER_SCHEMA=$dbName";
        $data=M()->query($sql);
        foreach ($data as $row){
            $trigger = $row['TRIGGER_NAME'];
            $result[] = dropSql($trigger,"TRIGGER")
            $result[] = M()->query("SHOW CREATE TRIGGER ".$trigger);
            $result[] = $row['SQL Original Statement'].';';
        }
        return $result;
        
    }

    static protected function dropSql($name,$type)
    {
        return "DROP $type IF EXISTS `$name`;";
    }



}