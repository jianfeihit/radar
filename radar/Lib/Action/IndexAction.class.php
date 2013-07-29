<?php
class IndexAction extends Action {
    public function index(){
        $data = M();
        $keywords = $data->query("SELECT keyword,count(1) FROM `t_keyword_page` GROUP BY keyword order by count(1) desc limit 0,4");
        $result = array(); 
        
        foreach ($keywords as $key => $value) {
            $sql = "SELECT id,title,digist,DATE_FORMAT(checkdate,'%m') month,DATE_FORMAT(checkdate,'%d') day FROM t_keyword_page where keyword='".$value[keyword]."' order by checkDate desc limit 0,4";
            $kp = $data->query($sql);
            $result[$value[keyword]]=$kp;
        }
        $this->assign('kp',$result);
        Vendor ("flashchart.Includes.FusionCharts");
        $chart_sitepage =renderChartHTML ( "../Public/Charts/MSLine.swf",
		urlencode (U("Report/countsitepage")), "", "blockday", "350", 164, false );
		$chart_daypage =renderChartHTML ( "../Public/Charts/MSArea.swf",
		urlencode (U("Report/countdaypage")), "", "blockday", "350", 164, false );
		$chart_keypage =renderChartHTML ( "../Public/Charts/MSArea.swf",
		urlencode (U("Report/countkeypage")), "", "blockday", "350", 164, false );
		$this->assign("chart_sitepage",$chart_sitepage);
		$this->assign("chart_daypage",$chart_daypage);
		$this->assign("chart_keypage",$chart_keypage);
        // $this->display("./Tpl/Test/test.html");
        $this->display('./Tpl/index1.html');
    }

    public function getCheckData(){
        $siteDAO = M("site");
        $siteCount = $siteDAO->count();

        $linkDAO = M("link");
        $checktimes = $linkDAO->sum("checktimes");

        $keywordPageDAO = M("keyword_page");
        $keywordPageCount = $keywordPageDAO->count();
        
        $ary=array(
            'siteCount'=>$siteCount,
            'keywordPageCount'=>$keywordPageCount,
            'checktimes'=>$checktimes,
        );
        $this->ajaxReturn($ary,'success',1);
    }
}