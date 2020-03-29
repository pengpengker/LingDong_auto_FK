<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"F:\MaLong\item\faka\application\templates\pc\manage\default\user\manage_money.html";i:1539744078;}*/ ?>
<form class="layui-form layui-box" style='padding:25px 30px 20px 0' action="__SELF__" data-auto="true" method="post">

    <div class="layui-form-item">
        <label class="layui-form-label">资金信息</label>
        <div class="layui-input-block">
			<blockquote class="layui-elem-quote layui-quote-nm">
			<p>可用余额：<span class="text-danger"><?php echo $user['money']; ?></span>元</p>
			</blockquote>
			<blockquote class="layui-elem-quote layui-quote-nm">
			<p>冻结余额：<span class="text-danger"><?php echo $user['freeze_money']; ?></span>元</p>
			</blockquote>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">操作类型</label>
        <div class="layui-input-block">
            <input type="radio" title="加钱" name="action" value="inc" checked>
            <input type="radio" title="扣钱" name="action" value="dec">
            <input type="radio" title="解冻" name="action" value="unfreeze">
            <input type="radio" title="冻结" name="action" value="freeze">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">操作金额</label>
        <div class="layui-input-block">
            <input type="number" name="money" value="" required="required" title="请输入操作金额" placeholder="请输入操作金额" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">操作备注</label>
        <div class="layui-input-block">
            <textarea class="layui-textarea" name="mark" rows="5" cols="80"></textarea>
        </div>
    </div>

    <div class="hr-line-dashed"></div>

    <div class="layui-form-item text-center">
        <input type="hidden" name="user_id" value="<?php echo (isset($user['id']) && ($user['id'] !== '')?$user['id']:'0'); ?>">
        <button class="layui-btn" type='submit'>执行操作</button>
        <button class="layui-btn layui-btn-danger" type='button' data-confirm="确定要取消吗？" data-close>取消</button>
    </div>

</form>

<script>
    layui.use('form', function(){
        var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
        form.render();
    });
</script>
