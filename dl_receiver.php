<?php

$url = 'http://open.denglu.cc/receiver?'.$_SERVER['QUERY_STRING'];

header('HTTP/1.1 301 Moved Permanently');

header('location: '.$url);


?>