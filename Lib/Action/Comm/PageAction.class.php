<?php
class PageAction extends Action {
    public function index($select=null,$p,$display){
        if(!isset($p)){$p=1;}
        $page_size=C('PAGE_SIZE');
    	$this->data=$select->page($p.','.$page_size)->select();
        $count =$select->count();

        import("ORG.Util.Page");
        $Page = new Page($count,$page_size);
        $this->page=$Page->show();
        $this->prePage=$Page->prePage;
        $this->nextPage=$Page->nextPage;
        $this->pageinfo=$Page->nowPage.'/'.$Page->totalPages;
        $this->display($display);
    }
}