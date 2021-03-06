<?php

namespace app\index\controller;

use app\common\model\Channel as ChannelModel;
use app\common\model\Goods;
use app\common\model\Goods as GoodsModel;
use app\common\model\GoodsCoupon as CouponModel;
use app\common\model\Order as OrderModel;
use app\common\model\User as UserModel;
use app\common\Pay as PayAPI;
use think\Db;
use think\Request;
use think\Exception;

class Pay extends Base {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 部分渠道需要请求上游获得授权 openid 跟 appid 才能调起微信支付
     */
    public function auth() {
        $trade_no = input('trade_no/s', '');
        $order    = OrderModel::get(['trade_no' => $trade_no]);

        if (!$order) {
            return '不存在该订单！';
        }

        if ($order->status == 1) {
            return '该订单已完成！';
        }

        $channel = ChannelModel::get(['id' => $order['channel_id'], 'status' => 1]);
        if (!$channel) {
            return '该支付产品没有可用的支付渠道！';
        }

        switch ($channel['code']) {
            case 'pafbWxGzh': //平安付微信公众号
            case 'Wsyh': //网商银行
                // 渠道账户
                $account = $channel->accounts()->where(['id' => $order->channel_account_id])->find();

                if (!$account) {
                    return '不存在支付渠道：' . $channel->title . '的账号！';
                }

                // 支付下单
                $openid    = input('open_id/s');
                $subOpenid = input('sub_open_id/s');
                $PayAPI    = PayAPI::load($channel, $account);
                $res       = $PayAPI->realOrder($openid, $subOpenid, $order->trade_no, '投诉QQ：' . sysconf('site_info_qq') . ' 订单：' . $order->trade_no, round($order->total_price, 2));

                if ($res === false) {
                    die($PayAPI->getError());
                }

                if ($channel['code'] == 'Wsyh') {
                    //网商银行可能是支付宝，需要特殊处理
                }

                //微信原生支付
                $this->assign('json', $res->pay_url);
                $this->assign('url', url('/orderquery', ['orderid' => $order->trade_no]));
                return $this->fetch('wx_native');

                break;
        }

    }

    // 付款页
    public function payment() {
        $token = session('token');
        if (!$token) {
            $token = md5(time() . md5(time()) . time()) . time();
            session('token', $token);
        }
        $this->assign('token', $token);

        $trade_no = input('trade_no/s', '');
        $order    = OrderModel::get(['trade_no' => $trade_no]);
        if (!$order) {
            return '不存在该订单！';
        }
        if ($order->status == 1) {
            return '该订单已完成！';
        }
        $channel = ChannelModel::get(['id' => $order['channel_id'], 'status' => 1]);
        if (!$channel) {
            return '该支付产品没有可用的支付渠道！';
        }
		//判断是否是代理商品
		if(!empty($order->dj_order_id)){
			//修改金额
			$order->total_price = round(OrderModel::get(['trade_no' => $order->dj_order_id])['total_price']+$order->total_price,3);
		}
		//判断商品是否存在
		if(!GoodsModel::get(['id' => $order->goods_id,'status' => 1])){
			return '该商户产品不存在，请刷新重试！';
		}
        switch ($order->pay_content_type) {
            case 2:
                // 跳转链接
                if (in_array($order->paytype, ['64', '54', '98'])) {
                    if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
                        //如果是微信公众号支付要重新走一次支付渠道下单

                        // 渠道账户
                        $account = $channel->accounts()->where(['id' => $order->channel_account_id])->find();

                        if (!$account) {
                            return '不存在支付渠道：' . $channel->title . '的账号！';
                        }

                        // 支付下单
                        $PayAPI = PayAPI::load($channel, $account);
                        $res    = $PayAPI->order($order->trade_no, '投诉QQ：' . sysconf('site_info_qq') . ' 订单：' . $order->trade_no, round($order->total_price, 2));

                        if ($res === false) {
                            die($PayAPI->getError());
                        }

                        if ($order->paytype == '98') {
                            //汉口这个坑可能会返回跳转链接
                            if ($res->content_type == 2) {
                                header('location:' . $res->pay_url);
                                exit;
                            }
                        }

                        //微信原生支付
                        $this->assign('json', $res->pay_url);
                        $this->assign('url', url('/orderquery', ['orderid' => $order->trade_no]));
                        return $this->fetch('wx_native');
                    }
                }

                if ($order->paytype == 112) {
                    // 唤起支付
                    if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
                        //如果是微信公众号支付要重新走一次支付渠道下单
                        // 渠道账户
                        $account = $channel->accounts()->where(['id' => $order->channel_account_id])->find();

                        if (!$account) {
                            return '不存在支付渠道：' . $channel->title . '的账号！';
                        }

                        // 支付下单
                        $PayAPI = PayAPI::load($channel, $account);
                        $res    = $PayAPI->order($order->trade_no, '投诉QQ：' . sysconf('site_info_qq') . ' 订单：' . $order->trade_no, round($order->total_price, 2));

                        if ($res === false) {
                            die($PayAPI->getError());
                        }
                        $order->pay_url = $res->pay_url;

                        return htmlspecialchars_decode($order->pay_url);
                    }

                }

                if ($order->paytype == 67) {
                    if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
                        //如果是微信公众号支付要重新走一次支付渠道下单

                        // 渠道账户
                        $account = $channel->accounts()->where(['id' => $order->channel_account_id])->find();

                        if (!$account) {
                            return '不存在支付渠道：' . $channel->title . '的账号！';
                        }

                        // 支付下单
                        $PayAPI = PayAPI::load($channel, $account);
                        $res    = $PayAPI->order($order->trade_no, '投诉QQ：' . sysconf('site_info_qq') . ' 订单：' . $order->trade_no, round($order->total_price, 2));

                        if ($res === false) {
                            die($PayAPI->getError());
                        }

                        header('location:' . $res->pay_url);
                        exit;
                    }
                }
                header('location:' . $order->pay_url);
                exit;

                break;
            case 3:
                // 唤起支付

                //如果是掌灵付的微信公众号支付，每次打开都重算一次 pay_url;
                if ($order->paytype == 59) {
                    // 渠道账户
                    $account = $channel->accounts()->where(['id' => $order->channel_account_id])->find();

                    if (!$account) {
                        return '不存在支付渠道：' . $channel->title . '的账号！';
                    }

                    // 支付下单
                    $PayAPI = PayAPI::load($channel, $account);
                    $res    = $PayAPI->order($order->trade_no, '投诉QQ：' . sysconf('site_info_qq') . ' 订单：' . $order->trade_no, round($order->total_price, 2));

                    if ($res === false) {
                        die($PayAPI->getError());
                    }
                    $order->pay_url = $res->pay_url;
                }

                return htmlspecialchars_decode($order->pay_url);

                break;
            case 4:
                //既可以二维码扫，又可以手机直接浏览器调起对应的应用进行支付
                if ($this->request->isMobile()) {
                    //手机浏览，直接跳转
                    header('location:' . $order->pay_url);
                    exit;
                }
                //否则走二维码渲染流程

                break;
            case 5:
                //微信原生支付
                $this->assign('json', $order->pay_url);
                $this->assign('url', url('/orderquery', ['orderid' => $order->trade_no]));
                return $this->fetch('wx_native');

                break;
            case 6:
                //优畅上海 qqwap 专用
                $this->assign('jump', $order->pay_url);
                $this->assign('channel', $channel);
                $this->assign('order', $order);
                return $this->fetch('ycsh_qqwap');
                break;
            case 7:
                //在新url中提交支付form
                $url = url('index/pay/wx_jspay_page') . '?trade_no=' . $order->trade_no . '&url=' .
                base64_encode(Request::instance()->domain() . '/index/pay/submit_order_form?trade_no=' . $order->trade_no);
                header('location:' . $url);
                exit;
                break;
            case 8:
            	//QQ jsapi支付页面
            	$this->assign('prepay_id', $order->pay_url);
            	return $this->fetch('qq_native');
            	break;
        }
        //是否免签渠道
        if ($channel['code'] == 'QPayAli' || $channel['code'] == 'QPayWx') {
            $is_qpay = 1;
        } else {
            $is_qpay = 0;
        }
        $this->assign('channel', $channel);
        $this->assign('is_qpay', $is_qpay);
        $this->assign('order', $order);
        return $this->fetch();
    }

    // 查询订单状态
    public function getOrderStatus() {
        $token = input('token/s', '');
        if ($token != session('token')) {
            return json(['msg' => '非法请求']);
        }

        $trade_no = input('orderid/s', '');
        $order    = OrderModel::get(['trade_no' => $trade_no]);
        if (!$order) {
            return '不存在该订单！';
        }
        return $order->status;
    }

    // 下单页
    public function order() {
        $token = session('token');
        if (!$token) {
            $token = md5(time() . md5(time()) . time()) . time();
            session('token', $token);
        }
        $this->assign('token', $token);

        ////////////////// 基础项 //////////////////
        //是否对接
        $is_duijie_shop_bool = false;
        //商品总结--支付传入
        $pirce = 0;
        // 商家
        $user = UserModel::get(['id' => input('userid/d', 0), 'status' => 1]);
        if (!$user) {
            return '不存在该商家！';
        }
        ////////////////// 业务项 //////////////////
        $goods = GoodsModel::get(['id' => input('goodid/d', 0), 'user_id' => $user->id, 'status' => 1,'is_freeze' => 0]);
        if (!$goods) {
            return '不存在该商品！';
        }
        if(!empty($goods->duijie_id)){
            $is_duijie_shop_bool = true;
            //定义对接下级的商品和用户信息
            $xj_goods_info = $goods;
            $xj_users_info = $user;
            $goods = GoodsModel::get(['id' => $goods->duijie_id, 'status' => 1,'is_duijie' => 1,'is_freeze' => 0]);
            if (!$goods) {
                return '商品不存在或下架';
            }
            if($xj_goods_info->price < $goods->duijie_smilepic){
                $xj_goods_info->price = $goods->duijie_smilepic;
            }
            $user = UserModel::get(['id' => $goods->user_id, 'status' => 1]);
            if (!$user) {
                return '对接不存在该商家！';
            }
            
            //设置该订单为对接，不可查，不可显示
            $data['dj_is_see'] = 1;
        }
        // 联系方式
        $data['contact'] = input('contact/s', '');
        if (empty($data['contact'])) {
            return '请填写联系方式！';
        }
        //订单商户id
        $data['user_id'] = $user->id;
        // 单号
        $data['trade_no']  = generate_trade_no('T', $data['user_id']);
        $data['create_at'] = $_SERVER['REQUEST_TIME'];
        $data['create_ip'] = $this->request->ip();
        // 商品ID
        $data['goods_id'] = $goods->id;
        // 商品名
        $data['goods_name'] = $goods->name;
        // 商品单价
        if($is_duijie_shop_bool){
        	$goods->price = $goods->duijie_price;
        	$data['goods_price'] = $goods->duijie_price;
        }else{
        	$data['goods_price'] = $goods->price;
        }
        // 成本价
        $data['goods_cost_price'] = $goods->cost_price;
        // 商品数量
        $quantity         = input('quantity/d', 0);
        $data['quantity'] = $quantity;
        if ($goods->cards_stock_count < $quantity) { // 检测库存
            return '库存不足！';
        }
        
        if(!$is_duijie_shop_bool){
			// 是否符合起够数量
	        if ($goods->limit_quantity > $quantity) {
	            return '起够数量不能少于' . $goods->limit_quantity . '张';
	        }
	        // 批发价
	        if ($goods->wholesale_discount == 1) { //判断是否符合优惠条件
	            $data['goods_price'] = $this->get_discount_price($goods, $quantity);
	        }
		}
        //对接商品不使用上级优惠券
		if($is_duijie_shop_bool){
			$data['coupon_id']    = 0;
            $data['coupon_price'] = 0;
		}

        // 优惠券
        $data['coupon_type'] = input('is_coupon/d', '');
        if ($data['coupon_type'] == 1) {
            $cate_id     = input('cateid/d', 0);
            $coupon_code = input('couponcode/s', '');
            // 检测优惠券是否可用
            $coupon = CouponModel::get([
                'user_id' => $user->id,
                'cate_id' => ['in', [0, $cate_id]],
                'code'    => $coupon_code,
            ]);
            if (!$coupon || $coupon->status != 1 || $coupon->expire_at < $_SERVER['REQUEST_TIME']) {
                return '不存在该优惠券或已过期！';
            }
            $coupon->status = 2; // 更新为已使用
            $coupon->save();
            $data['coupon_id'] = $coupon->id;
            if ($coupon->type == 100) { // 按百分比
                $data['coupon_price'] = $data['goods_price'] * $data['quantity'] * $coupon->amount / 100;
            } else { // 按元
                $data['coupon_price'] = $coupon->amount;
            }
        } else {
            $data['coupon_id']    = 0;
            $data['coupon_price'] = 0;
        }

		//对接商品不使用上级优惠券
		if(!empty($goods->duijie_id)){
			$data['coupon_id']    = 0;
            $data['coupon_price'] = 0;
		}
		
        ////////////////// 功能项 //////////////////
        // 售出通知
        $data['sold_notify'] = (int)$goods->sold_notify;
        // 提卡密码
        $data['take_card_type'] = (int)$goods->take_card_type;
        if ($goods->take_card_type == 0) { // 不用密码
            $data['take_card_password'] = '';
        } elseif ($goods->take_card_type == 2) { // 选填
            $data['take_card_password'] = input('pwdforsearch2/s', '');
        } else { // 必填
            $pwdforsearch1 = input('pwdforsearch1/s', '');
            if ($pwdforsearch1 === '') {
                return '请输入取卡密码';
            }
            $data['take_card_password'] = $pwdforsearch1;
        }
        // 邮件通知
        $data['email_notify'] = input('isemail/d', 0);
        $data['email']        = input('email/s', '');
        // 短信
        $data['sms_notify'] = input('is_rev_sms/d', 0);
        // 短信付费方
        $data['sms_payer'] = $goods->sms_payer; // 0买家承担 1商户承担
        // 短信费
        if ($data['sms_notify'] == 0 || $data['sms_payer'] == 1) {
            $data['sms_price'] = 0;
        } else {
            $data['sms_price'] = get_sms_cost();
        }

        ////////////////// 计算总价 //////////////////
        // 商品总价（单价*数量-优惠额）
        $data['total_product_price'] = round($data['goods_price'] * $data['quantity'] - $data['coupon_price'], 2);
        // 总价（商品总价+短信费）
        $data['total_price'] = $data['total_product_price'] + $data['sms_price'];
        // 总成本价
        $data['total_cost_price'] = round($data['goods_cost_price'] * $data['quantity'], 2);
        
        // echo '<pre>';
        // var_export($data);

        ////////////////// 支付下单项 //////////////////
        // 支付渠道
        $channel = ChannelModel::get(['id' => input('pid/d', 0), 'status' => 1]);
        if (!$channel) {
            return '该支付产品没有可用的支付渠道！';
        }
        //检查是否设置了分组费率
        $rate_group_user = Db::name('rate_group_user')->where('user_id', $user->id)->find();
        if(!empty($rate_group_user)) {
            $rate_group_rule = Db::name('rate_group_rule')
                ->where(['group_id' => $rate_group_user['group_id'], 'channel_id' => $channel->id, 'status' => 1])
                ->find();
            if(empty($rate_group_rule)) {
                $this->assign('error', '该商户未启用此支付渠道！');
                return $this->fetch();
            }
        }

        // 渠道账户
        $accounts = $channel->accounts()->where(['channel_id' => $channel->id, 'status' => 1])->select();
        if (empty($accounts)) {
            $this->assign('error', '不存在支付渠道：' . $channel->title . '的账号！');
            return $this->fetch();
        }
        $account = $accounts[0];
        if (count($accounts) > 1) {
            $account = $accounts[intval(floor(rand(0, count($accounts) - 1)))];
        }

        if (!$account) {
            $this->assign('error', '不存在支付渠道：' . $channel->title . '的账号！');
            return $this->fetch();
        }
        //银行支付
        if (input('bankid') != '') {
            $channel->bankid = input('bankid');
        }
        $data['paytype']            = $channel->paytype;
        $data['channel_id']         = $channel->id;
        $data['channel_account_id'] = $account->id;

        $smsPrice = get_sms_cost();
        if (round($data['total_price'], 2) < $smsPrice && $data['sms_payer'] == 1 && $data['sms_notify'] == 1) {
            // 开启了短信通知，商家承担费用
            $this->assign('error', '订单金额不足以扣除短信费');
            return $this->fetch();
        }

        ////////////////// 费率结算项 //////////////////
        // 手续费
        if($is_duijie_shop_bool){
            $data['rate'] = get_user_rate($user->id, $channel->id);
            $data['fee']  = 0;
            $fee_payer = Db::name('user')->where('id', $data['user_id'])->value('fee_payer');
            if (0 == $fee_payer) {
                // 获取系统配置
                $fee_payer = sysconf('fee_payer');
            }
            $data['fee_payer'] = $fee_payer;
            //买家承担费率
            if ($fee_payer == 2) {
                $data['total_price'] = bcadd($data['total_price'], $data['fee'], 4);
            }
            
            //消除短信费用
            $data['sms_notify'] = 0;
        }else{
            $data['rate'] = get_user_rate($user->id, $channel->id);
            $data['fee']  = round($data['rate'] * $data['total_product_price'], 3);
            if ($data['fee'] < sysconf('transaction_min_fee')) {
                $data['fee'] = sysconf('transaction_min_fee');
            }

            $fee_payer = Db::name('user')->where('id', $data['user_id'])->value('fee_payer');
            if (0 == $fee_payer) {
                // 获取系统配置
                $fee_payer = sysconf('fee_payer');
            }
            $data['fee_payer'] = $fee_payer;

            //买家承担费率
            if ($fee_payer == 2) {
                $data['total_price'] = bcadd($data['total_price'], $data['fee'], 4);
            }
            
            // 创建订单前的检查
	        // 检查商户余额是否足够抵扣短信费
	        if (($data['sms_payer'] == 1) && ($data['total_product_price'] < $data['sms_price'])) {
	            if ($user->money < $data['sms_price']) {
	                return '商户余额不足以支付短信费用，请联系商家';
	            }
	        }
        }

        // 代理手续费
        $data['agent_rate'] = 0;
        $data['agent_fee']  = 0;
        $data['status']     = 0;
        
        ////////////////// 费率结算项 //////////////////

        // 获取当前商家的结算周期
        $data['settlement_type'] = $user->settlement_type;
        // 未指定结算周期，跟随系统的结算周期，默认是 T1
        if ($data['settlement_type'] == -1) {
            $data['settlement_type'] = sysconf('settlement_type');
        }
        
        //  订单金额定义
        $pirce = $data['total_price'];

        //对接商户订单创建
        if($is_duijie_shop_bool){
            $datas['contact'] = $data['contact'];
            $datas['user_id'] = $xj_users_info->id;
            // 单号
            $datas['trade_no']  = generate_trade_no('T', $datas['user_id']);
            $datas['create_at'] = $data['create_at'];
            $datas['create_ip'] = $data['create_ip'];
            // 商品ID
            $datas['goods_id'] = $xj_goods_info->id;
            // 商品名
            $datas['goods_name'] = $xj_goods_info->name;
            // 商品单价 总价格-上级价格
            $datas['goods_price'] = $xj_goods_info->price;
            // 成本价
            $datas['goods_cost_price'] = $xj_goods_info->cost_price;
            //商品数量
            $datas['quantity'] = $data['quantity'];
            // 售出通知
            $datas['sold_notify'] = (int)$goods->sold_notify;
            // 提卡密码
            $datas['take_card_type'] = (int)$goods->take_card_type;
            // 邮件通知
            $datas['email_notify'] = $data['email_notify'];
            $datas['email']        = $data['email'];
            // 短信
            $datas['sms_notify'] = input('is_rev_sms/d', 0);
            // 短信付费方
            $datas['sms_payer'] = $goods->sms_payer; // 0买家承担 1商户承担
            // 短信费
            $datas['sms_price'] = $data['sms_price'];
            //清空上级短信费
            $data['sms_price'] = 0;
			
			//算批发价
			if ($xj_goods_info->wholesale_discount == 1) { //判断是否符合优惠条件
				//判断设置批发价是否低于上级设置最低加价
				$price_cache_pf = $this->get_discount_price($xj_goods_info, $quantity);
				if($price_cache_pf < $goods->duijie_smilepic){
					$price_cache_pf = $goods->duijie_smilepic;
				}
	            $datas['goods_price'] = $price_cache_pf;
	        }
			
            ////////////////// 计算总价 //////////////////
            // 商品总价（单价*数量）
            $datas['total_product_price'] = round($datas['goods_price'] * $datas['quantity'], 3);
            //判断此处是否为负数 -- 防止恶搞对接
            if($datas['total_product_price'] < 0){
            	$datas['total_product_price'] = 0;
            }
            // 总价（商品总价+短信费）
            $datas['total_price'] = $datas['total_product_price'] + $datas['sms_price'];
            // 总成本价
            $datas['total_cost_price'] = round($datas['goods_cost_price'] * $datas['quantity'], 3);
            
            ////////////////// 支付下单项 //////////////////
            // 支付渠道
            $channel = ChannelModel::get(['id' => input('pid/d', 0), 'status' => 1]);
            if (!$channel) {
                return '该支付产品没有可用的支付渠道！';
            }
            //检查是否设置了分组费率
            $rate_group_user = Db::name('rate_group_user')->where('user_id', $xj_users_info->id)->find();
            if(!empty($rate_group_user)) {
                $rate_group_rule = Db::name('rate_group_rule')
                    ->where(['group_id' => $rate_group_user['group_id'], 'channel_id' => $channel->id, 'status' => 1])
                    ->find();
                if(empty($rate_group_rule)) {
                    $this->assign('error', '该商户未启用此支付渠道！');
                    return $this->fetch();
                }
            }
            // 渠道账户
            $accounts = $channel->accounts()->where(['channel_id' => $channel->id, 'status' => 1])->select();
            if (empty($accounts)) {
                $this->assign('error', '不存在支付渠道：' . $channel->title . '的账号！');
                return $this->fetch();
            }
            $account = $accounts[0];
            if (count($accounts) > 1) {
                $account = $accounts[intval(floor(rand(0, count($accounts) - 1)))];
            }
            if (!$account) {
                $this->assign('error', '不存在支付渠道：' . $channel->title . '的账号！');
                return $this->fetch();
            }
            //银行支付
            if (input('bankid') != '') {
                $channel->bankid = input('bankid');
            }
            $datas['paytype']            = $channel->paytype;
            $datas['channel_id']         = $channel->id;
            $datas['channel_account_id'] = $account->id;
            if (round($datas['total_price'], 2) < $smsPrice && $datas['sms_payer'] == 1 && $datas['sms_notify'] == 1) {
                // 开启了短信通知，商家承担费用
                $this->assign('error', '订单金额不足以扣除短信费');
                return $this->fetch();
            }
            ////////////////// 费率结算项 //////////////////
            // 手续费
            $datas['rate'] = get_user_rate($xj_users_info->id, $channel->id);
            $datas['fee']  = round($datas['rate'] * ($pirce+$datas['total_product_price']), 3);
            if ($datas['fee'] < sysconf('transaction_min_fee')) {
                $datas['fee'] = sysconf('transaction_min_fee');
            }
            $fee_payer = Db::name('user')->where('id', $datas['user_id'])->value('fee_payer');
            if (0 == $fee_payer) {
                // 获取系统配置
                $fee_payer = sysconf('fee_payer');
            }
            $datas['fee_payer'] = $fee_payer;
            //买家承担费率
            if ($fee_payer == 2) {
                $datas['total_price'] = bcadd($datas['total_price'], $datas['fee'], 4);
            }
            // 代理手续费
            $datas['agent_rate'] = 0;
            $datas['agent_fee']  = 0;
            $datas['status']     = 0;
            // 创建订单前的检查
            // 检查商户余额是否足够抵扣短信费
            if (($datas['sms_payer'] == 1) && ($datas['total_product_price'] < $datas['sms_price'])) {
                if ($user->money < $datas['sms_price']) {
                    return '商户余额不足以支付短信费用，请联系商家';
                }
            }
            ////////////////// 费率结算项 //////////////////

            // 获取当前商家的结算周期
            $datas['settlement_type'] = $xj_users_info->settlement_type;
            // 未指定结算周期，跟随系统的结算周期，默认是 T1
            if ($datas['settlement_type'] == -1) {
                $datas['settlement_type'] = sysconf('settlement_type');
            }
            
            //此处算总价
            $pirce = round($pirce,2)+round($datas['total_price'],2);

            //设置data数据中的对接标识
            $datas['dj_order_id'] = $data['trade_no'];
        }
        if($is_duijie_shop_bool){
            // 支付下单
            $PayAPI = PayAPI::load($channel, $account);
            $res    = $PayAPI->order($datas['trade_no'], '投诉QQ：' . sysconf('site_info_qq') . ' 订单：' . $datas['trade_no'], round($pirce, 2));
            if ($res === false) {
                $this->assign('error', $PayAPI->getError());
                return $this->fetch();
            }
            // 支付地址
            $datas['pay_url'] = $res->pay_url;
            //  支付地址类型  1：二维码 2：跳转链接 3：表单 4: 二维码或跳转链接 5：微信原生
            $datas['pay_content_type'] = isset($res->content_type) ? $res->content_type : 1;
        }else{
            // 支付下单
            $PayAPI = PayAPI::load($channel, $account);
            $res    = $PayAPI->order($data['trade_no'], '投诉QQ：' . sysconf('site_info_qq') . ' 订单：' . $data['trade_no'], round($pirce, 2));
            if ($res === false) {
                $this->assign('error', $PayAPI->getError());
                return $this->fetch();
            }
            // 支付地址
            $data['pay_url'] = $res->pay_url;
            //  支付地址类型  1：二维码 2：跳转链接 3：表单 4: 二维码或跳转链接 5：微信原生
            $data['pay_content_type'] = isset($res->content_type) ? $res->content_type : 1;
        }

        // 创建订单
        $order = OrderModel::create($data);
        if (!$order) {
            return '订单创建失败，请重试！ -1';
        }
        $order->total_price = $pirce; //页面显示价格 -- 最终总价
        if($is_duijie_shop_bool){
            $order->goods_name = $datas['goods_name'];
            $order->trade_no = $datas['trade_no'];
            if(!OrderModel::create($datas)){
                return '订单创建失败，请重试！ -2';
            }
        }
        
        if($is_duijie_shop_bool){
            session('last_order_trade_no', $datas['trade_no']);
        }else{
            session('last_order_trade_no', $data['trade_no']);
        }
        $order->total_price = $pirce;
        $this->assign('order', $order);
        $this->assign('channel', $channel);
        $this->assign('isMobile', $this->request->isMobile());
        return $this->fetch();
    }

    /**
     * 获取参数
     */
    private function getParams() {
        // 渠道
        $channel = input('channel/s', '');
        $params  = [];
        switch ($channel) {
        	case 'UnionPay': //银联云闪付
            case 'JyWxPay':
            case 'JyWxGzhPay':
            case 'PayapiAli':
            case 'PayapiWx':
            case 'YiyunAliScan'://易云支付
            case 'YiyunAliWap':
            case 'YiyunWxGzh':
            case 'YiyunWxScan':
            case 'YiyunWxWap':
            case 'HenglongAliScan'://恒隆支付
            case 'HenglongAliWap':
            case 'HenglongWxScan':
            case 'HenglongWxGzh':
            case 'ShenduAliScan'://深度支付
            case 'ShenduWxScan':
            case 'ShenduWxGzh':
            case 'ShenduAliJspay':
            case 'Juhezhifu'://聚合支付
                $params = input('');
                break;
            case 'WsyhAliScan':
            case 'WsyhWxScan':
                $xml = file_get_contents('php://input');
                if (!empty($xml)) {
                    libxml_disable_entity_loader(true);
                    $params = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
                }
                if (empty($params)) {
                    $params = input('');
                }
                break;
            case 'PafbAliScan':
            case 'PafbWxScan':
            case 'PafbWxWap':
            case 'PafbAliWap':
                file_put_contents(LOG_PATH . 'alipay_wap_test.txt', "【" . date('Y-m-d H:i:s') . "】\r\n" . $_POST . "\r\n\r\n", FILE_APPEND);
                break;
            case 'Hkyh':
                //汉口银行
                $params = json_decode(file_get_contents("php://input"), true);
                break;
            case 'NZFAliqrcode': // 支付宝扫码
                parse_str(file_get_contents('php://input'), $params);
                break;
            case 'AlipayScan': // 支付宝扫码
            case 'AlipayWap': // 支付宝WAP
            	$params = $_POST;
            	break;
            case 'WxpayScan': // 支付宝WAP
                $xml = file_get_contents('php://input');
                libxml_disable_entity_loader(true);
                $params = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
                break;
            case 'QPayAli':
                parse_str(file_get_contents('php://input'), $params);
                break;
            case 'QPayWx':
                parse_str(file_get_contents('php://input'), $params);
                break;
            case 'DzWxScan':
            case 'DzAliScan':
            case 'DzQqScan':
            case 'DzWxGzh':
            case 'DzJdScan': //点缀支京东扫码
            case 'DzWxH5': //点缀支付微信H5扫码
            case 'DzAliToPay': //点缀支付支付宝即时到账
                parse_str(file_get_contents('php://input'), $params);
                break;
            case 'Lh15173PcPay':
            case 'Lh15173WapPay':
            case 'Lh15173QqPay':
                $params = input('');
                break;
            case 'LkAlipayScanPay':
            case 'LkWxH5Pay':
            case 'LkWxPay':
            case 'LkQQPay':
            case 'LkBankPay':
                $params = input('');
                break;
            case 'KjWxSanPay':
            case 'KjWxH5Pay':
            case 'KjAlipayScanPay':
            case 'KjAlipayH5Pay':
                parse_str(file_get_contents('php://input'), $params);
                break;
            case 'Ka12QqNative':
            case 'Ka12QqWap':
            case 'Ka12QuickBank':
            case 'Ka12QuickWap':
            case 'Ka12AlipayScan':
            case 'Ka12AlipayWap':
            case 'Ka12WxScan':
            case 'Ka12WxWap':
                parse_str(file_get_contents('php://input'), $params);
                break;
            case 'CodePayWxScan':
            case 'CodePayAliScan':
            case 'CodePayQqScan':
                parse_str(file_get_contents('php://input'), $params);
                break;
            case 'WxpayH5':
            case 'WxJsApi':
            	$xml = file_get_contents('php://input');
            	//判断是不是xml
            	$xml_parser = xml_parser_create(); 
            	if(!xml_parse($xml_parser,$xml,true)){ 
            		xml_parser_free($xml_parser); 
            		//不是xml,处理json
            		$params = json_decode($xml,true);
            	}else{
            		libxml_disable_entity_loader(true);
                	$params = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
            	}
                break;
            case 'QqNative':
            case 'SwiftAliScan':
            case 'SwiftAliWap':
            case 'SwiftWxScan':
            case 'SwiftJd':
            case 'SwiftWxWap':
            case 'SwiftWxGzh':
                $params = \Util\Qpay\QpayMchUtil::xmlToArray(file_get_contents('php://input'));
                break;
            case 'FnAliScan':
            case 'FnAliWap':
            case 'FnQqScan':
            case 'FnWxJspay':
            case 'FnWxScan':
                $params = input('');
                break;
            case 'ZlfWxScan':
            case 'ZlfQqScan':
            case 'ZlfAliScan':
            case 'ZlfWxJspay':
            case 'ZlfWxH5':
            case 'ZlfJdScan':
                $params = json_decode(file_get_contents("php://input"), true);
                break;
            case 'QgjfAlipayScan':
            case 'QgjfAlipayWap':
            case 'QgjfQqNative':
            case 'QgjfWxGzh':
            case 'QgjfWxScan':
            case 'QgjfWxWap':
            case 'TaomiAlipayScan':
            case 'TaomiAlipayWap':
            case 'TaomiQqNative':
            case 'TaomiWxGzh':
            case 'TaomiWxScan':
            case 'TaomiWxWap':
                parse_str(file_get_contents('php://input'), $params);
                break;
            case 'PYFalipay':
            case 'PYFqqpay':
            case 'PYFwxpay':
                //拼云付
                $params = json_decode(file_get_contents("php://input"), true);
                break;
            case 'YcshWxGzh':
            case 'YcshWxH5':
            case 'YcshWxScan':
            case 'YcshAliScan':
            case 'YcshAliWap':
            case 'YcshQqScan':
            case 'YcshQqWap':
                //优畅上海
                libxml_disable_entity_loader(true);
                $params = json_decode(json_encode(simplexml_load_string(file_get_contents("php://input"), 'SimpleXMLElement', LIBXML_NOCDATA)), true);
                break;
            case 'TpayWxpay':
            case 'TpayAlipay':
                //TPay
                $params = json_decode(file_get_contents("php://input"), true);
                break;
            case 'HnPayAliScan':
            case 'HnPayQqScan':
            case 'HnPayWxGzh':
            case 'HnPayWxH5':
            case 'HnPayWxScan':
                //海鸟
                parse_str(file_get_contents('php://input'), $params);
                break;
            case 'WmskWxScan':
            case 'WmskAliScan':
            case 'WmskQqScan':
            case 'WmskQqWap':
            case 'WmskWxWap':
            case 'WmskAliWap':
                //完美数卡
                $params = input('');
                break;
            case 'YunQq':
            case 'YunWx':
            case 'YunAli':
                //免签
                parse_str(file_get_contents('php://input'), $params);
                break;
            default:
                record_file_log('pay_params', '未知支付产品！' . $channel);
                die('未知支付产品！');
                break;
        }
        record_file_log('pay_params', json_encode($params));
        if (isset($params['channel'])) {
            unset($params['channel']);
        }
        return $params;
    }

    /**
     * 获取渠道名
     */
    private function getChannelName($params) {
        // 渠道
        $channel = input('channel/s', '');
        switch ($channel) {
        	case 'UnionPay': //银联云闪付
        		$trade_no = isset($params['orderId']) ? $params['orderId'] : '';
                break;
            case 'WsyhAliScan':
            case 'WsyhWxScan':
                $trade_no = isset($params['request']['body']['OutTradeNo']) ? $params['request']['body']['OutTradeNo'] : '';
                if (empty($trade_no)) {
                    $trade_no = isset($params['outTradeNo']) ? $params['outTradeNo'] : '';
                }
                break;
            case 'PafbAliScan':
            case 'PafbWxScan':
            case 'PafbWxWap':
            case 'PafbAliWap':
                $trade_no = isset($params['u_out_trade_no']) ? $params['u_out_trade_no'] : '';
                break;
            case 'Hkyh':
                //汉口银行
                $trade_no = isset($params['u_out_trade_no']) ? $params['u_out_trade_no'] : '';
                break;
            case 'NZFAliqrcode': // 支付宝扫码
                $trade_no = isset($params['orderid']) ? $params['orderid'] : '';
                break;
            case 'AlipayScan': // 支付宝扫码
            case 'AlipayWap': // 支付宝WAP
            case 'WxpayScan': // 微信扫码
            case 'ShenduAliScan'://深度支付
            case 'ShenduWxScan':
            case 'ShenduWxGzh':
            case 'ShenduAliJspay':
                $trade_no = isset($params['out_trade_no']) ? $params['out_trade_no'] : '';
                break;
            case 'QPayAli': //支付宝免签
                $trade_no = isset($params['orderid']) ? $params['orderid'] : '';
                break;
            case 'QPayWx': //微信免签
                $trade_no = isset($params['orderid']) ? $params['orderid'] : '';
                break;
            case 'PayapiAli': //PayApi支付宝免签
                $trade_no = isset($params['orderid']) ? $params['orderid'] : '';
                break;
            case 'PayapiWx': //PayApi微信免签
            case 'Juhezhifu'://聚合支付
                $trade_no = isset($params['orderid']) ? $params['orderid'] : '';
                break;
            case 'DzWxScan': //点缀支付微信扫码
            case 'DzAliScan': //点缀支付支付宝扫码
            case 'DzQqScan': //点缀QQ扫码
            case 'DzWxGzh': //点缀支付公众号支付
            case 'DzJdScan': //点缀支京东扫码
            case 'DzWxH5': //点缀支付微信H5扫码
            case 'DzAliToPay': //点缀支付支付宝即时到账
                $trade_no = isset($params['MerchantOrderNo']) ? $params['MerchantOrderNo'] : '';
                break;
            case 'Lh15173PcPay':
            case 'Lh15173WapPay':
            case 'Lh15173QqPay':
                $trade_no = isset($params['sp_billno']) ? $params['sp_billno'] : '';
                break;
            case 'LkAlipayScanPay':
            case 'LkWxH5Pay':
            case 'LkWxPay':
            case 'LkQQPay':
            case 'LkBankPay':
                $trade_no = isset($params['P_OrderId']) ? $params['P_OrderId'] : '';
                break;
            case 'KjWxSanPay':
            case 'KjWxH5Pay':
            case 'KjAlipayScanPay':
            case 'KjAlipayH5Pay':
                $trade_no = isset($params['merchant_order_no']) ? $params['merchant_order_no'] : '';
                break;
            case 'Ka12QqNative':
            case 'Ka12QqWap':
            case 'Ka12QuickBank':
            case 'Ka12QuickWap':
            case 'Ka12AlipayScan':
            case 'Ka12AlipayWap':
            case 'Ka12WxScan':
            case 'Ka12WxWap':
                $trade_no = isset($params['sdorderno']) ? $params['sdorderno'] : '';
                break;
            case 'CodePayWxScan':
            case 'CodePayAliScan':
            case 'CodePayQqScan':
                $trade_no = isset($params['pay_id']) ? $params['pay_id'] : '';
                break;
            case 'WxpayH5':
                $trade_no = isset($params['out_trade_no']) ? $params['out_trade_no'] : '';
                break;
            case 'WxJsApi':
            case 'QqNative':
                $trade_no = isset($params['out_trade_no']) ? $params['out_trade_no'] : '';
                break;
            case 'FnAliScan':
            case 'FnAliWap':
            case 'FnQqScan':
            case 'FnWxJspay':
            case 'FnWxScan':
                $trade_no = isset($params['out_trade_no']) ? $params['out_trade_no'] : '';
                break;
            case 'ZlfWxScan':
            case 'ZlfQqScan':
            case 'ZlfAliScan':
            case 'ZlfWxJspay':
            case 'ZlfWxH5':
            case 'ZlfJdScan':
                $trade_no = isset($params['mchntOrderNo']) ? $params['mchntOrderNo'] : '';
                break;
            case 'QgjfAlipayScan':
            case 'QgjfAlipayWap':
            case 'QgjfQqNative':
            case 'QgjfWxGzh':
            case 'QgjfWxScan':
            case 'QgjfWxWap':
            case 'TaomiAlipayScan':
            case 'TaomiAlipayWap':
            case 'TaomiQqNative':
            case 'TaomiWxGzh':
            case 'TaomiWxScan':
            case 'TaomiWxWap':
                //黔贵金服支付
                $trade_no = isset($params['out_trade_no']) ? $params['out_trade_no'] : '';
                break;
            case 'PYFalipay':
            case 'PYFqqpay':
            case 'PYFwxpay':
                //拼云付
                $trade_no = isset($params['out_trade_no']) ? $params['out_trade_no'] : '';
                break;
            case 'YcshWxGzh':
            case 'YcshWxH5':
            case 'YcshWxScan':
            case 'YcshAliScan':
            case 'YcshAliWap':
            case 'YcshQqScan':
            case 'YcshQqWap':
                //优畅上海
                $trade_no = isset($params['out_trade_no']) ? $params['out_trade_no'] : '';
                break;
            case 'TpayWxpay':
            case 'TpayAlipay':
                //TPay
                $trade_no = isset($params['order_number']) ? $params['order_number'] : '';
                break;
            case 'HnPayAliScan':
            case 'HnPayQqScan':
            case 'HnPayWxGzh':
            case 'HnPayWxH5':
            case 'HnPayWxScan':
                //海鸟
                $trade_no = isset($params['out_trade_no']) ? $params['out_trade_no'] : '';
                break;
            case 'WmskWxScan':
            case 'WmskAliScan':
            case 'WmskQqScan':
            case 'WmskQqWap':
            case 'WmskWxWap':
            case 'WmskAliWap':
                //完美数卡
                $trade_no = isset($params['sdcustomno']) ? $params['sdcustomno'] : '';
                break;
            case 'JyWxPay':
            case 'JyWxGzhPay':
                $trade_no = isset($params['ordernumber']) ? $params['ordernumber'] : '';
                break;
            case 'YunQq':
            case 'YunWx':
            case 'YunAli':
                //免签
                $trade_no = isset($params['ddh']) ? $params['ddh'] : '';
                break;
            case 'SwiftAliScan':
            case 'SwiftAliWap':
            case 'SwiftWxScan':
            case 'SwiftJd':
            case 'SwiftWxWap':
            case 'SwiftWxGzh':
                $trade_no = isset($params['out_trade_no']) ? $params['out_trade_no'] : '';
                break;
            case 'YiyunAliScan'://易云支付
            case 'YiyunAliWap':
            case 'YiyunWxGzh':
            case 'YiyunWxScan':
            case 'YiyunWxWap':
                $trade_no = isset($params['sdorderno']) ? $params['sdorderno'] : '';
                break;
            case 'HenglongAliScan'://恒隆支付
            case 'HenglongAliWap':
            case 'HenglongWxScan':
            case 'HenglongWxGzh':
                if(isset($params['orderid'])) {
                    $trade_no = $params['orderid'];
                } elseif(isset($params['ordernumber'])) {
                    $trade_no = $params['ordernumber'];
                } else {
                    $trade_no = '';
                }
                break;
            default:
                record_file_log('pay_params', '未知支付产品！' . $channel);
                die('未知支付产品！');
                break;
        }
        return $trade_no;
    }

    private function repeat() {
        // 渠道
        $channel = input('channel/s', '');
        switch ($channel) {
        	case 'UnionPay': //银联云闪付
            case 'WsyhAliScan':
            case 'WsyhWxScan':
                echo '<xml><RespInfo>SUCCESS</RespInfo></xml>';
                break;
            case 'Hkyh':
            case 'AlipayScan': // 支付宝扫码
            case 'AlipayWap': // 支付宝WAP
            case 'WxpayScan': // 微信扫码
            case 'LkAlipayScanPay':
            case 'LkWxH5Pay':
            case 'LkWxPay':
            case 'LkQQPay':
            case 'LkBankPay':
            case 'KjWxSanPay':
            case 'KjWxH5Pay':
            case 'KjAlipayScanPay':
            case 'KjAlipayH5Pay':
            case 'Ka12QqNative':
            case 'Ka12QqWap':
            case 'Ka12QuickBank':
            case 'Ka12QuickWap':
            case 'Ka12AlipayScan':
            case 'Ka12AlipayWap':
            case 'Ka12WxScan':
            case 'Ka12WxWap':
            case 'CodePayWxScan':
            case 'CodePayAliScan':
            case 'CodePayQqScan':
            case 'WxpayH5':
            case 'WxJsApi':
            	echo '<xml><RespInfo>SUCCESS</RespInfo></xml>';
                break;
            case 'QgjfAlipayScan':
            case 'QgjfAlipayWap':
            case 'QgjfQqNative':
            case 'QgjfWxGzh':
            case 'QgjfWxScan':
            case 'QgjfWxWap':
            case 'TaomiAlipayScan':
            case 'TaomiAlipayWap':
            case 'TaomiQqNative':
            case 'TaomiWxGzh':
            case 'TaomiWxScan':
            case 'TaomiWxWap':
            case 'YcshWxGzh':
            case 'YcshWxH5':
            case 'YcshWxScan':
            case 'YcshAliScan':
            case 'YcshAliWap':
            case 'YcshQqScan':
            case 'YcshQqWap':
                //优畅上海
            case 'TpayWxpay':
            case 'TpayAlipay':
            case 'SwiftAliScan':
            case 'SwiftAliWap':
            case 'SwiftWxScan':
            case 'SwiftJd':
            case 'SwiftWxWap':
            case 'SwiftWxGzh':
                //易云支付
            case 'YiyunAliScan':
            case 'YiyunAliWap':
            case 'YiyunWxGzh':
            case 'YiyunWxScan':
            case 'YiyunWxWap':
                //深度支付
            case 'ShenduAliScan':
            case 'ShenduWxScan':
            case 'ShenduWxGzh':
            case 'ShenduAliJspay':
                echo 'success';
                break;
            case 'NZFAliqrcode': // 支付宝扫码
            case 'QPayAli': //支付宝免签
            case 'QPayWx': //微信免签
            case 'Lh15173PcPay':
            case 'Lh15173WapPay':
            case 'Lh15173QqPay':
            case 'PayapiAli': //PayApi支付宝免签
            case 'PayapiWx': //PayApi微信免签
                echo 'OK';
                break;
            case 'DzWxScan': //点缀支付微信扫码
            case 'DzAliScan': //点缀支付支付宝扫码
            case 'DzQqScan': //点缀QQ扫码
            case 'DzWxGzh': //点缀支付公众号支付
            case 'DzJdScan': //点缀支京东扫码
            case 'DzWxH5': //点缀支付微信H5扫码
            case 'DzAliToPay': //点缀支付支付宝即时到账
            case 'HenglongAliScan'://恒隆支付
            case 'HenglongAliWap':
            case 'HenglongWxScan':
            case 'HenglongWxGzh':
            case 'Juhezhifu'://聚合支付
                echo 'ok';
                break;
            case 'QqNative':
                echo '<xml><return_code>SUCCESS</return_code></xml>';
                break;
            case 'PafbAliScan':
            case 'PafbWxScan':
            case 'PafbWxWap':
            case 'PafbAliWap':
            case 'FnAliScan':
            case 'FnAliWap':
            case 'FnQqScan':
            case 'FnWxJspay':
            case 'FnWxScan':
            case 'PYFalipay':
            case 'PYFqqpay':
            case 'PYFwxpay':
            case 'HnPayAliScan':
            case 'HnPayQqScan':
            case 'HnPayWxGzh':
            case 'HnPayWxH5':
            case 'HnPayWxScan':
                echo 'SUCCESS';
                break;
            case 'ZlfWxScan':
            case 'ZlfQqScan':
            case 'ZlfAliScan':
            case 'ZlfWxJspay':
            case 'ZlfWxH5':
            case 'ZlfJdScan':
                echo json_encode(['success' => 'true']);
                break;
            case 'WmskWxScan':
            case 'WmskAliScan':
            case 'WmskQqScan':
            case 'WmskQqWap':
            case 'WmskWxWap':
            case 'WmskAliWap':
            case 'YunQq':
            case 'YunWx':
            case 'YunAli':
            case 'JyWxPay':
            case 'JyWxGzhPay':
                //完美数卡
                echo "<result>1</result>";
                break;
            default:
                record_file_log('pay_params', '未知支付产品！' . $channel);
                die('未知支付产品！');
                break;
        }
        exit();
    }

    /**
     * 页面通知回调
     */
    public function page_callback() {
        file_put_contents(LOG_PATH . 'page_callback.txt', "【" . date('Y-m-d H:i:s') . "】\r\n" . file_get_contents("php://input") . "\r\n\r\n", FILE_APPEND);
        $params = input('');
        record_file_log('pay_page', json_encode($params));
        if (isset($params['channel'])) {
            unset($params['channel']);
        }
        $trade_no = $this->getChannelName($params);
        $order    = OrderModel::get(['trade_no' => $trade_no]);
        if (!$order) {
            // 记录错误订单
            record_file_log('pay_error', $trade_no . '不存在该订单！');
            die('不存在该订单！');
        }
        if ($order->status == 1) { //防止恶意刷新加钱
            // 记录错误订单
            record_file_log('pay_error', $trade_no . '该订单属于完成订单！');
            header('location:' . url('/orderquery') . '?orderid=' . $trade_no);
            die('该订单已完成！');
        }

        // 支付渠道
        $channel = $order->channel;
        if (!$channel) {
            record_file_log('pay_error', $trade_no . '不存在该支付渠道！');
            die('不存在该支付渠道！');
        }
        // 渠道账户
        $account = $order->channelAccount;
        if (!$account) {
            record_file_log('pay_error', $trade_no . '不存在支付渠道：' . $channel->title . '的账号！');
            die('不存在支付渠道：' . $channel->title . '的账号！');
        }
        // 回调通知
        $PayAPI = PayAPI::load($channel, $account);
        $PayAPI->page_callback($params, $order);
    }

    /**
     * 服务器通知回调
     */
    public function notify_callback() {
        file_put_contents(LOG_PATH . 'notify.txt', "【" . date('Y-m-d H:i:s') . "】\r\n" . file_get_contents("php://input") . "\r\n\r\n", FILE_APPEND);
        $params = $this->getParams();
        record_file_log('pay_notify', json_encode($params));
        $trade_no = $this->getChannelName($params);
        $order    = OrderModel::get(['trade_no' => $trade_no]);
        if (!$order) {
            // 记录错误订单
            record_file_log('pay_error', $trade_no . '不存在该订单！');
            die('不存在该订单！');
        }
        if ($order->status == 1) { //防止恶意刷新加钱
            // 记录错误订单
            record_file_log('pay_error', $trade_no . '该订单属于完成订单！');
            //直接返回给上游 success 或者 OK 之类的
            return $this->repeat();
        }
        // 支付渠道
        $channel = $order->channel;
        if (!$channel) {
            record_file_log('pay_error', $trade_no . '不存在该支付渠道！');
            die('不存在该支付渠道！');
        }
        // 渠道账户
        $account = $order->channelAccount;
        if (!$account) {
            record_file_log('pay_error', $trade_no . '不存在支付渠道：' . $channel->title . '的账号！');
            die('不存在支付渠道：' . $channel->title . '的账号！');
        }
        try{
        	 // 回调通知
	        $PayAPI = PayAPI::load($channel, $account);
	        if ($PayAPI->notify_callback($params, $order)) {
	            //支付完成，扣除库存
	            Goods::sendOut($trade_no);
	        }
        }catch(Exception $e){
        	record_file_log('pay_error', $trade_no . '传入支付控制器异常！'.$e);
        }
    }

    // 获取优惠价
    private function get_discount_price($goods, $quantity) {
        $price = $goods->price;
        $list  = $goods->wholesale_discount_list;
        $sort  = array_column($list, 'num');
        array_multisort($sort, SORT_DESC, $list);
        
        //判断商品有没有对接
        if(!empty($goods->duijie_id)){
        	$sj_goods = GoodsModel::get(['id' => $goods->duijie_id]);
        }
        foreach ($list as $v) {
            if ($quantity >= $v['num']) {
            	if(!empty($goods->duijie_id)){
            		//判断下级加价是否高于上级最低加价
            		if($goods->price < $sj_goods->duijie_smilepic){
            			$price = $sj_goods->duijie_smilepic + $sj_goods->duijie_price;
            		}else{
            			$price = $v['price'] + $sj_goods->duijie_price;
            		}
            	}else{
            		$price = $v['price'];
            	}
                break;
            }
        }
        return $price;
    }

    //支付结果页面
    public function pay_result() {
        $orderid = input('orderid/s', '');
        if (!$orderid) {
            $this->error('缺少参数');
        }
        $orderInfo = Db::name('order')->where(array('trade_no' => $orderid))->find();
        if (empty($orderInfo)) {
            $this->error('订单不存在');
        }
        //跳转到订单查询页面
        $this->redirect('/orderquery?orderid=' . $orderInfo['trade_no']);
        return false;
        $this->assign('order', $orderInfo);
        return view();
    }

    //检查订单支付状态
    public function check_order_status() {
        $out_trade_no = input('out_trade_no/s', '');
        if (!$out_trade_no) {
            return J(1, '参数错误！');
        }
        $order = Db::table('order')->where('trade_no', $out_trade_no)->find();
        if (empty($order)) {
            return J(1, '订单不存在！');
        }
        if ($order['status'] == 1) {
            return J(0, '支付成功', url('/orderquery') . '?orderid=' . $out_trade_no);
        } else {
            return J(1, '订单状态未改变');
        }
    }

    // 微信公众号支付跳转页面
    public function wx_js_api_call() {
        $code = input('code/s', '');
        if (!$code) {
            exit('缺少code参数！');
        }
        $trade_no = input('state/s', '');
        $order    = OrderModel::get(['trade_no' => $trade_no]);
        if (!$order) {
            exit('不存在该订单！');
        }
        if ($order->status == 1) {
            exit('该订单已完成！');
        }
        // 支付渠道
        $channel = $order->channel;
        if (!$channel) {
            die('不存在该支付渠道！');
        }
        // 渠道账户
        $account = $order->channelAccount;
        if (!$account) {
            die('不存在支付渠道：' . $channel->title . '的账号！');
        }
        // 回调通知
        $PayAPI = PayAPI::load($channel, $account);
        $PayAPI->js_api_call($code, $order);
    }

    //微信公众号支付在非微信浏览器打开中转页面
    public function wx_jspay_page() {
        $pay_url = input('url/s', '');
        if (!$pay_url) {
            exit('缺少参数');
        }
        $this->assign('pay_url', base64_decode($pay_url));

        $trade_no = input('trade_no/s', '');
        $this->assign('trade_no', $trade_no);

        $token = session('token');
        if (!$token) {
            $token = md5(time() . md5(time()) . time()) . time();
            session('token', $token);
        }
        $this->assign('token', $token);
        return view();
    }

    //在新页面提交支付form表单（用于公众号支付或服务窗支付，上游必须以form表单形式提交的情况，作为一个中转跳转，在非微信浏览器中打开生成二维码的作用）
    public function submit_order_form() {
        $trade_no = input('trade_no/s', '');
        $order    = OrderModel::get(['trade_no' => $trade_no]);
        if (!$order) {
            exit('不存在该订单！');
        }
        if ($order->status == 1) {
            exit('该订单已完成！');
        }
        // 支付渠道
        $channel = $order->channel;
        if (!$channel) {
            die('不存在该支付渠道！');
        }
        // 渠道账户
        $account = $order->channelAccount;
        if (!$account) {
            die('不存在支付渠道：' . $channel->title . '的账号！');
        }
        if($order['pay_content_type'] != 7) {
            die('denied');
        }
        echo $order['pay_url'];
    }
}
