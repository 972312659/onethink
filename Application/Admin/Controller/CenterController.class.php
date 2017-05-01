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
     * 物业管理首页   小区通知
     */
    public function index()
    {
        $id     =   I('get.id',1);
        $type   =   C('CONFIG_GROUP_LIST');
        $list   =   M("Notice")->where(array('status'=>1,'group'=>$id))->field('id,name,title,start,end,overdue')->order('id')->select();
        if($list) {
            $this->assign('list',$list);
        }
        $this->assign('id',$id);
        $this->meta_title = $type[$id].'小区通知';
        $this->display();
    }
    /**
     * 显示活动管理
     */
    public function article()
    {
        $list=M('Userdata')->select();
        foreach($list as &$v){
            $v['type']=M('Document')->find(['id'=>$v['target_id']]);
        }
        $this->assign('list',$list);
        $this->display();
//        var_dump($list);
    }
}