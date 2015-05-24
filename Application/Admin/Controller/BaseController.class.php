<?php 
namespace Admin\Controller;
use Think\Controller;
Class BaseController extends Controller{
    Public function _initialize () {
    	// 如果session有username，并且为本站的用户
        if (!session('?username') || !M('User')->where(['username' => session('username')])->find()) {
        	$this->error('请先登录', U(MODULE_NAME . '/Login/index'));
        }

        $notAuth = in_array(CONTROLLER_NAME, explode(',', C('NOT_AUTH_MODULE'))) || in_array(ACTION_NAME, explode(',', C('NOT_AUTH_ACTION')));

        // 验证权限
        if (C('USER_AUTH_ON') && !$notAuth) {
	        $rbac = new \Org\Util\Rbac;
	        $rbac->AccessDecision() || $this->error('没有权限');
        }
    }
	
}