<?php
class AccountAction extends Action {
	public function __construct(){
		parent::__construct();
		$this->is_login();
	}
	public function query(){
		import('ORG.Util.Page');
		header("Content-type:text/html;charset=utf-8");
		$key= trim(I("get.keyword"));
		$kobj = M("user");
		if($key==""){
			$count= $kobj->count();
			$Page = new Page($count,10);
			$show = $Page->show();
			$this->kdata = $kobj->order('createDate desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}else{
			$qmap['userName'] = array("like","%$key%");
			$count= $kobj->where($qmap)->count();
			$Page = new Page($count,10);
			$Page->parameter   .=   "keyword=".$key.'&';
			$show = $Page->show();
			$this->kdata = $kobj->where($qmap)->order('createDate desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		$this->assign('page',$show);
		$this->display();
	}

	public function add(){
		$this->display();
	}

	public function edit(){
		$userid = I("get.userid");
		$this->udata = M("user")->where("userid='$userid'")->find();
		$this->display();
	}
	public function delete(){
		$userid = I("get.userid");
		if(M("User")->where("userid='$userid'")->delete()){
			write_log("删除账户".$userid);
			$this->redirect("Account/query","",0,"页面跳转中......");
		}else{
			$this->error('删除失败！');
		}
	}
	public function save(){
		$user = D("User");
		if(!$user->create()){
			$this->error($user->getError());
		}else{
			$user->createDate=date("Y-m-d H:i:s");
			$user->add();
			write_log("新增账户".I("post.userId"));
			$this->redirect("Account/query","",0,"页面跳转中......");
		}
	}

	public function update(){
		$user = M("User");
		$userId = trim(I("post.userId"));
		$ary["userName"] = trim(I("post.userName"));
		$ary["userEmail"] = trim(I("post.userEmail"));
		if(""!=trim(I("post.pwd"))){
			$ary["pwd"] = md5(trim(I("post.pwd")));
			$repwd = md5(trim(I("post.repwd")));
			if($repwd!=$ary["pwd"]){
				$this->error("两次输入密码不一致");
			}
		}
		if($user->where("userId='$userId'")->save($ary)){
			write_log("更新账户".I("post.userId"));
			$this->redirect("Account/query","",0,"");
		}else{
			$this->error($user->getError());
		}

	}

	public function is_login(){
		if (!session("?loginuser")){
			$this->redirect("Login/show","",0,"");
		}
	}
}