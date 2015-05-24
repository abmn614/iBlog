<?php 
namespace Admin\Controller;
use Think\Controller;
Class LoginController extends Controller{
	/* 登录页面 */
	Public function index(){
		$this->display('index');
	}

	/* 登录验证 */
	Public function check(){
		// 是否post请求
		IS_POST || $this->error('非法请求', U('Login/index'));
    	$user = D('User');
    	// 自动验证表单数据
    	$user->create() || $this->error($user->getError(), U('Login/index'));
		$username = I('username');
		session('username', $username); // 避免二次输入用户名
		// 验证码验证
		if (!$this->checkVerify(I('verify'))) $this->error('验证码错误', U('Login/index'));
		// 验证用户密码
		$password = md5(I('password'));
		$is_exist = M('User')->where("(username = '%s' or email = '%s') and password = '%s'", $username, $username, $password)->find();

		if ($is_exist) {
			// 写入日志
			$log_data = [
				'username'		=>	session('username'),
				'op'			=>	'用户登录',
				'data'			=>	json_encode(['username' => $username, 'ip' => get_client_ip()]),
				'affected_rows'	=>	$affected_rows
			];
			M('Log')->add($log_data);

			// 重写username
			session('username', $is_exist['username']);
			session(C('USER_AUTH_KEY'), $is_exist['id']);

			// 权限验证
			$rbac = new \Org\Util\Rbac;
			$rbac::saveAccessList();

			// 超级管理员验证
			if (C('SUPER_ADMIN') === session('username')) {
				session(C('ADMIN_AUTH_KEY'), TRUE); // 不为空即可
			}

			$this->redirect('Index/index');
		}else{
			$this->error('帐号或密码错误', U('Login/index'));
		}
	}

	/* 退出登录 */
	Public function logout(){
		// 写入日志
		$log_data = [
			'username'		=>	session('username'),
			'op'			=>	'退出登录',
			'data'			=>	json_encode(['username' => session('username'), 'ip' => get_client_ip()]),
			'affected_rows'	=>	$affected_rows
		];
		M('Log')->add($log_data);
		session('username', NULL);
		session(NULL);
		$this->redirect('Login/index');
	}

	/* 生成验证码 */
	Public function verify(){
		$config = array(
		    'fontSize'    =>    20,    // 验证码字体大小
		    'length'      =>    4,     // 验证码位数
		    'useNoise'    =>    false, // 关闭验证码杂点
		    'imageW'	  =>	150, //
		    'imageH'	  =>	40, //
	    );
		$verify = new \Think\Verify($config);
		$verify->entry();
	}

	/* 检测验证码是否正确 */
	Public function checkVerify($code, $id = ''){
		$verify = new \Think\Verify();
		return $verify->check($code, $id);
	}
}