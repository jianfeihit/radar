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
        $resp = file_get_contents($seacher."/search/detailSearch?query=".$query);
        $ret = json_decode($resp, true);
        $this->assign('hits',$ret["result"]);
        $this->assign('q',$query);
        $this->display('./Tpl/search/queryintranet.html');
    }
    
    public function viewSnap(){
    	$linkId = I("get.id");
    	$q = I("get.q");
        if(empty($linkId)){
            $this->error("搜索词不能为空！");
        }
        $link=M("link")->where("id=966")->find();
        $snapshort = file_get_contents($link[snapPath]);
		$snapshort = highlight($snapshort,split("\\|", $q));
		$this->assign('snapshort',$snapshort);
		$this->display('./Tpl/search/viewsnapshort.html');
    }

    public function internet(){
       $this->display('./Tpl/search/queryinternet.html');
    }
    
    public function searchInternet(){
        $query = $_POST["q"];
        $array = $_POST["sis"];
		$sis =implode('|',$array);
        if(empty($query)){
            $this->error("搜索词不能为空！");
        }
        if(empty($sis)){
            $this->error("搜索范围不能为空！");
        }
        $seacher = C("SEARCHER");
        $resp = file_get_contents($seacher."/search/searchInternet?q=".$query."&sis=".$sis);
        $ret = json_decode($resp, true);
        $this->assign('hits',$ret);
        $this->assign('q',$query);
        $this->assign('sis',$sis);
        $this->display('./Tpl/search/queryinternet.html');
    }
}