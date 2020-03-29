<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"F:\MaLong\item\faka\application\templates\pc\manage\default\site\domain.html";i:1539744083;s:72:"F:\MaLong\item\faka\application\templates\pc\manage\default\content.html";i:1539744038;}*/ ?>
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
        <label class="col-sm-2 control-label">主站域名</label>
        <div class='col-sm-8'>
            <input type="text" name="site_domain" required="required" title="请输入主站域名" placeholder="请输入主站域名" value="<?php echo sysconf('site_domain'); ?>" class="layui-input">
            <p class="help-block">主站域名</p>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">全站资源域名</label>
        <div class='col-sm-8'>
            <input type="text" name="site_domain_res" required="required" title="请输入全站资源域名" placeholder="请输入全站资源域名" value="<?php echo sysconf('site_domain_res'); ?>" class="layui-input">
            <p class="help-block">全站资源域名</p>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">店铺推广域名</label>
        <div class='col-sm-8'>
            <input type="text" name="site_shop_domain" required="required" title="请输入店铺推广域名" placeholder="请输入店铺推广域名" value="<?php echo sysconf('site_shop_domain'); ?>" class="layui-input">
            <p class="help-block">用于店铺推广使用的域名，防止主域名报毒</p>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">禁用子域名</label>
        <div class='col-sm-8'>
            <textarea name="disabled_domains" id="" cols="30" rows="5" class="layui-textarea"><?php echo sysconf('disabled_domains'); ?></textarea>
            <p class="help-block">禁用子域名，使用“|”分隔</p>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">短网址功能</label>
        <div class='col-sm-8'>
            <select name="site_domain_short" class="layui-input" required>
                <option value="" <?php if(sysconf('site_domain_short')==''): ?>selected<?php endif; ?>>不启用</option>
                <option value="Baidu" <?php if(sysconf('site_domain_short')=='Baidu'): ?>selected<?php endif; ?>>百度短网址</option>
                <option value="Sina" <?php if(sysconf('site_domain_short')=='Sina'): ?>selected<?php endif; ?>>新浪短网址</option>
                <option value="U6" <?php if(sysconf('site_domain_short')=='U6'): ?>selected<?php endif; ?>>U6短网址 </option>
            </select>
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