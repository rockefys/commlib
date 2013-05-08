<?php
class ListAction extends Action {
    public function index(){
        $table='Article';
        $M= M($table);
        $select = $M->table($table.' a')->field("a.*,t.*")->join("topic t on a.id=t.id")->order('a.id desc');
        $page_size=C('PAGE_SIZE');
        if(array_key_exists( 'p',$_GET)) {
            $p=$_GET['p'];
        }
        else{
            $p=1;
        }
        pager($select,$p,'Comm:Index:list');
    }

    public function save($M){
        $id=$M->getPk();
        if(empty($M->$id)){
            $result=$M->add();
        }
        else{
            $result=$M->save();
        }
        if($result){
             $this->ajaxReturn(1,"success",1);
        }
        else{
             $this->ajaxReturn(0,'fail',0);
        }
    }
    
    public function delete($M,$ids){
        $id=$M->getPk();
        $result=$M->where($id.' in('.$ids.')')->delete();
        if($result){
             $this->ajaxReturn(1,"success",1);
        }
        else{
             $this->ajaxReturn(0,'fail',0);
        }
    }
}