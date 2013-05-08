<?php
class APIAction extends Action {
    public function index(){
    	
        $this->display();
    }

    public function delicious(){
        $username="rockefys";
        $password="1234560";
        Vendor('delicious');
        $delicious = new delicious(array('username'=>$username,'password'=>$password));
        $_params = array(
            'results'=>'',
            'meta'=>'yes'
        );
        $result = $delicious->posts_all($_params);
        $this->list=$result;
        $this->display();
    }
    /*
array(1) {
  [0] => array(9) {
    ["description"] => string(87) "贝叶斯推断及其互联网应用（三）：拼写检查 - 阮一峰的网络日志"
    ["extended"] => string(0) ""
    ["hash"] => string(32) "17a6ba16e1490dd9f2e967ac53ca2ec6"
    ["href"] => string(62) "http://www.ruanyifeng.com/blog/2012/10/spelling_corrector.html"
    ["meta"] => string(32) "d73dec823b8c409c38293fad3c306232"
    ["private"] => string(2) "no"
    ["shared"] => string(3) "yes"
    ["tag"] => string(8) "bayesian"
    ["time"] => string(20) "2012-11-07T21:56:56Z"
  }
}
*/

    public function pocketv2(){
    	$username="Rockefys";
    	$password="1234560*";
    	$APIkey='7a8B8p02T2f0gb78cFA26eDD84g0o2b7';
    	$url="https://readitlaterlist.com/v2/get?detailType=complete&count=10&tags=1&username=$username&password=$password&apikey=$APIkey";
    	$result=curl($url);
    	$this->data=$result['list'];
    	$this->display();
    }
    public function pocket(){
    	if(!session('?access_token')){
    		R('API/access');
    	}
    	$consumer_key='10467-0ed56679a754835ac1e15622';
	    $get_url='https://getpocket.com/v3/get';

	    $access_token=session('access_token');
    	$post_data=array("detailType"=>"complete","consumer_key"=>$consumer_key,"access_token"=>$access_token);
    	$post_data=json_encode($post_data);
    	$result=curl($get_url,$post_data);
    	$this->list=$result['list'];
    	$this->display('pocket');
    }

    public function access(){
    	$consumer_key='10467-0ed56679a754835ac1e15622';
		$redirect_url=U('/API/access@ideat.org');
	    $request_url="https://getpocket.com/v3/oauth/request";
	    $authorize_url='https://getpocket.com/v3/oauth/authorize';

	    if(!session('?request_token')){
	    	$post_data=array("consumer_key"=>$consumer_key,"redirect_uri"=>$redirect_url);
	    	$post_data=json_encode($post_data);
	    	$result=curl($request_url,$post_data);
			$request_token=$result['code'];
			session('request_token',$request_token);
	    	$authorize_url="https://getpocket.com/auth/authorize?request_token=$request_token&redirect_uri=$redirect_url";
	    	redirect($authorize_url);
    	}
    	else{
    		$request_token=session('request_token');
    		session('request_token',null);
    		$post_data=array("consumer_key"=>$consumer_key,"code"=>$request_token);
	    	$post_data=json_encode($post_data);
	    	$result=curl($authorize_url,$post_data);
	    	$access_token=$result['access_token'];
	    	session('access_token',$access_token);
	    	session('username',$result['username']);
	    	R('pocket');
    	}

    }

}