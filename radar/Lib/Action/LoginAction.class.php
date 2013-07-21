<?php
class LoginAction extends Action {
    public function show(){
       $this->display('./Tpl/login.html');
    }

    public function login(){
       $this->display('./Tpl/index.html');
    }
}