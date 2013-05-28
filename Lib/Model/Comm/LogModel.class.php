<?php
class LogModel extends Model {

    protected $insertFields = array('id','operate','para','content','result','ip','update_user','update_time','status');
    protected $updateFields = array('operate','para','content','result','ip','update_user','update_time','status');
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
            "join"=>array("join user on log.update_user=user.id "),
"field"=>"log.*,user.username",
        ),
        'latest'=>array(
            'where'=>array('is_deleted'=>'0'),
            'order'=>'update_time DESC',
        ),


    );
}