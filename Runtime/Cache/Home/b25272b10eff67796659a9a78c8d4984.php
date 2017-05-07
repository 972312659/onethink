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
                    <p class="navbar-text"><a href="<?php echo U('ourservice');?>" class="navbar-link">服务</a></p>
                </div>
                <div class="col-xs-3">
                    <p class="navbar-text"><a href="faxian.html" class="navbar-link">发现</a></p>
                </div>
                <div class="col-xs-3">
                    <p class="navbar-text"><a href="<?php echo U('My/index');?>" class="navbar-link">我的</a></p>
                </div>
            </div>
        </nav>
        <!--导航结束-->
    
    
    <div class="blank"></div>
    <h3 class="noticeDetailTitle"><strong><?php echo ($title); ?></strong></h3>
    <div class="noticeDetailInfo">
        报修时间：<?php echo (date('Y-m-d',$inputtime)); ?>
    </div>

    <div class="noticeDetailContent">
        <?php echo ($question); ?>
    </div>
    <div class="text-center">
        <div class="content">
        <?php if($status == 0): ?><a class="btn btn-info assess" status="0">已处理</a>
            <?php elseif($status == 1): ?>
                <textarea id="content" name="assess" class="form-control" rows="3" placeholder="输入评论"></textarea>
                <p><a class="btn btn-success assess" status="1">提交</a></p>
            <?php else: ?>
                评论：<?php echo ($assess); endif; ?>
        </div>
    </div>


</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/Public/Home/wechat/jquery-1.11.2.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/Public/Home/wechat/bootstrap/js/bootstrap.min.js"></script>


    <script>
        $('.assess').click(function(){
            var status = $(this).attr('status');
            var id="<?php echo ($id); ?>";
            var data={};
            var assess='';
            var s=0;
            if(status==1){
                assess=$('#content').val();
                s=1;
            }
            console.log(s);
            data={s:s,assess:assess,id:id};
            $.post("<?php echo U('detail');?>",data,function(data){
                var html = '';
                if(data.status){
                    html = '评论：'+assess;
                    $('.content').html(html);
                }else{
                    html = '<textarea id="content" name="assess" class="form-control" rows="3" placeholder="输入评论"></textarea>\
                            <p><a class="btn btn-success assess" status="1">提交</a></p><h1>M</h1>';
                    $('.assess').remove();
                    $(html).appendTo('.content');
                    $('.assess').click(function(){
                        assess=$('#content').val();
                        s=1;
                        data={s:s,assess:assess,id:id};
                        $.post("<?php echo U('detail');?>",data,function(data){
                            html = '评论：'+assess;
                            $('.content').html(html);
                        });
                    });
                }
            });
        });
    </script>

</body>
</html>