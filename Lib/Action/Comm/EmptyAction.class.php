<?php
import('@.Action.CommAction');
class EmptyAction extends Action {
	/*
	* 如果对于一个Module仅具有CommAction的功能，则可以无须其实体Action文件存在，通过EmptyAction跳转即可
	* 跳转到这里执行时，如果该Module在C('EMPTY_MODULE')中，并且调用的方法也是C('EMPTY_ACTION')中的方法
	* 则转给CommAction对应的方法执行
	*/
	public function _empty($name){
       if(strpos(C('EMPTY_MODULE'), MODULE_NAME.',')!==false){
       		if(strpos(C('EMPTY_ACTION') ,$name.',')!==false){
       			R("Comm/Comm/".$name);
       			exit();
       		}
       }
       $this->error('Unknow handle.',U('Index/index'));
    }
	
}