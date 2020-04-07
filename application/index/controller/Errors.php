<?php

namespace app\index\controller;
use think\Controller;


class Errors extends Controller
{
    public function info()
    {
        //站点关闭提示
        return $this->fetch();
    }
}
