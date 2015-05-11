<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$UserInfo = M('User')->select();
    	$this->UserInfo = $UserInfo;
    	
        $this->display('index');
    }
}