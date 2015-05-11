<?php
return array(
    // 单入口模块权限
    'MODULE_ALLOW_LIST'     => ['Home', 'Admin'],
    'DEFAULT_MODULE'        => 'Home',
	'MODULE_DENY_LIST'      => [],

    // 公共web资源
    'Public'    => './Public/',

    // 模版分隔符
    'TMPL_L_DELIM'  =>  '{{',            // 模板引擎普通标签开始标记
    'TMPL_R_DELIM'  =>  '}}',            // 模板引擎普通标签结束标记
);