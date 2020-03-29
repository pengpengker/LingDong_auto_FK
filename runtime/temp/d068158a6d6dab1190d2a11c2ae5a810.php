<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"F:\MaLong\item\faka\application\templates\pc\manage\default\index\main.html";i:1539744083;s:72:"F:\MaLong\item\faka\application\templates\pc\manage\default\content.html";i:1539744038;}*/ ?>
<div class="ibox">
    
<style>
    #acc li{float:left;width:24%;margin-right:1%;border:1px solid #eee;background:#fff;
		min-height: 300px;
		padding-top:30px;}
    #acc li:last-child{margin-right:0;}
    #acc li div{width:80%;margin:0 auto;padding-bottom:40px;}
    #acc li div img{float:left;margin-right:10px; width: 60px; height: 60px;}
    #acc li div p{font-size:14px;line-height:30px;padding-top:10px;}
    #acc li div span{display:block;border-top:1px solid #eee;clear:both;margin:20px auto;
		padding-top:10px;
		font-size:14px;line-height:24px;}
</style>

    <?php if(isset($title)): ?>
    <div class="ibox-title notselect">
        <h5><?php echo $title; ?></h5>
        
    </div>
    <?php endif; ?>
    <div class="ibox-content">
        <?php if(isset($alert)): ?>
        <div class="alert alert-<?php echo $alert['type']; ?> alert-dismissible" role="alert" style="border-radius:0">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php if(isset($alert['title'])): ?><p style="font-size:18px;padding-bottom:10px"><?php echo $alert['title']; ?></p><?php endif; if(isset($alert['content'])): ?><p style="font-size:14px"><?php echo $alert['content']; ?></p><?php endif; ?>
        </div>
        <?php endif; ?>
        
<div id="acc">
	<ul>
		<li>
		<div>
			<img src="/static/admin/images/icon1.png" width="80" height="80">
			<p>
				<font size="+3" color="#feb41c"><a href="/admin.html#/manage/user/index.html?date_range=<?php echo $today; ?> - <?php echo $today; ?>" style="color: rgb(254, 180, 28);"><?php echo $userStatis['today_register']; ?></a></font><br>
				 今日注册
			</p>
			<span>
昨日注册：
			<b><a href="/admin.html#/manage/user/index.html?date_range=<?php echo $yesterday; ?> - <?php echo $yesterday; ?>" style="color:#2c3038;"><?php echo $userStatis['yester_register']; ?></a></b>
			<br>
未审核：
			<b><a href="/admin.html#/manage/user/index.html?status=0" style="color:#2c3038;"><?php echo $userStatis['notpass_count']; ?></a></b>
			<br>
已冻结：
			<b><a href="/admin.html#/manage/user/index.html?is_freeze=1" style="color:#2c3038;"><?php echo $userStatis['is_freeze_count']; ?></a></b>
			</span>
		</div>
		</li>
		<li>
		<div>
			<img src="/static/admin/images/icon22.png" width="80" height="80">
			<p>
				<font size="+3" color="#feb41c"><a href="/admin.html#/manage/order/index.html?date_range=<?php echo $today; ?> - <?php echo $today; ?>" style="color: rgb(254, 180, 28);"><?php echo $orderStatis['today_quantity']; ?></a></font><br>
				 今日提交（笔）
			</p>
			<span>今日未付款：<b><a href="/admin.html#/manage/order/index.html?date_range=<?php echo $today; ?> - <?php echo $today; ?>&amp;status=0" style="color:#2c3038;"><?php echo $orderStatis['today_unpaid']; ?></a></b>
			<br>
今日成功订单：
			<b><a href="/admin.html#/manage/order/index.html?date_range=<?php echo $today; ?> - <?php echo $today; ?>&amp;status=1" style="color:#2c3038;"><?php echo $orderStatis['today_paid']; ?> </a></b>
			<br>
昨日成功订单：
			<b><a href="/admin.html#/manage/order/index.html?date_range=<?php echo $yesterday; ?> - <?php echo $yesterday; ?>&amp;status=1" style="color:#2c3038;"><?php echo $orderStatis['yester_paid']; ?> </a></b>
			</span>
		</div>
		</li>
		<li>
		<div>
			<img src="/static/admin/images/icon3.png" width="80" height="80">
			<p>
				<font size="+3" color="#feb41c"><a href="/admin.html#/manage/order/index.html?date_range=<?php echo $today; ?> - <?php echo $today; ?>&amp;status=1" style="color: rgb(254, 180, 28);"><?php echo $incomeStatis['today_sum']; ?> </a></font>
				<br>
				 今日付款总额（元）
			</p>
			<span>
				今日用户收入：<b><a href="/admin.html#/manage/order/index.html?date_range=<?php echo $today; ?> - <?php echo $today; ?>&amp;status=1" style="color:#2c3038;"><?php echo $incomeStatis['today_actual_sum']; ?> </a></b>
                <br>
                今日用户利润：<b><a href="/admin.html#/manage/order/index.html?date_range=<?php echo $today; ?> - <?php echo $today; ?>&amp;status=1" style="color:#2c3038;"><?php echo $incomeStatis['today_profit_sum']; ?> </a></b>
                <br>
                昨日付款总额： <b><a href="/admin.html#/manage/order/index.html?date_range=<?php echo $yesterday; ?> - <?php echo $yesterday; ?>&amp;status=1" style="color:#2c3038;"><?php echo $incomeStatis['yester_sum']; ?> </a></b>
                <br>
                昨日用户收入：
                <b><a href="/admin.html#/manage/order/index.html?date_range=<?php echo $yesterday; ?> - <?php echo $yesterday; ?>&amp;status=1" style="color:#2c3038;"><?php echo $incomeStatis['yester_actual_sum']; ?> </a></b>
			</span>
		</div>
		</li>
		<li>
		<div>
			<img src="/static/admin/images/icon44.png" width="80" height="80">
			<p>
				<font size="+3" color="#feb41c">
				<a href="/admin.html#/manage/cash/index.html?date_range=<?php echo $today; ?> - <?php echo $today; ?>" style="color: rgb(254, 180, 28);"><?php echo $cashStatis['today_sum']; ?> </a>
				</font><br>
				今日提现总额（元）
			</p>
			<span>昨日提现总额：<b><a href="/admin.html#/manage/cash/index.html?date_range=<?php echo $yesterday; ?> - <?php echo $yesterday; ?>" style="color:#2c3038;"><?php echo $cashStatis['yester_sum']; ?> </a></b>
			<br>
			今日付款总额：<b><a href="/admin.html#/manage/cash/index.html?date_range=<?php echo $today; ?> - <?php echo $today; ?>&amp;status=1" style="color:#2c3038;"><?php echo $cashStatis['today_ok_sum']; ?> </a></b>
			<br>
			昨日付款总额：<b><a href="/admin.html#/manage/cash/index.html?date_range=<?php echo $yesterday; ?> - <?php echo $yesterday; ?>&amp;status=1" style="color:#2c3038;"><?php echo $cashStatis['yester_ok_sum']; ?> </a></b></span>
		</div>
		</li>
	</ul>
</div>
<div id="channelStatis" style="width: 100%;height:400px;float:left"></div>
<div id="monthStatis" style="width: 100%;height:400px;float:left"></div>

    </div>
    
<script>
var channelStatis = echarts.init(document.getElementById('channelStatis'));
var today_option = {
    title : {
        text: '今日支付通道分析',
        subtext: '共<?php echo count($channelStatis['channel']); ?>个支付通道',
        x:'left'
    },
    tooltip : {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    legend: {
        type: 'scroll',
        orient : 'vertical',
        x : 'left',
        top: 50,
        bottom: 50,
        left: 0,
        data:[
            <?php foreach($channelStatis["channel"] as $v): ?>
            '<?php echo $v; ?>',
            <?php endforeach; ?>
        ]
    },
    color:['#63cd93','#4ab47a'],
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: true, readOnly: true},
            magicType : {
                show: true,
                type: ['pie', 'funnel'],
                option: {
                    funnel: {
                        x: '25%',
                        width: '50%',
                        funnelAlign: 'left',
                        max: 1548
                    }
                }
            },
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    series : [
        {
            name:'实时统计',
            type:'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:[
                <?php foreach($channelStatis['today'] as $channel): ?>
                {value:<?php echo $channel['money']; ?>, name:'<?php echo $channel['title']; ?>'},
                <?php endforeach; ?>
            ]
        }
    ]
};
channelStatis.setOption(today_option, true);

var monthStatis = echarts.init(document.getElementById('monthStatis'));
var month_option = {
    title: {
        text: '最近30天交易统计',
        x:'left'
    },
    color:['#45c8f4','#4ab47a'],
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'cross'
        }
    },
    legend: {
        data:['交易金额','收入金额'],
        x:'right'
    },
    grid: {
        top: 70,
        bottom: 50
    },
    xAxis: [
        {
            type : 'category',
            boundaryGap : false,
            data : [<?php echo $monthStatis['title']; ?>]
        }
    ],
    yAxis: [
        {
            type: 'value',
            left: 0
        }
    ],
    series: [
        {
            name:'交易金额',
            type:'line',
            smooth: true,
            data:[<?php echo $monthStatis['transaction_money']; ?>]
        },
        {
            name:'收入金额',
            type:'line',
            smooth: true,
            data:[<?php echo $monthStatis['actual_money']; ?>]
        }
    ]
};

monthStatis.setOption(month_option, true);

$(function(){
    $(window).resize(function(){
        // monthStatis.clear();
        // channelStatis.clear();
        // monthStatis.setOption(month_option, true);
        // channelStatis.setOption(today_option, true);
        monthStatis_width = $('#monthStatis').width();
        monthStatis_height = $('#monthStatis').height();
        monthStatis.resize({
            width: monthStatis_width,
            height:monthStatis_height
        })
        channelStatis_width = $('#channelStatis').width();
        channelStatis_height = $('#channelStatis').height();
        channelStatis.resize({
            width: channelStatis_width,
            height:channelStatis_height
        })
    });
})
</script>

</div>