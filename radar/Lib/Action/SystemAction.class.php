<?php
class KeywordAction extends Action {

    public function query(){
       $this->display('./Tpl/keyword/querykeyword.html');
    }

    public function add(){
       $this->display('./Tpl/keyword/addkeyword.html');
    }
}