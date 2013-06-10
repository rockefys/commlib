<?php
Class UserModel extends  AdvModel{
    protected $_map = array(
        'uname' =>'username', // 把表单中name映射到数据表的username字段
        'mail'  =>'email', // 把表单中的mail映射到数据表的email字段
        'pwd'	=>'password'
    );	
    protected $readonlyField = array('username', 'email');

}