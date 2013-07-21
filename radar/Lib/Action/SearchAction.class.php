<?php
class SearchAction extends Action {

    public function intranet(){
       $this->display('./Tpl/search/queryintranet.html');
    }

    public function internet(){
       $this->display('./Tpl/search/queryinternet.html');
    }
}