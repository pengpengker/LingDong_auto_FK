<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:74:"F:\MaLong\item\faka\application\templates\pc\admin\default\user\index.html";i:1539744051;s:71:"F:\MaLong\item\faka\application\templates\pc\admin\default\content.html";i:1539744034;}*/ ?>
<div class="ibox">
    
    <?php if(isset($title)): ?>
    <div class="ibox-title notselect">
        <h5><?php echo $title; ?></h5>
        
<div class="nowrap pull-right" style="margin-top:10px">
    <button data-modal='<?php echo url("$classuri/add"); ?>' data-title="添加用户" class='layui-btn layui-btn-small'>
        <i class='fa fa-plus'></i> 添加用户
    </button>
    <button data-update data-field='delete' data-action='<?php echo url("$classuri/del"); ?>' class='layui-btn layui-btn-small layui-btn-danger'>
        <i class='fa fa-remove'></i> 删除用户
    </button>
</div>

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
<form class="layui-form layui-form-pane form-search" action="__SELF__" onsubmit="return false" method="get">
    <div class="layui-form-item layui-inline">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-inline">
            <input name="username" value="<?php echo (\think\Request::instance()->get('username') ?: ''); ?>" placeholder="请输入用户名" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item layui-inline">
        <label class="layui-form-label">手机号</label>
        <div class="layui-input-inline">
            <input name="phone" value="<?php echo (\think\Request::instance()->get('phone') ?: ''); ?>" placeholder="请输入手机号" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item layui-inline">
        <label class="layui-form-label">电子邮箱</label>
        <div class="layui-input-inline">
            <input name="mail" value="<?php echo (\think\Request::instance()->get('mail') ?: ''); ?>" placeholder="请输入电子邮箱" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item layui-inline">
        <label class="layui-form-label">登录时间</label>
        <div class="layui-input-inline">
            <input name="date" id='range-date' value="<?php echo (\think\Request::instance()->get('date') ?: ''); ?>"
                   placeholder="请选择登录时间" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item layui-inline">
        <button class="layui-btn layui-btn-primary"><i class="layui-icon">&#xe615;</i> 搜 索</button>
    </div>
</form>
<!-- 表单搜索 结束 -->

<form onsubmit="return false;" data-auto="true" method="post">
    <?php if(empty($list)): ?>
    <p class="help-block text-center well">没 有 记 录 哦！</p>
    <?php else: ?>
    <input type="hidden" value="resort" name="action"/>
    <table class="layui-table" lay-skin="line" lay-size="sm">
        <thead>
            <tr>
                <th class='list-table-check-td'>
                    <input data-none-auto="" data-check-target='.list-check-box' type='checkbox'/>
                </th>
                <th class='text-left nowrap'>用户名</th>
                <th class='text-left nowrap'>手机号</th>
                <th class='text-left nowrap'>电子邮箱</th>
                <th class='text-left nowrap'>登录次数</th>
                <th class='text-left nowrap'>最后登录</th>
                <th class='text-left nowrap'>状态</th>
                <th class='text-left nowrap'>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($list as $key=>$vo): ?>
            <tr>
                <td class='list-table-check-td'>
                    <input class="list-check-box" value='<?php echo $vo['id']; ?>' type='checkbox'/>
                </td>
                <td class='text-left nowrap'>
                    <?php echo $vo['username']; ?>
                </td>
                <td class='text-left nowrap'>
                    <?php echo (isset($vo['phone']) && ($vo['phone'] !== '')?$vo['phone']:"<span class='color-desc'>还没有设置手机号</span>"); ?>
                </td>
                <td class='text-left nowrap'>
                    <?php echo (isset($vo['mail']) && ($vo['mail'] !== '')?$vo['mail']:"<span class='color-desc'>还没有设置邮箱</span>"); ?>
                </td>
                <td class='text-left nowrap'>
                    <?php echo (isset($vo['login_num']) && ($vo['login_num'] !== '')?$vo['login_num']:"<span class='color-desc'>从未登录</span>"); ?>
                </td>
                <td class='text-left nowrap'>
                    <?php echo (format_datetime($vo['login_at']) ?: "<span class='color-desc'>从未登录</span>"); ?>
                </td>
                <td class='text-left nowrap'>
                    <?php if($vo['status'] == 0): ?>
                    <span>已禁用</span>
                    <?php elseif($vo['status'] == 1): ?>
                    <span style="color:#090">使用中</span>
                    <?php endif; ?>
                </td>
                <td class='text-left nowrap'>
                    <?php if(auth("$classuri/edit")): ?>
                    <span class="text-explode">|</span>
                    <a data-modal='<?php echo url("$classuri/edit"); ?>?id=<?php echo $vo['id']; ?>' href="javascript:void(0)">编辑</a>
                    <?php endif; if(auth("$classuri/auth")): ?>
                    <span class="text-explode">|</span>
                    <a data-modal='<?php echo url("$classuri/auth"); ?>?id=<?php echo $vo['id']; ?>' href="javascript:void(0)">授权</a>
                    <?php endif; if(auth("$classuri/pass")): ?>
                    <span class="text-explode">|</span>
                    <a data-modal='<?php echo url("$classuri/pass"); ?>?id=<?php echo $vo['id']; ?>' href="javascript:void(0)">密码</a>
                    <?php endif; if($vo['status'] == 1 and auth("$classuri/forbid")): ?>
                    <span class="text-explode">|</span>
                    <a data-update="<?php echo $vo['id']; ?>" data-field='status' data-value='0' data-action='<?php echo url("$classuri/forbid"); ?>'
                       href="javascript:void(0)">禁用</a>
                    <?php elseif(auth("$classuri/resume")): ?>
                    <span class="text-explode">|</span>
                    <a data-update="<?php echo $vo['id']; ?>" data-field='status' data-value='1' data-action='<?php echo url("$classuri/resume"); ?>'
                       href="javascript:void(0)">启用</a>
                    <?php endif; if(auth("$classuri/del")): ?>
                    <span class="text-explode">|</span>
                    <a data-update="<?php echo $vo['id']; ?>" data-field='delete' data-action='<?php echo url("$classuri/del"); ?>'
                       href="javascript:void(0)">删除</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if(isset($page)): ?><p><?php echo $page; ?></p><?php endif; endif; ?>
    <script>
        window.laydate.render({range: true, elem: '#range-date', format: 'yyyy/MM/dd'});
    </script>
</form>

    </div>
    
</div>