<?php
class AccountModel extends Model {

    protected $insertFields = array('id','activation_key','reset_token','last_modified','last_failed_attempt','failed_logins','last_failed_ip');
    protected $updateFields = array('admin_profile','activation_key','last_login_date','last_modified','last_login_ip','lockout_expires','last_failed_ip');
    protected $readonlyField = array('id','reset_token','last_failed_attempt','failed_logins');

    //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
    protected $_validate = array(
           array('verify','require','验证码必须！'), //默认情况下用正则进行验证
    array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
    array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
    array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
    array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
    );

    //array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array (
        array('status','1'),  // 新增的时候把status字段设置为1
    array('password','md5',1,'function') , // 对password字段在新增的时候使md5函数处理
    array('name','getName',1,'callback'), // 对name字段在新增的时候回调getName方法
    array('create_time','time',2,'function'), // 对create_time字段在更新的时候写入当前时间戳
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