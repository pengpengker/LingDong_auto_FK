<?php

namespace Gateway\Pay\WeChat;

use App\Library\CurlRequest;
use App\Library\Helper;
use Gateway\Pay\ApiInterface;
use Illuminate\Support\Facades\Log;

class Api implements ApiInterface
{
    private $url_notify = '';
    private $url_return = '';

    public function __construct($spbf68a1)
    {
        $this->url_notify = SYS_URL_API . '/pay/notify/' . $spbf68a1;
        $this->url_return = SYS_URL . '/pay/return/' . $spbf68a1;
    }

    function goPay($sp3125db, $spce6180, $sp73abe8, $spe456af, $spb7b113)
    {
        $sp5a0200 = $spb7b113;
        $sp94a44d = strtoupper($sp3125db['payway']);
        if (strpos(@$_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            $sp94a44d = 'JSAPI';
            $sp8ac132 = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}" . '/pay/' . $spce6180;
            $sp65df55 = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $sp3125db['APPID'] . '&redirect_uri=' . urlencode($sp8ac132) . '&response_type=code&scope=snsapi_base#wechat_redirect';
            if (!isset($_GET['code'])) {
                header('Location: ' . $sp65df55);
                die;
            }
            $sp9d708d = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $sp3125db['APPID'] . '&secret=' . $sp3125db['APPSECRET'] . '&code=' . $_GET['code'] . '&grant_type=authorization_code';
            $spa51230 = @json_decode(CurlRequest::get($sp9d708d), true);
            if (!is_array($spa51230) || empty($spa51230['openid'])) {
                if (isset($spa51230['errcode']) && $spa51230['errcode'] === 40163) {
                    header('Location: ' . $sp65df55);
                    die;
                }
                die('<h1>获取微信OPENID<br>错误信息: ' . (isset($spa51230['errcode']) ? $spa51230['errcode'] : $spa51230) . '<br>' . (isset($spa51230['errmsg']) ? $spa51230['errmsg'] : $spa51230) . '<br>请返回重试</h1>');
            }
            $spa63a19 = $spa51230['openid'];
        } else {
            if ($sp94a44d === 'JSAPI') {
                $sp8ac132 = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}" . '/pay/' . $spce6180;
                header('Location: /qrcode/pay/' . $spce6180 . '/wechat?url=' . urlencode($sp8ac132));
                die;
            }
        }
        $this->defineWxConfig($sp3125db);
        require_once __DIR__ . '/lib/WxPay.Api.php';
        require_once 'WxPay.NativePay.php';
        require_once 'WxLog.php';
        $spb13521 = new \NativePay();
        $sp72c497 = new \WxPayUnifiedOrder();
        $sp72c497->SetBody($sp73abe8);
        $sp72c497->SetAttach($spce6180);
        $sp72c497->SetOut_trade_no($spce6180);
        $sp72c497->SetTotal_fee($sp5a0200);
        $sp72c497->SetTime_start(date('YmdHis'));
        $sp72c497->SetTime_expire(date('YmdHis', time() + 600));
        $sp72c497->SetGoods_tag('pay');
        $sp72c497->SetTrade_type($sp94a44d);
        if ($sp94a44d === 'MWEB') {
            $sp72c497->SetScene_info('{"h5_info": {"type":"Wap","wap_url": "' . SYS_URL . '","wap_name": "发卡平台"}}');
        }
        if ($sp94a44d === 'JSAPI') {
            $sp72c497->SetOpenid($spa63a19);
        }
        $sp72c497->SetProduct_id($spce6180);
        $sp72c497->SetSpbill_create_ip(Helper::getIP());
        $sp72c497->SetNotify_url($this->url_notify);
        $sp5c45d8 = $spb13521->unifiedOrder($sp72c497);
        function getValue($spce6180, $sp5c45d8, $sp10da54)
        {
            if (!isset($sp5c45d8[$sp10da54])) {
                Log::error('Pay.WeChat.goPay, order_no:' . $spce6180 . ', error:' . json_encode($sp5c45d8));
                if (isset($sp5c45d8['err_code_des'])) {
                    throw new \Exception($sp5c45d8['err_code_des']);
                }
                if (isset($sp5c45d8['return_msg'])) {
                    throw new \Exception($sp5c45d8['return_msg']);
                }
                throw new \Exception('获取支付数据失败');
            }
            return $sp5c45d8[$sp10da54];
        }

        if ($sp94a44d === 'NATIVE') {
            $sp09d604 = getValue($spce6180, $sp5c45d8, 'code_url');
            header('Location: /qrcode/pay/' . $spce6180 . '/wechat?url=' . urlencode($sp09d604));
        } elseif ($sp94a44d === 'JSAPI') {
            $sp413bca = array('appId' => $sp3125db['APPID'], 'timeStamp' => strval(time()), 'nonceStr' => md5(time() . 'nonceStr'), 'package' => 'prepay_id=' . getValue($spce6180, $sp5c45d8, 'prepay_id'), 'signType' => 'MD5');
            $sp931fd0 = new \WxPayJsApiPay();
            $sp931fd0->FromArray($sp413bca);
            $sp413bca['paySign'] = $sp931fd0->MakeSign();
            header('Location: /qrcode/pay/' . $spce6180 . '/wechat?url=' . urlencode(json_encode($sp413bca)));
        } elseif ($sp94a44d === 'MWEB') {
            $sp09d604 = getValue($spce6180, $sp5c45d8, 'mweb_url');
            $sp0f4dd5 = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}" . '/qrcode/pay/' . $spce6180 . '/wechat?url=query';
            echo view('utils.redirect', array('url' => $sp09d604 . '&redirect_url=' . urlencode($sp0f4dd5)));
        }
        die;
    }

    private function defineWxConfig($sp3125db)
    {
        if (!defined('wx_APPID')) {
            define('wx_APPID', $sp3125db['APPID']);
        }
        if (!defined('wx_MCHID')) {
            define('wx_MCHID', $sp3125db['MCHID']);
        }
        if (!defined('wx_SUBAPPID')) {
            define('wx_SUBAPPID', @$sp3125db['sub_appid']);
        }
        if (!defined('wx_SUBMCHID')) {
            define('wx_SUBMCHID', @$sp3125db['sub_mch_id']);
        }
        if (!defined('wx_KEY')) {
            define('wx_KEY', $sp3125db['KEY']);
        }
        if (!defined('wx_APPSECRET')) {
            define('wx_APPSECRET', $sp3125db['APPSECRET']);
        }
    }

    function verify($sp3125db, $sp5aca2e)
    {
        $spd8c93f = isset($sp3125db['isNotify']) && $sp3125db['isNotify'];
        $this->defineWxConfig($sp3125db);
        require_once __DIR__ . '/lib/WxPay.Api.php';
        require_once 'WxLog.php';
        if ($spd8c93f) {
            return (new PayNotifyCallBack($sp5aca2e))->Handle(false);
        } else {
            $spce6180 = @$sp3125db['out_trade_no'];
            $sp72c497 = new \WxPayOrderQuery();
            $sp72c497->SetOut_trade_no($spce6180);
            if (isset($sp3125db['sub_mch_id'])) {
                $sp72c497->SetSub_mch_id($spce6180);
            }
            try {
                $sp5c45d8 = \WxPayApi::orderQuery($sp72c497);
                if (array_key_exists('trade_state', $sp5c45d8) && $sp5c45d8['trade_state'] == 'SUCCESS') {
                    call_user_func_array($sp5aca2e, array($sp5c45d8['out_trade_no'], $sp5c45d8['total_fee'], $sp5c45d8['transaction_id']));
                    return true;
                } else {
                    Log::debug('Pay.WeChat.verify, orderQuery failed. ' . json_encode($sp5c45d8));
                    return false;
                }
            } catch (\Throwable $spdfbc42) {
                Log::error('Pay.WeChat.verify, orderQuery exception:. ' . $spdfbc42->getMessage(), array('exception' => $spdfbc42));
                return false;
            }
        }
    }

    function refund($sp3125db, $spebe6f4, $spd5193b, $spb7b113)
    {
        if (!isset($sp3125db['ssl_cert']) || !isset($sp3125db['ssl_key'])) {
            throw new \Exception('请设置 ssl_cert(证书文件) 和 ssl_key(证书key)');
        }
        $this->defineWxConfig($sp3125db);
        if (!defined('wx_SSLCERT')) {
            $sp80c49f = tmpfile();
            fwrite($sp80c49f, '-----BEGIN CERTIFICATE-----
' . wordwrap(trim($sp3125db['ssl_cert']), 64, '
', true) . '
-----END CERTIFICATE-----');
            define('wx_SSLCERT', stream_get_meta_data($sp80c49f)['uri']);
        }
        if (!defined('wx_SSLKEY')) {
            $sp636525 = tmpfile();
            fwrite($sp636525, '-----BEGIN PRIVATE KEY-----
' . wordwrap(trim($sp3125db['ssl_key']), 64, '
', true) . '
-----END PRIVATE KEY-----');
            define('wx_SSLKEY', stream_get_meta_data($sp636525)['uri']);
        }
        require_once __DIR__ . '/lib/WxPay.Api.php';
        require_once 'WxLog.php';
        $sp72c497 = new \WxPayRefund();
        $sp72c497->SetOut_refund_no('anfaka' . date('YmdHis'));
        $sp72c497->SetOut_trade_no($spebe6f4);
        $sp72c497->SetTotal_fee($spb7b113);
        $sp72c497->SetRefund_fee($spb7b113);
        $sp5c45d8 = \WxPayApi::refund($sp72c497);
        if ($sp5c45d8['return_code'] !== 'SUCCESS') {
            throw new \Exception($sp5c45d8['return_msg']);
        }
        if ($sp5c45d8['result_code'] !== 'SUCCESS') {
            throw new \Exception($sp5c45d8['err_code_des'], $sp5c45d8['err_code']);
        }
        return true;
    }
}