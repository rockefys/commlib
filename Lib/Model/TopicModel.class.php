<?php
Class TopicModel extends AdvModel{
    protected $_map = array(
    	'id'		=>'id',
        'views'	=>'views',
        'comments'	=>'comments',
        'good'  =>'good',
        'favorite'  =>'favorite'
    );

    protected $_validate = array(

    );
     protected $_auto = array (

    );
}