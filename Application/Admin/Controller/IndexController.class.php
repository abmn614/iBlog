<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController {
	/* 管理员列表 */
    Public function index(){
        $userInfo = D('User')->relation('role')->select();

    	$this->userInfo = $userInfo;

    	$this->controller = '用户管理';
    	$this->action = '用户列表';
        $this->display('index');
    }

    /* 管理员 添加 */
    Public function add(){
        $this->roles = M('Role')->select();
    	$this->controller = '用户管理';
    	$this->action = '添加管理员';
    	$this->display('add');
    }

    /* 管理员 添加 提交 */
    Public function addDo(){
    	IS_POST || $this->error('非法请求', U('Index/add'));
    	$user = D('User');
        // 验证两次输入的密码是否一致
        if (I('password') !== I('repassword')) $this->error('两次输入的密码不一致', U('Index/add'));
        // 验证用户名或者邮箱是否存在
        $username = I('username');
        if ($user->where("username = $username OR email = $username")->find()) {
            $this->error('该用户已存在', U('Index/add'));
        }
    	$data = [
    		'username'		=>	$username,
    		'password'		=>	md5(I('password')),
    		'email'			=>	I('email'),
    		'nickname'		=>	I('nickname'),
    	];
    	// 插入新用户
		if ($affected_rows = $user->add($data)) {
            foreach (I('role') as $k => $v) {
                $data_role[] = [
                    'user_id'   =>  $affected_rows,
                    'role_id'   =>  $v
                ];
            }
            if ($affected_rows_role = M('Role_user')->addAll($data_role)) {
                // 写入日志
                unset($data['password']);
                $log_data = [
                    'username'      =>  session('username'),
                    'op'            =>  '添加管理员',
                    'data'          =>  json_encode(['user' => $data, 'role' => $data_role]),
                    'affected_rows' =>  $affected_rows
                ];
                M('Log')->add($log_data);
            }

			$this->redirect('Index/index');
		}else{
			$this->error('添加管理员失败', U('Index/add'));
		}
    }

    /* 管理员 修改 */
    Public function alter(){
    	$id = I('id');
    	$this->userInfo = M('User')->find($id);

    	$this->controller = '用户管理';
    	$this->action = '修改资料';
    	$this->display('alter');
    }

    /* 管理员 修改 提交 */
    Public function alterDo(){
    	IS_POST || $this->error('非法请求', U('Index/alter'));
    	$user = D('User');
    	$data = [
    		'id'		=>	I('id'),
    		'email'		=>	I('email'),
    		'nickname'	=>	I('nickname')
    	];
    	if($affected_rows = $user->save($data)){
    		// 写入日志
    		$log_data = [
    			'username'		=>	session('username'),
    			'op'			=>	'修改管理员信息',
    			'data'			=>	json_encode($data),
    			'affected_rows'	=>	$affected_rows
    		];
			M('Log')->add($log_data);
    		
    		$this->redirect('Index/index');
    	}else{
    		$this->error('更新资料失败', U('Index/index'));
    	}
    }

    /* 锁定 or 解锁管理员 */
    Public function lockDo(){
    	$data = [
    		'id'		=>	I('id'),
    		'status'	=>	I('status')
    	];
    	if ($affected_rows = M('User')->save($data)) {
    		// 写入日志
    		$log_data = [
    			'username'		=>	session('username'),
    			'op'			=>	'锁定|解锁用户',
    			'data'			=>	json_encode($data),
    			'affected_rows'	=>	$affected_rows
    		];
    		M('Log')->add($log_data);
    		$this->redirect('Index/index');
    	}else{
    		$this->error('操作失败');
    	}
    }

}