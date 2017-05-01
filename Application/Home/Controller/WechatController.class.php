<?php
/**
 * Created by PhpStorm.
 * User: 邓伟
 * Date: 2017/4/27
 * Time: 9:29
 */

namespace Home\Controller;


use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;

class WechatController extends HomeController
{
    /**
     * 连接微信接口
     */
    public function index()
    {
        $app=new Application(C('wechat'));
        $server=$app->server;
        $server->setMessageHandler(function ($message) {
            switch ($message->MsgType) {
                case 'event':
                    switch ($message->Event){
                        case 'CLICK':
                            switch ($message->EventKey){
                                case 'V1001_TODAY_MUSIC':
                                    return '热卖商品';
                                    break;
                            }
                    }
                    return $message->EventKey;
                    break;
                case 'text':
                    if($message->Content=='美女排行榜'){
                        //图片信息
                        $imgs=[
                            ['title'=>'第1名','description'=>'一','picurl'=>'http://sh.sinaimg.cn/2015/0521/U11428P18DT20150521115725.jpg','url'=>'http://baidu.com'],
                            ['title'=>'第2
    名','description'=>'二','picurl'=>'http://sh.sinaimg.cn/2015/0521/U11428P18DT20150521115723.jpg','url'=>'http://baidu.com'],
                            ['title'=>'第3名','description'=>'三','picurl'=>'http://sh.sinaimg.cn/2015/0521/U11428P18DT20150521115724.jpg','url'=>'http://baidu.com'],
                            ['title'=>'第4名','description'=>'四','picurl'=>'http://sh.sinaimg.cn/2015/0521/U11428P18DT20150521115722.jpg','url'=>'http://baidu.com'],
                            ['title'=>'第5名','description'=>'五','picurl'=>'http://sh.sinaimg.cn/2015/0521/U11428P18DT20150521115724_1.jpg','url'=>'http://baidu.com'],
                            ['title'=>'第6名','description'=>'六','picurl'=>'http://sh.sinaimg.cn/2015/0521/U11428P18DT20150521115719.jpg','url'=>'http://baidu.com'],
                            ['title'=>'第7名','description'=>'七','picurl'=>'http://sh.sinaimg.cn/2015/0521/U11428P18DT20150521115720.jpg','url'=>'http://baidu.com'],
                            ['title'=>'第8名','description'=>'八','picurl'=>'http://sh.sinaimg.cn/2015/0521/U11428P18DT20150521115718.jpg','url'=>'http://baidu.com'],
                        ];
                        $data=[];
                        foreach($imgs as $img){
                            $news = new News([
                                'title'       => $img['title'],
                                'description' => '...',
                                'url'         => $img['url'],
                                'image'       => $img['picurl'],
                                // ...
                            ]);
                            $data[]=$news;
                        }
                        return $data;
                    }elseif($message->Content=='解除绑定'){
                        return self::cancel($message->FromUserName);
                    }elseif($message->Content=='帮助'){
                        return '输入：热卖商品，可以展示最新的热卖详情；输入：解除绑定，则可以解除商城与微信的绑定';
                    }elseif($message->Content=='热卖商品'){
                        return '热卖商品';
                    }
                    return $message->Content;
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });
        $response = $server->serve();
        // 将响应输出
        $response->send(); // Laravel 里请使用：return $response;
    }

    /**
     * 发送文字取消绑定
     */
    public function cancel($openId)
    {
        //判断是否绑定
        $user=M('Member')->find(['openid'=>$openId]);
        if($user){
            $data=$user->creat();
            $data->openid=null;
            if($user->save()){
                //记录行为
                action_log('update_channel', 'channel', $data['id'], UID);
                $this->success('编辑成功', U('index'));
                return '解除绑定成功';
            }
        }
        return '未绑定';
    }
    /**
     * callback  获取openid
     */
    public function actionCallback()
    {
        $app = new Application(C('wechat'));
        $user = $app->oauth->user();
        //得到openId
        $openId=$user->id;
        //将openId保存到session
        session('openId',$openId);
        //跳转到刚才的页面
        if(session('?target_url')){
            $target_url=session('target_url');
            session('target_url',null);//清空target_url
            return $this->redirect($target_url);
        }
    }
}