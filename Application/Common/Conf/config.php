<?php
return array(
    // 单入口模块权限
    'MODULE_ALLOW_LIST'     => ['Home', 'Admin'],
    'DEFAULT_MODULE'        => 'Home',
	'MODULE_DENY_LIST'      => [],

    // 公共web资源
    'Public'    => './Public/',

    // 模版分隔符
    'TMPL_L_DELIM'  =>  '{ ', // 模板引擎普通标签开始标记
    'TMPL_R_DELIM'  =>  ' }', // 模板引擎普通标签结束标记

    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'iblog', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'ib_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集

);