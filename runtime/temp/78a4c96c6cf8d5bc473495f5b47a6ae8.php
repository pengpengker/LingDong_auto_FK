<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:76:"F:\MaLong\item\faka\application\templates\pc\index\default\\order\query.html";i:1543624690;s:78:"F:\MaLong\item\faka\application\templates\pc\index\default\default_header.html";i:1585471324;s:78:"F:\MaLong\item\faka\application\templates\pc\index\default\default_footer.html";i:1539744034;}*/ ?>
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
    <link rel="stylesheet" href="/static/app/default/vendors/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="/static/app/default/vendors/themify-icon/themify-icons.css">
    <!--custom css-->
    <link rel="stylesheet" href="/static/app/default/css/style.css">
    <link rel="stylesheet" href="/static/app/default/css/responsive.css">
    <script src="/static/app/js/jquery-2.2.1.min.js"></script>
    <script src="/static/app/js/formvalidator_min.js"></script>
    <script src="/static/app/js/formvalidatorregex.js"></script>
    <script src="/static/app/js/layer.js"></script>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/static/app/js/clipboard.js"></script>
    <style type="text/css">
        .main_box p {
            white-space: inherit !important;
            word-wrap: break-word;
        }

        .nb {
            border: 0px;
        }

        .w10 {
            width: 10%;
        }

        .search_box {
            margin-top: 10px;
        }

        li[role="presentation"] {
            width: 33%;
        }

        li[role="presentation"] a,
        li[role="presentation"] a:hover {
            color: #ffffff;
            background-color: #29acf5;
        }

        li.active a {
            color: #0C0C0C;
        }
    </style>
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
    <section class="shop_banner">
        <div class="container">
            <div class="row">
                <div class="col-sm-7 hero_text">
                    <h2 class="hero_title">全新体验</h2>
                    <p> 加入我们，打开你的成功之门! </p>
                </div>
            </div>
        </div>
    </section>
    <section class="box-zone">
        <div class="order_form">

            <input type="hidden" id="query-type" value="<?php echo $querytype; ?>">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" <?php if ($querytype==1){?>class="active"
                    <?php }?>><a href="#cache" aria-controls="cache" role="tab" data-toggle="tab" data-index="1">浏览器缓存查卡密</a></li>
                <li role="presentation" <?php if ($querytype==2){?>class="active"
                    <?php }?>><a href="#trade" aria-controls="trade" role="tab" data-toggle="tab" data-index="2">通过订单编号查询</a></li>
                <li role="presentation" <?php if ($querytype==3){?>class="active"
                    <?php }?>><a href="#contract" aria-controls="contract" role="tab" data-toggle="tab" data-index="3">通过联系方式查询</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" id="cache" <?php if ($querytype==1){?>class="tab-pane active"
                    <?php }?> class="tab-pane">
                    <div class="search_box nb w10">
                        <button onclick="orderid_or_contact()">查 询</button>
                    </div>
                </div>
                <div role="tabpanel" id="trade" <?php if ($querytype==2){?>class="tab-pane active"
                    <?php }?> class="tab-pane">
                    <div class="search_box">
                        <!-- <form action=""> -->
                        <i class="iconfont icon-sousuo"></i>
                        <input name="orderid" type="text" value="<?php echo $trade_no; ?>" placeholder="请输入订单号" class="order_input"
                            id="orderid_input2" autocomplete="off">
                        <span style="position: absolute;right: 140px;top: 10px;"><img id="chkcode_img" src="__PUBLIC__/orderquery"
                                style="height: 43px;display:none"></span>
                        <button onclick="orderid_or_contact()">查 询</button>
                        <!-- <button type="submit">查 询</button> -->
                        <!-- </form> -->
                    </div>
                </div>
                <div role="tabpanel" id="contract" <?php if ($querytype==3){?>class="tab-pane active"
                    <?php }?> class="tab-pane">
                    <div class="search_box">
                        <!-- <form action=""> -->
                        <i class="iconfont icon-sousuo"></i>
                        <input name="orderid" type="text" value="<?php echo $trade_no; ?>" placeholder="请输入联系方式" class="order_input"
                            id="orderid_input3" autocomplete="off">
                        <span style="position: absolute;right: 140px;top: 10px;"><img id="chkcode_img" src="__PUBLIC__/orderquery"
                                style="height: 43px;display:none"></span>
                        <button onclick="orderid_or_contact()">查 询</button>
                        <!-- <button type="submit">查 询</button> -->
                        <!-- </form> -->
                    </div>
                </div>

            </div>
            <?php if($trade_no!==null): if(empty($order)): ?>
            <div style="display:block;width:216px;margin:100px auto;font-size:24px;"><?php if($is_verify): ?>不存在该订单<?php endif; ?></div>
            <?php else: ?>
            <div class="main_box">
                <h3><i class="iconfont icon-dingdan"></i> 订单日期：<?php echo date("Y-m-d H:i:s",$order['create_at']); ?></h3>
                <p>订单编号：<b><?php echo $order['trade_no']; ?></b></p>
                <p>付款方式：<b><?php echo get_paytype_name($order['paytype']); ?></b></p>
                <p>付款信息：<b>
                        <lable style="color: orangered" id="paystatus"><?php if($order['status']==1): ?>付款成功<?php else: ?>未付款<?php endif; ?></lable>
                    </b></p>
                <!-- <p>订单状态：<b>正常</b></p> -->
                <p>订单金额：<b><?php echo $order['total_price']; ?></b></p>
                <p>实付款：<b><?php echo $order['total_price']; ?></b></p>
                <p>卖家QQ：<b><a target="_blank" href="//wpa.qq.com/msgrd?v=3&amp;uin=<?php echo $order['user']['qq']; ?>&amp;site=qq&amp;menu=yes"><?php echo $order['user']['qq']; ?></a></b></p>
                <?php if(isset($canComplaint) && $canComplaint): ?>
                <p><a style="padding:5px 8px;background-color:#678cf7;color:white" href="/complaint?trade_no=<?php echo $order['trade_no']; ?>"
                        target="_blank">订单投诉</a></p>
                <?php endif; ?>
                <p style="float:none;clear:both">卡密信息：
                    <b id="tips0" style="color: rgb(251,55,55)">
                    </b>
                    <b id="cardinfo0" style="color: rgb(251,55,55)">
                    </b>
                </p>
                <div class="notice">
                    <p>
                        <font color="red">平台提示：</font>有疑问请及时联系客服QQ：<?php echo sysconf('site_info_qq'); ?>
                        ，如遇到虚假交易请在当天22点前联系客服协助处理或者点击上方投诉按钮。
                         
                    </p>
                </div>
                <div class="clear"></div>
            </div>
            <script>
                var flag = true;
                var loading = '';
                var stop = false;
                $(function () {
                    getgoods('<?php echo $order['trade_no']; ?>', '0');
                    /*<?php if($order['status'] == '0'): ?>*/
                    layer.msg('正在获取支付状态 ...', function () {
                        loading = layer.load(1, {
                            shade: [0.1, '#fff'] //0.1透明度的白色背景
                        });
                    });
                    setTimeout('oderquery(1)', 3000);
                    window.setTimeout("request_stop()", 30000);
                    /*<?php endif; ?>*/
                });
            </script>
            <?php endif; endif; ?>
            <div class="clear"></div>
        </div>



        <script>
            function getgoods(orderid, id) {
                setTimeout(function () {
                    $.getJSON('/checkgoods', {
                        orderid: orderid,
                        t: new Date().getTime(),
                        token: "<?php echo $token; ?>"
                    }, function (data) {
                        if (data) {
                            $('#cardinfo' + id).html('<br>' + data.msg);
                            if (data.status == 1) {
                                $('#tips' + id).html('(已发货/购买数：<font color="green">' + data.quantity +
                                    '</font>/' + data.quantity + ')');
                            }
                        }
                    });
                }, 1000);
            };
        </script>
        <script>
            function orderid_or_contact() {
                var queryType = $('#query-type').val();
                var input_val = $('#orderid_input' + queryType).val() || '';

                if (queryType != 1 && input_val == '') {
                    if (queryType == 2) {
                        layer.msg('订单号为空！', {
                            icon: 2,
                            time: 2000
                        });
                    } else {
                        layer.msg('联系方式为空！', {
                            icon: 2,
                            time: 2000
                        });
                    }
                } else {
                    var needChkcode = "<?php echo sysconf('order_query_chkcode'); ?>";
                    if (needChkcode == 1) {
                        // $('#chkcode_img').show().attr('src', '/chkcode');
                        chkcode(input_val, queryType);
                    } else {
                        window.location.href = '/orderquery?orderid=' + input_val + '&querytype=' + queryType;
                    }
                }
            }

            function oderquery(t) {
                if (flag == false) return false;
                var orderid = '<?php echo $trade_no; ?>';
                $.post('/pay/getOrderStatus', {
                    orderid: orderid,
                    token: "<?php echo $token; ?>"
                }, function (ret) {
                    if (ret == 1) {
                        layer.close(loading);
                        flag = false;
                        stop = true;
                        $('#paystatus').html('付款成功');
                        getgoods('<?php echo $trade_no; ?>', '0');
                    }
                });
                t = t + 1;
                setTimeout('oderquery(' + t + ')', 3000);
            }

            function request_stop() {
                if (stop == true) return false;
                flag = false;
                layer.close(loading);
                layer.alert('系统未接收到付款信息，如您已付款请联系客服处理！');
            }

            function chkcode(input_val, queryType) {
                layer.prompt({
                    title: '请输入验证码',
                    formType: 3
                }, function (chkcode) {
                    $.post('/orderquery/checkverifycode', {
                        chkcode: chkcode,
                        token:'<?php echo $token; ?>'
                    }, function (data) {
                        if (data == 'ok') {
                            layer.msg('验证码输入正确', {
                                icon: 1
                            }, function () {
                                window.location.href = '/orderquery?orderid=' + input_val +
                                    '&chkcode=' + chkcode + '&querytype=' + queryType;
                                //                        window.parent.location.reload(); //刷新父页面
                            });
                        } else {
                            layer.msg('验证码输入错误', {
                                icon: 2,
                                time: 3000
                            }, function () {
                                //                        window.parent.location.reload(); //刷新父页面
                            });
                        }

                    });
                });
                $('.layui-layer-prompt .layui-layer-content').prepend($(
                    '<img style="cursor:pointer;height: 60px;" id="chkcode_img" src="/chkcode" onclick="javascript:this.src=\'/chkcode\'+\'?time=\'+Math.random()">'
                ))
            }

            $(function () {
                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    var index = $(e.target).data('index');
                    $('#query-type').val(index);
                })
            })
            var clipboard = new ClipboardJS('.btn');
            clipboard.on('success', function(e) {
                layer.msg('复制成功！', {
                    icon: 1
                });
            });
            clipboard.on('error', function(e) {
                layer.msg('复制失败，请手动复制！', {
                    icon: 2
                });
            });
        </script>
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

    <script src="/static/plugs/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>