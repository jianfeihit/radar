<?php
class KeywordAction extends Action {

    public function query(){
	    import('ORG.Util.Page');
	    $kobj = M("keyword");
	    $count= $kobj->count();
	    $Page = new Page($count,25);
	    $show = $Page->show();
	    $this->kdata = $kobj->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
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