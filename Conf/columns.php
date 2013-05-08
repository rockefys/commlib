<?php
return array(
'columns'=> array(
	'Menu'=> array(
		'id'=>'ID',
		'name'=>'名称',
		'title'=>'标题',
		'location'=>'位置',
		'order'=>'顺序',
		'pid'=>'父级',
		'link'=>'链接',
		'target'=>'打开方式',
	),
	'Log'=> array(
		'id'=>'ID',
		'username'=>'用户',
		'operate'=>'操作',
		'ip'=>'IP',
		'update_time'=>'时间',
		'status'=>'状态',
	),
	'Database'=> array(
		'id'=>"ID",
		'Name'=>'名称',
		'Comment'=>'标题',
		''=>'模块',
		' '=>'分组',
		'Rows'=>'记录数',
		'Data_length'=>'数据长度',
		'Index_length'=>'索引大小',
		'Engine'=>'引擎',
		'Collation'=>'字符集',
	),
	'Module'=> array(
		'id'=>'ID',
		'name'=>'名称',
	),
	'Iuser'=> array(
		'id'=>'ID',
		'username'=>'名称',
		'numb'=>'手机号',
	),
	'Passport'=> array(
		'id'=>'ID',
		'xing'=>'姓',
		'ming'=>'名',
		'numb'=>'护照编号',
	),
	'Broadband'=> array(
		'id'=>'ID',
		'cat_id'=>'宽带类型',
		'phone'=>'宽带号码',
		'use_during'=>'使用时长',
		'start_date'=>'安装日期',
		'end_date'=>'截止日期',
		'cost'=>'安装费用',
		'status'=>'状态',
	),
	'Department'=> array(
		'id'=>'ID',
		'numb'=>'编号',
		'name'=>'名称',
		'update_time'=>'更新时间',
	),
	'View' => array(
		"name" => '名称',
		"column_name"=>'名称',
		'column_title'=>'标题',
		'query_able'=>'查询',
		'list_show'=>'显示',
		'add_show'=>'显示',
		'control_type'=>'控件类型',
		'validate'=>'验证条件'
	),
	'User' => array(
		"id"=>"",
		"username"=>"用户名",
		"display_name"=>"名称",
		"email"=>"email",
		"register_time"=>"注册时间",
		"status"=>"状态"
	),
	'Role' => array(
		"id"=>"",
		"numb"=>"编号",
 		"name"=>"名称",
 		"status"=>"状态"
 	),
 	'Authority' => array(
		"id"=>"",
		"numb"=>"编号",
 		"name"=>"名称",
 		"url"=>"URL",
 		"status"=>"状态"
 	),
 	'Group' => array(
		"id"=>"",
		"numb"=>"编号",
 		"name"=>"名称",
 		"status"=>"状态"
 	),
 	'Parameters' => array(
		"id"=>"",
 		"name"=>"名称",
 		"value"=>"值",
 		"default"=>"默认值",
 		"type"=>"类型",
 		"desc"=>"描述",
 		"status"=>"状态"
 	),
 	'Article' => array(
		"id"=>"",
		"title"=>"标题",
		"author"=>"作者",
		"tags"=>"标签",
		"commentStatus"=>"评论",
		"status"=>"状态",
		
	),
	'Backup' => array(
		"id"=>"",
		"path"=>"路径",
 		"backup_time"=>"时间",
 		"desc"=>"描述",
 		"status"=>"状态"
 	),


)
);