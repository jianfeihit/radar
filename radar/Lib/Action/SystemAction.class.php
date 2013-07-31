<?php
class SystemAction extends Action {

	public function failsite(){
		$data = M();
		$ret = $data->query("select s.siteName,t.c,t.siteId from t_site s,(select count(1) c,siteId from t_link GROUP BY siteId having count(1)<5) t where s.id=t.siteId");
		$this->assign('failrecords',$ret);
		$this->display("");
	}
	
	public function viewDetail(){
		$id = I("get.siteId");
		$data = M();
		$ret = $data->query("select link from t_link where siteId=".$id);
		$this->assign('links',$ret);
		$this->display("./Tpl/system/viewfailsite.html");
	}

	public function auditlog(){
		C("TEST",1);
		import('ORG.Util.Page');
		header("Content-type:text/html;charset=utf-8");
		$key= trim(I("get.keyword"));
		$kobj = M("OperateLog");
		if($key==""){
			$count= $kobj->count();
			$Page = new Page($count,10);
			$show = $Page->show();
			$this->kdata = $kobj->order('operateDate desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}else{
			$qmap['operateContent'] = array("like","%$key%");
			$count= $kobj->where($qmap)->count();
			$Page = new Page($count,10);
			$Page->parameter   .=   "keyword=".$key.'&';
			$show = $Page->show();
			$this->kdata = $kobj->where($qmap)->order('operateDate desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		$this->assign('page',$show);
		$this->display();
	}

	public function system(){
		$st = time()+microtime();
		$i=0;
		$j=0;
		while($i<1000000){
			$i++;
			1+1;
		}
		$et = time()+microtime();
		$fst=time()+microtime();
		$i=0;
		while($i<1000000){
			$i++;
			1/$i*$i;
		}
		$fet=time()+microtime();
		$this->timeone = round($et-$st,4);
		$this->timetwo = round($fet-$fst,4);
		$crawlerState = M()->query("SELECT value FROM t_system_monitor where type='crawler' and SYSDATE()-lastupdatetime<30*60");
		if(count($crawlerState)==1){
			$this->assign('crawlerstate',$crawlerState[0][0]);
		}else{
			$this->assign('crawlerstate',0);
		}
		$dbstate = M()->query("SELECT value FROM t_system_monitor where type='db' and SYSDATE()-lastupdatetime<30*60");
		if(count($dbstate)==1){
			$this->assign('dbstate',$dbstate[0][0]);
		}else{
			$this->assign('dbstate',0);
		}
		$this->display();
	}
}