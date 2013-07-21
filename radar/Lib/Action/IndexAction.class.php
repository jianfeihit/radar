<?php
class IndexAction extends Action {
    public function index(){
       $this->display('./Tpl/index.html');
    }
}