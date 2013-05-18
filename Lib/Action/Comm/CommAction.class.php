<?php
class CommAction extends Action {
    public function _initialize(){
        //权限验证
    }

    public function _empty($action){
       
    } 
    
    public function index(){ 
        $condition = $this->_param('query');
        if(!empty($condition)){
            $condition = queryFilter($condition);
        }

        $M =  M(MODULE_NAME);
        $select = $M->where($data)->join()->order();
  
        $this->page($select,$condition); 
    }
 
    
    public function lists(){
        $table = 'Article';
        $M =  M($table);
 
        $select  =  $M->table($table.' a')->field("a.*,t.*")->join("topic t on a.id = t.id")->order('a.id desc');
        $page_size = C('PAGE_SIZE');
        if(array_key_exists( 'p',$_GET)) {
            $p = $_GET['p'];
        }
        else{
            $p = 1;
        }
        pager($select,$p,'Comm:Index:list');
    } 
    
    public function refer(){
        $this->index();
    }

    public function add(){
        layout(!$this->isAjax());
        $this->display();
    } 
 
    public function view($id = null){
		$this->edit(id);
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
				
		$M  =  M(MODULE_NAME);
        $this->data = $M->find($id);
        if(empty($this->data)) {
        	$this->error('Sorry for we can not deal your handle.'); 
			exit();
        }

        $this->log(MODULE_NAME.'/'.ACTION_NAME,$this->data);
        $pk = $M->getPk();
        $this->pk = $pk;
        $this->id = $M->$pk;

		//if($this->isAjax())
		//	$this->ajaxReturn($this->data);
		//else
        
			$this->display('add');
    }
    public function operate($id = null,$op=null){ 
         layout(!$this->isAjax());
        if(empty($id)){
            $id = $this->_param('id');
            $op = $this->_param('op');
        }
        if(empty($id)){
            $this->error('Please supply an id of the record for handle.'); 
            exit(); 
        }
                
        $M  =  M(MODULE_NAME);
        switch ($op) {
            case 'enable':
                $data = array( 'enable'=> 1);
                break;
            case 'disable':
                $data = array( 'enable'=> 0);
                break;
            default:
                break;
        }
        if(isset($data)){
            $result = $M->where($M->getPk().'='.$id)->setField($data);
            if(empty($result)) {
                $this->error('Sorry for we can not deal your handle.'); 
                exit();
            }
        }

       $this->log(MODULE_NAME.'/'.ACTION_NAME,$result);
        if($this->isAjax())
            $this->ajaxReturn('Success');
        else
            $this->success('Success.');

    }
    
    public function save(){ 
     	layout(!$this->isAjax()); 
     		
        $M  = M(MODULE_NAME);
        if(!$M->create()){
            $this->error($M->getError());
        }
        $id = $M->getPk();
        if(empty($M->$id)){
            $result = $M->add();
            $data = $M->find($id);
            $this->log(MODULE_NAME.'/add',$data,$result);
        }
        else{
            $result = $M->save();
            $data = $M->find($M->$id);
            $this->log(MODULE_NAME.'/'.ACTION_NAME,$data,$result);
        }
        
        if($result){
             $this->ajaxReturn(1,"success",1);
        }
        else{
             $this->ajaxReturn(0,'fail',0);
        }            
    }
   
    public function delete(){
    	layout(!$this->isAjax()); 

        $ids    = $this->_post('ids');
        $M      =  M(MODULE_NAME);
        $id     = $M->getPk();
        $result = $M->where($id.' in('.$ids.')')->delete();

        $this->log(MODULE_NAME.'/'.ACTION_NAME,$ids,$result);
        if($this->isAjax())
  				$this->ajaxReturn($result?'Success':'Fail');
        else if($result){ 
         		$this->success('Success');
 			}
			else{
				$this->error('Fail');
			}              
 		}

    public function log($module,$data,$result = false){
        $M = M('log');
        $M->update_user = session('userid');
        $M->update_time = date('Y-m-d H:i:s',time());
        $M->operate     = $module;
        $M->content     = json_encode($data);
        $M->result      = $result;
        $M->ip          =  get_client_ip();
        $M->status      = '0';
        $M->add();
    }
    public function page($select = null,$condition = null,$type=null){
        $p              = $this->_param("p");
        if(empty($p))$p = 1;
        $this->pk       = $select->getPk();
        $module         = $select->getModelName();
        $con            = C("query.".$module);
        $page_size      = C('PAGE_SIZE');
        if(isset($type))
            $this->type = $type;
        else
            $this->type = ACTION_NAME;
        $this->module   = $module;
        $this->queryUrl = U($module.'/index');
        $this->columns  = C("columns.".$module);
        $this->cname    = $con['cname'];
        $this->cond     = $con['cond'];
        if(isset($select)){
            if(!$result)
              $this->data = $select->page($p.','.$page_size)->select();
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
    	   'index' =>array(
    	  		'view',
    	  		'edit',
    	  		'delete'
    	  ),
    	  'refer' =>array(
    	  		'check',
    	  		'view'  		
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
        
        if($this->isAjax()){
            $this->display('Comm:Index:table');
        }
        else{
            $this->display('Comm:Index:table-admin');
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
        $M  =  M(MODULE_NAME);
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