<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
  
  	public function abc(){
    	echo "您好：".cookie('user_name').'<a href="'.url('login/loginout').'">退出</a>';
    }
}
