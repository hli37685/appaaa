<?php
require_once dirname(__FILE__) . '/init.php';
DI()->loader->addDirs('Api');
DI()->ucloud = new UCloud_Lite();
$api = new PhalApi();
$rs = $api->response();
$rs->output();

