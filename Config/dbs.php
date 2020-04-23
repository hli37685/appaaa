<?php

return array(

    'servers' => array(
        'db_api' => array(
            'host'      => '127.0.0.1',
            'name'      => 'qweasd_c32w_top',
            'user'      => 'qweasd_c32w_top',
            'password'  => 'qweasd_c32w_top',
            'port'      => '3306',
            'charset'   => 'UTF8',
        ),
    ),

    'tables' => array(
        //通用路由
        '__default__' => array(
            'prefix' => '',
            'key' => 'id',
            'map' => array(
                array('db' => 'db_api'),
            ),
        ),
    ),
);
