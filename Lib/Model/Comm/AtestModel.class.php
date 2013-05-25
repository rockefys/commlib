<?php
class AtestModel extends AdvModel {
    protected $fields =  array (
        //0 => 'id', 
        //1 => 'name', 
        //'_autoinc' => true, 
        //'_pk' => 'id', 
        //'_type' => array ( 
        //    'id' => 'int(11) unsigned', 
        //    'name' => 'varchar(50)', 
        //),
            );

    protected $insertFields = array();
    protected $updateFields = array();
    protected $readonlyField = array();

    public $viewFields = array(
        //'Category'=>array('title'=>'category_name', '_on'=>'Blog.category_id=Category.id','_type'=>'INNER'),
            );

    protected $_filter = array(
        //'过滤的字段'=>array('写入过滤规则','读取过滤规则',是否传入整个数据对象),
            )

    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
            )

    protected $_auto = array ( 
        //array(填充字段,填充内容,[填充条件,附加规则])
            }

    protected $_map = array(
        //'txt_user' =>'username', //把表单中的text_user字段映射到数据表的username字段
            )

    protected $_scope = array(
        'default'=>array(
            //'table'->array(''=>''),
            //'field'->array(''=>''),
            //'where'=>array(''=>''),
            //'order'=>array(''=>''),
            //'limit'->array(''=>''),
        ),
    )
}