<?php
// define
define('DELICIOUS_API', 'https://api.del.icio.us/v1/');

// Update
define('UPDATE', DELICIOUS_API.'posts/update');

// Posts
define('POSTS_ADD', DELICIOUS_API.'posts/add?');
define('POSTS_DELETE', DELICIOUS_API.'posts/delete?');
define('POSTS_GET', DELICIOUS_API.'posts/get?');
define('POSTS_DATES', DELICIOUS_API.'posts/dates?');
define('POSTS_RECENT', DELICIOUS_API.'posts/recent?');
define('POSTS_ALL', DELICIOUS_API.'posts/all?');
define('POSTS_ALL_HASHES', DELICIOUS_API.'posts/all?hashes');
define('POSTS_SUGGEST', DELICIOUS_API.'posts/suggest?');

// Tags
define('TAGS_GET', DELICIOUS_API.'tags/get');
define('TAGS_DELETE', DELICIOUS_API.'tags/delete?');
define('TAGS_RENAME', DELICIOUS_API.'tags/rename?');

// Tag Bundles
define('TAGS_BUNDLES_ALL', DELICIOUS_API.'tags/bundles/all?');
define('TAGS_BUNDLES_SET', DELICIOUS_API.'tags/bundles/set?');
define('TAGS_BUNDLES_DELETE', DELICIOUS_API.'tags/bundles/delete?');


class delicious{	
	private $_username;
	
	private $_password;
	
	public $responses;
	
	public $curl_error;
	
	public $curl_getinfo;
	
	function delicious($_params=array()){
		if(!empty($_params['username']) && !empty($_params['password'])){
			$this->_username = urlencode($_params['username']);
			$this->_password = urlencode($_params['password']);
		}
	}
	
	private function connect($url,$params){
		settype($params_url,"string");
		if(count($params) > 0){
			foreach($params as $key=>$val){
				$params_url .= (!empty($val))?'&'.$key.'='.urlencode($val):'';
			}
		}
		
		$_curl = curl_init($url.$params_url);		
		curl_setopt($_curl, CURLOPT_BINARYTRANSFER, 1);  
        curl_setopt($_curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($_curl, CURLOPT_SSL_VERIFYHOST, 0); 
        curl_setopt($_curl, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($_curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY); 
        curl_setopt($_curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($_curl, CURLOPT_USERPWD, $this->_username .":".$this->_password); 
        $_result = curl_exec($_curl) or die('Error executing the HTTP Request.'); 
		$_response = $this->xml_parser($_result);
		
		$this->curl_error = curl_error($_curl);
		$this->curl_getinfo = curl_getinfo($_curl);
		$this->responses = $_response;
		
		curl_close($_curl);
		
		return $_response;
	}
	
	private function xml_parser($xml){
		settype($data,"Array");
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($xml), $_response);
		xml_parser_free($parser);
				
		if(count($_response) > 0){
			foreach($_response as $key=>$val){
				if($val['type'] == 'complete'){
					if($_response[0]['tag'] == 'suggest'){
						$data[] = array('tag'=>$val['tag'],'value'=>$val['value']);
					}
					elseif($_response[0]['tag'] == 'result'){					
						$data[]['code'] = (array_key_exists('attributes',$_response[0]))?$val['attributes']['code']:$val['value'];
					}
					else{
						$data[] = $val['attributes'];
					}
				}
			}
		}
		return $data;
	}
	
	/*
		Update
	*/
	public function update(){
		return $this->connect(UPDATE,array());
	}
	
	/*
		Posts
	*/	
	public function posts_add($_params=array()){
		$_result = $this->connect(POSTS_ADD,$_params);
		return ($_result[0]['code']=='done')?true:false;
	}
	
	public function posts_delete($_url=null){
		$_result = $this->connect(POSTS_DELETE,array('url'=>$_url));
		return ($_result[0]['code']=='done')?true:false;
	}
	
	public function posts_get($_params=array()){
		return $this->connect(POSTS_GET,$_params);
	}
		
	public function posts_recent($_params=array()){
		return $this->connect(POSTS_RECENT,$_params);
	}
	
	public function posts_dates($_tag=null){
		return $this->connect(POSTS_DATES,array('tag'=>$_tag));
	}
	
	public function posts_all($_params=array()){
		$_params['results'] = (empty($_params['results']))?'0':$_params['results'];
		$_params['meta'] = (empty($_params['meta']))?'yes':$_params['meta'];

		return $this->connect(POSTS_ALL,$_params);
	}
	
	public function posts_all_hashes(){
		return $this->connect(POSTS_ALL_HASHES,array('results'=>'0'));
	}
	
	public function posts_suggest($_url=null){
		return $this->connect(POSTS_SUGGEST,array('url'=>$_url));
	}
	
	/*
		Tags
	*/
	public function tags_get(){
		return $this->connect(TAGS_GET,array());
	}
	
	public function tags_delete($_tag=null){
		$_result = $this->connect(TAGS_DELETE,array('tag'=>$_tag));
		return ($_result[0]['code']=='done')?true:false;
	}
	
	public function tags_rename($_old=null, $_new=null){
		$_result = $this->connect(TAGS_RENAME,array('old'=>$_old, 'new'=>$_new));
		return ($_result[0]['code']=='done')?true:false;
	}
	
	public function tags_bundles_all($_bundle=null){
		return $this->connect(TAGS_BUNDLES_ALL,array('bundle'=>$_bundle));
	}
	
	public function tags_bundles_set($_params=array()){
		$_result = $this->connect(TAGS_BUNDLES_SET,$_params);
		return ($_result[0]['code']=='done')?true:false;
	}
	
	public function tags_bundles_delete($_bundle=null){
		$_result = $this->connect(TAGS_BUNDLES_DELETE,array('bundle'=>$_bundle));
		return ($_result[0]['code']=='done')?true:false;
	}
}
?>