<?php
    /**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
    $string = str_replace('%20','',$string);
    $string = str_replace('%27','',$string);
    $string = str_replace('%2527','',$string);
    $string = str_replace('*','',$string);
    $string = str_replace('"','&quot;',$string);
    $string = str_replace("'",'',$string);
    $string = str_replace('"','',$string);
    $string = str_replace(';','',$string);
    $string = str_replace('<','&lt;',$string);
    $string = str_replace('>','&gt;',$string);
    $string = str_replace("{",'',$string);
    $string = str_replace('}','',$string);
    $string = str_replace('\\','',$string);
    $string = remove_xss($string);
    return $string;
}

/**
 * xss过滤函数
 *
 * @param $string
 * @return string
 */
function remove_xss($string) { 
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

    $parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');

    $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

    $parm = array_merge($parm1, $parm2); 

    for ($i = 0; $i < sizeof($parm); $i++) { 
        $pattern = '/'; 
        for ($j = 0; $j < strlen($parm[$i]); $j++) { 
            if ($j > 0) { 
                $pattern .= '('; 
                $pattern .= '(&#[x|X]0([9][a][b]);?)?'; 
                $pattern .= '|(&#0([9][10][13]);?)?'; 
                $pattern .= ')?'; 
            }
            $pattern .= $parm[$i][$j]; 
        }
        $pattern .= '/i';
        $string = preg_replace($pattern, '', $string); 
    }
    return $string;
}

    function pager($select,$p,$display){
        R("Comm/Page/index",array($select,$p,$display));
    }

	function md5Encrypt($str){
		$key='*M3~A0!R9@C1"#H7%s2^o/f4-t?3';
		return md5(md5($str.$key).$key);
	}
    function get_time(){
        return date('Y-m-d H:i:s',time());
    }
    
    function logs($data,$result = false){
        $M = M('log');
        $M->update_user = session('userid');
        $M->update_time = NOW_TIME;
        $M->operate     = __SELF__;
        $M->content     = json_encode($data);
        $M->result      = $result;
        $M->ip          =  get_client_ip();
        $M->add();
    }

    function byteFormat($bytes) {
        $sizetext = array(" B", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        return round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), 2) . $sizetext[$i];
    }
    function curl($url,$post_data=null,$json=true){
        $ch= curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        
        if(!empty($post_data)){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-Type: application/json","X-Accept: application/json"));
        }
        $result = curl_exec($ch);
        curl_close($ch);
        if($result===false) {return curl_error($ch);} 
        else {if($json)$result = json_decode($result,true); return $result;}
        
    }
    function saveM($M){
        $id=$M->getPk();
        if(empty($M->$id)){
            $result=$M->add();
        }
        else{
            $result=$M->save();
        }
        return $result;
    }
    function tagFilter($tags){
        $search=array(';','；','。','、','，');
        $symbol=',';
        $tags=str_replace($search, $symbol, $tags);
        return $tags;
    }
    function LazyInc($M,$column,$n=1){
        $M->switchModel("Adv")->setLazyInc($column,$n,6);
    }

    //POST数据处理
    //未输入值的txtbox为空，应当移除
    //复选框未选择，则不会出现在post中，应添加条件并赋值为false
    function queryFilter($data){
        foreach ($data as $key => $value) {
            if(empty($value) || empty($value[1])){
                unset($data[$key]);
            }
        }
        return $data;
    }
    /*
    tinyint(1) bool;
    char,varchar
    验证长度
    int,TINYINT,SMALLINT,MEDIUMINT,BIGINT
    验证数值和大小
    FLOAT,DOUBLE,DECIMAL
    验证数值
    TINYTEXT,TEXT,MEDIUMTEXT,LONGTEXT
    TINYBLOB,BLOB,MEDIUMBLOB,LONGBLOB
    验证长度
    BINARY,VARBINARY()
    验证长度
    DATE,date
    TIME,
    DATETIME,TIMESTAMP,
    YEAR 
    验证日期时间
    */
    function validator($vo){
        $str ='{';
        $str .= $vo['Null']=='NO'?'required:true,':'';
        $str .= strpos($vo['Type'],'char')!==false?'maxlength:'.substr($vo['Type'],strpos($vo['Type'],"(")+1,-1).',':'';
        if(strpos($vo['Type'],'int')!==false){
            $str .= 'digits:true,';
            switch(strtoupper(substr($vo['Type'],0,3)))
            {
                case 'TIN'://TINYINT
                    $str .= strpos($vo['Type'],'unsigned')!==false?'range:[0,255]':'range:[-128,127]';
                  break;  
               case 'SMA'://SMALLINT
                    $str .= strpos($vo['Type'],'unsigned')!==false?'range:[0,65535]':'range:[-32768,32767]';
                  break; 
                case 'MED'://MEDIUMINT
                    $str .= strpos($vo['Type'],'unsigned')!==false?'range:[0,16777215]':'range:[-8388608,8388607]';
                  break; 
                case 'INT'://INT
                    $str .= strpos($vo['Type'],'unsigned')!==false?'range:[0,4294967295]':'range:[-2147483648,2147483647]';
                  break; 
                case 'BIG'://BIGINT
                    $str .= strpos($vo['Type'],'unsigned')!==false?'range:[0,18446744073709551615]':'range:[-9223372036854775808,9223372036854775807]';
                  break; 
            }
        }
        $str .= strpos($vo['Type'],'decimal')!==false?'number:true,':'';
        $str .= strpos($vo['Type'],'float')!==false?'number:true,':'';
        $str .= strpos($vo['Type'],'double')!==false?'number:true,':'';
        $str .= strpos($vo['Type'],'year')!==false?'range:[0,9999],digits:true,':'';
        $str .= strpos($vo['Type'],'timestamp')!==false?'dateISO:true,':'';
        $str .= strpos($vo['Type'],'datetime')!==false?'dateISO:true,':'';
        $str .= strpos($vo['Type'],'time')!==false?'date:true,':'';
        $str.='}';
        return $str;
    }
    function control_type($vo){
        if($vo['Key']==='PRI')$type='hidden';
        elseif(strpos($vo['Type'],'char')!==false)$type='text';
        elseif(strpos($vo['Type'],'text')!==false)$type='area';
        elseif(strpos($vo['Type'],'enum')!==false)$type='select';
        elseif($vo['Type']=='datetime' || $vo['Type']=='timestamp')$type='datetime';
        elseif($vo['Type']=='date')             $type='date';
        elseif($vo['Type']=='time')             $type='time';
        elseif(strpos($vo['Type'],'bit')!==false)$type='checkbox';
        //else if(strpos($vo['type'],'set')!==false)$type='checkboxs';
        else if(
                strpos($vo['Type'],'int')!==false 
            ||  strpos($vo['Type'],'decimal')!==false 
            ||  strpos($vo['Type'],'float')!==false 
            ||  strpos($vo['Type'],'double')!==false
            )
            $type='digit';

        return $type;
    }

    function getNumb(){
        return array(
            'a' => 1, 
            '123' =>123, 
            'c' => 3, 
            'd' => 4, 
            );
    }
    function getName(){
        return array(
            'a' => 'a', 
            'b' => 'b', 
            'c' => 'c', 
            'd' => 'd', 
            );
    }
    function getStatus(){
        return array(
            '1' => 1, 
            '2' => 2, 
            '3' => 3, 
            '4' => 4, 
            );
    }
    function mkdirs($dir){       
        if(!is_dir($dir)){       
            if(!mkdirs(dirname($dir))){       
                return false;       
            }
            if(!file_exists($dir)){
                if(!mkdir($dir,0777)){       
                    return false;       
                }
            }
            return true; 
        }
        return true;       
    }
    

?>