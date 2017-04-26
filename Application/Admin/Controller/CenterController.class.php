<?php
/**
 * Created by PhpStorm.
 * User: 邓伟
 * Date: 2017/4/26
 * Time: 14:15
 */

namespace Admin\Controller;




class CenterController extends AdminController
{
    /**
     * 物业管理首页
     */
    public function index()
    {
        $id     =   I('get.id',1);
        $type   =   C('CONFIG_GROUP_LIST');
        $list   =   M("Config")->where(array('status'=>1,'group'=>$id))->field('id,name,title,extra,value,remark,type')->order('sort')->select();
        if($list) {
            $this->assign('list',$list);
        }
        $this->assign('id',$id);
        $this->meta_title = $type[$id].'设置';
        $this->display();
    }
}