<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:85:"F:\MaLong\item\faka\application\templates\pc\index\default\\order\complaintquery.html";i:1543624690;s:68:"F:\MaLong\item\faka\application\templates\pc\index\default\main.html";i:1539744034;s:70:"F:\MaLong\item\faka\application\templates\pc\index\default\header.html";i:1539744034;s:70:"F:\MaLong\item\faka\application\templates\pc\index\default\footer.html";i:1539744034;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo sysconf('site_name'); ?><?php echo sysconf('site_subtitle'); ?></title>
    <meta name="keywords" content="<?php echo sysconf('site_keywords'); ?>" />
    <meta name="description" content="<?php echo sysconf('site_desc'); ?>" />
    <link rel="shortcut icon" href="<?php echo sysconf('browser_icon'); ?>" />
    <link rel="stylesheet" href="/static/app/css/main.css">
    <link rel="stylesheet" href="/static/app/css/main_mobile.css">
    <link rel="stylesheet" href="/static/app/css/iconfont.css">
    <link rel="stylesheet" href="/static/app/css/animate.min.css">
    <link rel="stylesheet" href="/static/app/css/swiper.min.css">
    <link rel="stylesheet" href="/static/plugs/layui/css/layui.css"></link>
    <script src="/static/app/js/jquery-2.2.1.min.js"></script>
    <script src="/static/app/js/swiper.min.js"></script>
    <script src="/static/app/js/formvalidator_min.js"></script>
    <script src="/static/app/js/formvalidatorregex.js"></script>
    <script src="/static/app/js/layer.js"></script>
    
<style>
    .ts_title{
        height:60px;
        text-align:center;
        line-height:60px;
        font-size:18px;
        background:#409ccf;
        border-top-left-radius:3px;
        border-top-right-radius:3px;
        color:#fff;
    }
    .ts_con {
        padding:30px 0;
        position:relative;
    }
    .ts_con p{
        height:40px;
        width: 100%;
        background:#fff;
        border:1px solid #eee;
        border-radius:2px;
        line-height:40px;
        margin-bottom:20px;
    }
    .ts_con p span{
        width:120px;
        font-size:16px;
        background:#409ccf;
        display:block;
        float:left;
        border-top-left-radius:2px;
        border-bottom-left-radius:2px;
        color:#fff;
        text-align:center;
        margin-right:10px;
    }
    .ts_con p input,.ts_con p select{
        width: calc(100% - 140px);
        width: -moz-calc(100% - 140px);
        width: -webkit-calc(100% - 140px);
        border:none;
        font-size:14px;
        outline:none;
        letter-spacing:1px;
    }
    @media (max-width: 780px){
        .ts_con p input,.ts_con p select{
            width: 160px;
            border:none;
            font-size:14px;
            outline:none;
            letter-spacing:1px;
        }
        .footer_bottom .footer-menu{
            text-align:center;
        }
    }
    .ts_con p textarea{
        width: calc(100% - 140px);
        width: -moz-calc(100% - 140px);
        width: -webkit-calc(100% - 140px);
        max-width:780px;
        height:195px;
        border:none;
        font-size:14px;
        line-height:35px;
        outline:none;
        letter-spacing:1px;
        resize: none;
    }
    .btn_ts{
        display:block;
        border:none;
        height:48px;
        width:200px;
        line-height:48px;
        background:#409ccf;
        color:#fff;
        margin:0 auto;
        border-radius:2px;
        font-weight:bold;
    }
    .file input {
        position: absolute;
        font-size: 100px;
        right: 0;
        top: 0;
        opacity: 0;
    }
    .shili img{
        position:absolute;
        top:38px;
        left:0;
    }
</style>


</head>
<body>

<section class="page_top">
    <div class="container">
    <!--导航-->
<div class="top">
    <div class="logo"><a href="/"><img src="<?php echo sysconf('site_logo'); ?>" alt="" height="44"></a></div>
    <div class="nav_btn"><i></i></div>
    <div class="user_btns">
        <?php if(session('?merchant.user')): ?>
        <a href="/merchant" class="login_btn" >商户中心</a><a  href="/logout" class="reg_btn">退出登录</a>
        <?php else: ?>
        <a href="/login" class="login_btn">登录</a><a href="/register" class="reg_btn">注册</a>
        <?php endif; ?>
    </div>
    <div class="nav">
        <ul>
            <?php if(is_array($nav) || $nav instanceof \think\Collection || $nav instanceof \think\Paginator): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <li><a href="<?php echo $vo['url']; ?>" <?php if($vo['target'] == '1'): ?>target="_blank"<?php endif; ?>><?php echo $vo['title']; ?></a></li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
    <div class="main_shadow">
        <ul>
            <?php if(is_array($nav) || $nav instanceof \think\Collection || $nav instanceof \think\Paginator): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <li><a href="<?php echo $vo['url']; ?>" <?php if($vo['target'] == '1'): ?>target="_blank"<?php endif; ?>><?php echo $vo['title']; ?></a></li>
            <?php endforeach; endif; else: echo "" ;endif; if(session('?merchant.user')): ?>
            <li><a href="/merchant">商户中心</a></li>
            <li><a href="/logout">退出登录</a></li>
            <?php else: ?>
            <li><a href="/login">登录</a></li>
            <li><a href="/register">快速注册</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>

    </div>
</section>



<div class="order_form">
    <div class="ts_title">
        投 诉 查 询
    </div>
    <div class="ts_con">

        <form name='report' action="<?php echo url('Index/order/complaintPass'); ?>" method='get' style="padding: 0 20px;" onsubmit="return checker()">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <p><span>订单编号</span><input name="trade_no" type="text" value="<?php echo \think\Request::instance()->get('id'); ?>"></p>
            <button type="submit" class="btn_ts">查询</button>
        </form>

    </div>
</div>

<!--返回顶部-->
<div class="toTop"><i class="iconfont icon-angle-up"></i>TOP</div>

<footer class="row footer-area">

    <div class="row m0 footer_bottom">
        <div class="container">
            <div class="row" style="padding:0 20px">
                <div class="col-sm-6 col-md-6">
                    <?php echo sysconf('site_info_copyright'); ?>
                </div>

            </div>
        </div>
    </div>
</footer>
<script src="/static/app/js/main.js"></script>
<script src="/static/app/js/main_mobile.js"></script>
<script>
    var mySwiper = new Swiper ('.swiper-container', {
        slidesPerView : 'auto',
        autoplay : 3000,
        direction: 'horizontal',
        loop: true,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
    })
</script>


<script>
    function checker(){
        var trade_no = $('[name=trade_no]').val();
        if(trade_no.length === 0) {
            layer.msg('请填写订单号');
            return false;
        }

        return true;
    }
</script>

</body>
</html>
