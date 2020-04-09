<?php
/**
 * 新浪短网址
 * @author Veris
 */

namespace app\common\util\dwz;

use app\common\util\DWZ;
use service\HttpService;

class Im extends DWZ
{
    const API_URL    = 'https://915.im/api.ashx';
    const APP_KEY    = 'FC447277711EB70CB94E0DBAAF4E8BAA';
    const APP_Userid = '3101';
    const APP_Format = 'txt';

    public function create($url)
    {
        $res=HttpService::get(SELF::API_URL,[
            'format'   =>SELF::APP_Format,
            'userId'   =>SELF::APP_Userid,
            'key'      =>SELF::APP_KEY,
            'url'      =>$url,
        ]);
        if($res===null || $res===false){
            return false;
        }
        return $res;
    }
}
