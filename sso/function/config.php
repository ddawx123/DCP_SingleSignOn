<?php
/**
* SSO应用配置文件，在这里定义一些常量用于登记MySQL数据库服务器的信息。
* Copyright 2016-2017 DingStudio All Rights Reserved
*/

define('mysql_server_address','sqld.example.com:3306');//MySQL数据库服务器地址
define('mysql_server_username','root');//MySQL数据库服务器账号
define('mysql_server_password','123456');//MySQL数据库服务器密码
define('mysql_server_dbname','ssobase');//MySQL数据库对应库名（你需要确保上面的账号密码有权限操作它）

define('cross_domain_config','dingstudio.cn');//跨域设置-主域名配置（配置SSO服务端所在的父域）
define('register_allowed','true');//是否开放前台注册
define('findpwd_allowed','true');//是否开放前台注册

//TODO：这里还可以定义其他常量（这里的常量在本程序全局有效）
