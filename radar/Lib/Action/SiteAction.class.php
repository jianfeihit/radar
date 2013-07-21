<?php
class SiteAction extends Action {

    public function query(){
       $this->display('./Tpl/site/querysite.html');
    }

    public function add(){
       $this->display('./Tpl/site/addsite.html');
    }
}