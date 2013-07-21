<?php
class AccountAction extends Action {

    public function query(){
       $this->display('./Tpl/account/queryaccount.html');
    }

    public function add(){
       $this->display('./Tpl/account/addaccount.html');
    }
}