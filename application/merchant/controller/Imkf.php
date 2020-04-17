<?php


namespace app\merchant\controller;

use service\MerchantLogService;
use think\Db;

class Imkf extends Base
{
    public function index()
    {
        $this->setTitle('在线沟通');
        
        return $this->fetch();
    }
}