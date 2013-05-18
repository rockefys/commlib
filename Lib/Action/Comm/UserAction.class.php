<?php
class UserAction extends CommAction {
   public function profile(){
      $M = M('user');
      if($this->isPost()){
         $M = M('User');
         $result=$M->create();
         if(!empty($result)){
            $M->id=session('userid');
            $M->username=session('username');
            $M->save();
            $this->msg='Success';
         }
         else{
            $this->msg='Fail';
         }
      }

      $id=session('userid');
      $this->data=$M->find($id);
      $pk=$M->getPk();
      $this->pk=$pk;
      $this->id=$M->$pk;
      layout(!$this->isAjax());
      $this->display('add');

   }
   public function changepwd(){
   		if($this->_post('password')){
   			$old_password=$this->_post('password');
   			$new_password=$this->_post('new_password');
   			if($new_password===$this->_post('new_password_confirm')){
   				$M=M('User');
	   			$password=$M->where('id='.session('userid'))->getField('password');
	   			$encrypt_password=md5Encrypt($old_password);
			    if(isset($password) && ($password===$encrypt_password)){
			    	if(!empty($password)){
				    	$result=$M->where('id='.session('userid'))->setField('password',md5Encrypt($new_password));
				    	$this->msg='Success';
				    }
   				}
   				else{
   					$this->msg='密码错误';
   				}
   			}
   			else{
   					$this->msg='两次密码输入不一致';
   				}

   		}
   		else{
   			
   		}
         $this->display();
   }

}