<?php
class TestAction extends Action {
    public function test(){
        // $Data = M('Data'); // 实例化Data数据模型
        // $this->data = $Data->select();
        $this->display();
    }
}