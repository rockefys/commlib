<?php
class CodeAction extends CommAction {
	public function indexs()
	{
		$this->module=$this->_post('module_name');
		$module=$this->_post('module_name');
        $M=new Model();
		$this->Modules = R('Comm/Database/gettables');
		if(isset($module)){
			$this->data=$M->query("SHOW FULL COLUMNS FROM {$this->module}");
			layout(false);
			$module=ucwords($module);
			$file="add.html";
			$path="./Tpl/Comm/$module/";
			if(!file_exists($path)){
	    	    mkdirs($path);
	    	}
			$content=$this->fetch('add');
			//file_put_contents($path.$file, $content);

			$path="./Lib/Action/Comm/";
			if(!file_exists($path)){
	    	    mkdirs($path);
	    	}
	    	$content='<?php
class '.$module.'Action extends CommAction {

}';
			$file=$module."Action.class.php";
			//file_put_contents($path.$file, $content);
			layout(!$this->isAjax());
			//$this->display();
			$this->success("Success");
		}
		else{
			layout(!$this->isAjax());
			$this->display();
		}

	}
	public function index(){
		R("Comm/Database/table");
	}
	public function edit(){
		$this->show(ACTION_NAME);
	}
	public function refer(){
        $module=$this->_param('module');
        $this->module=$module;
        $M=M($module);
        $this->pk=$M->getpk();
        $this->fk=$this->_param('fk');
        if(strpos($this->fk,'_id')!==false)
        	$this->refer_module = substr($this->fk,0,-3);
        $this->Modules = R('Comm/Database/gettables');

		$M=new Model('module_table');
		$data=array('table_name' => $module);
		$result=$M->where($data)->find();
		$M1=new Model();
		$data = $M1->query("SHOW COLUMNS FROM `{$module}`;");
        $fields = array();
        $i=0;
        foreach ($data as $k=>&$v){
            $fields["$i"] = "{$v['Field']}";
            ++$i;
        }
		$this->module_columns=$fields;
		if(isset($result)){
			$this->module=$result['module'];
			
			$M=new Model('module_column');
			$condition=array('module_id' => $result['id']);
			$this->data=$M->field("`field` Field,`title` `Comment`,`type` Type,query_able,list_show,list_order,control_type,add_show,add_order,validate
				,tips")->where($condition)->select();
		}
		else{
	        $M=new Model();
			$this->data=$M->query("SHOW FULL COLUMNS FROM {$module}");
		}

		layout(!$this->isAjax());
		$this->display();
		exit();
		$this->show(ACTION_NAME);
	}

	public function getColumn(){
		$table=$this->_param('id');
		$M = new Model();
        $data = $M->query("SHOW COLUMNS FROM `{$table}`;");
        $fields = array();
        $i=0;
        foreach ($data as $k=>&$v){
            $fields["$i"] = "{$v['Field']}";
            ++$i;
        }

        $this->ajaxReturn($fields,'JSON');
    }
	public function save(){
        $M =M('module_column');
        $condition=$_POST["query"];
        $group=$this->_param('group');
        $module=$this->_param('module');
        $name=$this->_param('name');
        $data['app']=$this->getAppName();
        $data['group']=$group;
        $data['module']=$module;
        $data['table_name']=$name;
        $M2=M('module_table');
        $module_id=$M2->add($data);
        $pk=$M2->getpk();
        foreach ($condition as $key => $value) {
        		$value['module_id']=$module_id;
        		if ($value['query_able']==='on')
        			$value['query_able']=true;
        		else
        			$value['query_able']=false;
        		if ($value['list_show']==='on')
        			$value['list_show']=true;
        		else
        			$value['list_show']=false;
				if ($value['add_show']==='on')
        			$value['add_show']=true;
        		else
        			$value['add_show']=false;
        		if(isset($value[$pk]))
        			$result=$M->save($value);
        		else
        			$result=$M->add($value);

        }

        layout(!$this->isAjax());
        if($result){
             $this->ajaxReturn(1,"success",1);
        }
        else{
             $this->ajaxReturn(0,'fail',0);
        }            
	}
	public function show($action=null){
		$module=$this->_param('id');
		$this->module=$this->_param('id');
		$this->type=$action.'-'.($this->isAjax()?'ajax':'');
		$this->group=$this->getGroup();
		$this->Modules = R('Comm/Database/gettables');

		$M=new Model('module_table');
		$data=array('table_name' => $module);
		$result=$M->where($data)->find();
		
		if(isset($result)){
			$this->module=$result['module'];
			
			$M=new Model('module_column');
			$condition=array('module_id' => $result['id']);
			$this->data=$M->field("`field` Field,`title` `Comment`,`type` Type,query_able,list_show,list_order,control_type,add_show,add_order,validate
				,tips")->where($condition)->select();
			layout(!$this->isAjax());
			$this->display('show');
		}
		else{
	        $M=new Model();
			$this->data=$M->query("SHOW FULL COLUMNS FROM {$module}");
			layout(!$this->isAjax());
			$this->display('index');
		}

	}
	//生成模块结构和信息 分组/模块/方法
	public function build_module(){
		$M = M('Module');
		$M->query("truncate table module");
		$app = $this->getAppName();
		$groups = $this->getGroup();
		$n=0;
		foreach ($groups as $group) {
			$data[++$n]['group'] = $group;
			$modules = $this->getModule($group);
			foreach ($modules as $module) {
				$data[++$n]['module'] = $module;
				$module_name=$app.'://'.$group.'/'.$module;
				$functions = $this->getFunction($module_name);
				foreach ($functions as $function) {
					$data[++$n]['app'] = $app;
					$data[$n]['group'] = $group;
					$data[$n]['module'] = $module;
					$data[$n]['function'] = $function;
				}
			}
		}
		$M->addAll($data);
		$this->success('success');
	}
	//根据分组和模块生成代码文件Action和tpl，参数都为空则生成全部
	public function build_code($group=null,$module=null)
	{
		if(empty($module))
			$modules = R('Comm/Database/gettables');
		else
			$modules[] = $module;

		if(empty($group))$group = 'Admin';

		foreach ($modules as $module) {
			$M=M();
			$this->data = $M->query("SHOW FULL COLUMNS FROM $module");
			$module = ucwords($module);
			$this->build_tpl($group ,$module);
			$this->build_action($group ,$module);
		}
		layout(!$this->isAjax());
		$this->success("Success");

	}
	public function build_tpl($group,$module){
		$file = "add.html";
		$path = "./Tpl/$group/$module/";
		if(!file_exists($path)){
    	    mkdirs($path);
    	}
    	layout(false);
		$content=$this->fetch('add');
		file_put_contents($path.$file, $content);
	}

	public function build_action($group,$module){
		$path="./Lib/Action/$group/";
			if(!file_exists($path)){
	    	    mkdirs($path);
	    	}
	    	$content='<?php
class '.$module.'Action extends CommAction {

}';
			$file=$module."Action.class.php";
			file_put_contents($path.$file, $content);
	}

	public function getAppName(){
		return APP_NAME;
	}

	public function getGroup(){
		$result = explode(',',C('APP_GROUP_LIST'));
		return $result;
	}

	public function getModule($group){
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

	public function getFunction($module){
		if(empty($module))return null;
		$action=A($module);
		$functions=get_class_methods($action);
		$inherents_functions = array(
			'_initialize','__construct','getActionName','isAjax','display','show','fetch',
			'buildHtml','assign','__set','get','__get','__isset',
			'__call','error','success','ajaxReturn','redirect','__destruct'
		);

		foreach ($functions as $func){
			if(!in_array($func, $inherents_functions)){
				$customer_functions[]=$func;
			}
		}
		return $customer_functions;
	}

}