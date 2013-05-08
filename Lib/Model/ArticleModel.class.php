<?php
Class ArticleModel extends Model{
    protected $_map = array(
    	'id'		=>'id',
        'name'  	=>'name',
        'title'		=>'title',
        'excerpt'	=>'excerpt',
        'content'	=>'content',
        'author'	=>'author',
        'tags'		=>'tags',
        //''	=>'',
        //''	=>'',
    );

    protected $_validate = array(
        array('title','require','title require.'), 
        array('title','','title already exsits.',0,'unique',1)
    );
     protected $_auto = array ( 
        array('status','publish'),  // 新增的时候把status字段设置为1
    );
}