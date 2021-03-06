<?php

namespace app\index\controller;

use app\common\model\Complaint as ComplaintModel;
use app\common\model\ComplaintMessage;
use app\common\model\Goods;
use app\common\model\Order as OrderModel;
use app\common\util\Sms;
use service\FileService;
use think\captcha\Captcha;
use think\Controller;
use think\Db;
use think\Exception;
use think\Request;
use think\Session;
use think\Cookie;

class Order extends Base {
    private $seKey = 'zhiyu';
    private $expire = 60;

    public function __construct() {
        parent::__construct();

        if ($this->request->isPost()) {
            //post 请求进来，校验 token
            $token = input('token/s', '');
            if (empty($token) || $token != session('token')) {
                $this->error('非法请求');
            }
        }

        // 检查是否有 token ，如果没有，设置请求 token
        $token = session('token');
        if (!$token) {
            $token = md5(time() . md5(time()) . time()) . time();
            session('token', $token);
        }
        $this->assign('token', $token);
    }

    /**
     * 查询订单
     */
    public function query() {
        $code = input('chkcode/s', '');
        $this->assign('chkcode', $code);

        $queryType = input('querytype/d', '2');
        $this->assign('querytype', $queryType);

        $trade_no = input('orderid/s', '');
        $this->assign('trade_no', $trade_no);

        $is_verify = false;

        if ($trade_no || $queryType == 1) {
            // 验证码不能为空
            if (sysconf('order_query_chkcode') == 1) {
                $key    = $this->authcode($this->seKey) . 'orderquery';
                $secode = Session::get($key, '');

                if (!empty($code) && !empty($secode)) {

                    // session 过期
                    if (time() - $secode['verify_time'] > $this->expire) {
                        Session::delete($key, '');
                    } else {
                        if ($secode['verify_code'] == $code) {
                            $is_verify = true;

                            Session::delete($key, '');
                        }
                    }
                }
            } else {
                $is_verify = true;
            }

            switch ($queryType) {
                case '1':
                    //获取已登录用户最近一次购买的卡密
                    if (session('last_order_trade_no')) {
                        $trade_no = session('last_order_trade_no');
                        //$order    = OrderModel::where(['trade_no' => $trade_no,'dj_is_see' => null])->order('id DESC')->find();
                        $order    = OrderModel::where(['trade_no' => $trade_no])->order('id DESC')->find();
                    } else {
                        $order = false;
                    }
                    break;
                case '2':
                    //按订单号方式获取
                    //$order = OrderModel::where(['trade_no' => $trade_no,'dj_is_see' => null])->order('id DESC')->find();
                    $order = OrderModel::where(['trade_no' => $trade_no])->order('id DESC')->find();
                	break;
                case '3':
                	$this->error('请使用浏览器缓存或订单号查询');
                	
                    //按联系方式获取
                    //此处dj_is_see为上级对接订单标识，加上则不可查，dj_is_see有内容说明是上级订单
                    //$count = OrderModel::where(['contact' => $trade_no, 'status' => 1,'dj_is_see' => null])->count();
                    $count = OrderModel::where(['contact' => $trade_no, 'status' => 1])->count();
                    if ($count > 1) {
                    	//$order = OrderModel::where(['contact' => $trade_no, 'status' => 1,'dj_is_see' => null])->order('id DESC')->paginate(30);
                        $order = OrderModel::where(['contact' => $trade_no, 'status' => 1])->order('id DESC')->paginate(30);
                        foreach ($order as $key => $value){
                        	//判断是否是对接订单
                        	if(!empty($order[$key]['dj_order_id'])){
				            	$sj_order = OrderModel::where(['trade_no' => $order[$key]['dj_order_id']])->find();
				            	if($sj_order){
				            		$order[$key]['goods_price'] = round($order[$key]['goods_price'] + $sj_order->goods_price+ $order[$key]['fee'],3);
				            	}
				            }
                        }
                        // 分页
                        $page = $order->render();
                        $this->assign('page', $page);
                        $this->assign('sekey', $this->seKey);
                        $this->assign('order', $order);
                        return $this->fetch('querybycontact');
                    } else {
                        //$order = OrderModel::where(['contact' => $trade_no, 'status' => 1,'dj_is_see' => null])->order('id DESC')->find();
                        $order = OrderModel::where(['contact' => $trade_no, 'status' => 1])->order('id DESC')->find();
                    }

                    break;
            }

            // 如果存在密码
            if ($order && $order->take_card_type != 0) {
                if (!empty($order->take_card_password)) {
                    $take_card_password = input('pwd/s', '');
                    if ($take_card_password) {
                        if ($take_card_password != $order->take_card_password) {
                            $this->error('查询密码错误！');
                        } else {
                            $is_verify = true;
                        }
                    } else {
                        $this->assign('trade_no', $order->trade_no);
                        return $this->fetch('query_pass');
                    }
                }
            }

            if (!empty($order) && $order['first_query'] == 0) {
                $is_verify = true;

                $order->save(['first_query' => 1]);
            }
        }

        $l = input('l/s', '');
        if ($l && $l == md5($trade_no . $this->seKey)) {
            $is_verify = true;
        }

        $this->assign('is_verify', $is_verify);
        if ($is_verify) {
        	if(!empty($order->dj_order_id)){
            	$sj_order = OrderModel::where(['trade_no' => $order->dj_order_id])->order('id DESC')->find();
            	if($sj_order){
            		$order->total_price = round($order->total_price + $sj_order->total_price,3);
            	}
            }
	        $this->assign('order', $order);
            if (isset($order->channel)) {
                $this->assign('channel', $order->channel);
            }

            if ($order['status'] == 1) {
                //查询订单资金是否还在冻结中，如果是，允许投诉，否则不允许用户投诉
                $unfreeze = Db::table('auto_unfreeze')->where(['trade_no' => $order['trade_no']])->find();
                if ($unfreeze) {
                    // 因为商户订单一旦结算了，钱就可能会被提走，极端情况下，商户余额里面可能一分钱都没有（跑路了）
                    // 那么平台就没办法追回这部分的损失，所以这里采用了支付后订单冻结24小时，而投诉只允许在冻结的 24小时内申请
                    // 支付超出 24 小时的订单，因为一开始提到的原因不再提供投诉入口，如果有问题，平台自行与商家，买家进行协商
                    $this->assign('canComplaint', true);
                }
            }
        }
        return $this->fetch();
    }

    /**
     * 检查商品并出货
     */
    public function checkGoods() {
        $token = input('token/s', '');
        if (empty($token) || $token != session('token')) {
            return json(['msg' => '非法请求']);
        }

        $trade_no = input('orderid/s', '');
        if ($trade_no) {
            return Goods::sendOut($trade_no);
        } else {
            return json([
                'msg'    => '请提供订单号',
                'status' => 0,
            ]);
        }
    }

    /**
     * 投诉
     */
    public function complaint() {
        if (!$this->request->isPost()) {
            return $this->fetch();
        }

        $trade_no = input('trade_no/s', '');
        $type     = input('type/s', '');
        $qq       = input('qq/s', '');
        $mobile   = input('mobile/s', '');
        $email	  = input('email/s', '');
        $desc     = input('desc/s', '');
		$zfbxm     = input('zfbxm/s', '');
		$zfb     = input('zfb/s', '');
		
        if (!$qq) {
            $this->error('请输入联系QQ！');
        }
        if (!is_mobile_number($mobile)) {
            $this->error('这不是一个有效的手机号格式！');
        }
        if (!$desc) {
            $this->error('请输入投诉说明！');
        }
        if (!$zfbxm) {
            $this->error('请输入支付宝姓名！');
        }
        if (!$zfb) {
            $this->error('请输入支付宝账号！');
        }

        $order = OrderModel::get(['trade_no' => $trade_no]);
        if (!$order) {
            $this->error('不存在该订单！');
        }
        if ($order->status === 0) {
            $this->error('该订单未完成，暂不能受理投诉！');
        }
        //判断是否超过投诉时间
        if(date('Ymd', $order->success_at) !== date('Ymd')){
        	$this->error('该订单已超过投诉时间，投诉仅支持购买成功后当天投诉！');
        }
		//上级订单不能被投诉
		if($order->dj_is_see === 1){
			$this->error('对接商品请投诉下级，无法投诉上级！');
		}
		$count = ComplaintModel::where(['trade_no' => $trade_no])->count();
        if ($count > 0) {
            $token = md5(md5(time()).rand(1000,5000));
            session('token',$token);
            $this->error('您已投诉过该订单！', url('Index/order/complaintpass', ['trade_no' => $trade_no, 'token' => $token]));
        }
		Db::startTrans();
        try {
            //投诉查看密码，需要发送到投诉人联系手机中
            $code = rand(100000, 999999);
            $complaint_data = [
                'user_id'   => $order->user_id,
                'trade_no'  => $trade_no,
                'type'      => $type,
                'qq'        => $qq,
                'mobile'    => $mobile,
                'desc'      => $desc,
                'zfbxm'      => $zfbxm,
                'zfb'      => $zfb,
                'status'    => 0,
                'create_at' => $_SERVER['REQUEST_TIME'],
                'create_ip' => $this->request->ip(),
                'pwd'       => $code,
                'expire_at' => time() + 86400,
            ];
            $res = $this->complaint_doing($complaint_data,$order);
            if(!$res){
            	Db::rollback();
            	$this->error('操作失败，请重试 - Code:1！');
            }
			//判断是否属于对接的商品被投诉
			if(!empty($order->dj_order_id)){
				$sj_order = OrderModel::get(['trade_no' => $order->dj_order_id]);
				if(!$sj_order){
					Db::rollback();
            		$this->error('操作失败，请重试 - Code:2！');
				}
				$complaint_data = [
	                'user_id'   => $sj_order->user_id,
	                'trade_no'  => $sj_order->trade_no,
	                'type'      => $type,
	                'qq'        => $qq,
	                'mobile'    => $mobile,
	                'desc'      => $desc,
	                'zfbxm'     => $zfbxm,
                	'zfb'       => $zfb,
	                'status'    => 0,
	                'is_duijie' => 1,
	                'create_at' => $_SERVER['REQUEST_TIME'],
	                'create_ip' => $this->request->ip(),
	                'pwd'       => $code,
	                'expire_at' => time() + 86400,
	            ];
	            $res = $this->complaint_doing($complaint_data,$sj_order,true);
	            if(!$res){
	            	Db::rollback();
	            	$this->error('操作失败，请重试 - Code:3！');
	            }
	            if(!Db::table('complaint')->where(['trade_no' => $trade_no])->update(['duijie_id' => $res->id])){
	            	Db::rollback();
	            	$this->error('操作失败，请重试 - Code:4！');
	            }
			}
			// 实例化投诉短信SDK
            $sms = new Sms;
	        // 向买家发送投诉信息
	        $sms->sendComplaintPwd($mobile, $trade_no, $code);
	        sendMail($email, '【' . sysconf('site_name') . '】' . '您的投诉已被受理，请查看投诉密码', '投诉密码为:'.$code, '', true);
	        // 向卖家发送投诉成功短信
	        $sms->sendComplaintNotify($order->user->mobile, $trade_no);
	        // 向被投诉的卖家发送投诉通知
	        sendMail($order->user->email, '【' . sysconf('site_name') . '】' . '您已被买家投诉，请及时登录商户普通沟通解决', '若您逾期今天无法解决，请向平台说明详细原因，否则视为买家获胜，将不结算该笔订单', '', true);
	        // 向被投诉的卖家上级用户发送被投诉通知
	        if(!empty($order->dj_order_id)){
	        	sendMail($sj_order->user->email, '【' . sysconf('site_name') . '】' . '您的下级对接的商品已被买家投诉，请及时联系您的下级对接商户沟通解决', '请您今天内与下级商户共同协商解决该投诉，下级商户订单号:'.$trade_no.' 本人订单号:'.$sj_order->trade_no, '', true);
	        }
	        $token = md5(md5(time()).rand(1000,5000));
	        session('token',$token);
        } catch (Exception $e) {
            Db::rollback();
            $this->error('操作失败，请重试- Code:5!' . $e->getTraceAsString());
        }
        Db::commit();
        $this->success('投诉成功！', url('Index/order/complaintpass', ['trade_no' => $trade_no, 'token' => $token]));
    }
    
    /**
     * 投诉锁定资金解冻 和 增加投诉数据
     * param $complaint_data 投诉表添加的数据
     * param $order 订单数据
     * param $isagent 是否为上级代理
     */
    public function complaint_doing($complaint_data,$order,$isagent = false) {
        $falg = false;
        //增加投诉表数据
        $res = ComplaintModel::create($complaint_data);
        if(!$res){
        	return $falg;
        }
        //发送投诉信息 投诉信息表  代理则不创建投诉信息表
        if(!$isagent){
        	 if(!Db::table('complaint_message')->insert([
	            'trade_no'  => $complaint_data['trade_no'],
	            'content'   => $complaint_data['desc'],
	            'create_at' => time(),
	        ])){
	        	return $falg;
	        }
        }
        //投诉申请成功，指定的订单作废，不允许该订单的资金解冻。
        if(!Db::table('auto_unfreeze')->where(['trade_no' => $order->trade_no])->update(['status' => -1])){
        	return $falg;
        }
        //冻结订单
        if(!Db::table('order')->where(['trade_no' => $order->trade_no])->update(['is_freeze' => 1])){
        	return $falg;
        }
        //判断是否 T0 结算的订单，如果是，需要扣除商家余额
	    if (0 == $order->settlement_type) {
	        $user    = Db::table('user')->where('id', $order->user->id)->lock(true)->find();
	        $balance = round($user['money'] - $order->total_price, 3);
	        if(!Db::table('user')->where('id', $user['id'])->update(['money' => ['exp', 'money-' . $order->total_price], 'freeze_money' => ['exp', 'freeze_money+' . $order->total_price]])){
	        	return $falg;
	        }
	        // 记录用户金额变动日志
	        record_user_money_log('freeze', $user['id'], $order->total_price, $balance, "T0订单被投诉，冻结金额：{$order->total_price}元");
	    }
	    return $res;
    }

    /**
     * 投诉查询页
     */
    public function complaintquery() {
        return $this->fetch();
    }

    /**
     * 投诉撤销
     */
    public function complaintCancel() {
        if ($this->request->isPost()) {
            $tradeNo   = input('trade_no/s', '');
            $pwd       = input('pwd/s', '');
            $complaint = ComplaintModel::where(['trade_no' => $tradeNo, 'pwd' => $pwd])->find();
            //关联的对接投诉id  上级的
            $duijie_id = $complaint->duijie_id;
            if ($complaint) {
                DB::startTrans();
                try {
                    $complaint->status = -1;
                    $res               = $complaint->save();
                    if ($res) {
                        //买家撤诉，该笔订单可以解冻
                        Db::table('auto_unfreeze')->where(['trade_no' => $complaint->trade_no])->update(['status' => 1]);
                        //资金状态修改成功，解冻订单
                        $res = Db::table('order')->where(['trade_no' => $complaint->trade_no])->update(['is_freeze' => 0]);
                        $order = OrderModel::get(['trade_no' => $tradeNo]);
                        //判断是否 T0 结算的订单，如果是，需要返还商家余额
                        if (0 == $order->settlement_type) {
                            $user    = Db::table('user')->where('id', $order->user->id)->lock(true)->find();
                            $balance = round($user['money'] + $order->total_price, 3);
                            if(!Db::table('user')->where('id', $user['id'])->update(['money' => ['exp', 'money+' . $order->total_price], 'freeze_money' => ['exp', 'freeze_money-' . $order->total_price]])){
                            	DB::rollback();
                				return J(500, '撤销失败，如有问题请联系客服处理');
                            }
                            // 记录用户金额变动日志
                            record_user_money_log('freeze', $user['id'], $order->total_price, $balance, "T0订单投诉撤诉，解冻金额：{$order->total_price}元");
                        }
                    }else{
                    	DB::rollback();
                    	return J(500, '撤销失败，如有问题请联系客服处理');
                    }
                    
                    //判断是否有投诉上级--对接
                    if(!empty($duijie_id)){
                    	$complaint = ComplaintModel::where(['id' => $duijie_id])->find();
                    	if ($complaint) {
                    		$complaint->status = -1;
                    		$res               = $complaint->save();
                    		$tradeNo = $complaint->trade_no;
                    		if ($res) {
                    			//买家撤诉，该笔订单可以解冻
		                        Db::table('auto_unfreeze')->where(['trade_no' => $complaint->trade_no])->update(['status' => 1]);
		                        //资金状态修改成功，解冻订单
		                        $res = Db::table('order')->where(['trade_no' => $complaint->trade_no])->update(['is_freeze' => 0]);
		                        $order = OrderModel::get(['trade_no' => $tradeNo]);
		                        //判断是否 T0 结算的订单，如果是，需要返还商家余额
		                        if (0 == $order->settlement_type) {
		                            $user    = Db::table('user')->where('id', $order->user->id)->lock(true)->find();
		                            $balance = round($user['money'] + $order->total_price, 3);
		                            if(!Db::table('user')->where('id', $user['id'])->update(['money' => ['exp', 'money+' . $order->total_price], 'freeze_money' => ['exp', 'freeze_money-' . $order->total_price]])){
		                            	DB::rollback();
		                				return J(500, '撤销失败，如有问题请联系客服处理');
		                            }
		                            // 记录用户金额变动日志
		                            record_user_money_log('freeze', $user['id'], $order->total_price, $balance, "上级对接-T0订单投诉撤诉，解冻金额：{$order->total_price}元");
		                        }
                    		}else{
		                    	DB::rollback();
		                    	return J(500, '撤销失败，如有问题请联系客服处理');
		                    }
                    	}
                    }
                } catch (Exception $e) {
                    DB::rollback();
                    halt($e);
                }
                DB::commit();
                return J(200, '撤销成功！');
            }
            return J(500, '密码不正确，如有问题请联系客服处理');
        }
    }

    /**
     * 投诉查询密码页
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function complaintPass() {
        if ($this->request->isPost()) {
            $tradeNo   = input('trade_no/s', '');
            $pwd       = input('pwd/s', '');
            $complaint = ComplaintModel::where(['trade_no' => $tradeNo, 'pwd' => $pwd])->find();
            if ($complaint) {
                //设置 cookie 半小时有效
                //cookie('complaint_order', $tradeNo, ['expire' => '1800']);
		session('complaint_order', $tradeNo);
                //cookie('complaint_pwd', $pwd, ['expire' => '1800']);
		session('complaint_pwd', $pwd);
                $token = md5(time() . md5(time()) . time()) . time();
                session('token', $token);
                return J(200, '密码正确！', '', url('Index/Order/complaintDetail') . '?token=' . $token);
            } else {
                return J(500, '密码不正确，如有问题请联系客服处理');
            }
        }

        $token = input('token/s', '');
        if (empty($token) || $token != session('token')) {
            return json(['msg' => '非法请求']);
        }

        $tradeNo = input('trade_no/s', '');
        if ($tradeNo) {
            $complaint = ComplaintModel::where(['trade_no' => $tradeNo])->find();
            if ($complaint) {
                $this->assign('complaint', $complaint);
            }
        }
        return $this->fetch('complaint_pass');
    }

    /**
     * 投诉详情
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function complaintDetail() {
        $token = input('token/s', '');
        if (empty($token) || $token != session('token')) {
            return json(['msg' => '非法请求']);
        }

        //获取投诉内容
        $tradeNo   = session('complaint_order');
	// $tradeNo = $_COOKIE["complaint_order"];
        $pwd       = session('complaint_pwd');
        $complaint = ComplaintModel::where(['trade_no' => $tradeNo, 'pwd' => $pwd])->find();
        if ($complaint) {
            $this->assign('complaint', $complaint);

            //延长 cookie 的有效期
            session('complaint_order', $tradeNo);
            session('complaint_pwd', $pwd);

            //获取投诉对话内容
            $messages = DB::name('complaint_message')->where(['trade_no' => $tradeNo])->select();
            $this->assign('messages', $messages);

            return $this->fetch('complaint_detail');
        } else {
            //清除 cookie
            //cookie('complaint_order', null);
            //cookie('complaint_pwd', null);
           // $this->error('登录已过期，请重新登录!!');
        }
    }

    /**
     * 发送沟通内容
     *
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function complaintSend() {
        if ($this->request->isPost()) {
            $content = input('content/s', '');
            if (empty($content)) {
                return J(500, '请输入沟通内容');
            }

            $tradeNo   = session('complaint_order');
            $pwd       = session('complaint_pwd');
            $complaint = ComplaintModel::where(['trade_no' => $tradeNo, 'pwd' => $pwd])->find();

            if ($complaint) {
                $data = [
                    'trade_no'  => $tradeNo,
                    'content'   => $content,
                    'create_at' => time(),
                ];
                ComplaintMessage::create($data);
                return J(200, '发送成功');
            } else {
                return J(500, '登录超时，请重新登录');
            }
        }
    }

    /**
     * 发送投诉图片
     *
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function complaintImg() {
        if ($this->request->isPost()) {
            //获取上传文件
            $file = $this->request->file('image');

            if ($file) {
                //检查文件的扩展名
                $ext = strtolower(pathinfo($file->getInfo('name'), PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'gif', 'png'])) {
                    //检查投诉是否存在
                    $tradeNo   = session('complaint_order');
                    $pwd       = session('complaint_pwd');
                    $complaint = ComplaintModel::where(['trade_no' => $tradeNo, 'pwd' => $pwd])->find();
                    if ($complaint) {
                        //保存图片
                        $md5      = [uniqid(), uniqid()];
                        $filename = join('/', $md5) . ".{$ext}";

                        $info = $file->move('static' . DS . 'upload' . DS . $md5[0], $md5[1], true);

                        if ($info) {
                            $file_url = FileService::getFileUrl($filename, 'local');
                            $data     = [
                                'trade_no'     => $tradeNo,
                                'content'      => $file_url,
                                'content_type' => '1',
                                'create_at'    => time(),
                            ];
                            ComplaintMessage::create($data);
                            return J(200, '发送成功');
                        } else {
                            return J(500, '发送失败，请稍候再试');
                        }
                    } else {
                        return J(500, '登录超时，请重新登录');
                    }
                } else {
                    return J(500, '发送失败，不支持的图片文件格式');
                }
            } else {
                return J(500, '请上传举证图片');
            }
        }
    }

    /**
     * 验证码
     */
    public function chkcode() {
        $captcha           = new Captcha();
        $captcha->fontSize = 30;
        $captcha->length   = 4;
        $captcha->useNoise = true;
        return $captcha->entry('order.query');
    }

    /**
     * 验证验证码
     */
    public function verifyCode() {
        $code = input('chkcode/s', '');
        if (verify_code($code, 'order.query')) {
            //验证成功之后保存验证码到session中，查询的时候判断是否超时
            $key                   = $this->authcode($this->seKey) . 'orderquery';
            $secode                = [];
            $secode['verify_code'] = $code; // 把校验码保存到session
            $secode['verify_time'] = time(); // 验证码创建时间
            Session::set($key, $secode, '');

            return 'ok';
        } else {
            return 'faile';
        }
    }

    /* 加密验证码 */
    private function authcode($str) {
        $key = substr(md5($this->seKey), 5, 8);
        $str = substr(md5($str), 8, 10);
        return md5($key . $str);
    }
}