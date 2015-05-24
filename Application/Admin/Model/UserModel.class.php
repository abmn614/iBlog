<?php 
namespace Admin\Model;
use Think\Model\RelationModel;
Class UserModel extends RelationModel{
    // 定义关联
    Protected $_link = [
        // 角色 - 用户
        'role' => [
            'mapping_type'          =>  self::MANY_TO_MANY,
            'foreign_key'           =>  'user_id',
            'relation_foreign_key'  =>  'role_id',
            'relation_table'        =>  __ROLE_USER__,
            'mapping_fields'		=>	'id,name'
            ]
        ];
}