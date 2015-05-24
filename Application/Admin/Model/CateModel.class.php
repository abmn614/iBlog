<?php 
namespace Admin\Model;
use Think\Model;
Class CateModel extends Model{
	Protected $_validate = [
		['name', 'require', '分类名称不能为空'],
	];
	
}