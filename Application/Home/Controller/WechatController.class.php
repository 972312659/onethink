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
use EasyWeChat\Support\Url;
use think\Controller;

include './vendor/autoload.php';//必须引入才能找到Application这个类
class WechatController extends Controller
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
    //查询菜单
    public function getMenus()
    {
        $app = new Application(C('wechat'));
        $menu = $app->menu;
        $menus = $menu->all();
        var_dump($menus);
    }

    //设置菜单
    public function setMenus()
    {
        $app = new Application(C('wechat'));
        $menu = $app->menu;
        $buttons = [
            [
                "type" => "click",
                "name" => "热卖商品",
                "key"  => "hot_goods"
            ],
            [
                "name"       => "个人中心",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "我的信息",
                        "url"  => U('Wechat/service','','',true),
                    ],
                    [
                        "type" => "view",
                        "name" => "我的订单",
                        "url"  => U('Wechat/service','','',true),
                    ],
                    [
                        "type" => "view",
                        "name" => "绑定账号",
                        "url"  => U('Wechat/service','','',true),
                    ],
                    [
                        "type" => "view",
                        "name" => "test",
                        "url"  => U('Wechat/test','','',true),
                    ],
                ],
            ],
        ];
        $r = $menu->add($buttons);
        var_dump($r);
    }
    /**
     *
     */
    public function service()
    {
        if(!is_login()){
            $this->redirect(U('Wechat/bang'));exit;
        }
        //获取openid
        if(!session('openid')){
            $app=new Application(C('wechat'));
            //将当前路由保存到session，便于授权回调地址跳回当前页面
            session('target_url',U('service'));
            $response = $app->oauth->redirect();
            $response->send();
        }
        //从session中取出openid
        $openid=session('openid');
        //用户是否绑定
        $member=M('Ucenter_member')->where(['openid'=>$openid])->select();
        if($member==null){
            //将当前路由保存到session，便于授权回调地址跳回当前页面
            session('target_url',U('service'));
            $this->redirect(U('bang'));
        }

    }
    /**
     * 用户绑定
     */
    public function bang()
    {
        var_dump(11);exit;
        //获取openid
        if(!session('openid')){
            $app=new Application(C('wechat'));
            $response = $app->oauth->redirect();
            //将当前路由保存到session，便于授权回调地址跳回当前页面
            session('target_url',U('bang'));
            $response->send();
        }
        //从session中取出openid
        $openid=session('openid');
        //用户是否绑定
        $member=M('Ucenter_member')->find(['openid'=>$openid]);
        //用户已绑定，就自动登录
        if($member){
            //将当前路由保存到session，便于授权回调地址跳回当前页面
            $Member = D('Member');
            $Member->login($member['id']);
            $target_url=session('target_url',U('bang'));
            session('target_url',null);
            $this->redirect($target_url);exit;
        }
        if(IS_POST){

        }
        $this->assign();
    }


    /**
     * 回调 获取openid
     */
    public function callback()
    {
        $app = new Application(C('wechat'));
        $oauth = $app->oauth;
        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();
        //得到openId
        $openId=$user->id;
        //将openId保存到session
        session('openId',$openId);
        if(session('?target_url')){
            $target_url=session('target_url');
            session('target_url',null);//清空target_url
            $this->redirect($target_url);exit;
        }
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
    public function test()
    {
        var_dump(C('wechat'));
        echo 111;
    }

}