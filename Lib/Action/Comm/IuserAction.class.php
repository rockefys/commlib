<?php
class IuserAction extends CommAction {
	public function add(){
		$M = M('User');
		$id=session('userid');
		$this->data=$M->find($id);
		$pk=$M->getPk();
		$this->pk=$pk;
		$this->id=$M->$pk;
		layout(!$this->isAjax());
        	$this->display();
    }
}