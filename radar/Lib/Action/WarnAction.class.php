<?php
class WarnAction extends Action {

    public function query(){
       $this->display('./Tpl/warn/querywarn.html');
    }

}