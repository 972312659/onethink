<?php
/**
 * Created by PhpStorm.
 * User: 邓伟
 * Date: 2017/4/26
 * Time: 13:58
 * 报修管理
 */

namespace Admin\Controller;



use Think\Page;

class FixController extends AdminController
{
    /**
     * 列表显示
     */
    public function index()
    {
//        C('DB_NAME');
//        $a=C('db_name');
//        var_dump($a);
        //I('变量类型.变量名',['默认值'],['过滤方法'])
//        echo I('get.id').'<br/>';
//        echo I('get.name',0).'<br/>';
//        echo I('server.REQUEST_METHOD').'<br/>';
//        echo I('param.1').'<br/>';
//        echo I('id').'<br/>';
        //D('[项目://][分组/]模型','模型层名称')
//            var_dump(D('Admin/Category'));
//            var_dump(D('AuthRule'));
        //M('[基础模型名:]模型名','数据表前缀','数据库连接信息')
//          var_dump(M('AuthRule'));
//exit;
        $pid=I('get.pid',0);
        //获取频道
        $map  = array( 'pid'=>$pid);
        $model = M('Fix');
        //实例化分页工具
        //每页显示多少条数据
        $pageSize=1;
        $page=new Page($model->count(),$pageSize);
        $fix=$model->where($map)->limit($page->firstRow.','.$page->listRows)->order('id asc')->select();
        int_to_string($fix,array('status'=>array(1=>'已维修',0=>'未维修')));
        $this->assign('fix', $fix);
        $this->assign('pid', $pid);
        $this->assign('page', $page->show());
        $this->meta_title = '报修管理';
        $this->display();

    }
    /**
     * 添加
     */
    public function add(){
        if(IS_POST){
            $fix = D('Fix');
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
            $pid = I('get.pid', 0);
            //获取父导航
            if(!empty($pid)){
                $parent = M('Fix')->where(array('id'=>$pid))->field('title')->find();
                $this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info',null);
            $this->meta_title = '新增报修';
            $this->display('edit');
        }
    }
    /**
     * 修改
     */
    public function edit($id=0)
    {
        if(IS_POST){
            $fix = D('Fix');
            $data = $fix->create();
            if($data){
                if($fix->save()){
                    //记录行为
                    action_log('update_fix', 'fix', $data['id'], UID);
                    $this->success('编辑成功', U('index'));
                } else {
                    $this->error('编辑失败');
                }

            } else {
                $this->error($fix->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('fix')->find($id);
            if(false === $info){
                $this->error('获取报修信息错误');
            }
            $pid = I('get.pid', 0);
            //获取父导航
            if(!empty($pid)){
                $parent = M('fix')->where(array('id'=>$pid))->field('title')->find();
                $this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info', $info);
            $this->meta_title = '编辑导航';
            $this->display();
        }
    }
    /**
     * 删除
     */
    public function del()
    {
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );

        if(M('fix')->where($map)->delete()){
            //记录行为
            action_log('update_fix', 'fix', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
}