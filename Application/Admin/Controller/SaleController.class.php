<?php
/**
 * Created by PhpStorm.
 * User: 邓伟
 * Date: 2017/5/2
 * Time: 19:22
 */

namespace Admin\Controller;


use Admin\Model\PictureModel;

class SaleController extends AdminController
{
    /**
     * 租售管理
     */
    public function index()
    {
        $list=M('saleAndRental')->select();
        foreach($list as &$v){
            $v['img']=get_cover($v['cover_id'],'path');
            $v['status']=($v['status']==1?'出租':'出售');
            $v['create_time']=date('Y-m-d h:i:s',$v['create_time']);
        }
        $this->assign('list',$list);
        $this->display();
    }

    /**
     * 添加
     */
    public function add(){
        if(IS_POST){
            $sale = D('saleAndRental');
            $data = $sale->create();
            if($data){
                $id = $sale->add();
                if($id){
                    $this->success('新增成功', U('index'));
                    //记录行为
                    action_log('update_saleAndRental', 'saleAndRental', $id, UID);
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($sale->getError());
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
            $this->meta_title = '新增租售';
            $this->display('edit');
        }
    }

    /**
     * 修改
     */
    public function edit($id=0)
    {
        if(IS_POST){
            $sale = D('saleAndRental');
            $data = $sale->create();
            if($data){
                if($sale->save()){
                    //记录行为
                    action_log('update_saleAndRental', 'saleAndRental', $data['id'], UID);
                    $this->success('编辑成功', U('index'));
                } else {
                    $this->error('编辑失败');
                }

            } else {
                $this->error($sale->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('saleAndRental')->find($id);
            if(false === $info){
                $this->error('获取报修信息错误');
            }
            $pid = I('get.pid', 0);
            //获取父导航
            if(!empty($pid)){
                $parent = M('saleAndRental')->where(array('id'=>$pid))->field('title')->find();
                $this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info', $info);
            $this->meta_title = '编辑导航';
            $this->display();
        }
    }
    /**
     * 更改发布状态
     */
    public function setStatus($Model = 'saleAndRental')
    {
        $ids    =   I('request.ids');
        $status =   I('request.status');
        if(empty($ids)){
            $this->error('请选择要操作的数据');
        }

        $map['id'] = array('in',$ids);
        switch ($status){
            case -1 :
                $this->delete($Model, $map, array('success'=>'删除成功','error'=>'删除失败'));
                break;
            case 0  :
                $this->forbid($Model, $map, array('success'=>'禁用成功','error'=>'禁用失败'));
                break;
            case 1  :
                $this->resume($Model, $map, array('success'=>'启用成功','error'=>'启用失败'));
                break;
            default :
                $this->error('参数错误');
                break;
        }
    }

    /**
     * 启用
     * @param string $model
     * @param array $where
     * @param array $msg
     */
    protected function forbid ( $model , $where = array() , $msg = array( 'success'=>'状态禁用成功！', 'error'=>'状态禁用失败！')){
        $data    =  array('publish' => 0);
        $this->editRow( $model , $data, $where, $msg);
    }

    /**
     * 禁用
     * @param string $model
     * @param array $where
     * @param array $msg
     */
    protected function resume (  $model , $where = array() , $msg = array( 'success'=>'状态恢复成功！', 'error'=>'状态恢复失败！')){
        $data    =  array('publish' => 1);
        $this->editRow(   $model , $data, $where, $msg);
    }


}