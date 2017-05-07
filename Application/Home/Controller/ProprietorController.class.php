<?php
/**
 * Created by PhpStorm.
 * User: 邓伟
 * Date: 2017/5/2
 * Time: 17:11
 */

namespace Home\Controller;


class ProprietorController extends LoginController
{
    /**
     * 业主认证
     */
    public function add()
    {
        //获得uid
        $uid=is_login();
        $pro=M('Proprietor');
        $user=$pro->where(['uid'=>$uid])->select();
        if($user){
            echo '已认证';exit;
        }
        //接收post传值并保存
        if(IS_POST){
            $data=$pro->create();
            $data['uid']=$uid;
            if($data){
                $id = $pro->data($data)->add();
                if($id){
                    $this->success('新增成功', U('Home/Index/index'));
                    //记录行为
                    action_log('update_proprietor', 'proprietor', $id, UID);
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($pro->getError());
            }
        }
        $this->display();
    }
}