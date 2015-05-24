<?php 
namespace Admin\Model;
use Think\Model\RelationModel;
Class PostModel extends RelationModel{
	Protected $_link = [
        // 文章分类
        'Cate'		=>	[
            'mapping_type'      =>  self::BELONGS_TO,
            'foreign_key'       =>  'cate_id',
            'mapping_fields'	=>	'name',
            'as_fields'			=>	'name:cate'
            ],

        'User'	=>	[
        	'mapping_type'      =>  self::BELONGS_TO,
            'foreign_key'       =>  'user_id',
            'mapping_fields'    =>  'username,nickname',
            'as_fields'         =>  'username,nickname'
        ],

        'Tag'	=>	[
    	    'mapping_type'  	=>	self::HAS_MANY,
	        'class_name'    	=> 	'Tag',
            'foreign_key'   	=> 	'post_id',
            'mapping_name'  	=> 	'tag',
            'mapping_fields'	=>	'name'
        ],
	];
}