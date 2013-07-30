<?php
class SiteAction extends Action {

	public function query(){
		header("Content-Type:text/html;Charset=utf-8");
		$siteName = trim(strip_tags(I("siteName")));
		import('ORG.Util.Page');
		$kobj = M("site");
		if(empty($siteName)){
			$count= $kobj->where("state != 2")->count();
			$Page = new Page($count,5);
			$show = $Page->show();
			$this->kdata = $kobj->where("state != 2")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}else{
			$count= $kobj->where("state != 2 and siteName like '%".$siteName."'")->count();
			$Page = new Page($count,5);
			$Page->parameter   .=   "siteName=".urlencode($siteName).'&';
			$show = $Page->show();
			$this->kdata = $kobj->where("state != 2 and siteName like '%".$siteName."%'")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		$this->assign('page',$show);// 赋值分页输出
		$this->display('./Tpl/site/querysite.html');
	}

	public function edit(){
		$kdata = M("site");
		$id = trim(I("id"));
		$site = $kdata->where("id=".$id)->find();
		$this->assign('s',$site);

		$kobj = M("keyword");
		$keywords = $kobj->select();
		$this->assign('keywords',$keywords);

		$this->display('./Tpl/site/addsite.html');
	}

	public function add(){
		$kobj = M("keyword");
		$keywords = $kobj->select();
		$this->assign('keywords',$keywords);
		$this->display('./Tpl/site/addsite.html');
	}

	public function updatestate(){
		$kdata = M("site");
		$id = trim($_GET["_URL_"][2]);
		$state = trim($_GET["_URL_"][3]);

		echo $id.$state;
		$condition['id'] = $id;
		$data['state'] = $state;
		$result = $kdata->where($condition)->save($data);

		if($result){
			$this->success('修改成功', '__URL__/query');
		}else{
			$this->error('修改失败');
		}
	}

	public function save(){
		$kdata = M("site");
		$siteName = trim(I("siteName"));
		$feedUrl = trim(I("feedUrl"));
		$monitorExp = trim(I("monitorExp"));
		$adminName = trim(I("adminName"));
		$adminTel = trim(I("adminTel"));
		$adminEmail = trim(I("adminEmail"));
		$runMode = trim(I("runMode"));
		$array = $_POST["keywords"];
		$keywords =implode('|',$array);
		// print_r($keywords);
		// validate
		if(empty($siteName)){
			$this->error("网站名称不能为空！");
		}
		if(empty($feedUrl)){
			$this->error("入口地址不能为空！");
		}

		if(empty($monitorExp)){
			$this->error("监控频率不能为空！");
		}
		if($runMode==""){
			$this->error("监控模式不能为空！");
		}
		if(empty($keywords)){
			$this->error("绑定关键词不能为空！");
		}

		if(!startWith($feedUrl,"http://")){
			$feedUrl = "http://".$feedUrl;
		}
		$siteHot = getHost($feedUrl);
		$mainHost = getMainHost($feedUrl);
		$ip = getIP($siteHot);
		$ispinfo = getISPAndCity($ip);
		$netsp = $ispinfo->isp;
		$province = $ispinfo->province;
		$city = $ispinfo->city;
		
		$positons = getPostion($ip);
        $lat = $positons["content"]["point"]["x"];
        $lng = $positons["content"]["point"]["y"];

		$ary=array(
            'siteName'=>$siteName,
            'feedUrl'=>$feedUrl,
            'monitorExp'=>$monitorExp,
            'adminName'=>$adminName,
            'adminTel'=>$adminTel,
            'adminEmail'=>$adminEmail,
            'runMode'=>$runMode,
            'keywords'=>$keywords,
            'ip'=>$ip,
            'netsp'=>$netsp,
            'province'=>$province,
            'city'=>$city,
            'mainHost'=>$mainHost,
            'state'=>"0",
            'lat'=>$lat,
            'lng'=>$lng,
            'lastupdatetime'=>date("Y-m-d H:i:s"),
		);

		$id = trim(I("id"));

		$link=array(
             'linkMD5'=>md5($feedUrl),
             'link'=>$feedUrl,
             'state'=>'0',
		);

		if(!empty($id)){
			$ary['id'] = $id;
			$link['siteId']=$id;
			$kdata->save($ary);
		}else{
			$siteId=$kdata->add($ary);
			$link['siteId']=$siteId;
		}

		M("link")->add($link);
		$this->success('新增成功', '__URL__/query');
	}
	public function getpagenums(){
		$id = I("get.id",0);
		echo M("Link")->where("siteId=$id")->count();
	}
}