<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:76:"F:\MaLong\item\faka\application\templates\pc\index\default\\index\index.html";i:1561817207;s:78:"F:\MaLong\item\faka\application\templates\pc\index\default\default_header.html";i:1585471324;s:78:"F:\MaLong\item\faka\application\templates\pc\index\default\default_footer.html";i:1539744034;}*/ ?>
<!DOCTYPE html>
<html lang="cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo sysconf('site_name'); ?><?php echo sysconf('site_subtitle'); ?></title>
    <meta name="keywords" content="<?php echo sysconf('site_keywords'); ?>" />
    <meta name="description" content="<?php echo sysconf('site_desc'); ?>" />
    <link rel="shortcut icon" href="<?php echo sysconf('browser_icon'); ?>" />
    <!-- Bootstrap -->
    <link href="/static/app/default/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/static/app/default/css/font-awesome.min.css">
    <link rel="stylesheet" href="/static/app/default/vendors/themify-icon/themify-icons.css">
    <link rel="stylesheet" href="/static/app/default/vendors/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="/static/app/default/vendors/owl-carousel/animate.css">
    <!--custom css-->
    <link rel="stylesheet" href="/static/app/default/css/style.css">
    <link rel="stylesheet" href="/static/app/default/css/responsive.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body data-spy="scroll" data-target="#bs-example-navbar-collapse-1" data-offset="100">
<style>
@media (max-width: 780px){
	.col-lg-3{
		float:left;
	}
	.main_menu_area_one.affix .menu_logo{    line-height: 61px;}
	.main_menu_area_one .menu_logo img{max-width: 115px;}
}

.affix .affix-top-logo{display: none !important;}
.affix-top .affix-logo{display: none !important;}
</style>
<header class="main_menu_area_one header affix-top" data-spy="affix" data-offset-top="100">
    <div class="col-md-3 col-lg-3">
        <div class="menu_logo affix-top-logo">
            <a href="/">
                <img src="<?php echo sysconf('site_logo'); ?>" alt="logo">
            </a>
        </div>
        <div class="menu_logo affix-logo">
            <a href="/">
                <img src="<?php echo sysconf('merchant_logo'); ?>" alt="logo">
            </a>
        </div>
    </div>
    <div class="col-md-7 col-lg-7">
        <nav class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html"><img src="<?php echo sysconf('site_logo'); ?>" alt="logo" style="width:50%;margin:0 auto"></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav menu">

                    <?php if(is_array($nav) || $nav instanceof \think\Collection || $nav instanceof \think\Paginator): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <li class="dropdown submenu">
                        <a  class="dropdown-toggle" href="<?php echo $vo['url']; ?>" <?php if($vo['target'] == '1'): ?>target="_blank"<?php endif; ?>><?php echo $vo['title']; ?></a>
                    </li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </nav>
    </div>
    <div class="col-md-2 col-lg-2">
        <div class="right-icon">
            <ul class="nav navbar-nav navbar-right">
                <?php if(session('?merchant.user')): ?>

                <li class="nav-item dropdown submenu mobile-shop logon-btn">
                    <a class="dropdown-toggle" href="/merchant"> <span class="btn thm-btn green_btn">商户中心</span></a>
                </li>
                <li  class="search_btnreg-btn"><a href="/logout"> <span>退出</span></a></li>
                <?php else: ?>
                <li class="nav-item dropdown submenu mobile-shop logon-btn">
                    <a class="dropdown-toggle" href="/login"> <span class="btn thm-btn green_btn">登录</span></a>
                </li>
                <li class="search_btn reg-btn"><a class="dropdown-toggle" href="/register"> <span>注册</span></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>

<div class="hero_area" id="home">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 hero_text wow fadeInLeft">
                <h2>全新的售卡体验 </h2>
                <h2>New Experience</h2>
                <p>致力于解决虚拟商品的快捷寄售服务，为商户及其买家提供便捷、绿色、安全、快速的销售和购买体验 </p>
                <a href="/merchant" class="btn pro_btn">立即加入</a>
            </div>
            <div class="col-sm-6 wow fadeInRight">
                <img class="header_mac_img" src="/static/app/default/image/banner/mac-book.png" alt="mac">
            </div>
        </div>
    </div>
</div>

<div class="perfect_area sec-pad" id="service">
    <div class="container">
        <div class="section-title wow fadeInUp">
            <h2 class="title">核心优势技术实力 / Advantage</h2>
            <p>数十万用户的信赖之选，提供便捷、绿色、安全、快速的销售和购买体验.</p>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-12 pull-right">
                <div class="easy-img">
                    <img src="/static/app/default/image/easy-img.png" alt="perfect">
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="perfect-item wow fadeIn" data-wow-delay="100ms">
                    <div class="media">
                        <div class="media-left">
                            <img src="/static/app/default/image/icon/s-search.png" alt="s">
                        </div>
                        <div class="media-body">
                            <h3>服务全球</h3>
                            <p>业务范围覆盖全国用户遥遥领先</p>
                        </div>
                    </div>
                </div>
                <div class="perfect-item wow fadeIn" data-wow-delay="150ms">
                    <div class="media">
                        <div class="media-left">
                            <img src="/static/app/default/image/icon/sitting.png" alt="s">
                        </div>
                        <div class="media-body">
                            <h3>持续更新</h3>
                            <p>系统持续更新，功能持续完善.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="perfect-item wow fadeIn" data-wow-delay="200ms">
                    <div class="media">
                        <div class="media-left">
                            <img src="/static/app/default/image/icon/manage.png" alt="s">
                        </div>
                        <div class="media-body">
                            <h3>极速响应</h3>
                            <p>全天候10秒响应服务.</p>
                        </div>
                    </div>
                </div>
                <div class="perfect-item wow fadeIn" data-wow-delay="250ms">
                    <div class="media">
                        <div class="media-left">
                            <img src="/static/app/default/image/icon/seo.png" alt="s">
                        </div>
                        <div class="media-body">
                            <h3>资金保障</h3>
                            <p>次日即可结算，您的资金安全将得到充分的保障.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="seo-features bg-color">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 wow fadeInLeft" data-wow-delay="250ms">
                <img class="img-responsive" src="/static/app/default/image/features/power-img.png" alt="power">
            </div>
            <div class="col-sm-6 wow fadeInRight" data-wow-delay="250ms">
                <div class="features-content">
                    <h2 class="title">界面简约自适应</h2>
                    <p>简约的UI交互体验可以给您一个体验度极高的商户后台，更好的下单体验，零门槛注册，即刻打开致富之门！</p>
                    <a href="http://www.huimaw.com/" class="btn thm-btn pro_btn green_btn">加入我们</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="seo-features seo-features-bg" id="feature">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 wow fadeInUp" data-wow-delay="250ms">
                <div class="features-content">
                    <h2 class="title">服务器安全</h2>
                    <p> 采用群集服务器，防御高，故障率低，无论用户身在何处，均能获得流畅安全可靠的体验，全天候10秒响应服务 打造强悍性能防御 技术服务支撑极速响应.</p>
                    <a href="http://www.huimaw.com/" class="btn pro_btn">现在加入</a>
                </div>
            </div>
            <div class="col-sm-6 wow fadeInUp" data-wow-delay="450ms">
                <img class="img-responsive" src="/static/app/default/image/features/graph.png" alt="power">
            </div>
        </div>
    </div>
</section>

<section class="seo-features seo-features3">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 wow fadeInLeft" data-wow-delay="150ms">
                <img class="img-responsive" src="/static/app/default/image/features/pie.png" alt="power">
            </div>
            <div class="col-sm-6 wow fadeInRight" data-wow-delay="150ms">
                <div class="features-content">
                    <h2 class="title">终身陪伴</h2>
                    <p> 服务器集群部署，多重安全保证，是您创业路上的好帮手！ 系统持续更新，功能持续完善，让商户以及客户的体验不断接近完美是我们一直不变的追求</p>
                    <ul>
                        <li>
                            <i class="fa fa-check" aria-hidden="true"></i>搞标准的代码写法，系统安全。
                        </li>
                        <li>
                            <i class="fa fa-check" aria-hidden="true"></i> 极速售后响应，无后顾之忧
                        </li>
                        <li>
                            <i class="fa fa-check" aria-hidden="true"></i>不断的迭代更新，功能更全
                        </li>
                    </ul>
                    <a href="/register" class="btn pro_btn green_btn">加入我们</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="seo-rang-area bg-color sec-pad">
    <div class="container">
        <div class="range-content wow fadeInUp" data-wow-delay="150ms">
            <img src="/static/app/default/image/features/range.png" alt="range">
            <h2 class="title">为您提供一站式虚拟商品在线购买以及全自动发货服务!</h2>
            <p> 采用群集式服务器，防御高，故障率低，无论用户身在何处，均能获得100%流畅安全可靠的体验。.</p>
            <a href="http://www.huimaw.com/" class="btn pro_btn green_btn">加入我们</a>
        </div>
    </div>
</section>


<footer class="row footer-area">

    <div class="row m0 footer_bottom">
        <div class="container">
            <div class="row" style="padding: 0 20px;">
                <div class="col-sm-12 text-center">
                    <?php echo htmlspecialchars_decode(sysconf('site_info_copyright')); ?>
                    <a href="javascript:void(0)" onclick="window.open('http://www.miitbeian.gov.cn/');" >
                        <?php echo htmlspecialchars_decode(sysconf('site_info_icp')); ?></a> <?php echo htmlspecialchars_decode(sysconf('site_statistics')); ?>
                </div>
            </div>
        </div>
    </div>
</footer>



<script src="/static/app/default/js/jquery-2.2.1.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/static/plugs/bootstrap/js/bootstrap.min.js"></script>
<script src="/static/app/default/vendors/owl-carousel/owl.carousel.min.js"></script>
<script src="/static/app/default/js/wow.js"></script>
<script src="/static/app/default/js/custom.js"></script>
</body>
</html>