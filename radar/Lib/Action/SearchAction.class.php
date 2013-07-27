<?php
class SearchAction extends Action {

    public function intranet(){
       $this->display('./Tpl/search/queryintranet.html');
    }

    public function searchIntranet(){
        $query = I("get.q");
        if(empty($query)){
            $this->error("搜索词不能为空！");
        }
        $seacher = C("SEARCHER");
        $rsp = file_get_contents($req);
        return json_decode($rsp);
    }

    public function internet(){
       $this->display('./Tpl/search/queryinternet.html');
    }
}