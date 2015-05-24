<?php 
namespace Admin\Controller;
use Think\Controller;

Class PostController extends BaseController{
	/* 文章列表 */
	Public function index(){
		$this->posts = D('Post')->relation(true)->where(['status' => I('status')])->select();

		$this->controller = '文章';
		$this->action = '文章列表';
		$this->display('index');
	}

	/* 添加文章 */
	Public function postAdd(){
		$user = M('User')->where(['username' => session('username')])->find();
		$this->username = empty($user['nickname']) ? $user['username'] : $user['nickname'];
		$this->userid = $user['id'];

		$cate = M('Cate')->where(['status' => 0])->order('sort')->select();
		$cateList = new \Common\Util\Category;
		$cate = $cateList::getList($cate);
		$this->cates = $cate;

		$this->controller = '文章';
		$this->action = '添加文章';
		$this->display('post_add');
	}

	/* 添加文章 提交 */
	Public function postAddDo(){
		IS_POST || $this->error('非法提交', U('Post/post_add'));
		$post = M('Post');
		$data = [
			'title'		=>	I('title'),
			'cate_id'	=>	I('cate_id', 0, 'intval'),
			'user_id'	=>	I('user_id'),
			'content'	=>	I('content'),
		];
		if ($affected_rows = $post->add($data)) {
			$tags = explode(',', I('tag'));
			foreach ($tags as $v) {
				$data_tag[] = [
					'post_id'	=>	$affected_rows,
					'name'		=>	$v
				];
			}
			if ($affected_rows_tag = M('Tag')->addAll($data_tag)) {
				// 写入日志
				$data['post_id'] = $affected_rows;
				$log_data = [
					'username'		=>	session('username'),
					'op'			=>	'发表文章',
					'data'			=>	json_encode(['data_post' => $data, 'data_tag' => $data_tag]),
					'affected_rows'	=>	$affected_rows
				];
				M('Log')->add($log_data);
				$this->redirect('Post/index');
			}else{
				$this->error('添加文章tag失败', U('Post/postAdd'));
			}
		}else{
			$this->error('发布文章失败', U('Post/postAdd'));
		}
	}

	/* 修改文章 */
	Public function postAlter(){
		$id = I('id');
		$this->post = D('Post')->relation('User')->find($id);
		$cate = M('Cate')->where(['status' => 0])->order('sort')->select();
		$cateList = new \Common\Util\Category;
		$this->cates = $cateList::getList($cate);

		$tags = M('Tag')->where(['post_id' => $id])->select();
		foreach ($tags as $v) {
			$tag .= $v['name'] . ',';
		}
		$this->tag = rtrim($tag, ',');

		$this->controller = '文章';
		$this->action = '修改文章';
		$this->display('post_alter');
	}

	/* 修改文章 提交 */
	Public function postAlterDo(){
		IS_POST || $this->error('非法提交', U('Post/index'));
		// p(I('post.'));die;
		$id = I('id');
		$data = [
			'id'		=>	$id,
			'title'		=>	I('title'),
			'content'	=>	I('content'),
			'user_id'	=>	I('user_id'),
			'cate_id'	=>	I('cate_id')
		];
		if ($affected_rows = M('Post')->save($data)) {
			// 先清除旧tag
			M('Tag')->where(['post_id' => $id])->delete();
			// 修改tag

			$tags = explode(',', I('tag'));
			foreach ($tags as $v) {
				$data_tag[] = [
					'post_id'	=>	$id,
					'name'		=>	$v
				];
			}
			if ($affected_rows_tag = M('Tag')->addAll($data_tag)) {
				// 写入日志
				$log_data = [
					'username'		=>	session('username'),
					'op'			=>	'修改文章',
					'data'			=>	json_encode($data),
					'affected_rows'	=>	$affected_rows
				];
				M('Log')->add($log_data);
				$this->redirect('Post/index');
			}else{
				$this->error('修改tag失败', U('Post/index'));
			}
		} else {
			$this->error('修改文章失败', U('Post/index'));
		}
	}

	/* 删除文章 */
	Public function postDelDo(){
		$data = [
			'id'		=>	I('id'),
			'status'	=>	2
		];
		if ($affected_rows = M('Post')->save($data)) {
			// 写入日志
			$log_data = [
				'username'		=>	session('username'),
				'op'			=>	'删除文章',
				'data'			=>	json_encode($data),
				'affected_rows'	=>	$affected_rows
			];
			M('Log')->add($log_data);
			$this->redirect('Post/index');
		}else{
			$this->error('删除失败', U('Post/index'));
		}
	}

	/* 锁定文章 */
	Public function lockDo(){
		$data = [
			'id'		=>	I('id'),
			'status'	=>	I('status')
		];
		if ($affected_rows = M('Post')->save($data)) {
			// 写入日志
			$log_data = [
				'username'		=>	session('username'),
				'op'			=>	'锁定文章',
				'data'			=>	json_encode($data),
				'affected_rows'	=>	$affected_rows
			];
			M('Log')->add($log_data);
			$this->redirect('Post/index');
		}else{
			$this->error('锁定失败', U('Post/index'));
		}
	}

	/* 分类列表 */
	Public function cateList(){
		$cate = M('Cate')->where(['status' => 0])->order('sort')->select();
		$cateList = new \Common\Util\Category;
		$this->cates = $cateList::getList($cate);

		$this->controller = '文章';
		$this->action = '分类列表';
		$this->display('cate_list');
	}

	/* 改变列表排序 */
	Public function cateSort(){
		IS_POST || $this->error('非法提交', U('Post/cateList'));
		$cate = M('Cate');
		foreach (I('post.') as $id => $sort) {
			$data = [
				'id'	=>	$id,
				'sort'	=>	$sort
			];
			if ($affected_rows = $cate->save($data)) {
				// 写入日志
				$log_data = [
					'username'		=>	session('username'),
					'op'			=>	'改变文章分类列表排序',
					'data'			=>	json_encode($data),
					'affected_rows'	=>	$affected_rows
				];
				M('Log')->add($log_data);
			}
		}
		$this->redirect('Post/cateList');
	}

	/* 添加分类 */
	Public function cateAdd(){
		$this->id = I('id');
		$cate = M('Cate')->order('sort')->select();
		$cateList = new \Common\Util\Category;
		$cate = $cateList->getList($cate);
		$this->cates = $cate;
		$this->controller = '文章';
		$this->action = '添加分类';
		$this->display('cate_add');
	}

	/* 添加分类 提交 */
	Public function cateAddDo(){
		IS_POST || $this->error('非法提交', U('Post/cateAdd'));
		$cate = D('Cate');
		$data = [
			'parent_id'		=>	I('parent_id'),
			'name'		=>	I('name')
		];
		if ($affected_rows = $cate->add($data)) {
			// 写入日志
			$log_data = [
				'username'		=>	session('username'),
				'op'			=>	'添加文章分类',
				'data'			=>	json_encode($data),
				'affected_rows'	=>	$affected_rows
			];
			M('Log')->add($log_data);
			$this->redirect('Post/cateList');
		}else{
			$this->error('文章分类添加失败', U('Post/cateAdd'));
		}
	}

	/* 修改分类 */
	Public function cateAlter(){
		$id = I('id');
		$this->id = $id;
		$cate = M('Cate')->order('sort')->select();
		$cateList = new \Common\Util\Category;
		$this->cates = $cateList->getList($cate);
		$this->cateCurrent = M('Cate')->find($id);
		$this->controller = '文章';
		$this->action = '分类修改';
		$this->display('cate_alter');
	}

	/* 修改分类 提交 */
	Public function cateAlterDo(){
		IS_POST || $this->error('非法提交', U('Post/cateList'));
		$cate = D('Cate');
		$data = [
			'id'			=>	I('id'),
			'parent_id'		=>	I('parent_id'),
			'name'		=>	I('name')
		];
		if ($affected_rows = $cate->save($data)) {
			// 写入日志
			$log_data = [
				'username'		=>	session('username'),
				'op'			=>	'修改文章分类',
				'data'			=>	json_encode($data),
				'affected_rows'	=>	$affected_rows
			];
			M('Log')->add($log_data);
			$this->redirect('Post/cateList');
		}else{
			$this->error('文章分类修改失败', U('Post/cateList'));
		}
	}


	/* 删除分类 */
	Public function cateDelDo(){
		$id = I('id');
		$cate = M('Cate');
		$post = M('Post');
		// 是否有子目录
		if ($cate->where(['parent_id' => $id])->find()) {
			$this->error('该目录有子目录，无法删除');
		}
		// 是否有文章
		if ($post->where(['cate_id' => $id])->find()) {
			$this->error('该目录不为空，无法删除');
		}else{
			$data = [
				'id'		=>	$id,
				'status'	=>	1
			];
			if ($affected_rows = $cate->save($data)) {
				// 写入日志
				$log_data = [
					'username'		=>	session('username'),
					'op'			=>	'删除文章目录',
					'data'			=>	json_encode($data),
					'affected_rows'	=>	$affected_rows
				];
				M('Log')->add($log_data);
				$this->redirect('Post/cateList');
			}else{
				$this->error('删除文件目录失败');
			}
		}
	}



}