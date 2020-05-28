<?php
/**
 * 新浪短网址
 * @author Veris
 */

namespace app\common\util\dwz;

use app\common\util\DWZ;
use service\HttpService;

class Sina extends DWZ
{
    const API_URL    = 'https://www.mynb8.com/api3/sina.html';
    public function create($url)
    {
        $res=HttpService::get(SELF::API_URL,[
        	'appkey'		=>'2c049da154ca78ec7a1c7d6a27e2a5bd',
            'long_url'      =>$url,
        ]);
        if($res===null || $res===false){
            return false;
        }
        $json=json_decode($res);
        if(!$json || $json->rs_code!== 0 || !$json->short_url){
            return false;
        }
        return $json->short_url;
    }
}
