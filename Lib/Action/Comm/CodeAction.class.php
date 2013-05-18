<?php
class CodeAction extends Action {
	public function indexs()
	{
		$this->module=$this->_post('module_name');
		$module=$this->_post('module_name');
        $M=M();
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
	public function index($refresh=false){
		$M= M('Module_table');
		$result=$M->count();
		if($result==='0' || $refresh){
	        $result=$M->query('SHOW TABLE STATUS');
	        $M->where("name!=''")->setField('status','0');
			foreach ($result as $k => $v) {
				$data[$k]['name'] 			= $v['Name'];
				$data[$k]['module']			= ucwords($v['Name']);
				$data[$k]['group']			= 'Admin';
				$data[$k]['title']			=$v['Comment'];
				$data[$k]['rows']			=$v['Rows'];
				$data[$k]['engine']			=$v['Engine'];
				$data[$k]['collation']		=$v['Collation'];
				$data[$k]['data_length']	=$v['Data_length'];
				$data[$k]['index_length']	=$v['Index_length'];
				$data[$k]['create_time']	=$v['Create_time'];
				$data[$k]['update_time']	=$v['Update_time'];
				$data[$k]['build']			=1;
				$data[$k]['status']			='1';
			}
			if($refresh)
				$M->addAll($data,$options=array(),$replace=true);
			else
				$M->addAll($data);
		}
		$this->showpk=1;
    	R('Comm/Comm/page',array($M));
	}

	public function edit($refresh=false)
	{
		$this->groups=$this->getGroup();
		$this->modules = R('Comm/Database/gettables');
		$M=M('module_table');
		$this->pk=$M->getpk();
		$module=$this->_param($this->pk);
		$result=$M->find($module);
        $this->group=$result['group'];
        $this->module=$result['module'];
        $this->table_name=$result['name'];
        $this->validate=$result['validate'];
        $this->auto=$result['auto'];
        $M =M('module_column');
        $map['module']=$module;
        $result=$M->where($map)->order('list_order')->select();
        if(empty($result) || $refresh!==false){
        	$result = $M->query("SHOW FULL COLUMNS FROM `{$module}`");
	        foreach ($result as $k=>$v){
	        	$data[$k]['id']=$this->table_name.'.'.$v['Field'];
		        $data[$k]['module']=$this->module;
		        $data[$k]['field']=$v['Field'];
		        $data[$k]['title']=$v['Comment'];
		        $data[$k]['type']=$v['Type'];
		        $data[$k]['empty']=$v['Null'];
		        $data[$k]['pk']=$v['Key'];
		        $data[$k]['default']=$v['Default'];
		        $data[$k]['query_able']=true;
		        $data[$k]['insert_able']=true;
		        $data[$k]['update_able']=true;
		        $data[$k]['readonly']=false;
		        $data[$k]['list_show']=true;
		        $data[$k]['list_order']=$k;
				$data[$k]['add_show']=true;
		        $data[$k]['add_order']=$k;
		        $data[$k]['control_type']=control_type($v);
		        $data[$k]['validate']=validator($v);
		        $data[$k]['tips']='';
		        $data[$k]['status']='1';
			}
			if($refresh!==false){
				$M->addAll($data,$options=array(),$replace=true);
			}
			else{
				$M->addAll($data);
			}
			
			$this->data=$M->where($map)->order('list_order')->select();
		}
		else{
			$this->data=$result;
		}
		$this->type=$action.'-'.($this->isAjax()?'ajax':'');
		layout(!$this->isAjax());
		$this->display('index');
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

		$M=M('module_table');
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
		$this->data=$M->select();//->where("module='".$this->_param('module')."'")
		$this->display('refer');
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
        $map['name']=$this->_param('table_name');

        $M=M('module_table');
        $M->where($map)->save($data);
        $module=$this->_param('module');
        $table_name=$this->_param('table_name');
        $data=null;
        $M =M('module_column');
        $data=$_POST["query"];
        $k=0;
        dump($data);
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
        }
        $result=$M->addAll($data,$options=array(),$replace=true);
        layout(!$this->isAjax());
        $msg=$result?'Success':'Fail';
        $this->redirect('Code/edit',array('name' => $table_name),3,$msg);
	}
	public function mapping_save(){ 
     	layout(!$this->isAjax()); 
     	$data=$this->_param();
     	unset($data['_URL_']);
     	$data['pk'] =$this->_param('module').'.'.$this->_param('pk');
     	$data['fk'] =$this->_param('module_refer').'.'.$this->_param('fk');

        $M = M('module_refer');
        if(!$M->create()){
            $this->error($M->getError());
            exit();
        }

        $pk = $M->getPk();
        if(empty($M->$pk)){
        	$data[$pk]=$this->_param('module').'.'.$this->_param('module_refer').'.'.$this->_param('map_type').'.'.$this->_param('pk').'.'.$this->_param('fk');
            $result = $M->add($data,$options=array(),$replace=true);
        }
        else{
            $result = $M->save($data);
        }
        $this->redirect('Code/refer',array('module' => $this->_param('module')));        
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

	public function view($name=null){
		$this->modules = R('Comm/Database/gettables');
		$M=M('module_table');
		$module = ucwords($name);
		$result=$M->getByModule($module);
		if(!empty($result)){
			$M=M('module_column');
			$this->module=$module;
			$this->data = $M->where("module='%s'",$module)->order('add_order')->select();
			layout(false);
			$this->add_show= $this->fetch('add');
			$this->edit_show=$this->fetch('edit');
			$this->add= htmlentities($this->add_show);
			$this->edit=htmlentities($this->edit_show);
			$this->action=htmlentities($this->fetch('action'));
			$this->model=htmlentities($this->fetch('model'));
		}
		layout(!$this->isAjax());
		$this->display();
	}

	//根据分组和模块生成代码文件Action和tpl，参数都为空则生成全部
	public function build_code($group=null,$module=null)
	{
		if(empty($module)){
			$M=M('module_table');
			$modules=$M->field('name')->select();
		}
		else
			$modules[] = $module;

		if(empty($group))$group = 'Admin';

		foreach ($modules as $module) {
			$M=M('module_column');
			$this->data = $M->where("module='%s'",$module)->select();
			$module = ucwords($module);
			$this->build_tpl($group ,$module);
			$this->build_module($group ,$module);
			$this->build_action($group ,$module);
		}
		layout(!$this->isAjax());
		$this->success("Success");

	}
	public function build_code_auto($group=null,$module=null)
	{
		if(empty($module)){
			$modules = R('Comm/Database/gettables');
		}
		else
			$modules[] = $module;

		if(empty($group))$group = 'Admin';

		foreach ($modules as $module) {
			$M=M();
			$this->data = $M->query("SHOW FULL COLUMNS FROM {$module}");
			$module = ucwords($module);
			$this->build_tpl($group ,$module);
			$this->build_module($group ,$module);
			$this->build_action($group ,$module);
		}
		layout(!$this->isAjax());
		$this->success("Success");

	}
	public function build_tpl($group,$module){
		$path = "./Tpl/$group/$module/";
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
	public function build_model($group,$module){
		$path="./Lib/Model/$group/";
		if(!file_exists($path)){
    	    mkdirs($path);
    	}
    	layout(false);
		$file=$module."Model.class.php";
		$content="<?php\n".$this->fetch('model');
		file_put_contents($path.$file, $content);
	}

	public function build_action($group,$module){
		$path="./Lib/Action/$group/";
		if(!file_exists($path)){
    	    mkdirs($path);
    	}
    	layout(false);
		$file=$module."Action.class.php";
		$content="<?php\n".$this->fetch('action');
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