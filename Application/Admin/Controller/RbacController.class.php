<?php 
namespace Admin\Controller;
use Think\Controller;
Class RbacController extends BaseController{
	/* 用户列表 */
	Public function index(){
		$this->redirect('Index/index');
	}

	/* 角色列表 */
	Public function roleList(){
		$this->roles = M('Role')->select();
	
		$this->controller = '权限验证';
		$this->action = '角色列表';
		$this->display('role_list');
	}

	/* 添加角色 */
	Public function roleAdd(){
		$this->controller = '权限验证';
		$this->action = '添加角色';
		$this->display('role_add');
	}

	/* 添加角色 提交 */
	Public function roleAddDo(){
		IS_POST || $this->error('非法提交');
		$role = M('Role');
		$data = [
			'name'		=>	I('name'),
			'remark'	=>	I('remark'),
			'status'	=>	I('status'),
		];
		if ($affected_rows = $role->add($data)) {
			// 写入日志
			$log_data = [
				'username'		=>	session('username'),
				'op'			=>	'添加角色',
				'data'			=>	json_encode($data),
				'affected_rows'	=>	$affected_rows
			];
			M('Log')->add($log_data);
			$this->redirect('Rbac/roleList');
		}else{
			$this->error('角色添加失败', U('Rbac/roleAdd'));
		}
	}

	/* 角色修改 */
	Public function roleAlter(){
		$id = I('id');
		$this->role = M('Role')->find($id);
		$this->controller = '权限验证';
		$this->action = '角色修改';
		$this->display('role_alter');
	}

	/* 角色修改 提交 */
	Public function roleAlterDo(){
		IS_POST || $this->error('非法提交', U('Rbac/roleList'));
		$role = M('Role');
		$data = [
			'id'		=>	I('id'),
			'name'		=>	I('name'),
			'remark'	=>	I('remark'),
			'status'	=>	I('status')
		];
		if ($affected_rows = $role->save($data)) {
			// 写入日志
			$log_data = [
				'username'		=>	session('username'),
				'op'			=>	'角色修改',
				'data'			=>	json_encode($data),
				'affected_rows'	=>	$affected_rows
			];
			M('Log')->add($log_data);
			$this->redirect('Rbac/roleList');
		}else{
			$this->error('角色修改失败', U('Rbac/roleList'));
		}
	}

	/* 角色开启|禁用 */
	Public function roleBanDo(){
		$data = [
			'id'		=>	I('id'),
			'status'	=>	I('status')
		];
		if ($affected_rows = M('Role')->save($data)) {
			// 写入日志
			$log_data = [
				'username'		=>	session('username'),
				'op'			=>	'角色启用|禁用',
				'data'			=>	json_encode($data),
				'affected_rows'	=>	$affected_rows
			];
			M('Log')->add($log_data);
			$this->redirect('Rbac/roleList');
		}else{
			$this->error('操作失败', U('Rbac/roleList'));
		}
	}

	/* 节点列表 */
	Public function nodeList(){
		$this->role_id = I('id');
		$nodes = M('Node')->order('id')->select();
		$access = M('Access')->where(['role_id' => $this->role_id])->getField('node_id', true);
		$this->nodes = node_merge($nodes, $access);
		
		$this->controller = '权限验证';
		$this->action = '节点列表';
		$this->display('node_list');
	}

	/* 添加节点 */
	Public function nodeAdd(){
		$this->pid = I('pid', 0, 'intval');
		$this->level = I('level', 1, 'intval');

		switch ($this->level) {
			case 1:
				$this->type = '应用';
				break;
			case 2:
				$this->type = '控制器';
				break;
			case 3:
				$this->type = '方法';
				break;
		}

		$this->controller = '权限验证';
		$this->action = '添加节点';
		$this->display('node_add');
	}

	/* 添加节点 */
	Public function nodeAddDo(){
		IS_POST || $this->error('非法提交', U('Rbac/nodeAdd'));
		$node = M('Node');
		$data = [
			'name'		=>	I('name'),
			'remark'	=>	I('remark'),
			'status'	=>	I('status'),
			'pid'		=>	I('pid', 0, 'intval'),
			'level'		=>	I('level', 1, 'intval'),
		];
		if ($affected_rows = $node->add($data)) {
			// 写入日志
			$log_data = [
				'username'		=>	session('username'),
				'op'			=>	'节点添加',
				'data'			=>	json_encode($data),
				'affected_rows'	=>	$affected_rows
			];
			M('Log')->add($log_data);
			$this->redirect('Rbac/nodeList');
		}else{
			$this->error('节点添加失败', U('Rbac/nodeList'));
		}
	}

	/* 节点修改 */
	Public function nodeAlter(){
		$this->node = M('Node')->find(I('id'));
	
		$this->controller = '权限验证';
		$this->action = '节点修改';
		$this->display('node_alter');
	}

	/* 节点修改 提交 */
	Public function nodeAlterDo(){
		IS_POST || $this->error('非法提交', U('Rbac/nodeList'));
		$node = M('Node');
		$data = [
			'id'		=>	I('id'),
			'name'		=>	I('name'),
			'remark'	=>	I('remark'),
			'status'	=>	I('status')
		];
		if ($affected_rows = $node->save($data)) {
			// 写入日志
			$log_data = [
				'username'		=>	session('username'),
				'op'			=>	'节点修改',
				'data'			=>	json_encode($data),
				'affected_rows'	=>	$affected_rows
			];
			M('Log')->add($log_data);
			$this->redirect('Rbac/nodeList');
		}else{
			$this->error('节点修改失败', U('Rbac/nodeList'));
		}
	}

	/* 节点开启|禁用 提交 */
	Public function nodeBanDo(){
		$data = [
			'id'		=>	I('id'),
			'status'	=>	I('status')
		];
		if ($affected_rows = M('Node')->save($data)) {
			// 写入日志
			$log_data = [
				'username'		=>	session('username'),
				'op'			=>	'节点启用|禁用',
				'data'			=>	json_encode($data),
				'affected_rows'	=>	$affected_rows
			];
			M('Log')->add($log_data);
			$this->redirect('Rbac/nodeList');
		}else{
			$this->error('操作失败', U('Rbac/nodeList'));
		}
	}

	/* 删除方法节点 */
	Public function nodeDelDo(){
		$id = I('id');
		$affected_rows_node = M('Node')->where(['id' => $id])->delete();
		$affected_rows_access = M('Access')->where(['node_id' => $id])->delete();

		// 写入日志
		$log_data = [
			'username'		=>	session('username'),
			'op'			=>	'删除节点',
			'data'			=>	json_encode(['node_id' => $id, 'affected_rows_node' => $affected_rows_node, 'affected_rows_access' => $affected_rows_access]),
			'affected_rows'	=>	$affected_rows_node + $affected_rows_access
		];
		M('Log')->add($log_data);

		$this->redirect('Rbac/nodeList');
	}

	/*  */
	Public function accessDo(){
		IS_POST || $this->error('非法提交', U('Rbac/roleList'));
		$access = M('Access');
		$role_id = I('post.')['role_id'];
		$role_id || $this->error('没有指定角色', U('Rbac/roleList'));
		foreach (I('post.')['access'] as $k => $v) {
			$tmp = explode('_', $v);
			$data[] = [
				'node_id'	=>	$tmp[0],
				'level'		=>	$tmp[1],
				'role_id'	=>	$role_id
			];
		}
		// 分配权限前，先清除该角色的旧权限
		$access->where(['role_id' => $role_id])->delete();
		if ($affected_rows = $access->addAll($data)) {
			// 写入日志
			$log_data = [
				'username'		=>	session('username'),
				'op'			=>	'分配权限',
				'data'			=>	json_encode($data),
				'affected_rows'	=>	$affected_rows
			];
			M('Log')->add($log_data);
			$this->redirect('Rbac/roleList');
		}else{
			$this->error('操作失败', U('Rbac/roleList'));
		}
	
	}

	/* 用户角色修改 */
	Public function userRoleAlter(){
        $userInfo = D('User')->relation('role')->find(I('id'));
        foreach ($userInfo['role'] as $v) {
        	$tmp[] = $v['id'];
        }
        $userInfo['role_id'] = $tmp;
        $this->userInfo = $userInfo;
		$this->roles = M('Role')->where(['status' => 1])->select();

		$this->controller = '权限验证';
		$this->action = '分配角色';
		$this->display('user_role_alter');
	}

	/* 用户角色修改 提交 */
	Public function userRoleAlterDo(){
		IS_POST || $this->error('非法提交', U('Index/index'));
		$id = I('id');
		$role_user = M('Role_user');
		$roles = I('role');
		foreach ($roles as $v) {
			$data[] = [
				'user_id'	=>	$id,
				'role_id'	=>	$v
			];
		}
		// 先删除该用户的旧角色
		$role_user->where(['user_id' => $id])->delete();
		if ($affected_rows = $role_user->addAll($data)) {
			// 写入日志
			$log_data = [
				'username'		=>	session('username'),
				'op'			=>	'用户角色修改',
				'data'			=>	json_encode(['user_id' => $id, 'role_id' => $roles]),
				'affected_rows'	=>	$affected_rows
			];
			M('Log')->add($log_data);
			$this->redirect('Index/index');
		}else{
			$this->error('用户角色修改失败', U('Index/index'));
		}
	}

}