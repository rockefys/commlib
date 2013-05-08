<?php
class TableAction extends Action {
    public function index($select=null,$type=null,$condition=null,$result=false,$pk=null){
        $page_size=C('PAGE_SIZE');
        if(array_key_exists( 'p',$_GET)) {
            $p=$_GET['p'];
        }
        else{
            $p=1;
        }
        $module=$select->getModelName();
        if(isset($pk))
            $this->pk=$pk;
        else
            $this->pk=$select->getPk();

        $con=C("query.".$module);
        $this->type=$type;
        $this->module=$module;
        $this->queryUrl=U($module.'/index');
        $this->columns=C("columns.".$module);
        $this->cname=$con['cname'];
        $this->cond=$con['cond'];
    	if(isset($select)){
            if(!$result)
    		  $this->data=$select->page($p.','.$page_size)->select();
            else{
                $M=M();
                $this->data=$M->query($select->options['table']);
            }
            $count =$select->count();
    	}
    	else{
    		
    	}

        import("ORG.Util.Page");
        $param = array(
            'listRows'=>10,         //每页记录数
            'parameter'=>$condition,//url分页后继续带的参数
            'target'=>'table-content',    //ajax更新内容的容器id，不带#
            'pagesId'=>'page'      //分页后页的容器id不带# target和pagesId同时定义才Ajax分页
        );
        extract($param);
        if ($target && $pagesId)
            $Page = new Page($count, $page_size, $parameter, $url,$target, $pagesId);
        else
            $Page = new Page($count, $page_size, $parameter,$url);
        $this->page = $Page->show();
        $this->prePage=$Page->prePage;
        $this->nextPage=$Page->nextPage;
        $this->pageinfo=$Page->nowPage.'/'.$Page->totalPages;
        layout(!$this->isAjax());
        if($this->isAjax()){
            $this->display('Comm:Index:table');
        }
        else{
            $this->display('Comm:Index:table-admin');
        }
        
    }

}