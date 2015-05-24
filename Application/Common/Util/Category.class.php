<?php 
namespace Common\Util;
Class Category{
	/* 按级别给出分类列表 */
	Static Public function getList($cate, $parent_id = 0, $level = 0, $sign = '-- '){
		$arr = [];
		foreach ($cate as $k => $v) {
			if ($v['parent_id'] == $parent_id) {
				$v['level'] = $level + 1;
				$v['sign'] = str_repeat($sign, $level);
				$arr[] = $v;
				$arr = array_merge($arr, self::getList($cate, $v['id'], $level + 1));
			}
		}
		return $arr;
	}

	/* 根据id求出所有父分类 */
	Static Public function getParent($cate, $parent_id){
		$arr = [];
		foreach ($cate as $k => $v) {
			if ($v['id'] == $parent_id) {

			}
		}
	}
}