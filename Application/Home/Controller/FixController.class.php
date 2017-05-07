<?php
/**
 * Created by PhpStorm.
 * User: 邓伟
 * Date: 2017/4/27
 * Time: 11:18
 */

namespace Home\Controller;


class FixController extends LoginController
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
                $uid=is_login();
                $proprietor=M('Proprietor')->where(['uid'=>$uid])->find();
                $data['address']=$proprietor['room_number'];
                $data['uid']=$uid;
                $id = $fix->data($data)->add();
                if($id){
                    $this->success('新增成功', U('Index/index'),2);
                    //记录行为
                    action_log('update_fix', 'fix', $id, UID);
                } else {
                    $this->error('新增失败', U('index'),2);
                }
            } else {
                $this->error($fix->getError());
            }
        } else {
            if($uid=is_login()){//判断是否登录
                $result=M('Proprietor')->where(['uid'=>$uid])->find();
                //判断是否认证
                if(!$result){
                    $this->redirect('Proprietor/add');exit;
                }
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