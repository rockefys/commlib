<?php
class ArticleAction extends CommAction {
    public function index(){
    	$table=MODULE_NAME;
        $columns=C("columns.".$table);
        $M= M($table);
        $M->table($table.' a')->field("a.*,t.*")->join("topic t on a.id=t.id")->where("1=1")->order('a.id desc');
        //R('Comm/Table/index',array($M,$type));
        $this->list=$M->select();
        $this->display("article");
    }

   
    public function views($n=0){
        $table=MODULE_NAME;
        $columns=C("columns.".$table);
        $M= M($table);
        $M->table($table.' a')->field("a.*,t.*")->join("topic t on a.id=t.id");
        $M->where("a.id=$n and t.id=$n")->limit(1);
        $T=M("topic");
        $T->switchModel("Adv")->where('id='.$n)->setLazyInc('views',1,60);

        $this->article=$M->find();
        $this->display("article_detail");
    }
    public function save(){
        // $M = M(MODULE_NAME);
        // if(!$M->create()){
        //     $this->ajaxReturn(1,'Fail to save.',1);
        // }
        // $time=getTime();
        // $M->tags=implode(',', $this->_post('tags'));
        // //$M->tags=tagFilter($M->tags);
        // $pk=$M->getPk();
        // $id=$M->$pk;
        // $result=saveM($M);
        // if(empty($id) && $result){
        //     $T=M("topic");
        //     $T->id=$result;
        //     $T->post_date=$time;
        //     $T->add();
        // }
        // else{

        //     //if(empty($retult))
        //     //    $this->ajaxReturn($M->getError());
        // }
        // $this->redirect('views',array('n'=>(empty($id)?$result:$id)));
    }
}