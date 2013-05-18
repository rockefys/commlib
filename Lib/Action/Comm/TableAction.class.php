<?php
class TableAction extends Action {
    public function index($select=null,$condition=null,$type=null,$result=false,$module=null,$pk=null){
        $p              = $this->_get("p");//tp3.1.3bug,_param无法取到p
        if(empty($p)) $p = 1;
        if(empty($pk)) $this->pk = $select->getPk();
        if(empty($module))$module = $select->getModelName();
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

        if(!$result){
            $this->data = $select->page($p.','.$page_size)->select();
            $count  = $select->count();
        }
        else{
            $this->data = $select;
            $count  = count($select);
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
    public function views($module,$pk,$data){
        $page_size=C('PAGE_SIZE');
        if(array_key_exists( 'p',$_GET)) {
            $p=$_GET['p'];
        }
        else{
            $p=1;
        }
        if(isset($pk))
            $this->pk=$pk;
        $this->type='index';
        $con=C("query.".$module);
        $this->module=$module;
        $this->queryUrl=U($module.'/index');
        $this->columns=C("columns.".$module);
        $this->cname=$con['cname'];
        $this->cond=$con['cond'];
        $this->data=$data;

        $count =10;//count($data);
        import("ORG.Util.Page");
        $param = array(
            'listRows'=>$count,         //每页记录数
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
        $this->pageinfo=$Page->totalPages;
        layout(!$this->isAjax());

        if($this->isAjax()){
            $this->display('Comm:Index:table');
        }
        else{
            $this->display('Comm:Index:table-admin');
        }
    }
}