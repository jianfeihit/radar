<?php
class WarnAction extends Action {

	public function query(){
		$param = trim(I("get.param"));// 查询词，模糊查询
		$st = trim(I("get.st")); // 查询类型，k表示按照关键词查询，s表示按照网站名称查询,默认按照关键词查询

		import('ORG.Util.Page');
		$kobj = M("keyword_page");

		if($st == "s"){// 按照网站名称查询
			$qmap['siteName'] = array("like","%$param%");
			$count= $kobj->count();
			$Page = new Page($count,10);
			$show = $Page->show();
			$Page->parameter   .=   "param=".urlencode($param).'&';
			$this->kdata = $kobj->where($qmap)->order('checkDate desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}else{// 按照关键词查询
			$qmap['keyword'] = array("like","%$param%");
			$count= $kobj->where($qmap)->count();
			$Page = new Page($count,10);
			$Page->parameter   .=   "param=".urlencode($param).'&';
			$show = $Page->show();
			$this->kdata = $kobj->where($qmap)->order('checkDate desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		$this->assign("param",$param);
		$this->assign('page',$show);// 赋值分页输出
		$this->display('./Tpl/warn/querywarn.html');
	}

	public function view(){
		$kdata = M("keyword_page");
		$id = trim(I("id"));
		$kp = $kdata->where("id=".$id)->find();

		$snapshort = file_get_contents($kp[snapPath]);
		$snapshort = highlight($snapshort,split("\\|", $kp[keyword]));
		$this->assign('snapshort',$snapshort);
		$this->assign('kp',$kp);

		$sitedata = M("site");
		$site = $sitedata->where("id=".$kp[siteId])->find();
		$this->assign('site',$site);

		$this->display('./Tpl/warn/viewwarn.html');
	}

	public function mailnotify(){
		$id = I("post.id",0);
		$data = M("KeywordPage")->where("id=$id")->find();
		if($data){
			$mailto = M("Site")->where("id=".$data["siteId"])->getField("adminEmail");
			$mailbody="";
			$mailbody.="<strong>网站名称：".$data["siteName"]."</strong><br>";
			$mailbody.="<strong>关键词：".$data["keyword"]."</strong><br>";
			$mailbody.="新闻标题：".$data["title"]."<br>";
			$mailbody.="链接：".$data["link"]."<br>";
			$mailbody.="摘要：".$data["digist"]."<br>";
			$mailbody.="<div style='text-align:right;'>@此邮件由系统自动发送，请勿直接回复！</div>";
			if($mailto!=""&&true===($result=think_send_mail($mailto,"","告警信息",$mailbody))){
				$ary["success"] = true;
				$ary["msg"] = $result;
				M("KeywordPage")->where("id=$id")->setField("isHandle",1);
				echo json_encode($ary);
			}else{
				echo json_encode(array("success"=>false,"msg"=>"发送失败".$result));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"数据不存在！"));
		}
	}
	public function getMailstr(){
		$id = I("get.id");
		$ary = explode(",",$id);
		$datas = M("KeywordPage")->where("id in($id)")->select();
		$mailbody="";
		if($datas){
			foreach($datas as $key=>$data){
				$mailbody.="<strong>网站名称：".$data["siteName"]."</strong><br />";
				$mailbody.="<strong>关键词：".$data["keyword"]."</strong><br />";
				$mailbody.="新闻标题：".$data["title"]."<br />";
				$mailbody.="链接：".$data["link"]."<br />";
				$mailbody.="摘要：".$data["digist"]."&nbsp;<br /><hr />&nbsp;<br />";
			}
		}
		return $mailbody;
	}
}