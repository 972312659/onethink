<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use OT\DataDictionary;
use Think\Page;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController
{

    //系统首页
    public function index()
    {

        $category = D('Category')->getTree();
        $lists = D('Document')->lists(null);

        $this->assign('category', $category);//栏目
        $this->assign('lists', $lists);//列表
        $this->assign('page', D('Document')->page);//分页


        $this->display();
    }

    /**
     * 显示小区通知
     */
    public function notice()
    {
        $i = 1;
        $list = M('Document')->where(['category_id' => 40])->limit(0, $i)->select();
        foreach ($list as &$v) {
            $pic = M('Picture')->where(['id' => $v['cover_id']])->select();
            $v['cover_id'] = $pic[0]['path'];

        }
        $this->assign('list', $list);
        $this->display();

    }

    /**
     * 显示所有文章列表
     * 便民服务、小区通知、商家活动、小区活动
     *
     * 通过ajax请求获取更多数据
     */
    public function service()
    {
        $category_id=I('get.category_id');
        $model=M('Document')->where(['category_id' => $category_id,'status'=>1]);
//        $pageSize=C('LIST_ROWS');//默认每页显示条数
        $pageSize=1;
        $list = $model->page(I('p',1), $pageSize)->select();
        //给数据正确的格式
        foreach ($list as &$v) {
            $v['url']=U('detail',['id'=>$v['id']]);//url地址
            $v['create_time']=date('Y-m-d H:i:s',$v['create_time']);//时间格式化
            $v['img'] = get_cover($v['cover_id'],'path');//给图片路径
            $v['join']=0;
            if(!session('uid')){
                $map=['uid'=>session('uid'),'type'=>$v['category_id'],'target_id'=>$v['id']];
                $v['join']=(M('Userdata')->where($map)->find()==null?0:1);
            }
        }
        //发送ajax请求
        if(IS_AJAX){
            if(empty($list)){//没有数据，返回info信息和status=0
                $this->error('没有数据');
            }else{//有数据，返回info数据和status=1
                $this->success($list);
            }
        }
        //发送数据到页面,显示页面
        $this->assign('list', $list);
        $this->display();

    }
    /**
     * ajax加载更多
     */
    public function getmore()
    {
        $list = M('Document');
        $page = I('post.page'); //获取请求的页数
        $category_id = I('post.category_id');  //获取请求的category_id
        $count=$list->where(['category_id' => $category_id])->count();//总共页数
        $start = $page*1;
        $data = $list->where(['category_id' => $category_id])->limit($start, 1)->select();
        foreach ($data as &$l) {
            $p = M('Picture')->where(['id' => $l['cover_id']])->select();
            $l['cover_id'] = $p[0]['path'];
            $l['url']=U('detail',['id'=>$l['id']]);
            $l['create_time']=date('Y-m-d H:i:s',$l['create_time']);

        }
        $data['length']=$count;
        echo json_encode($data);  //转换为json数据输出
    }

    /**
     * 详情页
     */
    public function detail()
    {
        //接收get传值，得到id
        $id = I('get.id');
        $map=['id' => $id];
        $list = M('Document')->where($map)->select();
        $list = $list[0];
        $list['join']=0;
        if(!session('uid')){
            $map=['uid'=>session('uid'),'type'=>$list['category_id'],'target_id'=>$list['id']];
            $list['join']=(M('Userdata')->where($map)->find()==null?0:1);
        }
        $this->assign('list', $list);
        $this->display();
    }
    /**
     * 活动报名 ajax
     */
    public function join()
    {
        //判断是否登录，没有登录则返回错误信息
        if(!session('uid')){
            $this->error("没有登录");
        }
        //已登录，则完成报名
        $uid=session('uid');
        $id=I('post.id');
        $category_id=I('post.category_id');
        $model=M('Userdata');
        $map=['uid'=>$uid,'type'=>$category_id,'target_id'=>$id];
        $result=$model->where($map)->find();
        //已经报名
        if($result!=null){
            $model->delete($map);
            $this->success('0');
        }
        $model->create($map);
        $model->add();
        $this->success('1');
    }
    /**
     * 浏览次数更新
     */
    public function addView()
    {
        $id=I('post.id');
        $model=M('Document');
        $model->where(['id'=>$id])->setInc('view');
        $this->success();
    }
}
