<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:73:"F:\MaLong\item\faka\application\templates\pc\index\t4\\user\register.html";i:1585470549;s:73:"F:\MaLong\item\faka\application\templates\pc\index\t4\default_header.html";i:1585470377;s:73:"F:\MaLong\item\faka\application\templates\pc\index\t4\default_footer.html";i:1585470377;}*/ ?>
<!DOCTYPE html>
<html lang="cn">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo sysconf('site_name'); ?><?php echo sysconf('site_subtitle'); ?> 444</title>
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
    <script src="/static/app/js/jquery-2.2.1.min.js"></script>
    <script src="/static/app/js/formvalidator_min.js"></script>
    <script src="/static/app/js/formvalidatorregex.js"></script>
    <script src="/static/app/js/layer.js"></script>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->
    <style>
        input, button, select, textarea{
        line-height: normal;
    }
    @media only screen and (min-width: 768px) {
        .layui-layer-iframe{min-height: 400px;}

    }
    @media only screen and (max-width: 768px) {
        .layui-layer-iframe{width: 80%!important;}

    }
</style>
</head>
<section data-spy="scroll" data-target="#bs-example-navbar-collapse-1" data-offset="100">
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
    <div class="col-md-2 col-lg-2">
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
    <div class="col-md-8 col-lg-8">
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
                    <h2 class="hero_title">全新发卡体验</h2>
                    <p> 加入我们，打开你的成功之门! </p>
                </div>
            </div>
        </div>
    </section>
    <section class="user-login">
        <div class="user_form">
            <div class="user_tab">
                <ul>
                    <li><a href="/login">登录</a></li>
                    <li class="actived"><a href="/register">注册</a></li>
                </ul>
            </div>
            <div class="clear"></div>
            <form method="post" action="/register/regsave" id="reg" name="reg">
                <input type="hidden" name="spread_userid" value="<?php echo \think\Request::instance()->get('user_id'); ?>">
                <div style="position: relative;">
                    <p style="color: #999;font-size: 16px;width:100%;text-align: center;position: absolute;top:-30px;">
                        提示：买家无需注册账号
                    </p>
                </div>
                <div class="user_input">
                    <i class="iconfont icon-gerenzhongxin"></i><input type="text" id="newusername" name="reginfo[username]"
                        placeholder="请输入用户名"><span id="newusernameTip" class="span_check_tip"></span>
                </div>
                <div class="user_input">
                    <i class="iconfont icon-shouji"></i><input type="text" id="newmobile" name="reginfo[mobile]"
                        placeholder="请输入11位手机号码"><span id="newmobileTip" class="span_check_tip"></span>
                </div>
                <div class="user_input">
                    <i class="iconfont icon-mima"></i><input type="password" name="reginfo[password]" placeholder="密码"
                        id="password1"><span id="password1Tip" class="span_check_tip"></span>
                </div>
                <div class="user_input">
                    <i class="iconfont icon-mima"></i><input type="password" name="reginfo[confirmpassword]"
                        placeholder="确认密码" id="password2"><span id="password2Tip" class="span_check_tip">
                </div>
                <div class="user_input">
                    <i class="iconfont icon-qq"></i><input type="text" name="reginfo[qq]" placeholder="QQ" id="newqq"><span
                        id="newqqTip" class="span_check_tip">
                </div>
                <div class="user_input">
                    <i class="iconfont icon-youxiang"></i><input type="text" name="reginfo[email]" placeholder="邮箱"
                        class="lxfs" id="newemail"><span id="newemailTip" class="span_check_tip"></span>
                </div>
                <?php if(sysconf('site_register_smscode_status')==1): ?>
                <div class="user_input">
                    <i class="iconfont icon-mima"></i><input type="text" name="reginfo[chkcode]" placeholder="验证码"
                        class="lxfs">
                </div>
                <?php if(sysconf('site_register_code_type') == 'sms'): ?>
                <button type="button" id="click_checkcode_phone" style="background: #d9ee97 !important;">获取验证码</button>
                <?php elseif(sysconf('site_register_code_type') == 'email'): ?>
                <button type="button" id="click_checkcode_email" style="background: #d9ee97 !important;">获取验证码</button>
                <?php endif; endif; if(sysconf('spread_invite_code')==1): ?>
                <div class="user_input">
                    <i class="iconfont icon-mima"></i><input type="text" name="reginfo[invite_code]" placeholder="邀请码<?php if(sysconf('is_need_invite_code')==1): ?>（必填）<?php else: ?>（选填）<?php endif; ?>"
                        class="lxfs">
                </div>
                <?php endif; ?>
                <div class="ymm-prompt form-group">
                    <p>
                        <input style="display:inline-block;vertical-align: middle;" id="check" class="checkbox" name="agree"
                            type="checkbox">
                        <span class="color1" style="font-size: 14px;">阅读并同意<a id="agreement" href="javascript:;" target="_blank"
                                style="margin-top:-1px;display:inline-block">《服务协议》</a> </span>
                    </p>
                </div>
                <button type="button" id="regBtn">注 册</button>
                <a href="/login" class="right_link">返回登录</a>
            </form>
        </div>
        <script type="text/javascript">
            $('#agreement').click(function () {
                layer.open({
                    type: 2,
                    title: '服务协议',
                    area: ['60%'],
                    anim: 2,
                    content: ['/index/index/agreement']

                });
            })
            $(function () {
                $('#click_checkcode_phone').on('click', getCode);
                $('#click_checkcode_email').on('click', getEmailCode);
            });
            var token = "<?php echo $sms_token; ?>";

            function getCode() {
                var phone = $('#newmobile').val();
                //var name=$('#newusername').val();
                var reg = /\d{11}/;
                if (phone == '' || !reg.test(phone)) {
                    alert('请填写正确的手机号码！');
                    $('#newmobile').focus();
                    return false;
                }
                layer.prompt({
                    title: '请输入验证码',
                    formType: 3
                }, function (chkcode) {
                    $('#click_checkcode_phone').off('click');
                    $.post('/register/sms', {
                        chkcode: chkcode,
                        token: token,
                        phone: phone,
                        t: new Date().getTime()
                    }, function (ret) {
                        //                        console.log(ret);
                        if (ret.code === 1) {
                            layer.closeAll();
                            layer.msg(ret.msg);
                            token = ret.data.token;
                            $('#click_checkcode_phone').html('<i class="times">80</i> 秒后重发');
                            timeC(80, '#click_checkcode_phone');
                        } else {
                            alert(ret.msg);
                            $('#click_checkcode_phone').on('click', getCode);
                        }
                    }, 'json');
                })
                $('.layui-layer-prompt .layui-layer-content').prepend($(
                    '<img style="cursor:pointer;height: 60px;" id="chkcode_img" src="/chkcode" onclick="javascript:this.src=\'/chkcode\'+\'?time=\'+Math.random()">'
                ))
            }
            //邮箱验证
            function getEmailCode() {
                var email = $('#newemail').val();
                var reg =
                    /^([\w-.]+)@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.)|(([\w-]+.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(]?)$/;
                if (email == '' || !reg.test(email)) {
                    alert('请填写正确的邮箱！');
                    $('#newemail').focus();
                    return false;
                }
                $('#click_checkcode_email').off('click');
                $.post('/register/email', {
                    email: email,
                    t: new Date().getTime()
                }, function (ret) {
                    //                        console.log(ret);
                    if (ret.code === 1) {
                        layer.closeAll();
                        layer.msg(ret.msg);
                        $('#click_checkcode_email').html('<i class="times">80</i> 秒后重发');
                        timeC(80, "#click_checkcode_email");
                    } else {
                        alert(ret.msg);
                        $('#click_checkcode_email').on('click', getEmailCode);
                    }
                }, 'json');
            }

            function timeC(t, obj) {
                if (t == 0) {
                    $(obj).on('click', getCode);
                    $(obj).text('获取验证码');
                } else {
                    t = t - 1;
                    $(obj + ' i.times').text(t);
                    setTimeout('timeC(' + t + ',"' + obj + '")', 1000);
                }
            }

            $(function () {
                $("#newusername").focus();

                $("#r2").click(function () {
                    $(".btn-code").attr("disabled", true);
                    $(".btn-code").addClass('notallowsubmit');
                });

                $("#r1").click(function () {
                    $(".btn-code").attr("disabled", false);
                    $(".btn-code").removeClass('notallowsubmit');
                });

                $.formValidator.initConfig({
                    formid: "reg",
                    onerror: function (msg) {
                        alert(msg)
                    },
                    onsuccess: function () {
                        return true;
                    }
                });
                //验证手机的
                $("#newmobile").formValidator({
                        onshow: " ",
                        onfocus: "请输入11位手机号",
                        onempty: "手机一定要填写哦",
                        oncorrect: "<font color=green>√该手机可以注册</font>"
                    })
                    .inputValidator({
                        min: 11,
                        max: 11,
                        onerror: "你填写的手机长度不正确,请确认"
                    })
                    .regexValidator({
                        regexp: "^1[3-9](\\d){9}$",
                        onerror: "你输入的不是11位手机号码"
                    })
                    .ajaxValidator({
                        type: "get",
                        url: "/register/checkinfo",
                        success: function (data) {
                            if (data == 0) {
                                return true;
                            } else {
                                return false;
                            }
                        },
                        buttons: $(".btn_zc"),
                        error: function () {
                            alert("服务器没有返回数据，可能服务器忙，请重试！");
                        },
                        onerror: "<font color=red> * 该手机号码已被使用，请更换！</font>",
                        onwait: "正在对手机号码进行合法性校验，请稍候..."
                    });
                //用户名验证
                $("#newusername").formValidator({
                        onshow: " ",
                        onfocus: "请输入正确的用户名",
                        onempty: "用户名是您登录账户的凭证，一定要填写哦",
                        oncorrect: "<font color=green>√该用户名可以注册</font>"
                    })
                    .inputValidator({
                        min: 4,
                        max: 11,
                        onerror: "你填写的用户名长度不正确,请确认"
                    })
                    .ajaxValidator({
                        type: "get",
                        url: "/register/checkuser",
                        success: function (data) {
                            if (data == 0) {
                                return true;
                            } else {
                                return false;
                            }
                        },
                        buttons: $(".btn_zc"),
                        error: function () {
                            alert("服务器没有返回数据，可能服务器忙，请重试！");
                        },
                        onerror: "<font color=red> * 该用户名已被使用，请更换！</font>",
                        onwait: "正在对用户名进行合法性校验，请稍候..."
                    });

                $("#newemail").formValidator({
                        onshow: " ",
                        onfocus: "用于找回密码、接收校验信息等操作",
                        oncorrect: "<font color=green> √ 邮箱地址填写完成</font>",
                        defaultvalue: ""
                    })
                    .inputValidator({
                        min: 6,
                        max: 100,
                        onerror: "你填写的邮箱地址长度不正确,请确认"
                    })
                    .regexValidator({
                        regexp: "^([\\w-.]+)@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.)|(([\\w-]+.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(]?)$",
                        onerror: "你填写的邮箱格式不正确"
                    })
                    .ajaxValidator({
                        type: "get",
                        url: "/register/checkinfo",
                        success: function (data) {
                            if (data == 0) {
                                return true;
                            } else {
                                return false;
                            }
                        },
                        buttons: $(".btn_zc"),
                        error: function () {
                            alert("服务器没有返回数据，可能服务器忙，请重试！");
                        },
                        onerror: "<font color=red> * 该邮箱已被使用，请更换！</font>",
                        onwait: "正在对邮箱进行合法性校验，请稍候..."
                    });
                $("#newidcard").formValidator({
                    empty: true,
                    onshow: "身份证",
                    onfocus: "身份证",
                    oncorrect: "请确认无误，注册后不能修改。",
                    onempty: "注册后不能修改"
                }).inputValidator({
                    max: 18,
                    onerror: "最多18位字符"
                });
                $("#newqq").formValidator({
                    onshow: " ",
                    onfocus: "请填写联系QQ号码",
                    oncorrect: "<font color=green> √ QQ填写完成</font>",
                    onempty: "QQ一定填写哦"
                }).inputValidator({
                    min: 5,
                    max: 12,
                    onerror: "QQ号最少5位，最多11位数字"
                }).regexValidator({
                    regexp: "qq",
                    datatype: "enum",
                    onerror: "您输入的QQ帐号不正确"
                });
                $("#password1").formValidator({
                    onshow: " ",
                    onfocus: "密码不能为空",
                    oncorrect: "<font color=green> √ 密码填写完成</font>"
                }).inputValidator({
                    min: 6,
                    empty: {
                        leftempty: false,
                        rightempty: false,
                        emptyerror: "密码两边不能有空符号"
                    },
                    onerror: "密码不能为空,请确认"
                });
                $("#password2").formValidator({
                    onshow: " ",
                    onfocus: "两次密码必须一致哦",
                    oncorrect: "<font color=green> √ 密码一致</font>"
                }).inputValidator({
                    min: 6,
                    empty: {
                        leftempty: false,
                        rightempty: false,
                        emptyerror: "重复密码两边不能有空符号"
                    },
                    onerror: "重复密码不能为空,请确认"
                }).compareValidator({
                    desid: "password1",
                    operateor: "=",
                    onerror: "2次密码不一致,请确认"
                });
            });
            $('#regBtn').click(function () {
                if ($(".onError").length > 0) {
                    layer.msg($(".onError").first().text());
                    return false;
                }
                if (false === $("#check").is(':checked')) {
                    layer.msg('请先同意服务协议');
                    return false;
                }
                var loading = '';
                $.ajax({
                    type: 'post',
                    url: '/index/user/doRegister',
                    dataType: "json",
                    data: $("form").serialize(),
                    beforeSend: function (xhr) {
                        loading = layer.load()
                    },
                    success: function (res) {
                        layer.close(loading);
                        if (res.code == 1) {
                            layer.alert(res.msg, function () {
                                window.location.href = '/merchant';
                            });
                        } else {
                            layer.msg(res.msg);
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        layer.close(loading);
                        layer.msg('连接错误');
                    }
                });
            })
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



    <script src="/static/app/js/main.js"></script>
    <script src="/static/app/js/main_mobile.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/static/plugs/bootstrap/js/bootstrap.min.js"></script>
    <script src="/static/app/default/vendors/owl-carousel/owl.carousel.min.js"></script>
    <script src="/static/app/default/vendors/owl-carousel/owl.carousel.min.js"></script>
    <script src="/static/app/default/js/wow.js"></script>
    <script src="/static/app/default/js/custom.js"></script>
    </body>

</html>