<?php
class NavWidget extends Widget{
    public function render($data){
    	$M=M('Menu');
    	$condition=array('link' => $data['link'],'level'=>$data['level'],'status'=>'1','is_deleted'=>0 );
        $result=$M->field("id,level,pid,name,title,link,target")->where($condition)->find();
		$content=$result[$data['type']];
    	return $content;
   }

}