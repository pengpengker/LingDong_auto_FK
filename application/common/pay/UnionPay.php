<?php
/**
 * 微信公众号支付
 * @author mapeijian
 */
namespace app\common\pay;

use think\Db;
use think\Request;
use app\common\Pay;
use think\Loader;
use think\Session;
use think\Exception;
use unionpay\sdk\AcpService;
use unionpay\sdk\SDKConfig;

class UnionPay extends Pay{
    protected $code='';
    protected $error='';

    public function getCode()
    {
        return $this->code;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * 支付
     * @param string $outTradeNo 外部单号
     * @param string $subject 标题
     * @param float $totalAmount 支付金额
     */
    public function order($outTradeNo,$subject,$totalAmount)
    {
        $AcpService = new AcpService();
        $SDKConfig = new SDKConfig();
        $params = array(
			//以下信息非特殊情况不需要改动
			'version' => '5.1.0',                 //版本号
			'encoding' => 'utf-8',				  //编码方式
			'txnType' => '01',				      //交易类型
			'txnSubType' => '01',				  //交易子类
			'bizType' => '000201',				  //业务类型
			'frontUrl' =>  Request::instance()->domain().'/pay/page/UnionPay',  //前台通知地址
			'backUrl' =>  Request::instance()->domain().'/pay/notify/UnionPay',	  //后台通知地址
			'signMethod' => '01',	              //签名方法
			'channelType' => '08',	              //渠道类型，07-PC，08-手机
			'accessType' => '0',		          //接入类型
			'currencyCode' => '156',	          //交易币种，境内商户固定156
			
			//TODO 以下信息需要填写
			'merId' => $this->account->params->merId,		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
			'orderId' => $outTradeNo,	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
			'txnTime' => date('YmdHis'),	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
			'txnAmt' => $totalAmount*100,	//交易金额，单位分，此处默认取demo演示页面传递的参数
			
			// 订单超时时间。
			// 超过此时间后，除网银交易外，其他交易银联系统会拒绝受理，提示超时。 跳转银行网银交易如果超时后交易成功，会自动退款，大约5个工作日金额返还到持卡人账户。
			// 此时间建议取支付时的北京时间加15分钟。
			// 超过超时时间调查询接口应答origRespCode不是A6或者00的就可以判断为失败。
			'payTimeout' => date('YmdHis', strtotime('+15 minutes')), 
	
			'riskRateInfo' =>'{commodityName='.$subject.'}',
	
			// 请求方保留域，
			// 透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据。
			// 出现部分特殊字符时可能影响解析，请按下面建议的方式填写：
			// 1. 如果能确定内容不会出现&={}[]"'等符号时，可以直接填写数据，建议的方法如下。
			//    'reqReserved' =>'透传信息1|透传信息2|透传信息3',
			// 2. 内容可能出现&={}[]"'符号时：
			// 1) 如果需要对账文件里能显示，可将字符替换成全角＆＝｛｝【】“‘字符（自己写代码，此处不演示）；
			// 2) 如果对账文件没有显示要求，可做一下base64（如下）。
			//    注意控制数据长度，实际传输的数据长度不能超过1024位。
			//    查询、通知等接口解析时使用base64_decode解base64后再对数据做后续解析。
			//    'reqReserved' => base64_encode('任意格式的信息都可以'),
			
			//TODO 其他特殊用法请查看 special_use_purchase.php
		);
		$AcpService->sign($params);
		$uri = $SDKConfig->getSDKConfig()->frontTransUrl;
		$html_form = $AcpService::createAutoFormHtml( $params, $uri );
        $this->code    =0;
        $obj           =new \stdClass();
        $obj->pay_url  =$html_form;
        $obj->content_type = 3;
        return $obj;
    }
    
    /**
     * 支付同步通知处理
     */
    public function page_callback($params,$order) {
        header('location:'.url('/orderquery').'?orderid='.$order->trade_no);
        die('success');
    }
    
    /**
     * 支付异步通知处理
     */
    public function notify_callback($params,$order) 
    {
        if($params && isset($params['orderId']) && $params['respCode']==='00'){
            // 验签
            $AcpService = new AcpService();
            if(!$AcpService->validate($params)){
                record_file_log('UnionPay_notify_error','验签错误！'."\r\n".$order->trade_no."\r\n");
                die('验签错误！');
            }
            // 金额异常检测
            if(empty($order->dj_order_id)){
                if($order->total_price>$params['txnAmt']/100){
                    record_file_log('UnionPay_notify_error','金额异常！'."\r\n".$order->trade_no."\r\n订单金额：{$order->total_price}，已支付：{$params['txnAmt']}");
                    die('金额异常！');
                }
            }else{
                $sj_order = Order::get(['trade_no' => $order->dj_order_id]);
                if($sj_order){
                    if(round($order->total_price,3)+round($sj_order->total_price,3)>round($params['txnAmt']/100,3)){
                        record_file_log('UnionPay_notify_error','对接支付总金额异常！'."\r\n".$order->trade_no."\r\n订单金额：{$order->total_price}，已支付：{$params['txnAmt']}");
                        die('对接支付总金额异常！');
                    }
                }else{
                    die('上级订单不存在');
                }
            }
            // TODO 这里去完成你的订单状态修改操作
            // 流水号
            $order->transaction_id =$params['queryId'];
            $this->completeOrder($order);
            record_file_log('UnionPay_notify_success',$order->trade_no);
            echo 'success';
            return true;
        }
    }
}
