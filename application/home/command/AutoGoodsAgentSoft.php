<?php

namespace app\home\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Log;
use think\Exception;

/**
 * 清理全网资源对接中的过期排序
 * @author Loopg
 * @version 1.0 2020年4月25日
 */
class AutoGoodsAgentSoft extends Command
{
    protected function configure()
    {
        $this->setName('AutoGoodsAgentSoft')->setDescription('Clean exptime agentlistsoft on database');
        $this->lockFileName = LOG_PATH . 'AutoGoodsAgentSoft.log';
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln("Clean in...");
        Log::record("========== " . date('Y-m-d H:i:s') . " 自动清理全网资源对接过期排序数据 ==========\n\n", Log::INFO);
        //1:查找所有过期的表
        try{
        	Db::name('goods_agent_soft')->whereTime('end_time','<',date('Y-m-d H:i:s'))->delete();
        }catch (\Exception $e) {
        	$output->writeln("清理时发生异常...");
        	Log::record("清理异常".$e->getmessage().'\r\n', Log::INFO);
        }
        Log::record("========== " . date('Y-m-d H:i:s') . " 自动清理全网资源对接过期排序数据执行完成 ==========\n\n", Log::INFO);
        $output->writeln("清理执行完成...");
    }
}