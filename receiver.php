<?php


/*
 *加载文件
 */
require_once dirname(__FILE__).'\Denglu.php';

$appID='52707denh7xFHpBYtEUEhgnOk6zOi3';
$apiKey='49934527EwK9aB7bakQvsft2SiJUuA';
$charset='utf-8';

/*
 *初始化接口类Denglu
 */
$api = new Denglu($appID,$apiKey,$charset);

/*
 *调用接品类相关方法获取媒体用户信息示例
 */
/*
"mediaID":7,							// 媒体ID
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
*/
if(!empty($_GET['token'])){
	try{
		$userInfo = $api->getUserInfoByToken($_GET['token']);
		$mediaID=$userInfo['mediaID'];
		$mediaUID=$userInfo['mediaUserID'];
		$uname=$userInfo['name'];
		$uid=$userInfo['personID'];
		session_start();
		$_SESSION['mediaID']=$mediaID;
		$_SESSION['mediaUserID']=$mediaUID;
		$_SESSION['username']=$uname;
		header("Location:http://ideat.org"); 
		exit();
	}catch(DengluException $e){
	//获取异常后的处理办法(请自定义)
		//return false;		
		//echo $e->geterrorCode();  //返回错误编号
		//echo $e->geterrorDescription();  //返回错误信息
	}
}

/*
 *发送绑定请求
 */
try{
	$result = $api->bind( $mediaUID, $uid, $uname, $uemail);
	var_dump($result);
}catch(DengluException $e){
	//处理办法同上
}

/*
 *发送解除绑定请求
 */
try{
	$result = $api->unbind( $mediaUID);
	var_dump($result);
}catch(DengluException $e){
	//处理办法同上
}

/*
 *获取网站可用的媒体信息
 */
try{
	$result = $api->getMedia();
	var_dump($result);
}catch(DengluException $e){
	//处理办法同上
}

/*
 *推送媒体用户登录新鲜事
 */
try{
	$result = $api->sendLoginFeed($mediaUserID);
	var_dump($result);
}catch(DengluException $e){
	//处理办法同上
}

/*
 *分享内容
 */
try{
	$result = $api->share( $mediaUserID, $content, $url, $uid);
	var_dump($result);
}catch(DengluException $e){
	//处理办法同上
}

/*
 *发送解除用户所有已绑定媒体用户的新求
 */
try{
	$result = $api->unbindAll($uid);
	var_dump($result);
}catch(DengluException $e){
	//处理办法同上
}


?>
