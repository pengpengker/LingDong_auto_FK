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
    const API_URL    = 'https://api.d5.nz/api/dwz/tcn.php';
    public function create($url)
    {
        $res=HttpService::get(SELF::API_URL,[
            'url'      =>$url,
        ]);
        if($res===null || $res===false){
            return false;
        }
        $json=json_decode($res);
        if(!$json || $json->code!== "200" || !$json->url){
            return false;
        }
        return $json->url;
    }
}
