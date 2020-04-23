<?php

class Model_User extends PhalApi_Model_NotORM {

    public function getList($data) {
        if(isset($data['id'])){
            $res = $this->getORM()
                ->select('*')
                ->where('code = ?',$data['id'])
                ->limit(($data['page'] - 1) * $data['limit'],$data['limit'])
                ->fetchAll();
        }else{
            $res = $this->getORM()
                ->select('*')
                ->limit(($data['page'] - 1) * $data['limit'],$data['limit'])
                ->fetchAll();
        }
        return $res;
    }

    public function count($data) {
        if(isset($data['id'])){
            $res = $this->getORM()
                ->select('*')
                ->where('code = ?',$data['id'])
                ->count();
        }else{
            $res = $this->getORM()
                ->select('*')
                ->count();
        }
        return $res;
    }

}
