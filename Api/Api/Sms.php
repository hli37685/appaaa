<?php
/**
 * 用户信息类
 */

class Api_Sms extends PhalApi_Api {

    public function getRules() {
        return array(
            '*' => array(
                'id' => array('name' => 'id', 'type' => 'string'),
                'cellphone' => array('name' => 'cellphone', 'type' => 'string'),
                'tel' => array('name' => 'tel', 'type' => 'string'),
                'content' => array('name' => 'content', 'type' => 'string'),
                'page' => array('name' => 'page', 'type' => 'int'),
                'limit' => array('name' => 'limit', 'type' => 'int'),
            ),
        );
    }
    public function send()
    {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $statusStr = array(
            "0" => "短信发送成功",
            "-1" => "参数不全",
            "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
            "30" => "密码错误",
            "40" => "账号不存在",
            "41" => "余额不足",
            "42" => "帐户已过期",
            "43" => "IP地址限制",
            "50" => "内容含有敏感词"
        );
        $code = rand(1000, 9999);
        $smsapi = "http://api.smsbao.com/";
        $user = "13150794478"; //短信平台帐号
        $pass = md5("123456789.."); //短信平台密码
        $content="【品阅】您的验证码是$code";//要发送的短信内容
        $phone = $this->cellphone;//要发送短信的手机号码
        $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
        $result =file_get_contents($sendurl) ;
        DI()->logger->debug('code:', $code);
        $info = array(
            'code'=>$code,
            'status'=>$statusStr[$result]
        );

        $rs['info'] = $info;
        return $rs;

    }
    public function download() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $u_model = new Model_User();
        $u_d = $u_model->get($this->id);
        $model = new Model_Sms();
        $data = array(
            'page'=>1,
            'limit'=>9999,
            'id'=>$this->id
        );

        $info = $model->getList($data);
        $str = "发件人 内容\r\n";
        foreach ($info as $value){
            $str .= $value['tel'].' '. str_replace(array("\r\n", "\r", "\n"), "", $value['content'])."\r\n";
//            echo "\r\n";
//            echo "\r\n";
//            echo $value['content'];
        }

        $filename = "D+".$u_d['code']."+".$u_d['cellphone'].".txt";
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

        $model = new Model_Sms();
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

        $model = new Model_Sms();

        $info = $model->delete(intval($_GET['id']));

        return $rs;
    }

    public function create() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $model = new Model_Sms();
        $data = array(
            'user_id'=>$this->id,
            'tel'=>$this->tel,
            'content'=>$this->content
        );
        $info = $model->insert($data);
        $rs['info'] = $info;

        return $rs;
    }
    public function import() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $model = new Model_Sms();
        DI()->logger->debug('smscontent'.$this->content);
        $content = $this->content;
        $content = trim($content,'[');
        $content = trim($content,']');
        $content = str_replace("{","",$content);
        $content = str_replace("}","",$content);
        $arr = explode(', ', $content);
//        var_dump($arr);
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
                    'user_id'=>$this->id,
                    'date'=>$date,
                    'tel'=>$address,
                    'content'=>$body
                ));
            }
        }


        //DI()->logger->debug('content:', $this->content);
//        $data = array(
//            'user_id'=>$this->id,
//            'tel'=>$this->tel,
//            'content'=>$this->content
//        );
        //$info = $model->insert($data);
        //$rs['info'] = $info;

        return $rs;
    }
    public function updatestatus() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $model = new Model_Sms();
        $info = $model->update($this->id,array(
            'status'=>1
        ));
        $rs['info'] = $info;

        return $rs;
    }



}
