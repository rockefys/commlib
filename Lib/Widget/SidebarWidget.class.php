<?php
class SidebarWidget extends Widget{
    public function render($data){
    	$M=M('Menu');
        $data=$M->field("id,level,pid,name,title,link,target")->where("level='0' and location='left'")->select();

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
    	return $content;
   }
	public function show(&$data,&$ary,&$index,$i,&$j,&$menu){
		$menu.= "
<div class='well sidebar-nav'>
	<ul class='nav nav-list '>
	  	<li class='nav-header'>
		    Menu
		</li>";
		foreach ($ary as $key => $value) {
			$menu.= "<li><a href='".U($data[$index[$key]]['link'])."' target='_".$data[$index[$key]]['target']."'>".$data[$index[$key]]['title']."</a>";
			if (is_array($value)&&(count($value)>0)){
				$this->show($data,$value,$index,$i+1,++$j,$menu);
			}
			$menu.= "</li>";
		}
		$menu.="</ul>
</div>";
	}

}