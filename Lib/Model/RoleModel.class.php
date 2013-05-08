<?php
Class RoleModel extends Model{
    protected $_map = array(
    	'id'	=>'id',
        'numb' =>'numb',
        'name'  =>'name'
    );	
    protected $_validate = array(
    array('name','require','name require.'), 
    array('name','','name already exsits.',0,'unique',1)
);
}