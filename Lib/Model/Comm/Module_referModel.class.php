<?php
class Module_referModel extends Model {

    protected $insertFields = array('id','module','fk','module_refer','pk','condition','fk_id','pk_id','relation_table','map_name','map_type','refer_type','map_fields','as_fields','field_show','order','limit');
    protected $updateFields = array('module','fk','module_refer','pk','condition','fk_id','pk_id','relation_table','map_name','map_type','refer_type','map_fields','as_fields','field_show','order','limit');
    protected $readonlyField = array('id');

    //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
    protected $_validate = array(
        
    );

    //array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array (
        
    );

    //'数据表字段'=>'表单字段'
    protected $_map = array(
        
    );

    protected $_scope = array(
        'default'=>array(
            'where'=>array('is_deleted'=>'0'),
            'order'=>'id DESC',
            
        ),
        'latest'=>array(
            'where'=>array('is_deleted'=>'0'),
            'order'=>'update_time DESC',
        ),


    );
}