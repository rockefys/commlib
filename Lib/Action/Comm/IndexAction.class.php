<?php
class IndexAction extends Action {
    public function index(){
        if(session('?username')){
            redirect(U('User/index'));
        }
        else{
            redirect(U('Login/index'));
        }
    }

    public function init(){
        
    }
    /*
    public function table(){
        $table="user";
        $columns=array("username"=>"username","password"=>"pwd","displayname"=>"name","email"=>"email","registered"=>"joinTime");
        $model= M($table);
        $select=$model->where()->join()->order();
        $action=A('Comm/Table');
        $action->index($table,$columns,$select);

    }*/
    
}