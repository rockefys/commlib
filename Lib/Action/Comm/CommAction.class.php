<?php
class CommAction extends Action {
    public function _initialize(){
        //权限验证
    }

    public function _empty($action){
       $this->error('Unknow handle.',U('index'));
    } 
    
    public function index(){
        $condition = $this->_param('query');
        if(!empty($condition)){
            $query    = C("query.".MODULE_NAME);
            foreach ($query as $key => $v) {
                switch ($v['query_type']) {
                    case 'eq':
                        $map[$key]=array($v['query_type'],$condition[$key]);
                        break;
                    case 'like':
                        $map[$key]=array($v['query_type'],'%'.$condition[$key].'%');
                        break;
                    case 'between':
                        $map[$key]=array($v['query_type'],$condition[$key].','.$condition[$key.'_1']);
                        break;
                }
            }
            $map = queryFilter($map);
        }
        else{
            $condition = $this->_param('pill');
             if(!empty($condition)){
                $para=explode('&', urldecode($condition));
                foreach ($para as $key => $v) {
                    $cond=explode('=', $v);
                    if(count($cond)===2)
                        $map[$cond[0]]=$cond[1];
                }
            }
        }
        $M =  D(MODULE_NAME);
        $condition['is_deleted']='0';
        $select = $M->scope('default')->join()->where($map)->order();
        $this->page($select,$map); 
    }

    public function refer(){
        $this->refer=$this->_param('refer');
        $this->index();
    }

    public function add(){
        layout(!$this->isAjax());
        $this->display('add');
    } 
 
    public function view($id=null){
		$this->edit($id);
	} 

    public function edit($id = null){ 
     	layout(!$this->isAjax());
		if(empty($id)){
            $id = $this->_param('id');
        }
      	if(empty($id)){
			$this->error('Please supply an id of the record for handle.'); 
			exit();	
		}
				
		$M  =  D(MODULE_NAME);
        $condition=array('is_deleted'=>'0');
        $data = $M->where($condition)->find($id);
        if(!empty($data)) {
        	$this->data=$data;
        }
        else{
            $this->error('Sorry for we can not deal your handle.'); 
            exit();
        }

        logs($this->data);
        $pk = $M->getPk();
        $this->pk = $pk;
        $this->id = $M->$pk;
        if(ACTION_NAME==="view")
            $this->readonly="readonly";
		$this->display('edit');
    }

    public function operate(){ 
        layout(!$this->isAjax());
        $op = $this->_param('op');
        $id = $this->_param('id');
        $mod = $this->_param('mod');

        switch ($op) {
            case 'enable':
                $data = array( 'status'=> '1');
                break;
            case 'disable':
                $data = array( 'status'=> '0');
                break;
            case 'add':
            case 'edit':
            case 'save':
            case 'delete':
            case 'view':
            case 'index':
                $this->type='index';
            case 'refer':
            case 'export':
            case 'improt':
            case 'upload':
                if(empty($mod))$mod=MODULE_NAME;
                $mod=ucwords($mod);
                $this->operate=ACTION_NAME.'?mod='.$mod.'&op=';
                R($mod.'/'.$op);
                exit();
                break;
            default:
                $this->error('Unknow handle.'); 
                exit();
                break;
        }
        if(empty($id)){
            $this->error('Please supply an id of the record for handle.'); 
            exit(); 
        }
                
        $M  =  D(MODULE_NAME);
        
        if(isset($data)){
            $result = $M->where($M->getPk().'='.$id)->setField($data);
            if(empty($result)) {
                $this->error('Sorry for we can not deal your handle.'); 
                exit();
            }
        }

       logs($this->param(),$result);
        if($this->isAjax())
            $this->ajaxReturn('Success');
        else
            $this->success('Success.');

    }
    
    public function save(){ 
     	layout(!$this->isAjax()); 
     		
        $M  = D(MODULE_NAME);
        if(!$M->create()){
            $this->error($M->getError());
            exit();
        }
        $id = $M->getPk();
        if(empty($M->$id)){
            $result = $M->add();
            $data = $M->find($id);
            logs($data,$result);
        }
        else{
            $result = $M->save();
            $data = $M->find($M->$id);
            logs($data,$result);
        }
        
        if($this->isAjax())
            $this->ajaxReturn($result?'Success':'Fail');
        else if($result){ 
                $this->success('Success');
            }
            else{
                $this->error('Fail');
            }                    
    }
   
    public function delete(){
    	layout(!$this->isAjax()); 
        $ids    = $this->_param('id');
        $M      =  D(MODULE_NAME);
        $id     = $M->getPk();
        $data=array('is_deleted'=>1);
        //$result = $M->where($id.' in('.$ids.')')->save($data);//逻辑删除
        $result = $M->where($id.' in('.$ids.')')->delete();
        logs($ids,$result);
        if($this->isAjax())
  			$this->ajaxReturn($result?'Success':'Fail');
        else if($result){ 
         		$this->success('Success');
 			}
			else{
				$this->error('Fail');
			}              
 	}

    public function page($select = null,$condition = null,$type=null,$custom=false){
        $p              = $this->_param("p");
        if(empty($p))$p = 1;
        $this->pk       = $select->getPk();
        $module         = $select->getModelName();
        $page_size      = C('PAGE_SIZE');
        if(empty($this->type)){
            if(isset($type))
                $this->type = $type;
            else
                $this->type = ACTION_NAME;
        }
        $this->module   = $module;
        $this->columns  = C("columns.".$module);
        $this->query    = C("query.".$module);
        $this->pill     = C("pill.".$module);

        if(isset($select)){
            if(!$result){
              $this->data = $select->page($p.','.$page_size)->where($condition)->select();
              $this->pk=$select->getPk();
            }
            else{
                $M = M();
                $this->data = $M->query($select->options['table']);
            }
            $count  = $select->count();
        }
        else{
            
        }

        $param  =  array(
            'listRows'  => 10,
            'parameter' => $condition,
            'target'    => "table-content-{$this->type}",
            'pagesId'   => 'page'
        );
        $toolbar_tr =  array(
            'menu' =>array(
                'view',
                'add',
                'edit',
                'delete'
          ),
    	   'index' =>array(
    	  		'view',
    	  		'edit',
    	  		'delete'
    	  ),
           'module'=>array(
            'view',
            'edit'
        ),
    	  'refer' =>array(
    	  		'refer'	
			),
			'view'  =>array(
				'view'
			)
     	); 
     		
		$this->toolbar_tr = $toolbar_tr[$this->type];
		extract($param);
        import("ORG.Util.Page");
        if ($target && $pagesId)
            $Page       = new Page($count, $page_size, $parameter, $url,$target, $pagesId);
        else
            $Page       = new Page($count, $page_size, $parameter,$url);
        $this->page     = $Page->show();
        $this->prePage  = $Page->prePage;
        $this->nextPage = $Page->nextPage;
        $this->pageinfo = $Page->nowPage.'/'.$Page->totalPages;

        layout(!$this->isAjax());
        if(!$custom){
             if(!$this->isAjax())
                $this->display('Comm:Index:table-admin');
            else
                $this->display('Comm:Index:table-operate');
        }
        else{
            $this->display();
        }
    }

    public function export(){
        vendor('PHPExcel.PHPExcel');
        $Excel = new PHPExcel(); 
        $Excel->getProperties()
        ->setCreator("Marchsoft")
        ->setLastModifiedBy("Marchsoft")
        ->setTitle("Marchsoft")
        ->setSubject("Marchsoft")
        ->setDescription("Marchsoft")
        ->setKeywords("Marchsoft")
        ->setCategory("Marchsoft");
        $Excel->setActiveSheetIndex(0);
        $Sheet  =  $Excel->getActiveSheet();  
        $ary  =  array("", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        
        $Sheet->getDefaultColumnDimension()->setAutoSize(true);
        $Sheet->getDefaultStyle()->getFont()->setName('Arial');
        $Sheet->getDefaultStyle()->getFont()->setSize(13);

        $i = 1;
        $columns = C("columns.".MODULE_NAME);
        foreach ($columns as $key  => $value) { 
            $Sheet->setCellValue($ary[$i/27].$ary[$i%27].'1', $value);
            ++$i;
        }
        $M  =  D(MODULE_NAME);
        $result = $M->select();
        for($j  = 0;$j<count($result) ; ++$j){
            $i  = 1;
            foreach ($columns as $key  => $value){
                $Sheet->setCellValue($ary[$i/27].$ary[$i%27].($j+2), $result[$j][$key]);
                ++$i;
            }
        }
        date_default_timezone_set("Asia/Shanghai"); 
        $date = date("Y-m-d-H-i-s");
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition:attachment;filename = update_".date('Y-m-d',mktime()).".xls");
        header("Pragma:no-cache");
        header("Expires:0");
        $objWriter  =  PHPExcel_IOFactory::createWriter($Excel, 'Excel5'); 
        $objWriter->save('php://output');
    }
    private function import($result){
        Vendor("PHPExcel.PHPExcel");
        Vendor("PHPExcel.PHPExcel.IOFactory");
        Vendor("PHPExcel.PHPExcel.Reader.Excel5");
        if($result)
        {
            $objReader  =  PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
            $objPHPExcel  =  $objReader->load($uploadfile);   
            $sheet  =  $objPHPExcel->getSheet(0);
            $highestRow  =  $sheet->getHighestRow(); // 取得总行数
            $highestColumn  =  $sheet->getHighestColumn(); // 取得总列数
           
            //读取文件
            for($j = 1;$j<= $highestRow;$j++)
            {
                for($k = 'A';$k<= $highestColumn;$k++)
                {
                 $kj = trim(iconv('utf-8','gbk',$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue()));
                 if($kj == "")
                 {
                    break ;
                 }
                 else
                 {
                    $str .=  $kj.'\\';//读取单元格
                 }
                }
                //explode:函数把字符串分割为数组。
                $str    = substr($str,0,strlen($str)-1);
                $strs   =  explode("\\",$str);
                $r      =  eval('return '.iconv('gbk','utf-8',var_export($strs,true)).';');
                print_r($r); exit;                                      
                $str    = "";
            }
                        
        }
        return $msg;
    }
    public function upload(){
        import('ORG.Net.UploadFile');
        $upload             = new UploadFile();
        $upload->maxSize    = C('MAX_UPLOAD_FILE_SIZE');
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','xls','doc');
        $upload->savePath   = __PUBLIC__.'/Upload/';
        if(!$upload->upload()) {
            $this->error($upload->getErrorMsg());
        }else{
            $info = $upload->getUploadFileInfo();
            $this->import($info);
        }
    }

}
