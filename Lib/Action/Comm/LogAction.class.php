<?php
class LogAction extends CommAction {
	protected function show($type){
        $this->refer=$this->_param('refer');
        $condition=$this->_post('query');
        if(!empty($condition)){
            $condition=queryFilter($condition);
        }
        $M= M(MODULE_NAME);
        $select=$M->table('log l')->field("l.*,u.username")->join("user u on l.update_user=u.id")->order('l.id desc');
        $this->page($select,$condition,$type);
    }
}