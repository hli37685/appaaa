<?php
/**
 * 用户信息类
 */

class Api_Code extends PhalApi_Api {

    public function getRules() {
        return array(
            '*' => array(
                'id' => array('name' => 'id', 'type' => 'string'),
                'code' => array('name' => 'code', 'type' => 'string'),
                'page' => array('name' => 'page', 'type' => 'int'),
                'limit' => array('name' => 'limit', 'type' => 'int'),
            ),
        );
    }
    public function get() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $model = new Model_Code();
        $info = $model->exist('code',$this->code);
        $rs['info'] = $info;

        return $rs;
    }

    public function getList() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $model = new Model_Code();

        $info = $model->getList(array(
            'page'=>$this->page,
            'limit'=>$this->limit
        ));
        $rs['count']=  (int)$model->count();
        $rs['info'] = $info;

        return $rs;
    }
	
	public function Delete() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $model = new Model_Code();

        $info = $model->delete(intval($_GET['id']));

        return $rs;
    }

    public function create() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $model = new Model_Code();
        $model->insert(array(
            'code'=>$this->code
        ));
        return $rs;
    }
    public function test() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $model = new Model_Code();
        $info = $model->test(array(
            array('code'=>1),
            array('code'=>2)
        ));
        $rs['info'] = $info;

        return $rs;
    }

}
