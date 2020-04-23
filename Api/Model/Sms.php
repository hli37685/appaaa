<?php

class Model_Sms extends PhalApi_Model_NotORM {

    public function getList($data) {
        if(isset($data['id'])){
            $res = $this->getORM()
                ->select('*')
                ->where('user_id = ?',$data['id'])
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
                ->where('user_id = ?',$data['id'])
                ->count();
        }else{
            $res = $this->getORM()
                ->select('*')
                ->count();
        }
        return $res;
    }


}
