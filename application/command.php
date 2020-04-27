<?php
return [
    'app\home\command\UnfreezeMoney',  //自动解冻
    'app\home\command\AutoCash',  //自动提现
    'app\home\command\Migrate', //数据迁移
    'app\home\command\Migrate20180510',  //数据迁移
    'app\home\command\AutoEmptyGoodsTrash',  //清理超过15天的商品
    'app\home\command\AutoClearExpireOrder',  //数据迁移
    'app\home\command\AutoGoodsAgentSoft', //清理全网对接过期排序数据
];