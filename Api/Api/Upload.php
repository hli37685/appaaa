<?php

class Api_Upload extends PhalApi_Api {

    /**
     * 获取参数
     * @return array 参数信息
     */
    public function getRules() {
        return array(
            'upload' => array(
                'file' => array(
                    'name' => 'file',
                    'type' => 'file'
                ),
            ),
        );
    }

    /**
     * 上传文件
     * @return string $url 绝对路径
     * @return string $file 相对路径，用于保存至数据库，按项目情况自己决定吧
     */
    public function upload() {
//        var_dump($_FILES);
//        var_dump($_REQUEST);
//        var_dump($this->file);
        //设置上传路径 设置方法参考3.2
        DI()->ucloud->set('save_path',date('Y/m/d'));

        //新增修改文件名设置上传的文件名称
//        DI()->ucloud->set('file_name', 'avatar');

        //上传表单名
        $rs = DI()->ucloud->upfile(current($_FILES));

        return $rs;
    }

}
?>