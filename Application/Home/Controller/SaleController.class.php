<?php
/**
 * Created by PhpStorm.
 * User: 邓伟
 * Date: 2017/5/3
 * Time: 10:20
 */

namespace Home\Controller;


class SaleController extends HomeController
{
    public function index()
    {
        $sale=M('saleAndRental');
        $map=array('publish'=>1);//已发布
        $data=$sale->where($map)->select();
        $list=[];
        $info=[];
        foreach($data as &$v) {
            $v['create_time'] = date('Y-m-d h:i:s', $v['create_time']);
            $v['img'] = get_cover($v['cover_id'], 'path');
            if($v['status']==1){
                $list[]=$v;
            }else{
                $info[]=$v;
            }
        }
        $this->assign('list',$list);
        $this->assign('info',$info);
        $this->display();
    }
    /**
     * 详情
     */
    public function saleDetail($id)
    {
        $sale=M('saleAndRental');
        $list=$sale->where(['id'=>$id])->find();
        $list['create_time'] = date('Y-m-d h:i:s', $list['create_time']);
        $list['img'] = get_cover($list['cover_id'], 'path');
        $this->assign('list',$list);
        $this->display('detail');
    }
}