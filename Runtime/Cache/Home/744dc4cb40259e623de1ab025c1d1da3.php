<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
        <title>Bootstrap 101 Template</title>

        <!-- Bootstrap -->
        <link href="/Public/Home/wechat/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/Public/Home/wechat/css/style.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style>
            .main{margin-bottom: 60px;}
            .indexLabel{padding: 10px 0; margin: 10px 0 0; color: #fff;}
        </style>
    

    
        <!--自定义样式-->
    

</head>
<body>
<div class="main">

    
        <!--导航部分-->
        <nav class="navbar navbar-default navbar-fixed-bottom">
            <div class="container-fluid text-center">
                <div class="col-xs-3">
                    <p class="navbar-text"><a href="index.html" class="navbar-link">首页</a></p>
                </div>
                <div class="col-xs-3">
                    <p class="navbar-text"><a href="fuwu.html" class="navbar-link">服务</a></p>
                </div>
                <div class="col-xs-3">
                    <p class="navbar-text"><a href="faxian.html" class="navbar-link">发现</a></p>
                </div>
                <div class="col-xs-3">
                    <p class="navbar-text"><a href="http://www.think.com/index.php?s=/Home/User/login" class="navbar-link">我的</a></p>
                </div>
            </div>
        </nav>
        <!--导航结束-->
    
    
    <div class="blank"></div>
    <h3 class="noticeDetailTitle"><strong><?php echo ($list["title"]); ?></strong></h3>
    <div class="noticeDetailInfo">发布者:XXX小区物管</div>
    <div class="noticeDetailInfo">
        发布时间：<?php echo (date('Y-m-d',$list["create_time"])); ?>
        <?php if($list["category_id"] == 43): ?><a href="javascript:;" class="join" style="padding-left: 300px">
                <?php if($list["join"] == 0): ?><span class="glyphicon glyphicon-star-empty"></span>
                    参加活动
                <?php else: ?>
                    <span class="glyphicon glyphicon-star"></span>
                    取消参加<?php endif; ?>
            </a><?php endif; ?>
    </div>

    <div class="noticeDetailContent">
        <?php echo ($list["description"]); ?>
    </div>
    </div>


</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/Public/Home/wechat/jquery-1.11.2.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/Public/Home/wechat/bootstrap/js/bootstrap.min.js"></script>


    <script>
        $(function(){
            var id="<?php echo ($list["id"]); ?>";
            //浏览数加1
            $.post("<?php echo U('addView');?>",{id:id},function(data){

            });
            //点击报名
            $(".join").click(function(){
                var category_id="<?php echo ($list["category_id"]); ?>";
                $.post("<?php echo U('join');?>",{id:id,category_id:category_id},function(data){
                    //判断是否登录
                    if(!data.status){
                        //没有登录，是否跳转到登录页面
                        if(confirm("未登录，是否登录")){
                            window.location.href="<?php echo U('User/login');?>";
                        }
                    }else{
                        //登录状态
                        if(parseInt(data.info)){
                            //取消报名成功
                            alert('已取消报名');
                            $html='<span class="glyphicon glyphicon-star-empty"></span>参加活动';
                            $('.join').text($html);
                        }else{
                            //报名成功
                            alert('报名成功');
                            $html='<span class="glyphicon glyphicon-star"></span>取消参加';
                            $('.join').text($html);
                        }
                    }
                });
            });
        });
    </script>

</body>
</html>