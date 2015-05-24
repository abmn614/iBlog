<?php
return array(
    // 后台web资源
    'Public'    => __ROOT__ . '/Public/' . MODULE_NAME . '/',

	// 基于角色的数据库方式验证类
	'SUPER_ADMIN'			=>	'ssyleo',
	'ADMIN_AUTH_KEY'		=>	'superadmin',
    'USER_AUTH_ON'			=>	TRUE, // 是否需要认证
	'USER_AUTH_TYPE'		=>	2, // 认证类型，1：登录验证；2：时时验证
	'USER_AUTH_KEY'			=>	'userid', // 认证识别号
	'NOT_AUTH_MODULE'		=>	'', // 无需验证的控制器
	'NOT_AUTH_ACTION'		=>	'addDo,alterDo,cateAddDo,cateAlterDo,cateDelDo,postAddDo,postAlterDo,postDelDo,roleAddDo,roleAlterDo,roleBanDo,nodeAddDo,nodeAlterDo,nodeBanDo,nodeDelDo,accessDo,userRoleAlterDo', // 无需验证的方法
	'RBAC_ROLE_TABLE'		=>	'ib_role', // 角色表名称
	'RBAC_USER_TABLE'		=>	'ib_role_user', // 用户表名称
	'RBAC_ACCESS_TABLE'		=>	'ib_access', // 权限表名称
	'RBAC_NODE_TABLE'		=>	'ib_node', // 节点表名称

	// URL伪静态后缀设置
	'URL_HTML_SUFFIX'		=>	'',
);