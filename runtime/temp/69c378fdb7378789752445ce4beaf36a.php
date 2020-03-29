<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"F:\MaLong\item\faka\application\templates\pc\manage\default\user\rate.html";i:1539744078;}*/ ?>
<form class="layui-form layui-box" style='padding:25px 30px 20px 0' action="__SELF__" data-auto="true" method="post">

    <div class="layui-form-item">
        <label class="layui-form-label">费率分组</label>
        <div class="layui-input-block">
            <select id="group" name="group">
                <option value="">不设置费率分组</option>
                <?php foreach($rate_group as $v): ?>
                <option value="<?php echo $v['id']; ?>" <?php if($v['id'] == $userGroup) { ?>selected<?php } ?>><?php echo $v['title']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <?php foreach($channels as $v): ?>
    <div class="layui-form-item">
        <label class="layui-form-label"><?php echo $v['title']; ?></label>
        <div class="layui-input-block">
            <input id="channel_ids<?php echo $v['id']; ?>" type="number" name="channel_ids[<?php echo $v['id']; ?>]" value="<?php echo (isset($userRate[$v['id']]) && ($userRate[$v['id']] !== '')?$userRate[$v['id']]:''); ?>" title="请输入<?php echo $v['title']; ?>" placeholder="请输入<?php echo $v['title']; ?>" class="layui-input">
            <span>单位：千分率，如千分之三，设置为 3 即可。充值费率：<?php echo $v['lowrate']*1000; ?>‰ 封顶费率：<?php echo $v['highrate']*1000; ?>‰</span>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="hr-line-dashed"></div>

    <div class="layui-form-item text-center">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <button class="layui-btn" type='submit'>保存</button>
        <button class="layui-btn layui-btn-danger" type='button' data-confirm="确定要取消吗？" data-close>取消</button>
    </div>

</form>

<script>
    group = {};
    layui.use('form', function(){
        var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
        form.render();

        form.on('select', function(){
            var id =$('#group').val();
            var groupInfo = group['g' + id];
            if (groupInfo) {
                //已经请求过分组数据
                setRate(groupInfo);
            } else {
                //没有请求过分组数据
                $.get("<?php echo url('manage/user/getRateRouteInfo'); ?>", {id: id}, function (res) {
                    if(res.code == 200) {
                        group['g' + id] = res.data
                        setRate(res.data);
                    }else{
                        layer.msg('费率分组数据请求错误，请刷新重试',{icon:2})
                    }
                }, 'json')
            }
        });
    });

    function setRate(data){
        for (i in data.rules){
            $('#channel_ids' + data.rules[i].channel_id).val(data.rules[i].rate * 1000);
        }
    }

</script>
