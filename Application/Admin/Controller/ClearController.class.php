<?php
/**
 * Created by PhpStorm.
 * User: 邓伟
 * Date: 2017/5/2
 * Time: 9:20
 */

namespace Admin\Controller;



class ClearController extends AdminController
{
    public function clear()
    {

        //设置程序不限次数的执行
        set_time_limit(0);
        //用一个死循环让程序一直执行
        while(true){
            //找到所有失效的文章    time()-update_time>0
            $models=M('Document')->where(['status'=>1])->where('update_time<'.(time()-3600))->select();
            $ids=[];
            //循环并删除
            foreach($models as $model){
                $overdue=M('Document');
//                $overdue->delete(['id'=>$model['id']]);
                $ids[]=$model['id'];
            }
            echo implode(',',$ids).'aaa</br>';
            //每隔3秒删除一次
            sleep(3);
        }
    }
}