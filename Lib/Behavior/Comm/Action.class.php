<?php
class AuthCheckBehavior extends Behavior {
    protected $options   =  array(
    	'USER_AUTH_ON'        =>true,   
    	'USER_AUTH_ID'        => 'user_id', 
    );
    public function run(&$return){
        if(C('USER_AUTH_ON')) {
            if(empty(session('username')){
            	$this -> redirect(U("Login/index"));
            }
        }
    }