<?php
	function md5Encrypt($str){
		$key='*M3~A0!R9@C1"#H7%s2^o/f4-t?3';
		return md5(md5($str.$key).$key);
	}
	function ajaxSave($M){
        $id=$M->getPk();
        if(empty($M->$id)){
            $result=$M->add();
        }
        else{
            $result=$M->save();
        }
        if($result){
             $this->ajaxReturn(1,"success",1);
        }
        else{
             $this->ajaxReturn(0,'fail',0);
        }
    }

    public function ajaxDelete($M,$ids){
        $id=$M->getPk();
        $result=$M->where($id.' in('.$ids.')')->delete();
        if($result){
             $this->ajaxReturn(1,"success",1);
        }
        else{
             $this->ajaxReturn(0,'fail',0);
        }
    }
	function save($M){
        $id=$M->getPk();
        if(empty($M->$id)){
            $result=$M->add();
        }
        else{
            $result=$M->save();
        }
        return $result;
    }

    public function delete($M,$ids){
        $id=$M->getPk();
        $result=$M->where($id.' in('.$ids.')')->delete();
        return $result;
    }
?>