<?php
class KeywordAction extends Action {
	public function __construct(){
		parent::__construct();
		$this->is_login();
	}
	public function is_login(){
		if (!session("?loginuser")){
			$this->redirect("Login/show","",0,"");
		}
	}
	public function query(){
		import('ORG.Util.Page');
		header("Content-type:text/html;charset=utf-8");
		$key= trim(I("get.keyword"));
		$kobj = M("keyword");
		if($key==""){
			$count= $kobj->count();
			$Page = new Page($count,10);
			$show = $Page->show();
			$this->kdata = $kobj->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}else{
			$qmap['keyword'] = array("like","%$key%");
			$count= $kobj->where($qmap)->count();
			$Page = new Page($count,10);
			$Page->parameter   .=   "keyword=".$key.'&';
			$show = $Page->show();
			$this->kdata = $kobj->where($qmap)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	}

	public function add(){
		$this->display();
	}
	public function edit(){
		$id = I("get.id");
		$this->kdata = M("keyword")->where("id='$id'")->find();
		$this->display();
	}
	public function save(){
		$kdata = M("keyword");
		$keyword = trim(I("keyword"));
		if(empty($keyword)){
			$this->error("关键字不能为空！");
		}
		$ary=array(
	'keyword'=>I("keyword"),
	'operator'=>'admin',//session("user")
	'addDate'=>date("Y-m-d H:i:s")
		);
		if($kdata->add($ary)){
			//$this->success('新增成功', '__URL__/query');
			$this->redirect("Keyword/query","",0,"页面跳转中......");
		}else{
			$this->error('新增失败');
		}
	}
}