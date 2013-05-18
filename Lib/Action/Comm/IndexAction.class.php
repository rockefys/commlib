<?php
class IndexAction extends Action {
    public function index(){
        if(session('?username')){
            $this->display('hero');
        }
        else{
            redirect(U('Login/index'));
        }
    }
}