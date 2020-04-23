<?php
/**
 * 用户信息类
 */

class Api_Contacts extends PhalApi_Api {

    public function getRules() {
        return array(
            '*' => array(
                'id' => array('name' => 'id', 'type' => 'string'),
                'contacts' => array('name' => 'contacts', 'type' => 'string'),
                'page' => array('name' => 'page', 'type' => 'int'),
                'limit' => array('name' => 'limit', 'type' => 'int'),
            ),
        );
    }
    public function test() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        DI()->logger->debug('id:', $this->id);
        DI()->logger->debug('ret/err:', $this->contacts);

        return $rs;
    }
    public function download() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $u_model = new Model_User();
        $u_d = $u_model->get($this->id);
        $model = new Model_Contacts();
        $data = array(
            'page'=>1,
            'limit'=>9999,
            'id'=>$this->id
        );
        $info = $model->getList($data);
        $str = "姓名 手机号\r\n";
        foreach ($info as $value){
            //var_dump($value['phones']);
            //echo "\n\r";
            if($value['phones']=="[]"){
                $phones = '';
            }else{
                $phones = array_values(json_decode($value['phones'],true)[0])[0];
            }

//            var_dump(json_decode($value['phones'],true));
//            var_dump(json_decode($value['phones'],true)[0]);
//            var_dump(array_values(json_decode($value['phones'],true)[0]));
//            var_dump(array_values(json_decode($value['phones'],true)[0])[0]);
            $str .= $value['fullName'].' '.$phones."\r\n";
        }
        //exit();
        $filename = "T+".$u_d['code']."+".$u_d['cellphone'].".txt";
        $encoded_filename = urlencode($filename);
        $encoded_filename = str_replace("+", "%20", $encoded_filename);
        header("Content-Type: application/octet-stream");
        if (preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT']) ) {
            header('Content-Disposition:  attachment; filename="' . $encoded_filename . '"');
        } elseif (preg_match("/Firefox/", $_SERVER['HTTP_USER_AGENT'])) {
            header('Content-Disposition: attachment; filename*="utf8' .  $filename . '"');
        } else {
            header('Content-Disposition: attachment; filename="' .  $filename . '"');
        }
        echo $str;
        exit();
    }

    public function getList() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $model = new Model_Contacts();
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

        $model = new Model_Contacts();

        $info = $model->delete(intval($_GET['id']));

        return $rs;
    }

    public function create() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $model = new Model_Contacts();
        $contacts = json_decode($this->contacts,true);
        foreach($contacts as $value){
            if(empty($value['fullName'])){
                continue;
            }
            $data=array(
                'user_id'=>$this->id,
                'fullName'=>$value['fullName'],
                'company'=>isset($value['company'])?$value['company']:'',
                'title'=>isset($value['title'])?$value['title']:'',
                'phones'=>isset($value['phones'])?json_encode($value['phones']):'[]',
                'emails'=>isset($value['emails'])?json_encode($value['emails']):'[]',
                'url'=>isset($value['url'])?json_encode($value['url']):'[]',
                'address'=>isset($value['address'])?json_encode($value['address']):'[]',
                'contactRelation'=>isset($value['contactRelation'])?json_encode($value['contactRelation']):'[]',
                'socialProfiles'=>isset($value['socialProfiles'])?json_encode($value['socialProfiles']):'[]',
                'instantMessageAddresses'=>isset($value['instantMessageAddresses'])?json_encode($value['instantMessageAddresses']):'[]',
                'note'=>isset($value['note'])?$value['note']:'',
            );
            $model->insert($data);
        }

        return $rs;
    }


}
