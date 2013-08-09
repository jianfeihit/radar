<?php
class BulletinsAction extends Action {
	public function __construct(){
		parent::__construct();
		$this->is_login();
	}
	public function is_login(){
		if (!session("?loginuser")){
			$this->redirect("Login/show","",0,"");
		}
	}
	public function open(){
		$data = M("bulletins");
		$ret = $data->order('createTime desc')->select();
		$this->assign('bulletins',$ret);
		$this->display('./Tpl/bulletins/bulletins.html');
	}

	public function downloadIt(){
		$kdata = M("bulletins");
		$id = trim(I("get.id"));
		$kp = $kdata->where("id=".$id)->find();
		download($kp["path"],$kp["title"]);
	}
}