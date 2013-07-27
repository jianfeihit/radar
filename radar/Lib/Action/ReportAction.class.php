<?php
class ReportAction extends Action {

    public function open(){
       $this->display('./Tpl/report/report.html');
    }
}