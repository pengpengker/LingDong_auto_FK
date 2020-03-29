<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"F:\MaLong\item\faka\application\templates\pc\manage\default\site\wordfilter.html";i:1539744083;s:72:"F:\MaLong\item\faka\application\templates\pc\manage\default\content.html";i:1539744038;}*/ ?>
<div class="ibox">
    
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
        
<form onsubmit="return false;" action="__SELF__" data-auto="true" method="post" class='form-horizontal' style='padding-top:20px'>

    <div class="form-group">
        <label class="col-sm-2 control-label">过滤开关</label>
        <div class='col-sm-8'>
            <select name="site_wordfilter_status" class="layui-input" required>
                <option value="0" <?php if(sysconf('site_wordfilter_status')==0): ?>selected<?php endif; ?>>关闭过滤</option>
                <option value="1" <?php if(sysconf('site_wordfilter_status')==1): ?>selected<?php endif; ?>>开启过滤</option>
            </select>
            <p class="help-block">过滤开关</p>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">危险关键字</label>
        <div class='col-sm-8'>
            <textarea name="site_wordfilter_danger" id="" cols="30" rows="5" class="layui-textarea"><?php echo sysconf('site_wordfilter_danger'); ?></textarea>
            <p class="help-block">危险关键字，使用“|”分隔</p>
        </div>
    </div>

    <div class="hr-line-dashed"></div>

    <div class="col-sm-4 col-sm-offset-2">
        <div class="layui-form-item text-center">
            <button class="layui-btn" type="submit">保存配置</button>
        </div>
    </div>

</form>
<script>
    layui.use('form', function(){
        var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
        form.render();
    });
</script>

    </div>
    
</div>