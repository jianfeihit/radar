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

	public function update(){
		$keyword = M("Keyword");
		$id = trim(I("post.id"));
		$ary["keyword"] = trim(I("post.keyword"));
		if($keyword->where("id='$id'")->save($ary)){
			write_log("更新关键字：".$ary["keyword"]);
			$this->redirect("Keyword/query","",0,"");
		}else{
			$this->error($keyword->getError());
		}
	}
	public function save(){
		$kdata = M("keyword");
		$keyword = trim(I("keyword"));
		if(empty($keyword)){
			$this->error("关键字不能为空！");
		}
		$loginuser =session("loginuser");
		$ary=array(
	'keyword'=>I("keyword"),
	'operator'=>$loginuser["userName"],
	'addDate'=>date("Y-m-d H:i:s")
		);
		if($kdata->add($ary)){
			write_log("新增关键字：".$ary["keyword"]);
			$this->redirect("Keyword/query","",0,"页面跳转中......");
		}else{
			$this->error('新增失败');
		}
	}
	public function delete(){
		$id = I("get.id",0);
		$keywords = M("Keyword")->where("id='$id'")->getField("keyword");
		if(M("keyword")->where("id='$id'")->delete()){
			write_log("删除关键字：".$keywords);
			$this->redirect("Keyword/query","",0,"页面跳转中......");
		}else{
			$this->error('删除失败！');
		}
	}
}