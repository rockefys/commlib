<?php
class LoginAction extends Action {
    public function index(){
    	if(session('?username') && session('userid')){
    		redirect(U('Index/index'));
    		exit();
    	}
    	if($this->isGet()){	
    		if(session('errors')>2)$this->show_recaptcha();					//如果为get请求并且错误尝试次数大于3,显示验证码
    		$this->display();
    		exit();
    	}
    	else if($this->isPost()){
    		 session('errors',0);
		    if(session('errors')<3 || $this->recaptcha_valiaute()) {	//如果为post请求并且错误尝试次数小于4,则无需验证；否则需要对验证码进行验证
		    	$username=htmlentities($this->_post('usernameoremail'));
		    	$password=htmlentities($this->_post('password'));
		    	$User = M('user');
				$User->where("username='%s' or email='%s' ",$username,$username)->find();
		        if(isset($User->password) && ($User->password===md5Encrypt($password))){
		            
		            session('username',$username);
					session('userid',$User->id);

					$M=M('log');
					$M->update_user = session('userid');
					$M->update_time = gettime();
					$M->operate 	= 'login';
					$M->ip 			= get_client_ip();
					$M->status 		= '0';
					$M->add();

					$this->msg='success!';
		            redirect(U('Index/index'));
		            exit();
		        }
		        else{
		        	if(session('errors')>2) {$this->recaptcha = recaptcha_get_html($this->publickey, $error);}
		        	$this->msg = "Wrong username or email and password combine.";
		        }
		    }
		   
		    session('errors',session('errors')+1);
		    $this->errors=session('errors');
			$this->username=$username;
        	$this->display();
		}
		else if($this->isAjax()){
			exit();//Ajax cross domain attack
		}
    }

    public function logout(){
		session('username',null);
		session('userid',null);
		session('mediaID',null);
		session('mediaUserID',null);
        $this->redirect("index");
    }

    protected function show_recaptcha(){
    	if(C('SHOW_RECAPTCHA')){
    		$this->publickey  = C('RECAPTCHA_PUBLICKEY');
    	}
    	else{
    		return;
		}
    	load("@.recaptchalib");
		$this->recaptcha = recaptcha_get_html($this->publickey, $this->error);
    }

    protected function recaptcha_valiaute(){
    	if(C('SHOW_RECAPTCHA')){
    		$this->publickey  = C('RECAPTCHA_PUBLICKEY');
    		$this->privatekey = C('RECAPTCHA_PRIVATEKEY');
    	}
    	else{
    		return true;
		}

    	load("@.recaptchalib");
    	$resp = null;
		$error = null;

		if (isset($_POST['recaptcha_response_field'])) {
		    $resp = recaptcha_check_answer ($this->privatekey,
		    	$_SERVER['REMOTE_ADDR'],
		    	$_POST['recaptcha_challenge_field'],
		    	$_POST['recaptcha_response_field']);

		    if ($resp->is_valid) {
		        return true;
		    } else {
		        $error = $resp->error;
		    }
		}
		$this->recaptcha = recaptcha_get_html($this->publickey, $error);
		return false;
    }

    public function signup(){
    	if($this->isGet()){
    		$this->display();
    		exit();
    	}
    	session('errors',0);
    	session('errors',session('errors')+1);
    	if(session('errors')>3 && !$this->recaptcha_valiaute()) {
			$this->display();
			exit();
		}
		$User = D("User");
		$username=$this->_post('uname');
		$email=$this->_post('mail');
		$password=$this->_post('pwd');

		//Verify
		$msg=(empty($username)?'username ':'').(empty($email)?'email ':'').(empty($password)?'password ':'');
		if(!empty($msg)){
			$msg.='shuould be input.';
		}else {
			if($password!=$this->_post('confirm')){
				$msg="The password didn't match.";
			}else{
				$id=$User->getFieldByUsername($username,'id');
				if(!empty($id)){
					$msg="Username already exists.";
				}
				else{
					$email=$User->getFieldByEmail($email,'id');
					if(!empty($email)){
						$msg="$email,Email already exists.";
					}
				}
			}
		}
		if(!empty($msg)){
			$this->username=$username;
			$this->email=$email;
			$this->msg=$msg;
			$this->display();
			exit();
		}

		$result=$User->create();
		if($result){
			$User->password=md5Encrypt($password);
			$User->add();
			$this->success('Register Success.',U('Login/index'),3);
		} else {
		    $this->error('Register Fail.',U('Login/signup'));
		}
		
		$this->display();
	}

}