<?php
class ReportAction extends Action {

	public function open(){
		Vendor ("flashchart.Includes.FusionCharts");
		$chart_stauts=renderChartHTML ( "../Public/Charts/Pie2D.swf",
		urlencode ("__URL__/taskstatus"), "", "blockday", "553", 268, false );
		$chart_mode=renderChartHTML ( "../Public/Charts/Pie2D.swf",
		urlencode ("__URL__/taskmode"), "", "blockday", "553", 268, false );
		$chart_daypage =renderChartHTML ( "../Public/Charts/MSArea.swf",
		urlencode ("__URL__/countdaypage"), "", "blockday", "350", 164, false );
		$chart_hourpage =renderChartHTML ( "../Public/Charts/MSLine.swf",
		urlencode ("__URL__/counthourpage"), "", "blockday", "350", 164, false );
		$chart_sitepage =renderChartHTML ( "../Public/Charts/MSLine.swf",
		urlencode ("__URL__/countsitepage"), "", "blockday", "350", 164, false );
		$this->assign("chart_status",$chart_stauts);
		$this->assign("chart_mode",$chart_mode);
		$this->assign("chart_daypage",$chart_daypage);
		$this->assign("chart_hourpage",$chart_hourpage);
		$this->assign("chart_sitepage",$chart_sitepage);
		$this->display('./Tpl/report/report.html');
	}
	public function keypage(){
		Vendor ("flashchart.Includes.FusionCharts");
		$chart_keywordpage =renderChartHTML ( "../Public/Charts/Column2D.swf",
		urlencode ("__URL__/countkeywordpage"), "", "blockday", "1150", 300, false );
		$chart_keywordcategory =renderChartHTML ( "../Public/Charts/MSColumn2D.swf",
		urlencode ("__URL__/countkeywordcategory"), "", "blockday", "1150", 300, false );
		$this->data = M("Site")->select();
		$this->assign("chart_keywordpage",$chart_keywordpage);
		$this->assign("chart_keywordcategory",$chart_keywordcategory);
		$this->display();
	}
	public function taskstatus(){
		$data = M("Site")->field("count(id) as num,state")->group("state")->select();
		$strxml='<chart showFCMenuItem="0" showValues="1" showLegend="1" legendPosition="bottom"  numberScaleUnit="'.iconv("utf-8","gb2312"," , ,万,千万")
		.'" formatNumberScale="1" numberScaleValue="10000,1,1,1000"
		palette="4" bgColor="666666" showPercentageValues="0" 
		use3DLighting="0" chartsshowShadow="0" showShadow="0"  bgAlpha="0" baseFontSize="12" baseFontColor="000000" bgAngle="0" showBorder="0" pieRadius="80" toolTipSepChar=":  "  >';
		foreach($data as $k=>$v){
			if($v['state']==0){
				$strxml.="<set label='".iconv("utf-8","gb2312","运行")."' value='".$v["num"]."' isSliced='1' color='429EAD' /> ";		
			}
			if($v['state']==1){
				$strxml.="<set label='".iconv("utf-8","gb2312","暂停")."' value='".$v["num"]."' isSliced='0' /> ";		
			}
			if($v['state']==2){
				$strxml.="<set label='".iconv("utf-8","gb2312","删除")."' value='".$v["num"]."' isSliced='0' /> ";		
			}
		}
		$strxml.="<styles><definition><style name='CaptionFont' type='font' bold='0' size='12' /></definition><application><apply toObject='subcaption' styles='CaptionFont' /></application></styles></chart>";
		echo $strxml;
	}
	public function taskmode(){
		$data = M("Site")->field("count(id) as num,runMode")->group("runMode")->select();
		$strxml='<chart showFCMenuItem="0" showValues="1" showLegend="1" legendPosition="bottom"  numberScaleUnit="'.iconv("utf-8","gb2312"," , ,万,千万")
		.'" formatNumberScale="1" numberScaleValue="10000,1,1,1000"
		palette="4" bgColor="666666" showPercentageValues="0" 
		use3DLighting="0" chartsshowShadow="0" showShadow="0"  bgAlpha="0" baseFontSize="12" baseFontColor="000000" bgAngle="0" showBorder="0" pieRadius="80" toolTipSepChar=":  "  >';
		foreach($data as $k=>$v){
			if($v['runMode']==0){
				$strxml.="<set label='".iconv("utf-8","gb2312","主动")."' value='".$v["num"]."' isSliced='1' color='429EAD' /> ";		
			}
			if($v['runMode']==1){
				$strxml.="<set label='".iconv("utf-8","gb2312","被动")."' value='".$v["num"]."' isSliced='0' /> ";		
			}
			if($v['runMode']==2){
				$strxml.="<set label='".iconv("utf-8","gb2312","两种")."' value='".$v["num"]."' isSliced='0' /> ";		
			}
		}
		$strxml.="</chart>";
		echo $strxml;
	}
	public function countdaypage(){
		$data =M("Link")->field("count(id) as num,DATE_FORMAT(lastCrawDate,'%Y-%m-%d') date")->where("lastCrawDate >'".date("Y-m-d",strtotime("-10 day"))."'")->group("date")->select();
		$strxml="<chart showFCMenuItem='0' lineThickness='2' showValues='0' anchorRadius='4' divLineAlpha='20' divLineColor='CC3300' divLineIsDashed='1' showAlternateHGridColor='1' alternateHGridAlpha='5' alternateHGridColor='CC3300' shadowAlpha='40' labelStep='2' numvdivlines='".(sizeof($data)-2)."' showAlternateVGridColor='1' chartsshowShadow='1' chartRightMargin='20' chartTopMargin='15' chartLeftMargin='0' chartBottomMargin='3' bgColor='FFFFFF' canvasBorderThickness='1' showBorder='0' legendBorderAlpha='0' bgAngle='360' showlegend='0' borderColor='DEF3F3' toolTipBorderColor='cccc99' canvasPadding='0' toolTipBgColor='ffffcc' legendShadow='0' baseFontSize='12' canvasBorderAlpha='20' outCnvbaseFontSize='10' outCnvbaseFontColor='000000' numberScaleValue='10000,1,1,1000' formatNumberScale='1' palette='2'  lineColor='AFD8F8'>";
		$strxml.="<categories>";
		$strcategory="";
		$strdataset="";
		foreach($data as $k=>$v){
			$strcategory.="<category label='".$v["date"]."' />";
			$strdataset.="<set value='".$v["num"]."' />";
		}
		$strxml .=$strcategory."</categories><dataset seriesName='".iconv("utf-8","gb2312","日增量")."' 
		color='FF9933' plotBorderColor='FF9900'>";
		$strxml.=$strdataset;
		$strxml.='</dataset><styles><definition><style name="CaptionFont" type="font" size="12"  /><style name="myLegendFont" type="font" size="11"  /></definition><application><apply toObject="CAPTION" styles="CaptionFont"  /><apply toObject="SUBCAPTION" styles="CaptionFont"  /><apply toObject="Legend" styles="myLegendFont"  /></application></styles></chart>';
		echo $strxml;
	}

	public function counthourpage(){
		$data =M("Link")->field("count(1) as num,DATE_FORMAT(lastCrawDate,'%H') hour")->group("hour")->select();
		$strxml="<chart showFCMenuItem='0' lineThickness='2' showValues='0' anchorRadius='4' divLineAlpha='20' divLineColor='CC3300' divLineIsDashed='1' showAlternateHGridColor='1' alternateHGridAlpha='5' alternateHGridColor='CC3300' shadowAlpha='40' labelStep='1' numvdivlines='".(sizeof($data)-2)."' showAlternateVGridColor='1' chartsshowShadow='1' chartRightMargin='20' chartTopMargin='15' chartLeftMargin='0' chartBottomMargin='3' bgColor='FFFFFF' canvasBorderThickness='1' showBorder='0' legendBorderAlpha='0' bgAngle='360' showlegend='0' borderColor='DEF3F3' toolTipBorderColor='cccc99' canvasPadding='0' toolTipBgColor='ffffcc' legendShadow='0' baseFontSize='12' canvasBorderAlpha='20' outCnvbaseFontSize='10' outCnvbaseFontColor='000000' numberScaleValue='10000,1,1,1000' formatNumberScale='1' palette='2'  lineColor='AFD8F8'>";
		$strxml.="<categories>";
		$strcategory="";
		$strdataset="";
		foreach($data as $k=>$v){
			$strcategory.="<category label='".$v["hour"]."' />";
			$strdataset.="<set value='".$v["num"]."' />";
		}
		$strxml .=$strcategory."</categories><dataset seriesName='".iconv("utf-8","gb2312","小时增量")."' 
		color='0033CC' anchorBorderColor='0033CC' color='FF9933' plotBorderColor='FF9900'>";
		$strxml.=$strdataset;
		$strxml.='</dataset><styles><definition><style name="CaptionFont" type="font" size="12"  /><style name="myLegendFont" type="font" size="11"  /></definition><application><apply toObject="CAPTION" styles="CaptionFont"  /><apply toObject="SUBCAPTION" styles="CaptionFont"  /><apply toObject="Legend" styles="myLegendFont"  /></application></styles></chart>';
		echo $strxml;
	}
	public function countsitepage(){
		$data =M("Site")->field("count(1) as num,DATE_FORMAT(lastupdatetime,'%Y-%m-%d') day")->group("day")->select();
		$strxml="<chart showFCMenuItem='0' lineThickness='2' showValues='0' anchorRadius='4' divLineAlpha='20' divLineColor='CC3300' divLineIsDashed='1' showAlternateHGridColor='1' alternateHGridAlpha='5' alternateHGridColor='CC3300' shadowAlpha='40' labelStep='1' numvdivlines='".(sizeof($data)-2)."' showAlternateVGridColor='1' chartsshowShadow='1' chartRightMargin='20' chartTopMargin='15' chartLeftMargin='0' chartBottomMargin='3' bgColor='FFFFFF' canvasBorderThickness='1' showBorder='0' legendBorderAlpha='0' bgAngle='360' showlegend='0' borderColor='DEF3F3' toolTipBorderColor='cccc99' canvasPadding='0' toolTipBgColor='ffffcc' legendShadow='0' baseFontSize='12' canvasBorderAlpha='20' outCnvbaseFontSize='10' outCnvbaseFontColor='000000' numberScaleValue='10000,1,1,1000' formatNumberScale='1' palette='2'  lineColor='AFD8F8'>";
		$strxml.="<categories>";
		$strcategory="";
		$strdataset="";
		foreach($data as $k=>$v){
			$strcategory.="<category label='".$v["day"]."' />";
			$strdataset.="<set value='".$v["num"]."' />";
		}
		$strxml .=$strcategory."</categories><dataset seriesName='".iconv("utf-8","gb2312","站点增量")."' 
		color='FF0000' anchorBorderColor='0033CC' color='FF9933' plotBorderColor='FF9900'>";
		$strxml.=$strdataset;
		$strxml.='</dataset><styles><definition><style name="CaptionFont" type="font" size="12"  /><style name="myLegendFont" type="font" size="11"  /></definition><application><apply toObject="CAPTION" styles="CaptionFont"  /><apply toObject="SUBCAPTION" styles="CaptionFont"  /><apply toObject="Legend" styles="myLegendFont"  /></application></styles></chart>';
		echo $strxml;
	}
	public function countkeypage(){
		$data =M("KeywordPage")->field("count(1) as num,DATE_FORMAT(checkDate,'%Y-%m-%d') day")->where("checkDate >'".date("Y-m-d",strtotime("-7 day"))."'")->group("day")->select();
		$strxml="<chart showFCMenuItem='0' lineThickness='2' showValues='0' anchorRadius='4' divLineAlpha='20' divLineColor='CC3300' divLineIsDashed='1' showAlternateHGridColor='1' alternateHGridAlpha='5' alternateHGridColor='CC3300' shadowAlpha='40' labelStep='1' numvdivlines='".(sizeof($data)-2)."' showAlternateVGridColor='1' chartsshowShadow='1' chartRightMargin='20' chartTopMargin='15' chartLeftMargin='0' chartBottomMargin='3' bgColor='FFFFFF' canvasBorderThickness='1' showBorder='0' legendBorderAlpha='0' bgAngle='360' showlegend='0' borderColor='DEF3F3' toolTipBorderColor='cccc99' canvasPadding='0' toolTipBgColor='ffffcc' legendShadow='0' baseFontSize='12' canvasBorderAlpha='20' outCnvbaseFontSize='10' outCnvbaseFontColor='000000' numberScaleValue='10000,1,1,1000' formatNumberScale='1' palette='2'  lineColor='AFD8F8'>";
		$strxml.="<categories>";
		$strcategory="";
		$strdataset="";
		foreach($data as $k=>$v){
			$strcategory.="<category label='".$v["day"]."' />";
			$strdataset.="<set value='".$v["num"]."' />";
		}
		$strxml .=$strcategory."</categories><dataset seriesName='".iconv("utf-8","gb2312","站点增量")."' 
		color='FF0000' anchorBorderColor='0033CC' color='FF9933' plotBorderColor='FF9900'>";
		$strxml.=$strdataset;
		$strxml.='</dataset><styles><definition><style name="CaptionFont" type="font" size="12"  /><style name="myLegendFont" type="font" size="11"  /></definition><application><apply toObject="CAPTION" styles="CaptionFont"  /><apply toObject="SUBCAPTION" styles="CaptionFont"  /><apply toObject="Legend" styles="myLegendFont"  /></application></styles></chart>';
		echo $strxml;
	}

	public function countkeywordpage(){
		$data =M("KeywordPage")->field("count(1) as num,keyword")->group("keyword")->order("num desc")->select();
		$strxml="<chart showFCMenuItem='0' lineThickness='2' showValues='1' anchorRadius='4' divLineAlpha='20' divLineColor='CC3300' divLineIsDashed='1' showAlternateHGridColor='1' alternateHGridAlpha='5' alternateHGridColor='CC3300' shadowAlpha='40' labelStep='1' numvdivlines='".(sizeof($data)-2)."' showAlternateVGridColor='1' chartsshowShadow='1' chartRightMargin='20' chartTopMargin='15' chartLeftMargin='0' chartBottomMargin='3' bgColor='FFFFFF' canvasBorderThickness='1' showBorder='0' legendBorderAlpha='0' bgAngle='360' showlegend='1' legendPostion='bottom' borderColor='DEF3F3' toolTipBorderColor='cccc99' canvasPadding='0' toolTipBgColor='ffffcc' legendShadow='0' baseFontSize='12' canvasBorderAlpha='20' outCnvbaseFontSize='10' outCnvbaseFontColor='000000' numberScaleValue='10000,1,1,1000' formatNumberScale='1' palette='2'  lineColor='AFD8F8'>";
		$strdataset="";
		foreach($data as $k=>$v){
			$strdataset.="<set value='".$v["num"]."' label='".iconv("utf-8","gb2312",$v["keyword"])."'  />";
		}
		$strxml.=$strdataset;
		$strxml.='<styles><definition><style name="CaptionFont" type="font" size="12"  /><style name="myLegendFont" type="font" size="12"  /></definition><application><apply toObject="CAPTION" styles="CaptionFont"  /><apply toObject="SUBCAPTION" styles="CaptionFont"  /><apply toObject="Legend" styles="myLegendFont"  /></application></styles></chart>';
		echo $strxml;
	}

	public function countkeywordcategory(){
		$data =M("KeywordPage")->field("count(1) as num,keyword,siteId")->group("siteId,keyword")->select();
		$cate = M("KeywordPage")->field("siteId,sitename")->group("siteId")->select();
		$keyw = M("KeywordPage")->field("keyword")->group("keyword")->select();
		$strxml="<chart showFCMenuItem='0' lineThickness='2' showValues='1' anchorRadius='4' divLineAlpha='20' divLineColor='CC3300' divLineIsDashed='1' showAlternateHGridColor='1' alternateHGridAlpha='5' alternateHGridColor='CC3300' shadowAlpha='40' labelStep='1' numvdivlines='".(sizeof($data)-2)."' showAlternateVGridColor='1' chartsshowShadow='1' chartRightMargin='20' chartTopMargin='15' chartLeftMargin='0' chartBottomMargin='3' bgColor='FFFFFF' canvasBorderThickness='1' showBorder='0' legendBorderAlpha='0' bgAngle='360' showlegend='1' legendPostion='bottom' borderColor='DEF3F3' toolTipBorderColor='cccc99' canvasPadding='0' toolTipBgColor='ffffcc' legendShadow='0' baseFontSize='12' canvasBorderAlpha='20' outCnvbaseFontSize='10' outCnvbaseFontColor='000000' numberScaleValue='10000,1,1,1000' formatNumberScale='1' palette='2' lineColor='AFD8F8'>";
		$strcategory="<categories>";
		$strdataset="";
		$ary = array();
		foreach($cate as $k=>$v){
			$strcategory.="<category label='".iconv("utf-8","gb2312",$v["sitename"])."' />";
			$ary[]=$v["siteId"];
		}
		$strxml.=$strcategory."</categories>";
		foreach($keyw as $k=>$v){
			$strdataset.="<dataset seriesName='".iconv("utf-8","gb2312",$v["keyword"])."' showValues='1'>";
			foreach($cate as $kk=>$vv){
				$count = M("KeywordPage")->where("siteId=".$vv["siteId"]." and keyword='".$v["keyword"]."'")->count();
				if($count){
					$strdataset.="<set value='".$count."' />";
				}else{
					$strdataset.="<set />";
				}
			}
			$strdataset.="</dataset>";
		}
		$strxml.=$strdataset;
		$strxml.='<styles><definition><style name="CaptionFont" type="font" size="12"  /><style name="myLegendFont" type="font" size="12"  /></definition><application><apply toObject="CAPTION" styles="CaptionFont"  /><apply toObject="SUBCAPTION" styles="CaptionFont"  /><apply toObject="Legend" styles="myLegendFont"  /><apply toObject="categories" styles="myLegendFont" /></application></styles></chart>';
		echo $strxml;
	}
	public function getpagenums(){
		$id = I("get.id",0);
		echo M("KeywordPage")->where("siteId=$id")->count();
	}
}