<?php
class KeywordAction extends Action {

	public function query(){
		header("Content-Type:text/html;Charset=utf-8");
		$keyword = trim(strip_tags(I("keyword")));
		import('ORG.Util.Page');
		$kobj = M("keyword");
		if(empty($keyword)){
			$count= $kobj->count();
			$Page = new Page($count,1);
			$show = $Page->show();
			$this->kdata = $kobj->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}else{
			$qmap['keyword'] = array("like","%$keyword%");
			$count= $kobj->where($qmap)->count();
			$Page = new Page($count,1);
			$Page->parameter   .=   "keyword=".urlencode($keyword).'&';
			$show = $Page->show();
			$this->kdata = $kobj->where($qmap)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		$this->assign('page',$show);// 赋值分页输出
		$this->display('./Tpl/keyword/querykeyword.html');
	}

	public function add(){
		$this->display('./Tpl/keyword/addkeyword.html');
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
			$this->success('新增成功', '__URL__/query');
		}else{
			$this->error('新增失败');
		}
	}
}