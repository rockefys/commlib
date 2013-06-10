<?php
class CodeAction extends Action {
	public function index($refresh=false){
		$M= M('Module_table');
		$count=$M->count();
		$table_detail=$M->query('SHOW TABLE STATUS');
        if($refresh===true)$M->where("name!=''")->setField('status','0');

		foreach ($table_detail as $k => $v) {
			$data[$k]['name'] 			= $v['Name'];
			$data[$k]['title']			=$v['Comment'];
			$data[$k]['module']			= ucwords($v['Name']);
			$data[$k]['group']			= 'Comm';
			$data[$k]['build']			=0;
			$data[$k]['status']			='1';
			if($refresh && $count!=='0')
				$result = $M->add($data[$k]);
		}

		if($count==='0') {$M->addAll($data);}
		if($refresh){$M->where("status='0'")->delete();}
		$data=array();
		foreach ($table_detail as $k => $v) {
			$data[$k]['name'] 			=$v['Name'];
			$data[$k]['title']			=$v['Comment'];
			$data[$k]['rows']			=$v['Rows'];
			$data[$k]['engine']			=$v['Engine'];
			$data[$k]['collation']		=$v['Collation'];
			$data[$k]['data_length']	=$v['Data_length'];
			$data[$k]['index_length']	=$v['Index_length'];
			$data[$k]['create_time']	=$v['Create_time'];
			$data[$k]['update_time']	=$v['Update_time'];
			$M->save($data[$k]);
		}
		$this->get_refer();
		$this->showpk=1;
    	R('Comm/Comm/page',array($M,null,'module',true));
	}

	public function edit($refresh=false){
		$refresh=$this->_param('refresh');
		$M=M('module_table');
		$this->pk=$M->getpk();
		$this->modules = $M->getField($this->pk,true);
		$this->groups=$this->getGroup();

		$result=$M->find($this->_param($this->pk));
		$module=$result['name'];
		$this->name=$result['name'];
        $this->group=$result['group'];
        $this->module=$result['module'];
        $this->validate=$result['validate'];
        $this->auto=$result['auto'];
        $M =M('module_column');
        $map['module']=$module;
        $result=$M->where($map)->order('list_order')->select();
        if(empty($result) || $refresh==true){
        	$this->build_column($module,$refresh);
			$this->data=$M->where($map)->order('list_order')->select();
		}
		else{
			$this->data=$result;
		}

		$M=M('module_refer');
		$this->refer=$M->where("module='%s'",$module)->getField('fk,pk,module_refer,field_show');

		$this->type=ACTION_NAME.'-'.($this->isAjax()?'ajax':'');
		layout(!$this->isAjax());
		$this->display('design');
	}
	protected function build_column($module,$refresh=false){

		$M=M('module_refer');
		$refer=$M->where("module='%s'",$module)->getField('fk,pk,module_refer,field_show');

		$M=M('module_column');
		$column_saved=$M->where("module='%s'",$module)->getField('field,field,type');
		if($refresh===true)$M->where("module='%s'",$module)->setField('status','0');
		$result = $M->query("SHOW FULL COLUMNS FROM `{$module}`");

        foreach ($result as $k=>$v){
        	//字段存在且类型无变化
        	if(!empty($column_saved) && $v['Type']===$column_saved[$v['Field']]['type'] && $refresh!=true)continue;
        	$show=true;
        	if($v['Key']==='PRI'){
        		$show=false;
        	}
        		//如果类型有变化或不存在，则添加
        	if($v['Type']!==$column_saved[$v['Field']]['type']){
	        	$data[$k]['id']=$module.'.'.$v['Field'];
		        $data[$k]['module']=$module;
		        $data[$k]['field']=$v['Field'];
		        $data[$k]['title']=$v['Comment'];
		        $data[$k]['type']=$v['Type'];
		        $data[$k]['empty']=$v['Null'];
		        $data[$k]['pk']=$v['Key'];
		        $data[$k]['default']=$v['Default'];

		        $data[$k]['query_able']= false;
		        $data[$k]['query_type']= strpos($v['Type'], 'date')!==false?'between':'eq';
		        $data[$k]['insert_able']=true;
		        $data[$k]['update_able']=$show;
		        $data[$k]['readonly']=false;
		        $data[$k]['list_show']=$show;
		        $data[$k]['list_order']=$k;
				$data[$k]['add_show']=$show;
		        $data[$k]['add_order']=$k;
		        $data[$k]['control_type']=control_type($v);
		        
		        if(!empty($refer[$v['Field']])){
		        	$data[$k]['control_type']='refer';
		        }
		        $data[$k]['validate']=validator($v);
		        $data[$k]['tips']='';
		        $data[$k]['status']='1';
		        $result=$M->add($data[$k],$options=array(),$replace=true);
		    }
		    else
		    if($refresh==true){
        		$data[$k]['id']=$module.'.'.$v['Field'];
        		$data[$k]['title']=$v['Comment'];
        		$data[$k]['type']=$v['Type'];
        		$data[$k]['control_type']=control_type($v);
        		if(!empty($refer[$v['Field']]))
		        	$data[$k]['control_type']='refer';
	        	$data[$k]['validate']=validator($v);
	        	$result=$M->save($data[$k]);
	        	$M->where("status='0'")->delete();
        	}
		}
		$M=M('module_table');
		$M->where("name='%s'",$module)->setField('status','1');
	}

	protected function operate(){ 
        $op = $this->_param('op');
        $module = $this->_param('mod');
        $M=M('module_refer');
        switch ($op) {
            case 'enable':
                $data = array( 'status'=> '1');
            case 'disable':
                $data = array( 'status'=> '0');
                
		        if(isset($data)){
		            $result = $M->where($M->getPk().'='.$id)->setField($data);
		            if(empty($result)) {
		                $this->error('Sorry for we can not deal your handle.'); 
		                
		            }
		        }
                break;
            case 'add':
            case 'edit':
            case 'save':
            case 'delete':
            	$map=array($M->getPk() => $this->_param($M->getPk()));
            	$M->where($map)->delete();
            	break;
            case 'view':
            case 'index':
                $this->type='index';
            case 'refer':
            case 'export':
            case 'improt':
            case 'upload':
            	if(empty($module))$module=MODULE_NAME;
            	$module=ucwords($module);
            	$this->operate=ACTION_NAME.'?mod=module_refer&op=';
            	R('Comm/'.$module.'/'.$op);
                exit();
            default:
            $this->error('Unknow handle.'); 
            exit();
                break;
        }
        
    }
    public function relation(){
    	$this->refer();
    }
	public function refer(){
		$this->get_refer();
		$op=$this->_param('op');
		$this->type='index';
		if(!empty($op)){
			$this->operate();
			return;
		}
        $module=$this->_param('name');
        $M=M($module);
        $this->module=$module;
        $this->pk=$M->getpk();
        $this->fk=$this->_param('fk');
        if(strpos($this->fk,'_id')!==false)
        	$this->refer_module = substr($this->fk,0,-3);
        $M=M('module_table');
        $this->Modules = $M->getField('name',true);

		$data=array('name' => $module);
		$result=$M->where($data)->find();
		$data = $M->query("SHOW FULL COLUMNS FROM `{$module}`;");
        $fields = array();
        $i=0;
        foreach ($data as $k=>&$v){
            $fields["$i"] = "{$v['Field']}";
            ++$i;
        }
		$this->module_columns=$fields;
		if(isset($result)){
			$M=M('module_column');
			$condition=array('module_id' => $result['id']);

			$this->data=$M->field("`field` Field,`title` `Comment`,`type` Type,query_able,list_show,list_order,control_type,add_show,add_order,validate
				,tips")->where($condition)->select();
		}
		else{
	        $M=M();
			$this->data=$M->query("SHOW FULL COLUMNS FROM {$module}");
		}

		layout(!$this->isAjax());
		$module='Module_refer';
		$this->columns  = C("columns.".$module);
		$M=M($module);
		$map=array('type' => ACTION_NAME );
		$this->data=$M->where($map)->select();//->where("module='".$this->_param('module')."'")
		$this->operate=ACTION_NAME.'?mod=module_refer&op=';
		$this->toolbar_tr =  array(
                'delete'
          );
		$this->display();
	}

	protected function get_refer(){
		$M=M('module_refer');
		$data=$M->query("select TABLE_NAME module,COLUMN_NAME fk,REFERENCED_TABLE_NAME module_refer,REFERENCED_COLUMN_NAME pk from INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
					where TABLE_SCHEMA='commlib' and REFERENCED_TABLE_SCHEMA='commlib' and POSITION_IN_UNIQUE_CONSTRAINT=1"
		);
		$pk = $M->getPk();

		foreach ($data as $key => $v) {
			$data[$key][$pk]=$v['module'].'-'.$v['fk'].'-'.$v['module_refer'].'-'.$v['pk'];
			$data[$key]['fk_id']=$v['module'].'.'.$v['fk'];
        	$data[$key]['pk_id']=$v['module_refer'].'.'.$v['pk'];
        	$data[$key]['condition']=$data[$key]['fk_id'].'='.$data[$key]['pk_id'];
        	$data[$key]['refer_type']='INNER';
        	$M=M($v['module_refer']);
        	$refer_fields = $M->getDbFields();
        	if(count($refer_fields)>1)
        		$data[$key]['field_show']=$refer_fields[1];
        	else
        		$data[$key]['field_show']=$refer_fields[0];

		}
        $M=M('module_refer');
        $M->addAll($data);

	}
	public function getColumn(){
		$table=$this->_param('id');
		$M = M();
        $data = $M->query("SHOW COLUMNS FROM `{$table}`;");
        $fields = array();
        $i=0;
        foreach ($data as $k=>&$v){
            $fields["$i"] = $v['Field'];
            ++$i;
        }

        $this->ajaxReturn($fields,'JSON');
    }

	public function save(){
        $data['app']=$this->getAppName();
        $data['group']=$this->_param('group');
        $data['module']=$this->_param('module');
        $data['validate']=$this->_param('validate');
        $data['auto']=$this->_param('auto');
        $module=$this->_param('name');
        $map['name']=$module;

        $M=M('module_table');
        $M->where($map)->save($data);
        
        $data=null;
        $M =M('module_column');
        $data=$_POST["query"];
        foreach ($data as $k => $v) {
        	/*
    		$data[$k]['id']=$v['id'];
	        $data[$k]['field']=$v['field'];
	        $data[$k]['title']=$v['title'];
	        $data[$k]['type']=$v['type'];
	        $data[$k]['empty']=$v['empty'];
	        $data[$k]['pk']=$v['dey'];
	        $data[$k]['default']=$v['default'];
	        $data[$k]['control_type']=$v['control_type'];
	        $data[$k]['validate']=$v['validate'];
	        $data[$k]['tips']=$v['tips'];
    		$data[$k]['list_order']=$v['list_order'];
	        $data[$k]['add_order']=$v['add_order'];
			*/
	        $data[$k]['status']='1';
	        $data[$k]['module']=$module;
	        if ($v['insert_able']==='on')
    			$data[$k]['insert_able']=true;
    		else
    			$data[$k]['insert_able']=false;
    		if ($v['update_able']==='on')
    			$data[$k]['update_able']=true;
    		else
    			$data[$k]['update_able']=false;
    		if ($v['query_able']==='on')
    			$data[$k]['query_able']=true;
    		else
    			$data[$k]['query_able']=false;
    		if ($v['list_show']==='on')
    			$data[$k]['list_show']=true;
    		else
    			$data[$k]['list_show']=false;
			if ($v['add_show']==='on')
    			$data[$k]['add_show']=true;
    		else
    			$data[$k]['add_show']=false;
    		$result=$M->add($data[$k],$options=array(),$replace=true);
        }

        layout(!$this->isAjax());

        $msg=$result?'Success':'Fail';
        $this->redirect('Code/edit',array('name' => $module),2,$msg);
	}
	public function mapping_save(){ 
     	layout(!$this->isAjax()); 
     	$data=$this->_param();
     	unset($data['_URL_']);
     	//$data['pk'] =$this->_param('module').'.'.$this->_param('pk');
     	//$data['fk'] =$this->_param('module_refer').'.'.$this->_param('fk');

        $M = M('module_refer');
        if(!$M->create()){
            $this->error($M->getError());
            exit();
        }

        $pk = $M->getPk();
        if(empty($M->$pk)){
        	$data[$pk]=$this->_param('module').'-'.$this->_param('fk').'-'.$this->_param('module_refer').'-'.$this->_param('pk');
        	$data['fk_id']=$this->_param('module').'.'.$this->_param('fk');
        	$data['pk_id']=$this->_param('module_refer').'.'.$this->_param('pk');
        	$condition=$this->_param('condition');
        	if(empty($condition)){
        		$data['condition']=$data['fk_id'].'='.$data['pk_id'];
        	}
            $result = $M->add($data,$options=array(),$replace=true);
        }
        else{
            $result = $M->save($data);
        }
        $this->redirect('Code/refer',array('module' => $this->_param('module')));        
    }

	public function view(){
		$M=M('module_table');
		$this->pk=$M->getPk();
		$this->modules = $M->getField($M->getPk(),true);
		$module=$this->_param($M->getPk());
		$result=$M->find($module);
		
		if(!empty($result)){
			$M=M('module_column');
			$this->name=$module;
			$this->data = $M->where("module='%s'",$module)->order('add_order')->select();
			
			$M=M('module_refer');
			$this->refers=$M->where("module='%s'",$module)->getField('fk,pk,id,module,module_refer,field_show');
			$this->module = ucwords($module);
			layout(false);
			$this->add_show= $this->fetch('add');
			$this->edit_show=$this->fetch('edit');
			$this->add= htmlentities($this->add_show,ENT_NOQUOTES,"utf-8");
			$this->edit=htmlentities($this->edit_show,ENT_NOQUOTES,"utf-8");
			$this->action=htmlentities($this->fetch('action'),ENT_NOQUOTES,"utf-8");
			$data=$this->data;
			foreach ($data as $key => $v) {
				if($v['insert_able']==1 || $v['pk']=='PRI'){
					$insert_fields[]="'".$v['field']."'";
				}
				if($v['update_able']==1){
					$update_fields[]="'".$v['field']."'";
				}
				if($v['insert_able']==1 && $v['update_able']==0){
					$readonly_fields[]="'".$v['field']."'";
				}
			}

			$M=M('module_table');
			$module=$name;
			$result=$M->getByModule($module);
			$data['validate']=$result['validate'];
			$data['auto']=$result['auto'];

			$refers=$this->refers;
			foreach ($refers as $key => $v) {
				$scope[]='"join '.$v['module_refer'].' on '.$v['module'].'.'.$v['fk'].'='.$v['module_refer'].'.'.$v['pk'].' '.$v['condition'].'"';
				$refer_fields[]=$v['module_refer'].'.'.$v['field_show'];
			}
			if(count($scope)>0){
				$data['scope']='"join"=>array('.implode(",", $scope)."),\n";
				$data['scope'].='"field"=>"'.$result['name'].'.*,'.implode(",", $refer_fields).'",';
			}
			
			$M=M($module);
			$this->pk=$M->getPk();
			$data['insert_fields']=implode(",", $insert_fields);
			$data['update_fields']=implode(",", $update_fields);
			$data['readonly_fields']=implode(",", $readonly_fields);
			$this->data=$data;
			$this->model=htmlentities($this->fetch('model'),ENT_NOQUOTES,"utf-8");
			$M=M($module);
			$this->data=$M->limit(10)->select();
			$this->module   = $module;
	        $this->columns  = C("columns.".$module);
	        $this->query    = C("query.".$module);
	        $this->toolbar_tr =array(
    	  		'view',
    	  		'edit',
    	  		'delete'
    	  	);
    	  	$this->index=$this->fetch('Comm:Index:table-admin');
    	  	$M=M('module_table');
			$this->pk=$M->getPk();
		}
		layout(!$this->isAjax());
		$this->display();
	}

	//生成模块结构信息 app/分组/模块/方法
	public function fetch_module(){
		
		$app = $this->getAppName();
		$groups = $this->getGroup();
		$M = M('module');
		$map=array('app' =>$app);
		$M->where($map)->delete();//先删除当前项目已经生成的节点数据
		$n=0;
		foreach ($groups as $group) {
			$modules = $this->getModule($group);
			foreach ($modules as $module) {
				$module_name=$app.'://'.$group.'/'.$module;
				$functions = $this->getFunction($module_name);
				foreach ($functions as $function) {
					$data[$n]['app'] = $app;
					$data[$n]['group'] = $group;
					$data[$n]['module'] = $module;
					$data[$n]['function'] = $function;
					$data[$n]['url']		= $group.'/'.$module.'/'.$function;
					++$n;
				}
			}
		}
		$M->addAll($data);
		dump($data);exit();
		$this->success('所有分组/模块/方法已成功读取到module表中.',null,30);
	}
	public function build_all(){
		$M=M('module_table');
		$modules=$M->where("type='customer'")->getField('name,group');
		$this->get_refer();
		foreach ($modules as $module => $group) {
			$this->build_column($module);
			$this->build_code($group,$module);
		}
		$this->success('Success.');
	}

	public function build(){
		$M=M('module_table');
		$module=$this->_param($M->getPk());
		$data=$M->find($module);
		if(!empty($data)){
			if($data['type']!=='system'){
				$this->build_code($data['group'],$data['module']);
				layout(!$this->isAjax());
				$this->redirect($data['group'].'/'.ucwords($module).'/index',null, 2, 'Success');
			}
			else{
				$this->error("Sorry, please don't rewrite the system module $module.");
			}
		}
		else{
			$this->error("Sorry, module not exists.");
			//日志记录，防止暴力枚举数据库表，超过3次输入验证码，超过10次拉黑名单
		}
	}

	//根据分组和模块生成代码文件Action和tpl
	protected function build_code($group=null,$module=null){
		if(empty($module)){return;}
		if(empty($group))$group = 'Admin';

		$M=M('module_column');
		$this->data = $M->where("module='%s'",$module)->order('add_order')->select();
		$module = ucwords($module);
		$this->module=$module;

		$M=M('module_refer');
		$this->refers=$M->where("module='%s'",$module)->getField('fk,pk,id,module,module_refer,field_show');
		
		$this->build_tpl($group,$module);
		$this->build_model($group,$module);
		$this->build_action($group,$module);
		$this->build_config($group,$module);
	}
	protected function build_tpl($group,$module){
		$path = TMPL_PATH."$group/$module/";
		if(!file_exists($path)){
    	    mkdirs($path);
    	}
    	layout(false);
    	$file = "add.html";
		$content=$this->fetch('add');
		file_put_contents($path.$file, $content);
		$file = "edit.html";
		$content=$this->fetch('edit');
		file_put_contents($path.$file, $content);
	}
	protected function build_model($group,$module){
		$insert_fields=array();
		$update_fields=array();
		$readonly_fields=array();
		$scope=array();
		$refer_fields=array();
		$data=$this->data;
		foreach ($data as $key => $v) {
			if($v['insert_able']==1 || $v['pk']=='PRI'){
				$insert_fields[]="'".$v['field']."'";
			}
			if($v['update_able']==1){
				$update_fields[]="'".$v['field']."'";
			}
			if($v['insert_able']==1 && $v['update_able']==0){
				$readonly_fields[]="'".$v['field']."'";
			}
		}

		$M=M('module_table');
		$result=$M->getByModule($module);
		$data['validate']=$result['validate'];
		$data['auto']=$result['auto'];

		$refers=$this->refers;
		foreach ($refers as $key => $v) {
			$scope[]='"join '.$v['module_refer'].' on '.$v['module'].'.'.$v['fk'].'='.$v['module_refer'].'.'.$v['pk'].' '.$v['condition'].'"';
			$refer_fields[]=$v['module_refer'].'.'.$v['field_show'];
		}
		if(count($scope)>0){
			$data['scope']='"join"=>array('.implode(",", $scope)."),\n";
			$data['scope'].='"field"=>"'.$result['name'].'.*,'.implode(",", $refer_fields).'",';
		}
		
		$M=M($module);
		$this->pk=$M->getPk();
		$data['insert_fields']=implode(",", $insert_fields);
		$data['update_fields']=implode(",", $update_fields);
		$data['readonly_fields']=implode(",", $readonly_fields);
		$this->data=$data;

		$path=LIB_PATH."Model/$group/";
		if(!file_exists($path)){
    	    mkdirs($path);
    	}
    	layout(false);
		$file=$module."Model.class.php";
		$content="<?php\n".$this->fetch('model');
		file_put_contents($path.$file, $content);
	}

	protected function build_action($group,$module){
		$path=LIB_PATH."Action/$group/";
		if(!file_exists($path)){
    	    mkdirs($path);
    	}
    	layout(false);
		$file=$module."Action.class.php";
		$content="<?php\n".$this->fetch('action');
		file_put_contents($path.$file, $content);
	}
	protected function build_config($group ,$module){
		$M=M('module_column');
		$data = $M->where("module='%s'",strtolower($module))->order('list_order')->select();
		$M=M('module_refer');
		$refer = $this->refers;

		$columns=array();
		$query=array();
		$join=array();
		foreach ($this->refers as $key => $v) {
			$join[$v['fk']]=$v['field_show'];
		}
		foreach ($data as $key => $v) {
			if($v['list_show']==1 || $v['pk']=='PRI'){
				if(isset($join[$v['field']]))
					$columns[$join[$v['field']]]=$v['title'];
				else
					$columns[$v['field']]=$v['title'];
			}

			if($v['query_able']==1){
				if(empty($refer[$v['field']])){
				 	if($v['control_type']==="select"){
				 		if(strpos($v['type'],'enum')!==false){
				 			$value= str_replace("'",'',substr($v['type'],5,-1));
				 		}
				 	}
				}
				elseif($v['control_type']==='refer')
			 			$value=$refer[$v['field']]['id'].','.$refer[$v['field']]['pk'].','.$refer[$v['field']]['field_show'].','.ucwords($refer[$v['field']]['module_refer']).'/refer';
			 	elseif($v['control_type']==='getField'){
			 			$value=ucwords($refer[$v['field']]['module_refer']).'.'.$refer[$v['field']]['pk'].','.$refer[$v['field']]['field_show'];
			 	}
				$query[$v['field']]=array(
					'title' => $v['title'], 
					'query_type'	=>$v['query_type'],		//eq,between,like
					'control_type'	=>$v['control_type'],	//input select checkboc refer getField
					'value'		=>$value,
				);
			}
		}
		$this->write_config($module,$columns,'columns');
		$this->write_config($module,$query,'query');
	}
	protected function write_config($module,$value,$type){
		if($type==='columns' || $type==='query'){
			$config=F($type,'',CONF_PATH);
	        C($type.'.'.$module,$value);
	        $config[$type][$module]=$value;
	        F($type,$config,CONF_PATH);
	    }
	}
	protected function getAppName(){
		return APP_NAME;
	}

	protected function getGroup(){
		$result = explode(',',C('APP_GROUP_LIST'));
		return $result;
	}

	protected function getModule($group){
		if(empty($group))return null;
		$group_path=LIB_PATH.'Action/'.$group;
		if(!is_dir($group_path))return null;
		$group_path.='/*.class.php';
		$ary_files = glob($group_path);
	    foreach ($ary_files as $file) {
	        if (is_dir($file)) {
	            continue;
	        }else {
	            	$files[] = basename($file,'Action.class.php');
	        }
	    }
	    return $files;

	}

	protected function getFunction($module){
		if(empty($module))return null;
		$action=A($module);
		$functions=get_class_methods($action);
		$inherents_functions = array(
			'_initialize','__construct','getActionName','isAjax','display','show','fetch',
			'buildHtml','assign','__set','get','__get','__isset',
			'__call','error','success','ajaxReturn','redirect','__destruct','_empty','__hack_module','__hack_action'
		);

		foreach ($functions as $func){
			if(!in_array($func, $inherents_functions)){
				$customer_functions[]=$func;
			}
		}
		return $customer_functions;
	}
}