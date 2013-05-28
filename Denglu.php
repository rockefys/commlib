<?php
/**
 * 目的：把基础方法用protected的形式封装在base里，不直接展现给最终用户
 * @author hyperion_cc, smyx
 * @version 2.0
 * @created 2012-8-29 13:33:00
 */
class Denglu
{
	protected $appID='52707denh7xFHpBYtEUEhgnOk6zOi3';
	protected $apiKey='49934527EwK9aB7bakQvsft2SiJUuA';
	protected $enableSSL;
	/**
	 * denglu API的域名，默认http://open.denglu.cc
	 * 设置此属性以满足以后有做二级域名重定向需求的客户
	 */
	protected $domain = 'http://open.denglu.cc';
	/**
	 * DENGLU RESTful API的地址
	 */
	protected $apiPath = array(
		'bind' => '/api/v3/bind',
		'unbind' => '/api/v3/unbind',
		'login' => '/api/v3/send_login_feed',
		'getUserInfo' => '/api/v4/user_info',
		'share' => '/api/v4/share',
		'getMedia' => '/api/v3/get_media',
		'unbindAll' => '/api/v3/all_unbind',
		'getBind' => '/api/v3/bind_info',
		'getInvite' => '/api/v3/friends',
		'getRecommend' => '/api/v3/recommend_user',
		'sendInvite' => '/api/v3/invite',
	    'latestComment' => '/api/v4/latest_comment', // 最新评论
		'getComments' => '/api/v4/get_comment_list', // 评论列表，用于数据本地化
		'getCommentState' => '/api/v4/get_change_comment_ids' // 评论状态列表
	);

	/*
	 * 系统的编码
	 */
	protected $charset;
	/**
	 * Provider的枚举，里面包括了/transfer/[name]的地址后缀
	 */
	protected $providers = array(
		'google' => '/transfer/google',
		'windowslive' => '/transfer/windowslive',
		'sina' => '/transfer/sina',
		'tencent' => '/transfer/tencent',
		'sohu' => '/transfer/sohu',
		'netease' => '/transfer/netease',
		'renren' => '/transfer/renren',
		'kaixin001' => '/transfer/kaixin001',
		'douban' => '/transfer/douban',
		'yahoo' => '/transfer/yahoo',
		'qzone' => '/transfer/qzone',
		'alipay' => '/transfer/alipay',
		'taobao' => '/transfer/taobao',
		'tianya' => '/transfer/tianya',
		'alipayquick' => '/transfer/alipayquick',
		'guard360' => '/transfer/guard360',
		'tianyi' => '/transfer/tianyi',
		'facebook' => '/transfer/facebook',
		'twitter' => '/transfer/twitter'
	);
	/**
	 * 当前用户各种属性的一个缓存
	 */
	var $user;
	/**
	 * 此sdk的版本号，初始为1.0
	 */
	const VERSION = '1.0';

	/**
	 * 加密方法
	 */
	protected $signatureMethod = 'MD5';

	/**
	 * 构造函数
	 * @param appID	灯鹭后台分配的appID {@link http://open.denglu.cc}
	 * @param apiKey	灯鹭后台分配的apiKey {@link http://open.denglu.cc}
	 * #param charset 系统使用的编码类型utf-8 或gbk
	 * @param signatureMethod	签名算法，暂时只支持MD5
	 */
	function Denglu($appID, $apiKey, $charset, $signatureMethod = 'MD5')//
	{
		$this->appID = $appID;
		$this->apiKey = $apiKey;
		$this->signatureMethod = $signatureMethod;
		$this->charset = $charset;
		$this->setEnableSSL();
	}

	/**
	 * 获取登陆/绑定链接
	 *
	 * @param isBind
	 *            是否用于绑定（非绑定则为登录）
	 * @param Provider
	 *            通过Denglu.Provider p = Denglu.Provider.guess(mediaNameEn) 获取。
	 *            mediaNameEn获取媒体列表中得到
	 * @param uid
	 *            用户网站的用户ID，绑定时需要
	 * @throws DengluException
	 */
	function getAuthUrl($Provider, $isBind = false, $uid = 0 )
	{
		$authUrl = $this->domain;

		if(isset($this->providers[$Provider])){
			$authUrl .= $this->providers[$Provider];
		}else{
			return array('errorCode'=>1,'errorDescription'=>'Please update your denglu-scripts to the latest version!');
		}

		if($isBind && $uid>0){
			$authUrl .= '?uid='.$uid;
		}

		return $authUrl;
	}

	/**
	 * 最新评论
	 *
	*/
	function latestComment($count)
	{
		return $this->callApi('latestComment',array('appid'=>$this->appID, 'count'=>$count),12);
	}

	/**
	 * 返回自己应用的评论列表，用于本地化保存评论数据。
	 *
	 * @param commentid 若指定此参数，则返回ID比commentid大的评论（即比commentid时间晚的评论），默认为0。 
	 * @param count    返回的记录条数，默认为50。 
	 *
	 * 返回值 eg: 
		{
			"postid":"1",
			"content":"我是一条评论",
			"mediaID":3,
			"createTime":"2012-04-26 12:38:14",
			"state":0,
			"commentID":38751,
			"userImage":"http://tp4.sinaimg.cn/2132511355/50/0/1",
				"userName":"testapis",
			"mediaUserID":1224050,
			"homepage":"http://weibo.com/2132511355",
			"ip":"106.3.63.172",
			"parent":
			{
				"postid":"1",
				"content":"我是它的父级评论",
				"mediaID":101,
				"commentID":38749,
				"userName":"水脉烟香",
				"userEmail":"xxx@qq.com",
				"mediaUserID":3529900,
				"homepage":"http://www.smyx.net/",
				"ip":"123.116.124.167"
			}
		}
	 */
	function getComments($commentid, $count = 50)
	{
		return $this->callApi('getComments',array('appid'=>$this->appID, 'commentid'=>$commentid, 'count'=>$count));
	}

	/**
	 * 返回自己应用的评论更新状态，比如评论被删除、审核，可以同步评论状态到本地。
	 *
	 * @param time 时间 单位为1小时，数字类型
	 * 返回结果 灯鹭评论ID
	 * 返回结果 0——正常评论，1——待审，2——垃圾评论，3——回收站，4——删除
	 *
	 * 返回值 eg: 
		{"582997":0,"571330":1,"571277":2,"583028":0}
	 */
	function getCommentState($time)
	{
		return $this->callApi('getCommentState',array('appid'=>$this->appID, 'time'=>$time));
	}

	/**
	 * 根据token获取用户信息
	 *
	 * @param token
	 *
	 * 返回值 eg:
	 * {
	 * 		"mediaID":7,							// 媒体ID
	 * 		"createTime":"2011-05-20 16:44:19",		// 创建时间
	 * 		"friendsCount":0,						// 好友数
	 * 		"location":null,						// 地址
	 * 		"favouritesCount":0,					// 收藏数
	 * 		"screenName":"denglu",					// 显示姓名
	 * 		"profileImageUrl":"http://head.xiaonei.com/photos/0/0/men_main.gif",		// 个人头像
	 * 		"mediaUserID":61,						// 用户ID
	 * 		"url":null,								// 用户博客/主页地址
	 * 		"city":null,							// 城市
	 * 		"description":null,						// 个人描述
	 * 		"createdAt":"",							// 在媒体上的创建时间
	 * 		"verified":0,							// 认证标志
	 * 		"name":null,							// 友好显示名称
	 * 		"domain":null,							// 用户个性化URL
	 * 		"province":null,						// 省份
	 * 		"followersCount":0,						// 粉丝数
	 * 		"gender":1,								// 性别 1--男，0--女,2--未知
	 * 		"statusesCount":0,						// 微博/日记数
	 * 		"personID":120							// 个人ID
	 * }
	 */
	function getUserInfoByToken($token, $refresh = false)
	{
		return $this->callApi('getUserInfo',array('token'=>$token));
	}

	/**
	 * 获取当前应用ID绑定的所有社会化媒体及其属性
	 *
	 *
	 * 返回值 eg:
	 * [
	 * 		{
	 * 			"mediaID":7,																		// ID
	 * 			"mediaIconImageGif":"http://test.denglu.cc/images/denglu_second_icon_7.gif",		// 社会化媒体亮色Icon
	 * 			"mediaIconImage":"http://test.denglu.cc/images/denglu_second_icon_7.png",			// 社会化媒体亮色Icon
	 * 			"mediaNameEn":"renren",																// 社会化媒体的名称的拼音
	 * 			"mediaIconNoImageGif":"http://test.denglu.cc/images/denglu_second_icon_no_7.gif",	// 社会化媒体灰色Icon
	 * 			"mediaIconNoImage":"http://test.denglu.cc/images/denglu_second_icon_no_7.png",		// 社会化媒体灰色Icon
	 * 			"mediaName":"人人网",																// 社会化媒体的名称
	 * 			"mediaImage":"http://test.denglu.cc/images/denglu_second_7.png",					// 社会化媒体大图标
	 * 			"shareFlag":0,																		// 是否有分享功能 0是1否
	 * 			"apiKey":"704779c3dd474a44b612199e438ba8e2"											// 社会化媒体的应用apikey
	 * 		}
	 * ]
	 */
	function getMedia()
	{
		return $this->callApi('getMedia',array('appid'=>$this->appID));
	}
	/**
	 *
	 * 获得同一用户的多个社会化媒体用户信息
	 *
	 * @param uid
	 *			用户网站的用户ID(可选)
	 *
	 * @param muid
	 *			社会化媒体的用户ID
	 *
	 * @return 返回值
	 * 				eq: array(
	 * 				array('mediaUserID'=>100,'mediaID'=>10,'screenName'=>'张三'),
	 * 				array('mediaUserID'=>101,'mediaID'=>11,'screenName'=>'李四'),
	 * 				array('mediaUserID'=>102,'mediaID'=>12,'screenName'=>'王五')
	 * 				)
	 *
	 */
	function getBind($muid, $uid=null)
	{
		if(empty($muid) || !isset($muid)){
			return $this->callApi('getBind',array('appid'=>$this->appID, 'uid'=>$uid));
		}
		return $this->callApi('getBind',array('appid'=>$this->appID, 'muid'=>$muid));
	}

	/**
	 *
	 * 获取可以邀请的媒体用户列表
	 *
	 * @param uid
	 *			用户网站的用户ID(可选)
	 *
	 * @param muid
	 *			社会化媒体的用户ID
	 *
	 * @return 返回值
	 * 				eq: array(
	 * 				array('mediaUserID'=>100,'mediaID'=>10,'screenName'=>'张三'),
	 * 				array('mediaUserID'=>101,'mediaID'=>11,'screenName'=>'李四'),
	 * 				array('mediaUserID'=>102,'mediaID'=>12,'screenName'=>'王五')
	 * 				)
	 *
	 */
	function getInvite($muid,$uid=null)
	{
		if(empty($muid) || !isset($muid)){
			return $this->callApi('getInvite',array('appid'=>$this->appID, 'uid'=>$uid));
		}
		return $this->callApi('getInvite',array('appid'=>$this->appID, 'muid'=>$muid));
	}

	/**
	 *
	 * 获取可以推荐的媒体用户列表
	 *
	 * @param uid
	 *			用户网站的用户ID(可选)
	 *
	 * @param muid
	 *			社会化媒体的用户ID
	 *
	 * @return 返回值
	 * 				eq: array(
	 * 				array('mediaUserID'=>100,'mediaID'=>10,'screenName'=>'张三'),
	 * 				array('mediaUserID'=>101,'mediaID'=>11,'screenName'=>'李四'),
	 * 				array('mediaUserID'=>102,'mediaID'=>12,'screenName'=>'王五')
	 * 				)
	 *
	 */
	function getRecommend($muid,$uid=null)
	{
		if(empty($muid) || !isset($muid)){
			return $this->callApi('getRecommend',array('appid'=>$this->appID, 'uid'=>$uid));
		}
		return $this->callApi('getRecommend',array('appid'=>$this->appID, 'muid'=>$muid));
	}

	/**
	 *
	 * 发送邀请
	 *
	 * @param muid
	 *			社会化媒体的用户ID
	 *
	 * @param uid
	 *			用户网站的用户ID(可选)
	 *
	 * @return 返回值 eg: {"result": "1"}
	 *
	 */
	function sendInvite($invitemuids, $muid, $uid=null)
	{
		if(empty($muid) || !isset($muid)){
			return $this->callApi('sendInvite',array('appid'=>$this->appID, 'uid'=>$uid, 'invitemuid'=>$invitemuids));
		}
		return $this->callApi('sendInvite',array('appid'=>$this->appID, 'muid'=>$muid, 'invitemuid'=>$invitemuids));
	}

	/**
	 * 用户绑定多个社会化媒体账号到已有账号上
	 *
	 * @param mediaUID
	 *            社会化媒体的用户ID
	 * @param uid
	 *            用户网站那边的用户ID
	 * @param uname
	 *            用户网站的昵称
	 * @param uemail
	 *            用户网站的邮箱
	 * @return 返回值 eg: {"result": "1"}
	 */
	function bind( $mediaUID, $uid, $uname, $uemail)
	{
		return $this->callApi('bind',array('appid'=>$this->appID,'muid'=>$mediaUID,'uid'=>$uid,'uname'=>$uname,'uemail'=>$uemail));
	}

	/**
	 * 用户解除绑定社会化媒体账号
	 *
	 * @param mediaUID    社会化媒体的用户ID
	 *
	 * 返回值 eg: {"result": "1"}
	 */
	function unbind( $mediaUID)
	{
		return $this->callApi('unbind',array('appid'=>$this->appID,'muid'=>$mediaUID));
	}

	/**
	 * 发送登录的新鲜事
	 *
	 * @param mediaUserID
	 *               从灯鹭获取的mediaUserID
	 *
	 * 返回值 eg: {"result": "1"}
	 */
	function sendLoginFeed($mediaUserID)
	{
		return  $this->callApi('login',array('muid'=>$mediaUserID,'appid'=>$this->appID));
	}

	/**
	 * 用户发布帖子、日志等信息时，可以把此信息分享到第三方
	 *
	 * @param mediaUserID
	 * @param content    分享显示的信息
	 * @param url    查看信息的链接
	 * @param uid    网站用户的唯一性标识ID
	 * @param imageurl    图片URL
	 * @param videourl    视频URL
	 * @param param1      文章ID, 用于同步微博的评论抓取回来
	 *
	 * 返回值 eg: {"result": "1"}
	 */
	function share( $mediaUserID, $content, $url, $uid, $imageurl = '', $videourl = '', $param1 = '')
	{
		return $this->callApi('share',array('appid'=>$this->appID,'muid'=>$mediaUserID,'uid'=>$uid,'content'=>$content,'imageurl'=>$imageurl,'videourl'=>$videourl,'param1'=>$param1,'url'=>$url));
	}

	/**
	 * 用户解除所有绑定社会化媒体账号
	 * @param uid 网站用户的唯一性标识ID
	 *
	 * 返回值 eg: {"result": "1"}
	 */
	function unbindAll($uid)
	{
		return $this->callApi('unbindAll',array('uid'=>$uid,'appid'=>$this->appID) );
	}

	/**
	 * 为HTTP请求加签名 签名算法： A、将请求参数格式化为“key=value”格式
	 * B、将上诉格式化好的参数键值对，以字典序升序排列后，拼接在一起；“key=valuekey=value”
	 * C、在上拼接好的字符串末尾追加上应用的api Key D、上述字符串的MD5值即为签名的值
	 *
	 * @param request
	 */
	protected function signRequest($request)
	{
		ksort($request);
		$sig = '';
		foreach($request as $key=>$value) {
			$sig .= "$key=$value";
		}
		$sig .= $this->apiKey;
		return md5($sig);
	}

	/**
	 * 将外部传进来的参数转换成http格式
	 * @param param 数组
	 */
	protected function createPostBody($param){
		foreach($param as $key => $v){
			if(is_array($v)){
				$param[$key] = implode(',',$v);
			}
			if(strtolower($this->charset)!='utf-8'){
				$param[$key] = $this->charsetConvert($v,'GBK','UTF-8');
			}
		}
		//include('./lib/cache_timestamp.php');
		//$param['timestamp'] = time()+$cache_timestamp.'000';
		$param['timestamp'] = time().'000';
		$param['appid'] = $this->appID;
		$param['sign_type'] = $this->signatureMethod;
		$param['sign']  = $this->signRequest($param);

		$arr = array();
		foreach($param as $key => $v){
			$arr[] = $key.'='.urlencode($v);
		}
		return implode('&',$arr);
	}
	/**
	 * 发送http请求并获得返回信息
	 * @param method 请求的api类型
	 * @param request 该请求所发送的参数
	 * @param return 本请求是否有返回值
	 */
	protected function callApi($method,$request=array()){
		$apiPath = $this->getapiPath($method);
		$post = $this->createPostBody($request);
		//if($method=='getUserInfo'){
		//	echo $apiPath.'?'.$post;die;
		//}
		$result = $this->makeRequest($apiPath,$post);

		$result = $this->parseJson($result);
		if(strtolower($this->charset)=='gbk'){
			$result = $this->charsetConvert($result, "GBK", "UTF8");
		}

		if(is_array($result) && isset($result['errorCode'])){
			$this->throwAPIException($result);
		}

		return $result;
	}
	/**
	 * 编码转换
	 * @param str 需要转换的字符串
	 * @param to 要转换成的编码
	 * @param from 字符串的初始编码
	 */
	protected function charsetConvert($str,$to,$from){
		if(!function_exists('mb_convert_encoding')){
			function mb_convert_encoding($string,$to,$from)
			{
				if ($from == "UTF-8")
				$iso_string = utf8_decode($string);
				else
				if ($from == "UTF7-IMAP")
				$iso_string = imap_utf7_decode($string);
				else
				$iso_string = $string;

				if ($to == "UTF-8")
				return(utf8_encode($iso_string));
				else
				if ($to == "UTF7-IMAP")
				return(imap_utf7_encode($iso_string));
				else
				return($iso_string);
			}
		}
		if(is_array($str)){
			foreach($str as $k => $v){
				$k = $this->charsetConvert($k,$to,$from);
				$v = $this->charsetConvert($v,$to,$from);
				$str[$k] = $v;
			}
		}else{
			return  mb_convert_encoding($str,$to,$from);
		}
		return $str;
	}

	/**
	 *抛出异常
	 *@param result
	 *
	 */
	protected function throwAPIException($result){
		$e = new DengluException($result);

		throw $e;
	}

	/**
	 * 发送HTTP请求并获得响应
	 * @param url 请求的url地址
	 * @param request 发送的http参数
	 */
	///////function makeRequest($request)
	protected function makeRequest($url, $post = '' ) {
		$return = '';
		$matches = parse_url($url);
		$host = $matches['host'];
		if(empty($matches['query'])) $matches['query']='';
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = 80;

		if(function_exists('curl_init') && function_exists('curl_exec')){
			$url .= '?'.$post;
			//$url = str_replace('http://','https://',$url);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 0);
			curl_setopt($ch, CURLOPT_USERAGENT, 'denglu');
			$return = curl_exec($ch);
			return $return;
		}

		if($post) {
			$out = "POST $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= 'Content-Length: '.strlen($post)."\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: \r\n\r\n";
			$out .= $post;
		} else {
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: \r\n\r\n";
		}

		if(function_exists('fsockopen')) {
			$fp = @fsockopen($host, $port, $errno, $errstr, 30);
		} elseif(function_exists('pfsockopen')) {
			$fp = @pfsockopen($host, $port, $errno, $errstr, 30);
		} else {
			return array('errorCode'=>1,'errorDescription'=>'Functions "fsockopen" and "pfsockopen" are not exists!');
		}

		if(!$fp) {
			return array('errorCode'=>1,'errorDescription'=>"Your website can't connect to denglu server!");
		} else {
			stream_set_blocking($fp, true);
			stream_set_timeout($fp, 30);
			@fwrite($fp, $out);
			$status = stream_get_meta_data($fp);
			if(!$status['timed_out']) {
				while (!feof($fp)) {
					if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
						break;
					}
				}

				$stop = false;
				while(!feof($fp) && !$stop) {
					$data = fread($fp,  8192);
					$return .= $data;
				}
			}
			@fclose($fp);
			return $return;
		}
	}

	/**
	 * 从apiPath数组里获得相应method的实际调用地址
	 *
	 * @param method
	 */
	protected function getApiPath($method)
	{
		return $this->domain.$this->apiPath[$method];
	}

	/**
	 * 解析JSON字符串
	 *
	 * 把从接口获取到的数据转换成json格式，在解析中进行接口返回错误分析
	 *
	 * @param input
	 */
	protected function parseJson($input)
	{
		if(!function_exists('json_decode'))
		{
			function json_decode($input)
			{
				$comment = false;
				$out = '$x=';

				for ($i=0; $i<strlen($input); $i++)
				{
					if (!$comment)
					{
					if (($input[$i] == '{') || ($input[$i] == '['))       $out .= ' array(';
					else if (($input[$i] == '}') || ($input[$i] == ']'))   $out .= ')';
					else if ($input[$i] == ':')    $out .= '=>';
					else                         $out .= $input[$i];
				}
				else $out .= $input[$i];
				if ($input[$i] == '"' && $input[($i-1)]!="\\")    $comment = !$comment;
				}
				eval($out . ';');
				return $x;
			}
		}
		return json_decode($input,1);
	}

	/**
	 *
	 * @param input
	 */
	protected function base64Encode($input)
	{
		return base64_encode($input);
	}

	/**
	 *
	 * @param input
	 */
	protected function base64Decode($input)
	{
		return base64_decode($input);
	}

	/**
	 *
	 * @param input
	 */

	function getapiKey()
	{
		return $this->apiKey;
	}

	/**
	 *
	 * @param newVal
	 */
	function setapiKey($newVal)
	{
		$this->apiKey = $newVal;
	}

	function getappID()
	{
		return $this->appID;
	}

	/**
	 *
	 * @param newVal
	 */
	function setappID($newVal)
	{
		$this->appID = $newVal;
	}

	function setEnableSSL(){
		if(function_exists('curl_init') && function_exists('curl_exec')){
			$this->enableSSL = true;
		}
	}

}

/**
 *异常类
* 错误类型对照表
 * Code Description
 * 1 	参数错误，请参考API文档
 * 2 	站点不存在
 * 3 	时间戳有误
 * 4 	只支持md5签名
 * 5 	签名不正确
 * 6 	token已过期
 * 7 	媒体用户不存在
 * 8 	媒体用户已绑定其他用户
 * 9 	媒体用户已解绑
 * 10 	未知错误
 */

class DengluException extends Exception
{

	var $errorCode;
	var $errorDescription;

	function DengluException($result)
	{
		$this->result = $result;
		$this->errorCode = $result['errorCode'];
		$this->errorDescription = $result['errorDescription'];

		parent::__construct($this->errorDescription, $this->errorCode);
	}



	function geterrorCode()
	{
		return $this->errorCode;
	}

	/**
	 *
	 * @param newVal
	 */
	function seterrorCode($newVal)
	{
		$this->errorCode = $newVal;
	}

	function geterrorDescription()
	{
		return $this->errorDescription;
	}


}
?>