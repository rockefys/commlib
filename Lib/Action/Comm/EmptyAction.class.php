<?php
class EmptyAction extends Action {

	/*
	//如果对于一个Module仅具有CommAction的功能，则可以无须其实体Action文件存在，
	//跳转到这里执行时，如果该Module在C('EMPTY_MODULE')中，并且调用的方法也是CommAction的方法
	//则转给CommAction对应的方法执行
	*/
	public function _empty($name){
       if(array_key_exists( MODULE_NAME, C('EMPTY_MODULE'))){
       		$functions=getFunction('Comm');
       		if(array_key_exists( $name, $functions)){
       			R("Comm/".$name);
       		}
       }
    }
	
}