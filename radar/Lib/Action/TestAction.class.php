<?php
class TestAction extends Action {
    public function test(){
        // $Data = M('Data'); // 实例化Data数据模型
        // $this->data = $Data->select();

        // 测试通过host获取IP 
        // $url='www.baidu.com';
        // $arr_url = gethostbyname($url);
        // print_r($arr_url);

        $url = "http://www.ccchaoyang.gov.cn1";
        $host1 = getMainHost($url);
        echo $host1;
        $this->display("./Tpl/Test/test.html");
    }
}