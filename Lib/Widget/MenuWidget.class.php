<?php
class MenuWidget extends Widget{
    public function render($data){
    	$M=M('Menu');
        $result['data']=$M->field("id,level,pid,name,title,link,target")->where("location='".$data['location']."'")->order('pid,`order` asc')->select();
		$content = $this->renderFile($data['location'],$result);
		
    	return $content;
   }

}