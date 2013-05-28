<?php
return array(
'PROJECT_NAME'		=>	'Commlib',
'PROJECT_URL'		=>	'http://ideat.org',
'PROJECT_ICP'		=>	'000000',
//Database
'DB_TYPE'   		=> 	'mysql', 	// 数据库类型
'DB_HOST'   		=> 	'localhost',// 服务器地址
'DB_NAME'   		=> 	'commlib', 	// 数据库名
'DB_USER'   		=> 	'root', 	// 用户名
'DB_PWD'    		=> 	'', 	// 密码
'DB_PORT'   		=> 	3306, 		// 端口
'DB_PREFIX' 		=> 	'', 		// 数据库表前缀
'DB_DEPLOY_TYPE'	=>	0,			// 数据库部署方式 0 集中式 1 分布式	0
'DB_RW_SEPARATE'	=>	FALSE,		// 数据库是否需要读写分离 分布式部署下有效	
'DB_MASTER_NUM'		=>	1,			// 设置读写分离后 主服务器数量	1
//'DB_SLAVE_NO'	=>	,				// 设置读写分离后 指定从服务器序号（3.1新增）	
'DB_SQL_BUILD_CACHE'=>	FALSE,		// 数据库查询的SQL创建缓存	FALSE
'DB_SQL_BUILD_QUEUE'=>	'file',		// SQL缓存队列的缓存方式	file
'DB_SQL_BUILD_LENGTH'=>	20,			// SQL缓存的队列长度	20
'DB_SQL_LOG'		=>	FALSE,		// 是否开启SQL日志记录（3.1新增）	FALSE

'PAGE_SIZE'			=>	10,
'MAX_UPLOAD_FILE_SIZE'	=>	10485760,	//允许最大上传文件大小 1048576 1M

'LIMIT_PROXY_VISIT'=>true,			//禁止代理访问
'LIMIT_ROBOT_VISIT' =>  true,		//禁止机器人访问
'LIMIT_REFLESH_TIME'=>'1',			//浏览器防刷新检测
'VAR_FILTERS'		=>'urldecode,addslashes,htmlspecialchar',
//CHARSET
'OUTPUT_CHARSET'	=>	'utf-8',	// 输出编码设置   
'DB_CHARSET'		=>	'utf-8',	// 数据库编码设置   
'TEMPLATE_CHARSET' 	=>	'utf-8',	// 模板编码设置  
'DEFAULT_CHARSET'	=>	'utf-8',	// 默认编码
//Group
'APP_GROUP_LIST' 	=> 	'Admin,Comm',	// 项目分组设定
'DEFAULT_GROUP'  	=> 	'Comm',	// 默认分组
'DEFAULT_THEME' 	=> 	'',

'EMPTY_MODULE'		=>	'Department,Role,Broadband,Broadband_cat,Passport,Parameters,Log,',	//允许通过EmptyAction处理的模块，注意以,结尾
'EMPTY_ACTION'		=>	'index,add,edit,save,delete,refer,view,operate,',//允许通过EmptyAction处理的Action，注意以,结尾

'LazyInc'			=>	'6',

'LOAD_EXT_CONFIG'=>'columns,query,pill',

'TMPL_SIRIP_SPACE'=>false,
//LAYOUT
'LAYOUT_ON' 		=> 	TRUE,		// 
//大小写验证
'APP_FILE_CASE'		=>	FALSE,		// 是否检查文件的大小写 对Windows平台有效
//Debug
'SHOW_PAGE_TRACE'	=>	TRUE,		// 显示页面Trace信息	FALSE

'SHOW_RECAPTCHA'	=> TRUE,
'RECAPTCHA_PUBLICKEY'=>"6LdPzNcSAAAAAD5xZltr8TYCO5i9T5hoJ6FZnWij",
'RECAPTCHA_PRIVATEKEY'=>"6LdPzNcSAAAAADQi80K3AYGqWGnUPKfkMps6QijL",

//ShowRuntime
/*
'SHOW_RUN_TIME'		=>	TRUE,		// 是否显示运行时间
'SHOW_ADV_TIME'		=>	TRUE,		// 是否显示详细的运行时间
'SHOW_DB_TIMES'		=>	TRUE,		// 是否显示数据库查询和写入次数
'SHOW_CACHE_TIMES'	=>	TRUE,		// 是否显示缓存操作次数
'SHOW_USE_MEM'		=>	TRUE,		// 是否显示内存开销
'SHOW_LOAD_FILE'	=>	TRUE,		// 是否显示加载文件数
'SHOW_FUN_TIMES'	=>	TRUE,		// 是否显示函数调用次数
*/
//SESSION
'SESSION_AUTO_START'=>	TRUE,		// 是否自动开启Session	TRUE
//'SESSION_OPTIONS'	=>	,			// session 配置数组	array()
//'SESSION_TYPE'		=>	,		// session hander类型	 
//'SESSION_PREFIX'	=>	,			// session 前缀	 
//'VAR_SESSION_ID'	=>	,			// sessionID的提交变量	session_id

//COOKIE
//'COOKIE_EXPIRE'		=>	3600,		// Coodie有效期(秒)
//'COOKIE_DOMAIN'		=>	'ideat.org',// Cookie有效域名	 
//'COOKIE_PATH'		=>				// Cookie路径	
//'COOKIE_PREFIX'		=>	'ideat_',	// Cookie前缀 避免冲突
/*
//TOKEN
'TOKEN_ON'			=>	true,  	// 是否开启令牌验证
'TOKEN_NAME'		=>	'__token__',	// 令牌验证的表单隐藏字段名称
'TOKEN_TYPE'		=>	'md5',		// 令牌哈希验证规则 默认为MD5
'TOKEN_RESET'		=>	FALSE,		// 令牌验证出错后是否重置令牌 默认为TRUE
*/
//URL设置
'URL_CASE_INSENSITIVE'	=>FALSE,	// URL是否不区分大小写	false
'URL_MODEL'			=>1,			// URL访问模式支持 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式);3 (兼容模式)	
//'URL_PATHINFO_DEPR'	=> 	'/',		// PATHINFO模式下的参数分割符	/
//'URL_PATHINFO_FETCH'=>,			// 用于兼容判断PATH_INFO 参数的SERVER替代变量列表 ORIG_PATH_INFO;REDIRECT_PATH_INFO;REDIRECT_URL
'URL_HTML_SUFFIX'	=>'jsp',				// URL伪静态后缀设置	 
//'URL_404_REDIRECT'	=>,				// 404跳转页面 部署模式有效（3.1新增）	 
//'URL_PARAMS_BIND'	=>,				// URL变量绑定到Action方法参数（3.1新增）	true

//数据缓存设置
//'DATA_CACHE_TIME'	=>	0,			// 数据缓存有效期 0表示永久缓存
//'DATA_CACHE_COMPRESS'=>	FALSE,		// 数据缓存是否压缩缓存	
//'DATA_CACHE_CHECK'	=>	FALSE,		// 数据缓存是否校验缓存	FALSE
//'DATA_CACHE_TYPE'	=>	'File',		// 数据缓存类型	File
//'DATA_CACHE_PATH'	=>	'TEMP_PATH',// 缓存路径设置 (仅对File方式缓存有效)	
//'DATA_CACHE_SUBDIR'	=>	FALSE,		// 使用子目录缓存(仅对File方式缓存有效)	FALSE
//'DATA_PATH_LEVEL'	=>	1,			// 子目录缓存级别(仅对File方式缓存有效)	1

//ParseTemplate行为配置
//'TMPL_CONTENT_TYPE'	=>	'text/html',// 默认模板输出类型	
//'TMPL_ACTION_ERROR'	=>	,		// 默认错误跳转对应的模板文件	系统模板目录下的dispatch_jump.tpl
//'TMPL_ACTION_SUCCESS'	=>	,		// 默认成功跳转对应的模板文件	同上
//'TMPL_EXCEPTION_FILE'	=>	,		// 异常页面的模板文件	系统模板目录下的think_exception.tpl
//'TMPL_DETECT_THEME'		=>	FALSE,	// 自动侦测模板主题	FALSE
//'TMPL_TEMPLATE_SUFFIX'	=>	'.html',// 默认模板文件后缀	
//'TMPL_FILE_DEPR'		=>	,		// 模板文件模块与操作之间的分割符，只对项目分组部署有效	/

//Error显示设置
//'ERROR_MESSAGE'	=>	,			// 错误显示信息，部署模式有效	 
//'ERROR_PAGE'		=>	,			// 错误定向页面，部署模式有效	 
//'SHOW_ERROR_MSG'	=>	FALSE,		// 是否显示错误信息	FALSE

//ReadHtmlCache
//'HTML_CACHE_ON'		=>	FALSE,		// 是否开启静态缓存
//'HTML_CACHE_RULES'	=>	,		// 静态缓存规则	array()
//'HTML_CACHE_TIME'	=>	60,			// 静态缓存有效期（秒）
'HTML_FILE_SUFFIX'	=>	'htm',		// 静态缓存后缀

//LOG
//'LOG_RECORD'			=>	TRUE,	// 是否记录日志信息
//'LOG_TYPE'			=>	,		// 默认日志记录类型 0 系统 1 邮件 3 文件 4 SAPI	3
//'LOG_DEST'			=>	,		// 日志记录目标	 
//'LOG_EXTRA'			=>	,		// 日志记录额外信息	 
//'LOG_LEVEL'			=>	,		// 允许记录的日志级别	EMERG,ALERT,CRIT,ERR
//'LOG_FILE_SIZE'		=>	,		// 日志文件大小限制（字节 文件方式有效）	2097152
//'LOG_EXCEPTION_RECORD'=>,			// 是否记录异常信息日志	FALSE	 

);
?>