<?php
class NavWidget extends Widget{
    public function render($data){
    	$M=M('Menu');
    	$condition=array('link' => $data['link'],'level'=>2 );
        $result=$M->field("id,level,pid,name,title,link,target")->where($condition)->find();
		if($data['type']=='cur')
			$content=$result=$result['title'];
		else if($data['type']=="pid")
			$content=$result=$result['pid'];
    	return $content;
   }

}