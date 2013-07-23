<?php
class WarnAction extends Action {

    public function query(){
        $param = trim(I("param"));// 查询词，模糊查询
        $st = trim(I("st")); // 查询类型，k表示按照关键词查询，s表示按照网站名称查询,默认按照关键词查询

        import('ORG.Util.Page');
        $kobj = M("keyword_page");
        
        if($st == "s"){// 按照网站名称查询
            $qmap['siteName'] = array("like","%$param%");
            $count= $kobj->count();
            $Page = new Page($count,5);
            $show = $Page->show();
            $Page->parameter   .=   "siteName=".urlencode($param).'&';
            $this->kdata = $kobj->order('checkDate desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        }else{// 按照关键词查询
            $qmap['keyword'] = array("like","%$param%");
            $count= $kobj->where($qmap)->count();
            $Page = new Page($count,5);
            $Page->parameter   .=   "keyword=".urlencode($param).'&';
            $show = $Page->show();
            $this->kdata = $kobj->where($qmap)->order('checkDate desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        }
        $this->assign('page',$show);// 赋值分页输出
        $this->display('./Tpl/warn/querywarn.html');
    }

    public function view(){
        $kdata = M("keyword_page");
        $id = trim(I("id"));
        $kp = $kdata->where("id=".$id)->find();
        
        $snapshort = file_get_contents($kp[snapPath]);
        $snapshort = highlight($snapshort,split("\\|", $kp[keyword]));
        $this->assign('snapshort',$snapshort);
        $this->assign('kp',$kp);

        $sitedata = M("site");
        $site = $sitedata->where("id=".$kp[siteId])->find();
        $this->assign('site',$site);

        $this->display('./Tpl/warn/viewwarn.html');
    }

}