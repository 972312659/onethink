<?php
/**
 * Created by PhpStorm.
 * User: 邓伟
 * Date: 2017/4/27
 * Time: 11:18
 */

namespace Home\Controller;


class FixController extends HomeController
{
    /**
     * 添加报修单
     */
    public function index()
    {
        if(IS_POST){
            $fix = D('Admin/Fix');
            $data = $fix->create();
            if($data){
                $id = $fix->add();
                if($id){
                    $this->success('新增成功', U('index'));
                    //记录行为
                    action_log('update_fix', 'fix', $id, UID);
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($fix->getError());
            }
        } else {
            if(is_login()){//判断是否登录
                $pid = I('get.pid', 0);
                //获取父导航
                if(!empty($pid)){
                    $parent = M('Admin/Fix')->where(array('id'=>$pid))->field('title')->find();
                    $this->assign('parent', $parent);
                }

                $this->assign('pid', $pid);
                $this->assign('info',null);
                $this->meta_title = '新增报修';
                $this->display('edit');
            }else{//未登录则跳转到登录页面
                $this->redirect('User/login');
            }

        }
    }
}