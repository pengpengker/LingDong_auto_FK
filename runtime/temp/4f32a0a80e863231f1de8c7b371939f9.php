<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"F:\MaLong\item\faka\application\templates\pc\merchant\default\index\index.html";i:1543624690;s:71:"F:\MaLong\item\faka\application\templates\pc\merchant\default\base.html";i:1585555954;}*/ ?>
<!DOCTYPE html>
<html lang="zh">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>商户面板 - <?php echo sysconf('site_name'); ?></title>

        <meta name="keywords" content="<?php echo sysconf('site_keywords'); ?>" />
        <meta name="description" content="<?php echo sysconf('site_desc'); ?>" />
        <link rel="shortcut icon" href="<?php echo sysconf('browser_icon'); ?>" />
        <!--Morris Chart CSS -->
		<link rel="stylesheet" href="/static/assets/plugins/morris/morris.css">

        <!-- Plugins css-->
        <link href="/static/assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="/static/assets/plugins/multiselect/css/multi-select.css"  rel="stylesheet" type="text/css" />
        <link href="/static/assets/plugins/select2/dist/css/select2.css" rel="stylesheet" type="text/css">
        <link href="/static/assets/plugins/select2/dist/css/select2-bootstrap.css" rel="stylesheet" type="text/css">
        <link href="/static/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
        <link href="/static/assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
        <link href="/static/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
		<link href="/static/assets/plugins/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">
		<link href="/static/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
		<link href="/static/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
        <link href="/static/plugs/awesome/css/font-awesome.min.css" rel="stylesheet">

        <!-- Sweet Alert css -->
        <link href="/static/assets/plugins/bootstrap-sweetalert/sweet-alert.css" rel="stylesheet" type="text/css" />
        <!-- form Uploads -->
        <link href="/static/assets/plugins/fileuploads/css/dropify.min.css" rel="stylesheet" type="text/css" />

        <!-- App css -->
        <link href="/static/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/static/assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="/static/assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="/static/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="/static/assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="/static/assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="/static/assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="/static/assets/js/modernizr.min.js"></script>
        <style type="text/css">
            .page-title{ overflow: hidden;  height: 70px;  width: auto;  text-overflow: ellipsis;  white-space: nowrap;  }
        </style>
    </head>


    <body class="fixed-left">

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                    <a href="/merchant/" class="logo"><img src="<?php echo sysconf('merchant_logo'); ?>" style="max-width:100%;max-height:60px" alt=""></a>
                </div>

                <!-- Button mobile view to collapse sidebar menu -->
                <div class="navbar navbar-default" role="navigation">
                    <div class="container">

                        <!-- Page title -->
                        <ul class="nav navbar-nav navbar-left">
                            <li>
                                <button class="button-menu-mobile open-left">
                                    <i class="zmdi zmdi-menu"></i>
                                </button>
                            </li>
                            <li>
                                <h4 class="page-title"><?php echo (isset($_title) && ($_title !== '')?$_title:"商户中心"); ?></h4>
                            </li>

                        </ul>

                        <!-- Right(Notification and Searchbox -->
                        <ul class="nav navbar-nav navbar-right">

                            <div class="right-guide hidden-xs">
                                
                                <li><i class="zmdi zmdi-user"></i>ID：<a href="<?php echo url('merchant/user/settings'); ?>"><?php echo $_user['id']; ?> </a></li>

                                <li><i class="zmdi zmdi-money"></i>今日可提现：<a href="javascript:;"><?php echo $_user['money']; ?> </a> <a style="color:#71b6f9;padding-left: 5px;" href="/merchant/cash/apply">提现</a></li>
                                <li><i class="zmdi zmdi-money"></i>未结余额：<a href="javascript:;"><?php echo $_user['freeze_money']; ?> </a><img src="/static/assets/images/help_ico.png" id="freeze_money_tip" style="width:15px;margin-top: -2px;cursor: pointer;"></li>
                                    <li>
                                        <a href="/merchant/user/settings" >
                                            <i class="zmdi zmdi-settings"></i> 设置
                                        </a>
                                    </li>

                                    <li>
                                        <a href="/logout" class="text-custom">
                                            <i class="zmdi zmdi-power"></i> 退出
                                        </a>
                                    </li>

                            </div>


                        </ul>

                    </div><!-- end container -->
                </div><!-- end navbar -->
            </div>
            <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">


                    <!-- End User -->

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <ul>
                        	<li class="text-muted menu-title">导航菜单</li>

                            <li>
                                <a href="/merchant" class="waves-effect <?php if($_controller=='Index'): ?>active<?php endif; ?>"><i class="zmdi zmdi-view-dashboard"></i> <span>管理中心</span> </a>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect <?php if(($_controller=='User' || $_controller=='Message') && $_action!='channel'): ?>active<?php endif; ?>">
                                    <i class="fa fa-user"></i>
                                    <span>商家管理</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="list-unstyled">
                                	<li class="waves-effect <?php if($_controller=='User' && $_action=='loginlog'): ?>active<?php endif; ?>">
                                        <a href="/merchant/user/loginlog">登录日志</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='Message' && $_action=='index'): ?>active<?php endif; ?>">
                                        <a href="/merchant/message/index">站内消息</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='User' && $_action=='settings'): ?>active<?php endif; ?>">
                                        <a href="/merchant/user/settings">商家设置</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='User' && $_action=='password'): ?>active<?php endif; ?>">
                                        <a href="/merchant/user/password">修改密码</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='User' && $_action=='link'): ?>active<?php endif; ?>">
                                        <a href="/merchant/user/link">店铺链接</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect <?php if($_controller=='Complaint'): ?>active<?php endif; ?>">
                                    <i class="fa fa-frown-o"></i>
                                    <span>投诉管理</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="list-unstyled">
                                	<li class="waves-effect <?php if($_controller=='Complaint' && $_action=='index' && \think\Request::instance()->get('status')===null): ?>active<?php endif; ?>">
                                        <a href="/merchant/complaint/index">全部投诉</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='Complaint' && $_action=='index' && \think\Request::instance()->get('status')==1): ?>active<?php endif; ?>">
                                        <a href="/merchant/complaint/index?status=1">已处理</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='Complaint' && $_action=='index' && \think\Request::instance()->get('status')==='0'): ?>active<?php endif; ?>">
                                        <a href="/merchant/complaint/index?status=0">未处理</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect <?php if($_controller=='Goods' || $_controller=='GoodsCategory'): ?>active<?php endif; ?>">
                                    <i class="fa fa-shopping-bag"></i>
                                    <span>商品管理</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="list-unstyled">
                                	<li class="waves-effect <?php if($_controller=='GoodsCategory' && $_action=='index'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods_category/index">商品分类</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='Goods' && $_action=='add'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods/add">添加商品</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='Goods' && $_action=='index'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods/index">商品列表</a>
                                    </li>
                                    <li class="waves-effect <?php if($_controller=='Goods' && $_action=='trash'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods/trash">回收站</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect <?php if($_controller=='GoodsDuijie' || $_controller=='GoodsDuijie'): ?>active<?php endif; ?>">
                                    <i class="fa fa-podcast"></i>
                                    <span>对接管理</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="list-unstyled">
                                    <li class="waves-effect <?php if($_controller=='GoodsDuijie' && $_action=='index'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods_duijie/index">对接中心</a>
                                    </li>
                                    <li class="waves-effect <?php if($_controller=='GoodsDuijie' && $_action=='myduijie'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods_duijie/myduijie">我的对接</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect <?php if($_controller=='GoodsCard'): ?>active<?php endif; ?>">
                                    <i class="fa fa-cc-discover"></i>
                                    <span>虚拟卡管理</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="list-unstyled">
                                    <li class="waves-effect <?php if($_controller=='GoodsCard' && $_action=='add'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods_card/add">添加虚拟卡</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='GoodsCard' && $_action=='index' && \think\Request::instance()->get('status')===null): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods_card/index">虚拟卡列表</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='GoodsCard' && $_action=='index' && \think\Request::instance()->get('status')==='2'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods_card/index?status=2">已售出</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='GoodsCard' && $_action=='index' && \think\Request::instance()->get('status')==='1'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods_card/index?status=1">未售出</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='GoodsCard' && $_action=='ashbin'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods_card/ashbin">回收站</a>
                                    </li>
                                	<!-- <li class="waves-effect <?php if($_controller=='GoodsCard' && $_action=='sold'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods_card/sold">最近卖出</a>
                                    </li> -->
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect <?php if($_controller=='GoodsCoupon'): ?>active<?php endif; ?>">
                                    <i class="fa fa-ticket"></i>
                                    <span>优惠券管理</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="list-unstyled">
                                	<li class="waves-effect <?php if($_controller=='GoodsCoupon' && $_action=='add'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods_coupon/add">添加优惠券</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='GoodsCoupon' && $_action=='index' && \think\Request::instance()->get('status')===null): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods_coupon/index">优惠券列表</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='GoodsCoupon' && $_action=='index' && \think\Request::instance()->get('status')==='2'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods_coupon/index?status=2">已使用</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='GoodsCoupon' && $_action=='index' && \think\Request::instance()->get('status')==='1'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods_coupon/index?status=1">未使用</a>
                                    </li>
                                    <li class="waves-effect <?php if($_controller=='GoodsCoupon' && $_action=='trash'): ?>active<?php endif; ?>">
                                        <a href="/merchant/goods_coupon/trash">回收站</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect <?php if($_controller=='Order' ||  $_controller=='User' && $_action=='channel'): ?>active<?php endif; ?>">
                                    <i class="fa fa-reorder"></i>
                                    <span>订单管理</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="list-unstyled">
                                    <li class="waves-effect <?php if($_controller=='User' && $_action=='channel'): ?>active<?php endif; ?>">
                                        <a href="/merchant/user/channel">付款方式</a>
                                    </li>
                                    <li class="waves-effect <?php if($_controller=='Order' && $_action=='channelstatis'): ?>active<?php endif; ?>">
                                        <a href="/merchant/order/channelStatis">渠道分析</a>
                                    </li>
                                    <li class="waves-effect <?php if($_controller=='Order' && $_action=='index' && \think\Request::instance()->get('status')===null): ?>active<?php endif; ?>">
                                        <a href="/merchant/order/index">订单列表</a>
                                    </li>
                                    <li class="waves-effect <?php if($_controller=='Order' && $_action=='analysis' && \think\Request::instance()->get('status')===null): ?>active<?php endif; ?>">
                                        <a href="/merchant/order/analysis">收益分析</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='Order' && $_action=='index' && \think\Request::instance()->get('status')==1): ?>active<?php endif; ?>">
                                        <a href="/merchant/order/index?status=1">已付款</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='Order' && $_action=='index' && \think\Request::instance()->get('status')==='0'): ?>active<?php endif; ?>">
                                        <a href="/merchant/order/index?status=0">未付款</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect <?php if($_controller=='Spread' || $_controller=='InviteCode'): ?>active<?php endif; ?>">
                                    <i class="fa fa-paper-plane-o"></i>
                                    <span>推广管理</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="list-unstyled">
                                	<li class="waves-effect <?php if($_controller=='Spread' && $_action=='index'): ?>active<?php endif; ?>">
                                        <a href="/merchant/spread/index">推广列表</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='Spread' && $_action=='rebate'): ?>active<?php endif; ?>">
                                        <a href="/merchant/spread/rebate">推广返利</a>
                                    </li>
                                    <?php if(sysconf('spread_invite_code') == 1): ?>
                                    <li class="waves-effect <?php if($_controller=='InviteCode' && $_action=='index'): ?>active<?php endif; ?>">
                                        <a href="/merchant/invite_code/index">邀请码管理</a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect <?php if($_controller=='Cash'): ?>active<?php endif; ?>">
                                    <i class="fa fa-dollar"></i>
                                    <span>提现管理</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="list-unstyled">
                                	<li class="waves-effect <?php if($_controller=='Cash' && $_action=='apply'): ?>active<?php endif; ?>">
                                        <a href="/merchant/cash/apply">申请提现</a>
                                    </li>
                                	<li class="waves-effect <?php if($_controller=='Cash' && $_action=='index'): ?>active<?php endif; ?>">
                                        <a href="/merchant/cash/index">提现列表</a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <!-- Sidebar -->
                    <div class="clearfix"></div>

                </div>

            </div>
            <!-- Left Sidebar End -->



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">
                    
<style>
	.text-blue{
		color:#679bdc;
	}

	.text-medium{
		font-size:24px;
	}

	.text-normal{
		font-size:12px;
	}

	.text-bold{
		font-weight: bold;
	}

	.m20{
		margin:35px;
	}

	.desc{
		line-height: 1.5rem;
		height: 1.5rem;
	}

	.icon-box{
		height: 20px;
		width: 20px;
		display: inline-block;
	}

	.icon{
		height: 100%;
		width: 100%;
	}
</style>
<style type="text/css">.cj_stat{text-anchor: middle;font-family: Arial;font-size: 20px;-webkit-tap-highlight-color: rgba(0, 0, 0, 0);color: #000;}</style>
<div class="row">
	<div class="col-lg-9">
		<div class="col-lg-3">
			<div class="card-box">
				<div class="widget-chart text-center text-blue text-medium text-bold m20">
					<?php echo sprintf("%.2f",$dealStat['money'] + 0); ?>
				</div>
				<ul class="list-inline chart-detail-list m-b-0 text-normal">
					<li class="desc">
						<div class="icon-box">
							<img class="icon m-r-5" src="/static/assets/images/icon_wallet.png" alt="">
						</div>
						当前账号金额（元）
					</li>
				</ul>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="card-box">
				<div class="widget-chart text-center text-blue text-medium text-bold m20">
					<?php echo sprintf("%.2f",$today['transaction_money']); ?>
				</div>
				<ul class="list-inline chart-detail-list m-b-0 text-normal">
					<li class="desc">
						<div class="icon-box">
							<img class="icon m-r-5" src="/static/assets/images/icon_yajin.png" alt="">
						</div>
						今日收入金额（元）
					</li>
				</ul>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="card-box">
				<div class="widget-chart text-center text-blue text-medium text-bold m20">
					<?php echo $today['count'] + 0; ?>
				</div>
				<ul class="list-inline chart-detail-list m-b-0 text-normal">
					<li class="desc">
						<div class="icon-box">
							<img class="icon m-r-5" src="/static/assets/images/icon_gongdan.png" alt="">
						</div>
						今日成交订单（笔）
					</li>
				</ul>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="card-box">
				<div class="widget-chart text-center text-blue text-medium text-bold m20">
					<?php echo $dealStat['yesterday_order_num']; ?>
				</div>
				<ul class="list-inline chart-detail-list m-b-0 text-normal">
					<li class="desc">
						<div class="icon-box">
							<img class="icon m-r-5" src="/static/assets/images/icon_light.png" alt="">
						</div>
						昨日成交订单（笔）
					</li>
				</ul>
			</div>
		</div>


		<!--第二行-->
		<div class="col-lg-3">
			<div class="card-box">
				<div class="widget-chart text-center text-blue text-medium text-bold m20">
					<?php echo $dealStat['cardnum']; ?>
				</div>
				<ul class="list-inline chart-detail-list m-b-0 text-normal">
					<li class="desc">
						<div class="icon-box">
							<img class="icon m-r-5" src="/static/assets/images/icon_card.png" alt="">
						</div>
						今日卖出卡量（张）
					</li>
				</ul>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="card-box">
				<div class="widget-chart text-center text-blue text-medium text-bold m20">
					<?php echo sprintf("%.2f",$today['transaction_money'] - $today['profit']); ?>
				</div>
				<ul class="list-inline chart-detail-list m-b-0 text-normal">
					<li class="desc">
						<div class="icon-box">
							<img class="icon m-r-5" src="/static/assets/images/icon_cost.png" alt="">
						</div>
						今日卖出成本（元）
					</li>
				</ul>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="card-box">
				<div class="widget-chart text-center text-blue text-medium text-bold m20">
					<?php echo sprintf("%.2f",$today['profit']); ?>
				</div>
				<ul class="list-inline chart-detail-list m-b-0 text-normal">
					<li class="desc">
						<div class="icon-box">
							<img class="icon m-r-5" src="/static/assets/images/icon_lirun.png" alt="">
						</div>
						今日获得利润（元）
					</li>
				</ul>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="card-box">
				<div class="widget-chart text-center text-blue text-medium text-bold m20">
					<?php echo sprintf("%.2f",$dealStat['money'] + $dealStat['last_cash']); ?>
				</div>
				<ul class="list-inline chart-detail-list m-b-0 text-normal">
					<li class="desc">
						<div class="icon-box">
							<img class="icon m-r-5" src="/static/assets/images/icon_last.png" alt="">
						</div>
						上次结算金额（元）
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="card-box">

			<h4 class="header-title m-t-0">今日支付通道分析</h4>
			<div id="morris-donut-example" style="height: 280px;"></div>
		</div>
	</div>
	<!-- end col -->
	<div class="col-lg-12">
		<div class="card-box">
			<h4 class="header-title m-t-0">近期交易统计</h4>
			<div id="morris-line-example" style="height: 280px;">
			</div>
		</div>
	</div>
	<!-- end col -->
</div>
<!-- end row -->
<div class="row">
	<div class="col-lg-4">
		<div class="card-box">
			<h4 class="header-title m-t-0  m-b-30">系统公告</h4>
			<div class="inbox-widget nicescroll">
				<?php foreach($articles as $v): ?>
				<a href="/merchant/index/notice?article_id=<?php echo $v['id']; ?>" data-toggle="modal" data-target="#myModal" >
					<div class="inbox-item">
						<p class="inbox-item-author">
							<?php echo $v['title']; ?>
							<san style=""><?php echo date("m/d H:i",$v['create_at']); ?></san>
						</p>
						<p class="inbox-item-text">
							<?php echo mb_substr(strip_tags(htmlspecialchars_decode($v['content'])),0,20,'utf-8'); ?>...
							<br>
						</p>
						<!-- <p class="inbox-item-date" >
                            <?php echo date("m/d H:i:s",$v['create_at']); ?>
                        </p> -->
					</div>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="card-box">
			<h4 class="header-title m-t-0 m-b-30">最新订单信息</h4>
			<div class="table-responsive">
				<table class="table">
				<thead>
				<tr>
					<th>
						订单号
					</th>
					<th>
						商品名称
					</th>
					<th>
						交易金额
					</th>
					<th>
						下单时间
					</th>
					<th>
						状态
					</th>
					<th>
						支付通道
					</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach($orders as $v): ?>
				<tr>
					<td>
						<?php echo $v['trade_no']; ?>
					</td>
					<td>
						<?php echo $v['goods_name']; ?>（<?php echo $v['quantity']; ?>张）
					</td>
					<td>
						<?php echo $v['total_price']; ?>
					</td>
					<td>
						<?php echo date("m/d H:i:s",$v['create_at']); ?>
					</td>
					<td>
						<?php if($v['status']==1): ?>
						<span class="label label-success"><?php echo $v['status_text']; ?></span>
						<?php else: ?>
						<span class="label label-default"><?php echo $v['status_text']; ?></span>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $v['channel']['title']; ?>
					</td>
				</tr>
				<?php endforeach; ?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- end col -->
</div>
<!-- end row -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	    <div class="modal-content">
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

                    </div> <!-- container -->
                </div> <!-- content -->

                <footer class="footer text-right">
                    <?php echo date('Y'); ?> © <?php echo sysconf('site_name'); ?>.
                </footer>

            </div>


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


            <!-- Right Sidebar -->
            <div class="side-bar right-bar">
                <a href="javascript:void(0);" class="right-bar-toggle">
                    <i class="zmdi zmdi-close-circle-o"></i>
                </a>
                <h4 class="">T`H`E`M`E`L`O`C`K`.`C`O`M`</h4>
                <div class="notification-list nicescroll">
                    <ul class="list-group list-no-border user-list">
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="avatar">
                                    <img src="/static/assets/images/users/avatar-2.jpg" alt="">
                                </div>
                                <div class="user-desc">
                                    <span class="name">Michael Zenaty</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">2 hours ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="icon bg-info">
                                    <i class="zmdi zmdi-account"></i>
                                </div>
                                <div class="user-desc">
                                    <span class="name">New Signup</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">5 hours ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="icon bg-pink">
                                    <i class="zmdi zmdi-comment"></i>
                                </div>
                                <div class="user-desc">
                                    <span class="name">New Message received</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">1 day ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item active">
                            <a href="#" class="user-list-item">
                                <div class="avatar">
                                    <img src="/static/assets/images/users/avatar-3.jpg" alt="">
                                </div>
                                <div class="user-desc">
                                    <span class="name">James Anderson</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">2 days ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item active">
                            <a href="#" class="user-list-item">
                                <div class="icon bg-warning">
                                    <i class="zmdi zmdi-settings"></i>
                                </div>
                                <div class="user-desc">
                                    <span class="name">Settings</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">1 day ago</span>
                                </div>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
            <!-- /Right-bar -->

        </div>
        <!-- END wrapper -->



        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="/static/assets/js/jquery.min.js"></script>
        <script src="/static/assets/js/bootstrap.min.js"></script>
        <script src="/static/assets/js/detect.js"></script>
        <script src="/static/assets/js/fastclick.js"></script>
        <script src="/static/assets/js/jquery.slimscroll.js"></script>
        <script src="/static/assets/js/jquery.blockUI.js"></script>
        <script src="/static/assets/js/waves.js"></script>
        <script src="/static/assets/js/jquery.nicescroll.js"></script>
        <script src="/static/assets/js/jquery.scrollTo.min.js"></script>

        <!-- Plugins Js -->
        <script src="/static/assets/plugins/switchery/switchery.min.js"></script>
        <script src="/static/assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
        <script type="text/javascript" src="/static/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
        <script type="text/javascript" src="/static/assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
        <script src="/static/assets/plugins/select2/dist/js/select2.min.js" type="text/javascript"></script>
        <script src="/static/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
        <script src="/static/assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
        <script src="/static/assets/plugins/moment/moment.js"></script>
     	<script src="/static/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
     	<script src="/static/assets/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
     	<script src="/static/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
     	<script src="/static/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="/static/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>

        <!-- Sweet Alert js -->
        <script src="/static/assets/plugins/bootstrap-sweetalert/sweet-alert.min.js"></script>
        <script src="/static/assets/pages/jquery.sweet-alert.init.js"></script>

        <!-- file uploads js -->
        <script src="/static/assets/plugins/fileuploads/js/dropify.min.js"></script>

        <script>
            //Date range picker
            $('.input-daterange-datepicker').daterangepicker({
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-default',
                cancelClass: 'btn-primary',
                locale: {
                    applyLabel: '应用',
                    cancelLabel: '取消',
                    daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
                    monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十月', '十二月'],
                    firstDay: 1
                }

            });
        </script>

        
<script>
$("#myModal").on("hidden", function() {
	$(this).removeData("modal");
});
$("#myModal").on("hidden.bs.modal", function() {
    $(this).removeData("bs.modal");
});
</script>
<!-- KNOB JS -->
<!--[if IE]>
<script type="text/javascript" src="/static/assets/plugins/jquery-knob/excanvas.js"></script>
<![endif]-->
<script src="/static/assets/plugins/jquery-knob/jquery.knob.js"></script>

<!--Morris Chart-->
<script src="/static/assets/plugins/morris/morris.min.js"></script>
<script src="/static/assets/plugins/raphael/raphael-min.js"></script>

<script>

!function($) {
"use strict";

var Dashboard1 = function() {
	this.$realData = []
};

//creates Bar chart
Dashboard1.prototype.createBarChart  = function(element, data, xkey, ykeys, labels, lineColors) {
	Morris.Bar({
		element: element,
		data: data,
		xkey: xkey,
		ykeys: ykeys,
		labels: labels,
		hideHover: 'auto',
		resize: true, //defaulted to true
		gridLineColor: '#eeeeee',
		barSizeRatio: 0.2,
		barColors: lineColors
	});
},

//creates line chart
Dashboard1.prototype.createLineChart = function(element, data, xkey, ykeys, labels, opacity, Pfillcolor, Pstockcolor, lineColors) {
	Morris.Line({
	  element: element,
	  data: data,
	  xkey: xkey,
	  ykeys: ykeys,
	  labels: labels,
	  fillOpacity: opacity,
	  pointFillColors: Pfillcolor,
	  pointStrokeColors: Pstockcolor,
	  behaveLikeLine: true,
	  gridLineColor: '#eef0f2',
	  hideHover: 'auto',
	  resize: true, //defaulted to true
	  pointSize: 0,
	  lineColors: lineColors
	});
},

//creates Donut chart
Dashboard1.prototype.createDonutChart = function(element, data, colors) {
	Morris.Donut({
		element: element,
		data: data,
		resize: true, //defaulted to true
		colors: colors
	});
},


Dashboard1.prototype.init = function() {

	//creating bar chart


	//create line chart
	var $data  = [
		<?php foreach($monthStatis as $day => $v): ?>
		{ y: '<?php echo $day; ?>', a: <?php echo $v['transaction_money']; ?>, b: <?php echo $v['profit']; ?> },
		<?php endforeach; ?>
	  ];
	this.createLineChart('morris-line-example', $data, 'y', ['a','b'], ['交易金额','利润'],['0.9'],['#ffffff'],['#999999'], ['#10c469','#188ae2']);

	//creating donut chart
	var $donutData = [
        <?php foreach($payStatis as $k => $v): ?>
            { label: '<?php echo $v['title']; ?>', value: <?php echo $v['total']; ?> },
		<?php endforeach; ?>
	];
	this.createDonutChart('morris-donut-example', $donutData, ['#5b69bc','#ff8acc', "#35b8e0", "#10c469", "188ae2"]);
},
//init
$.Dashboard1 = new Dashboard1, $.Dashboard1.Constructor = Dashboard1
}(window.jQuery),

//initializing
function($) {
"use strict";
$.Dashboard1.init();
}(window.jQuery);
</script>


        <!-- App js -->
        <script src="/static/assets/js/jquery.core.js"></script>
        <script src="/static/assets/js/jquery.app.js"></script>
        <script src="/static/app/js/layer.js"></script>
        <?php if(!(empty($common_announce) || (($common_announce instanceof \think\Collection || $common_announce instanceof \think\Paginator ) && $common_announce->isEmpty()))): ?>
        <div id="common_title" style="display: none"><?php echo $common_announce['title']; ?></div>
        <div id="common_announce" style="display: none"><div style="padding:15px"><?php echo htmlspecialchars_decode($common_announce['content']); ?></div></div>
        <script>
        layer.open({
            type: 1,
            skin: 'layui-layer-rim',
            title: $('#common_title').html(),
            area: ['680px', '400px'],
            content: $('#common_announce').html()
        });
        </script>
        <?php endif; ?>
        <script>
            if(!getCookie('freeze_money_tip')){
                setCookie('freeze_money_tip', 1, 1)
                layer.tips('今天的交易明天才可以提现哦', '#freeze_money_tip',{
                    tips: [1, '#ff5b5b'],
                });
            }
            $("#freeze_money_tip").click(function(){
                layer.alert('今天的交易明天才可以提现哦', {
                    btn: ['知道了']
                });
            });
            function setCookie(name, value, iDay)
            {
                var oDate=new Date();

                oDate.setDate(oDate.getDate()+iDay);

                document.cookie=name+'='+encodeURIComponent(value)+';expires='+oDate;
            }

            function getCookie(name)
            {
                var arr=document.cookie.split('; ');
                var i=0;
                for(i=0;i<arr.length;i++)
                {
                    //arr2->['username', 'abc']
                    var arr2=arr[i].split('=');

                    if(arr2[0]==name)
                    {
                        var getC = decodeURIComponent(arr2[1]);
                        return getC;
                    }
                }

                return '';
            }
        </script>
    </body>
</html>
