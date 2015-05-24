<?php
return array(
    // 单入口模块权限
    'MODULE_ALLOW_LIST'     => ['Home', 'Admin'],
    'DEFAULT_MODULE'        => 'Home',
	'MODULE_DENY_LIST'      => [],

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

    'DEFAULT_FILTER'    => 'trim,htmlspecialchars', // I方法两层过滤

    // 表单令牌配置
    'TOKEN_ON'      =>    true,  // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true

    // Ueditor百度编辑器
    'UEDITOR'   =>  __ROOT__ . '/Data/Ueditor/',
);