<?php
class LoginAction extends Action {
	public function show(){
		if(session("?loginuser")){
			$this->redirect("Index/index","",0,"");
		}else{
			$this->redirect("Login/index","",0,"");
			//$this->display();
		}
	}
	public function index(){
		if(session("?loginuser")){
			$this->redirect("Index/index","",0,"");
		}else{
			$this->display();
		}
	}

	public function login(){
		$userId = I("post.userId");
		$pwd = I("post.pwd");
		$ck = M("User")->where("userId='$userId' and pwd ='".md5($pwd)."'")->find();
		if($ck){
			session("loginuser",$ck);
			write_log("系统登录");
			if($ck["userType"]==2){
				$this->redirect("System/auditlog","",0,"");
			}else{
				$this->redirect("Index/index","",0,"");
			}
		}else{
			$this->error("用户ID或密码错误！");
		}
	}
	public function  loginout(){
		if(session("?loginuser")){
			write_log("注销登录");
			session(null);
		}
		$this->redirect("Login/show","",0,"");
	}
}