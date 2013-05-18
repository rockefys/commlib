<?php
Class CommModel extends Model{
    protected $_map = array(
    		'id'	=>'id',
        'numb' =>'numb',
    );	
    protected $_validate = array(
    		array('name','require','name require.'), 
    		array('name','','name already exsits.',0,'unique',1)
		);
		protected $_filter = array(
    		'content'=>array('contentWriteFilter','contentReadFilter'),
		);
		protected $readonlyField = array('name', 'email');
}