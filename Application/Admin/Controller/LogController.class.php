<?php 
namespace Admin\Controller;
use Think\Controller;
Class LogController extends BaseController{
	Public function index(){
		$this->logs = M('Log')->select();

		$this->controller = '操作日志';
		$this->action = '日志列表';
		$this->display('index');
	}
}