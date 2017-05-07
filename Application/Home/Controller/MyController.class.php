<?php
/**
 * Created by PhpStorm.
 * User: 邓伟
 * Date: 2017/5/3
 * Time: 11:59
 */

namespace Home\Controller;


use Think\Log;

class MyController extends LoginController
{
    public function index()
    {
        $uid=is_login();
        $member=M('Ucenter_member');
        $list=$member->field('id,username,score')->where(['id'=>$uid])->find();
        $this->assign($list);
        $this->display();
    }

    public function fix($id=0)
    {
        $fix=M('Fix');
        $map=['uid'=>$id];
        $list=$fix->where($map)->select();
        foreach($list as &$v){
            $v['url']=U('detail',['id'=>$v['id']]);
        }
        $this->assign('list',$list);
        $this->display('service');
    }
    /**
     * 详情
     */
    public function detail($id=0)
    {
        $fix=M('Fix');
        if(IS_AJAX){
            $id=I('post.id');
            $status=I('post.s');
            $map=['id'=>$id];
            switch($status){
                case 0:
                    $fix->where($map)->setInc('status');
                    $this->error();
                    break;
                case 1:
                    $data['assess']=I('post.assess');
                    $fix->where($map)->save($data);
                    $this->success();
                    break;
            }
            exit;
        }
        $list=$fix->where(['id'=>$id])->find();
        $this->assign($list);
        $this->display();
    }

    /**
     * 处理签到
     */
    public function register()
    {
        //得到传值
        $id=I('post.id');
        $score=I('post.score');
        //使用redis
        S(array(
                'type'=>'redis',
                'host'=>'127.0.0.1',
                'port'=>'6379',
                'prefix'=>'onethink',
                'expire'=>60)
        );
        $today=date('Ymd',time());
        $key=MODULE_NAME.CONTROLLER_NAME.$today.$id;
        $redis=S($key);
        if(!$redis){
            $number=rand(0,20);
            $member=M('Ucenter_member');
            $member->where(['id'=>$id])->setInc('score',$number);
            $score=$score+$number;
            S($key,$score,60*60*24);
            //签到日志
//            Log::record('id为'.$id.'于'.$today.'签到','INFO',true);
            $user=M('User_sing_in');
            $data['uid']=$id;
            $data['get_score']=$number;
            $data['sing_intime']=time();
            $user->add($data);
            //返回结果
            $this->success($score);
        }
        S($key,null);
        $this->error('一天只能签到一次');
    }
}