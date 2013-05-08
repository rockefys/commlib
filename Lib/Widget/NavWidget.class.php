<?php
class NavWidget extends Widget{
    public function render($data){
    	$M=M('Menu');
        $result=$M->field("id,level,pid,name,title,link,target")->where("link like '".$data['link']."%'")->find();
		if($data['type']=='cur')
			$content=$result=$result['title'];
		else if($data['type']=="pid")
			$content=$result=$result['pid'];
    	return $content;
   }

}