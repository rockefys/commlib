<?php
class MenuWidget extends Widget{
    public function render($data){
    	$M=M('Menu');
    	$condtion=array('level' => $data['level'],'status'=>'1','is_deleted'=>0);

        $result['data']=$M->field("id,level,pid,name,title,link,target")->where($condtion)->order('pid,`order` asc')->select();
		$content = $this->renderFile($data['location'],$result);
		
    	return $content;
   }
}