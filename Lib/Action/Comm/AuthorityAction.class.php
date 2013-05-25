<?php
class AuthorityAction extends Action {
    public function _initialize(){

    }
    public function index(){
        $M=M("Role");
        $this->data=$M->select();
        $this->columns=C("columns.Role");
        $this->display("role");
    }
    public function role(){
        $M=M("Role");
        $this->data=$M->select();
        $this->columns=C("columns.Role");
        $this->display("role");
    }
    public function user(){
        $M=M("User");
        $this->data=$M->select();
        $this->columns=C("columns.User");
        $this->display("role");
    }
    public function tree(){
        $this->display("Index:tree1");
    }
    public function edittree(){
        $this->display("Index:treeedit");
    }
    public function role_authority(){
        if(!$this->isAjax())return;
        $ids=htmlentities($_POST['ids']);
        $M=M();
        $result=$M->table(MODULE_NAME.' a')->field("a.id,name,pid,case isParent when 1 then 'true' else 'false' end isParent")->join('inner join role_authority ra on ra.aid=a.id and rid in'."($ids)")->where()->select();
        $this->ajaxReturn($result,'JSON');
    }
    public function user_authority(){
        if(!$this->isAjax())return;
        $ids=htmlentities($_POST['ids']);
        $M=M();
        $result=$M->table(MODULE_NAME.' a')->field("a.id,name,pid,case isParent when 1 then 'true' else 'false' end isParent")->join('inner join user_authority ua on ua.aid=a.id and uid in'."($ids)")->where()->select();
        $this->ajaxReturn($result,'JSON');
    }
    public function allnodes($ids=null){
        if(!empty($ids)){
            $M= M();
            $n=count(explode(',', $ids));
            $result=$M->query("
            SELECT a.id,name,parent,case is_node when 1 then 'true' else 'false' end as isParent
            ,ra.rid,case when ra.rid is null then 'false' else 'true' end as checked
            FROM authority a
            inner join role_authority ra on a.id=ra.aid and ra.rid in($ids)
            group by aid having count(*)>=$n
            ");
        }
        else{
            $M= M(MODULE_NAME);
            $result=$M->field("id,name,pId,case isParent when 1 then 'true' else 'false' end isParent")->select();
        }
        $this->ajaxReturn($result,'JSON');
     }
    public function nodes(){
        if(!$this->isAjax()){
            //return;
        }
        $id=htmlentities($_POST['id']);
        $type=htmlentities($_POST['type']);
        $data['name']=htmlentities($_POST['name']);
        $data['pid']=htmlentities($_POST['pid']);
        $data['level']=htmlentities($_POST['level']);
        $M=M('authority');
        if(empty($type)){
            if(empty($id))$id=0;
            $result=$M->field("id,name,pid,case isParent when 1 then 'true' else 'false' end isParent")->where('pid='.$id)->select();
        }else if($type=="all"){
            $result=$M->field("id,name,pid,case isParent when 1 then 'true' else 'false' end isParent")->select();
        }else if($type=="edit"){
            $result=$M->where('id='.$id)->save($data);
        }else if($type=='add'){
            $data['isnode']=($data['pid']==0?'0':'1');
            $result=$M->add($data);
        }else if($type=='del'){
            $result=$M->where('id='.$id)->delete();
        }
        $this->ajaxReturn($result,'JSON');
        
    }
    public function update(){
        if(!$this->isAjax())return;
            $M =M("role_authority");
            
            $rids=$this->_post('rids');
            if(empty($rids)){
                $this->ajaxReturn('error');
            }
            $aids=$this->_post('aids');
            $M->where('rid in'."($rids)")->delete();
            if(empty($aids))
                 $this->ajaxReturn('Success');
            $aids=explode(',',$aids);
            $roles=explode(',',$rids);
            
            $n=0;
            foreach ($roles as $rid) {
                foreach ($aids as $aid) {
                        $data[$n]['rid']=$rid;
                        $data[$n]['aid']=$aid;
                        ++$n;
                }
            }
            
            $result=$M->addAll($data);
            $result=$result>0?'Success':'Failed';
            $this->ajaxReturn($result);
        //}
    }
    public function save(){
    	//if($this->isAjax()){
            $M =M(MODULE_NAME);
            if(!$M->create()){
                $this->ajaxReturn(1,'Save Failed.',1);
            }
            $autoNumb=$this->_post('auto_numb');
            if(empty($autoNumb)){
                $this->numb=$M->numb;
            }
            else{
                $this->numb=uuid();  
            }
            R('Comm/Table/save',array($M));
            $M->query();
		//}
    }

}