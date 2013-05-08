<?php
class AuthCheckBehavior extends Behavior {
    protected $options   =  array(
    	'USER_AUTH_ON'        =>true,   
    	'USER_AUTH_ID'        => 'userid', 
    );
    public function run(&$return){
        //if(C('USER_AUTH_ON')) {
            $username=session('usernam');
            //if(empty($username)){
            	header("Login/index");
            //}
       // }
    }
}