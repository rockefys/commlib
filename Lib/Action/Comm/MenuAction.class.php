<?php
class MenuAction extends CommAction {
	
	public function indexs($n){
		$M=M(MODULE_NAME);
		$menu=$M->field("link")->where("id=$n")->find();
        $tabs=$M->field("id,level,pid,name,title")->where("pid=$n")->select();
		R($menu['link']);
	}
	public function tree1(){
        $this->display("Index:tree1");
    }
    public function tree(){
        $this->display("Index:treeedit");
    }
    public function sidebar(){
        $M=M(MODULE_NAME);
        $data=$M->field("id,level,pid,name,title")->select();

        //转换成数组，同级节点的索引值相同

        foreach ($data as $key => $value) {
        	$ary[$value['level']][$value['id']]=array();
        	$index[$value['id']]=$key;
        }
        $i=0;
        $n=count($ary);
        //倒序将下一级节点添加到当前节点的子节点
    	foreach (array_reverse($ary) as $key => $value) {
    		if(++$i==$n)break;
    		foreach ($value as $k => $val) {
    			$ary[$data[$k-1]['level']-1][$data[$k-1]['pid']][$k]=$ary[$key][$k];		
    		}
    	}
    	foreach ($ary as $key => $value) {
    		$i=1;$j=1;
    		$content='';
    		$this->show($data,$ary[$key],$index,$i,$j,$content);
    		break;
    	}
    	echo $content;
    	//layout(false);
    	//dump($this->content);
    	$this->display('Index:sidebar');
    }

    
    public function allnodes($ids=null){
        if(!empty($ids)){
            $M= M();
            $n=count(explode(',', $ids));
            $result=$M->query("
            SELECT id,name,title,pid
            FROM menu
            ");
        }
        else{
            $M= M(MODULE_NAME);
            $result=$M->field("id,name,pId")->select();
        }
        $this->ajaxReturn($result,'JSON');
     }
    public function nodes(){
        if(!$this->isAjax()){
            //return;
        }
        $id=htmlentities($_POST['id']);
        $type=htmlentities($_POST['type']);
        $data['name']=htmlentities($_POST['name']);
        $data['pid']=htmlentities($_POST['pid']);
        $M=M('MODULE_NAME');
        if(empty($type)){
            if(empty($id))$id=0;
            $result=$M->field("id,name,pid")->where('pid='.$id)->select();
        }else if($type=="all"){
            $result=$M->field("id,name,pid")->select();
        }else if($type=="edit"){
            $result=$M->where('id='.$id)->save($data);
        }else if($type=='add'){
            $data['isnode']=($data['pid']==0?'0':'1');
            $result=$M->add($data);
        }else if($type=='del'){
            $result=$M->where('id='.$id)->delete();
        }
        $this->ajaxReturn($result,'JSON');
        
    }
}