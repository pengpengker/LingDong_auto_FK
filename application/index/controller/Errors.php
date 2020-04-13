<?php

namespace app\index\controller;
use think\Controller;


class Errors extends Controller
{
    public function info()
    {
    	if(sysconf('site_status') === '0'){
    		//站点关闭提示
        	return $this->fetch();
    	}else{
    		$this->redirect('Index/Index');
    	}
    }
}
