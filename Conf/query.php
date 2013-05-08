<?php
return array(
'query'=> array(
	'Role' => array(
		'cname'=>array(
	        'numb'=>'编号',
	        'names'=>'列表',
	        'status'=>'状态',
	        'chk'=>'是否',
	        'name'=>'名称'
      	),
      	'cond'=>array(
	        'numb'=>'getNumb',
	        'names'=>'getName',
	        'status'=>'getStatus',
	        'chk'=>'false',
	        'name'=>''
      	),
	),
	'User' => array(
		'cname'=>array(
	      	"username"=>"用户名",
			"display_name"=>"名称",
			"email"=>"email",
      	),
      	'cond'=>array(
	        "username"=>"",
			"display_name"=>"",
			"email"=>"",
      	),
	),
	'Department'=> array(
		'cname'=>array(
	      	'id'=>'ID',
		'numb'=>'编号',
		'name'=>'名称',
		'update_time'=>'更新时间',
      	),
      	'cond'=>array(
		'numb'=>'',
		'name'=>'',
		'update_time'=>'',
      	),
		
	),
)
);