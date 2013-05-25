<?php
class MenuWidget extends Widget{
    public function render($data){
    	$M=M('Menu');
    	$condition=array('level' => $data['level'],'status'=>'1','is_deleted'=>0);
    	if($data['level']==2){
    		$cond=array('link' => MODULE_NAME.'/'.ACTION_NAME,'level'=>2 );
    		$pid=$M->where($cond)->getField('pid');
    		if(empty($pid))$pid=0;
    		$condition['pid']=$pid;
    	}
        $result['data']=$M->field("id,level,pid,name,title,link,target")->where($condition)->order('pid,`order` asc')->select();
		$content = $this->renderFile($data['location'],$result);
		
    	return $content.$results;
   }
}