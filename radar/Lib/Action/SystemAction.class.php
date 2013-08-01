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
		Vendor ("flashchart.Includes.FusionChartsHtmlFive");
		$chart_cpu=renderChart ( "../Public/Charts/RealTimeLine.swf?ChartNoDataText=%E6%B2%A1%E6%9C%89%E5%8F%AF%E6%98%BE%E7%A4%BA%E7%9A%84%E6%95%B0%E6%8D%AE&PBarLoadingText=%E6%AD%A3%E5%9C%A8%E8%BD%BD%E5%85%A5%E5%9B%BE%E8%A1%A8%EF%BC%8C%E8%AF%B7%E7%A8%8D%E5%80%99&XMLLoadingText=%E6%AD%A3%E5%9C%A8%E8%8E%B7%E5%8F%96%E6%95%B0%E6%8D%AE%EF%BC%8C%E8%AF%B7%E7%A8%8D%E5%80%99",
		urlencode ("__URL__/getUseage"), "", "chart_cpu", "100%", 268, false );
		$this->assign("chart_cpu",$chart_cpu);
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
	public function getCpuUseage(){
		$cpu=array();
		$mem = array();
		exec('wmic cpu get LoadPercentage',$cpu);
		$fmem=0;
		$tmem=0;
		exec('wmic os get FreePhysicalMemory',$fmem);
		exec('wmic os get TotalVisibleMemorySize',$tmem);
		$mempercentage =intval( ($fmem[1]/$tmem[1])*100);
		$mempercentages =100- ($mempercentage);
		echo "&label=22:24:2&value=".($cpu[1]?$cpu[1]:0)."|".$mempercentages;
	}
	public function getUseage(){
		echo "<chart showFCMenuItem='0' manageResize='1' bgColor='000000' bgAlpha='100' canvasBorderThickness='1' canvasBorderColor='008040' canvasBgColor='000000' yAxisMaxValue='100'  decimals='0' numdivlines='9' numVDivLines='28' numDisplaySets='30' divLineColor='008040' vDivLineColor='008040' divLineAlpha='100' chartLeftMargin='10' baseFontColor='00dd00' showRealTimeValue='1' dataStreamURL='getCpuUseage' refreshInterval='2' numberSuffix='%' labelDisplay='rotate' slantLabels='1' toolTipBgColor='000000' toolTipBorderColor='008040' baseFontSize='11' showAlternateHGridColor='0' legendBgColor='000000' legendBorderColor='008040' legendPadding='35' showLabels='0'>
<categories>
<category label='Start'/>
</categories>
<dataset color='00dd00' seriesName='CPU' showValues='0' alpha='100' anchorAlpha='0' lineThickness='3'>
<set value='0' />
</dataset>
<dataset color='ff5904' seriesName='Memory' showValues='0' alpha='100' anchorAlpha='0' lineThickness='3'>
<set value='0' />
</dataset>
</chart>";
	}
}