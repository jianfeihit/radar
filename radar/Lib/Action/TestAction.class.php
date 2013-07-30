<?php
class TestAction extends Action {
    public function test(){
        // $Data = M('Data'); // 实例化Data数据模型
        // $this->data = $Data->select();

        // 测试通过host获取IP 
        // $url='www.baidu.com';
        // $arr_url = gethostbyname($url);
        // print_r($arr_url);

        // $url = "http://www.ccchaoyang.gov.cn1";
        // $host1 = getMainHost($url);
        // echo $host1;

//        $data = M();
//        $keywords = $data->query("SELECT keyword,count(1) FROM `t_keyword_page` GROUP BY keyword order by count(1) desc limit 0,4");
//        $result = array(); 
//        
//        foreach ($keywords as $key => $value) {
//            $sql = "SELECT id,title,digist,DATE_FORMAT(checkdate,'%m') month,DATE_FORMAT(checkdate,'%d') day FROM t_keyword_page where keyword='".$value[keyword]."' order by checkDate desc limit 0,4";
//            $kp = $data->query($sql);
//            $result[$value[keyword]]=$kp;
//            // echo $value[keyword];
//            // echo "<br>";
//            // print_r($kp);
//            // echo "<br>";
//        }
//
//        // foreach ($result as $key => $value){
//        //     echo $key;
//        // }
//        $this->assign('kp',$result);
        // $this->assign('kp1',$result);
        $ret = file_get_contents("http://localhost:8080/searcher/search/detailSearch?query=%E5%90%89%E6%9E%97");
//        print_r(json_decode($ret)->result);
       /* foreach (json_decode($ret)->result as $key => $value) {
        	print_r($value->title);
        	echo "<br/>";
        }*/
        $arr_url = getPostion('218.62.14.228');
        print_r($arr_url);
        $this->display("./Tpl/Test/test.html");
    }
}