<?php
namespace app\index\controller;
 
use think\Controller;
 
class Regist extends Controller
{
  	public function index(){
      return $this->fetch();
    }
  
       // 处理注册逻辑
    public function doRegist()
    {
    	$param = input('post.');
    	if(empty($param['user_name'])){
    		
    		$this->error('用户名不能为空');
    	}
    	
    	if(empty($param['user_pwd'])){
    		
    		$this->error('密码不能为空');
    	}
      	if(empty($param['user_pwd_confirm'])){
    		
    		$this->error('确认密码不能为空');
    	}
       if($param['user_pwd']!=$param['user_pwd_confirm'])
   	 	{
        $this->error('两次输入密码不同');
    	}
 	
    	// 验证用户名是否存在
    	$has = db('users')->where('user_name', $param['user_name'])->find();
      
    	if(!empty($has)){    		
    		$this->error('用户名已存在，请换一个');
    	}else{      
            $data = ['user_name'=>$param['user_name'],'user_pwd'=>md5($param['user_pwd'])];
            $ok = db('users')->insert($data);      
        }
    	
    	
    	$this->redirect(url('index/index'));
    }
  
 
}
