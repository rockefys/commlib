<?php
class BroadbandModel extends Model {

    protected $insertFields = array('id','user_id','phone','cat_id','start_date','end_date','use_during','cost','manager','memo','broadband_username','broadband_password','status','update_user','update_time','is_deleted');
    protected $updateFields = array('user_id','phone','cat_id','start_date','end_date','use_during','cost','manager','memo','broadband_username','broadband_password','status','update_user','update_time','is_deleted');
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
            "join"=>array("join broadband_cat on broadband.cat_id=broadband_cat.id ","join user on broadband.user_id=user.id "),
"field"=>"broadband.*,broadband_cat.name,user.username",
        ),
        'latest'=>array(
            'where'=>array('is_deleted'=>'0'),
            'order'=>'update_time DESC',
        ),


    );
}