<?php
class SystemAction extends Action {

	public function failsite(){
		$this->display();
	}

	public function auditlog(){
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
		$this->display();
	}
}