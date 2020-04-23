<?php

class Model_Code extends PhalApi_Model_NotORM {

    public function getList($data) {
        return $this->getORM()
            ->select('*')
            ->limit(($data['page'] - 1) * $data['limit'],$data['limit'])
            ->fetchAll();
    }

    public function test($data) {
        return $this->getORM()
            ->insert_multi($data);
    }

    public function count() {
        return $this->getORM()->count();
    }
}
