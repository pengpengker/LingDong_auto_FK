<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"F:\MaLong\item\faka\application\templates\pc\manage\default\user\unlock.html";i:1539744078;s:72:"F:\MaLong\item\faka\application\templates\pc\manage\default\content.html";i:1539744038;}*/ ?>
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
        
<!-- 表单搜索 开始 -->
<form class="layui-form layui-form-pane form-search" action="__SELF__" data-auto="true" method="post" style="padding: 50px;">
    <div class="layui-form-item layui-block">
        <label class="layui-form-label">会员类型</label>
        <div class="layui-input-inline">
            <select class="layui-form-item layui-inline" name="usertype" style="display: block;height: 32px;">
                <option value="0">普通用户</option>
                <option value="1">管理员</option>
            </select>
        </div>
    </div>


    <div class="layui-form-item layui-block">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-inline">
            <input name="login_name" value="" placeholder="用户名" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item layui-inline">
        <button class="layui-btn layui-btn-primary"> 解 锁</button>
    </div>
</form>

    </div>
    
</div>