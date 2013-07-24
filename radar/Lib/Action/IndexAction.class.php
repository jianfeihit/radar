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