<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:73:"F:\MaLong\item\faka\application\templates\pc\admin\default\log\index.html";i:1539744053;s:71:"F:\MaLong\item\faka\application\templates\pc\admin\default\content.html";i:1539744034;}*/ ?>
<div class="ibox">
    
    <?php if(isset($title)): ?>
    <div class="ibox-title notselect">
        <h5><?php echo $title; ?></h5>
        
<div class="nowrap pull-right margin-top-10" style="margin-top:10px">
    <?php if(auth("$classuri/del")): ?>
    <button data-update data-field='delete' data-action='<?php echo url("$classuri/del"); ?>' class='layui-btn layui-btn-small layui-btn-danger'>
        <i class='fa fa-remove'></i> 删除日志
    </button>
    <?php endif; ?>
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
        <label class="layui-form-label">操作者</label>
        <div class="layui-input-inline">
            <input name="username" value="<?php echo (\think\Request::instance()->get('username') ?: ''); ?>" placeholder="请输入操作者" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item layui-inline">
        <label class="layui-form-label">操作行为</label>
        <div class="layui-input-inline">
            <select name='action' class='layui-select' lay-search="">
                <option value=''> - 所有记录 -</option>
                <!--<?php foreach($actions as $action): ?>-->
                <!--<?php if($action===\think\Request::instance()->get('action')): ?>-->
                <option selected="selected" value='<?php echo $action; ?>'><?php echo $action; ?></option>
                <!--<?php else: ?>-->
                <option value='<?php echo $action; ?>'><?php echo $action; ?></option>
                <!--<?php endif; ?>-->
                <!--<?php endforeach; ?>-->
            </select>
        </div>
    </div>

    <div class="layui-form-item layui-inline">
        <label class="layui-form-label">操作内容</label>
        <div class="layui-input-inline">
            <input name="content" value="<?php echo (\think\Request::instance()->get('content') ?: ''); ?>" placeholder="请输入操作内容" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item layui-inline">
        <label class="layui-form-label">操作时间</label>
        <div class="layui-input-inline">
            <input name="date" id='range-date' value="<?php echo (\think\Request::instance()->get('date') ?: ''); ?>"
                   placeholder="请选择操作时间" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item layui-inline">
        <button class="layui-btn layui-btn-primary"><i class="layui-icon">&#xe615;</i> 搜 索</button>
    </div>
</form>
<!-- 表单搜索 结束 -->

<form onsubmit="return false;" data-auto="true" method="post">
    <input type="hidden" value="resort" name="action"/>
    <table class="layui-table" lay-skin="line" lay-size="sm">
        <thead>
        <tr>
            <th class='list-table-check-td'>
                <input data-none-auto="" data-check-target='.list-check-box' type='checkbox'/>
            </th>
            <th class='text-center'>操作者</th>
            <th class='text-left'>节点</th>
            <th class='text-left'>行为</th>
            <th class='text-left'>操作内容</th>
            <th class='text-left'>操作位置</th>
            <th class='text-left'>操作时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list as $key=>$vo): ?>
        <tr>
            <td class='list-table-check-td'>
                <input class="list-check-box" value='<?php echo $vo['id']; ?>' type='checkbox'/>
            </td>
            <td class='text-center'><?php echo $vo['username']; ?></td>
            <td class='text-left'><?php echo $vo['node']; ?></td>
            <td class='text-left'><?php echo $vo['action']; ?></td>
            <td class='text-left'><?php echo $vo['content']; ?></td>
            <td class='text-left'><?php echo (isset($vo['isp']) && ($vo['isp'] !== '')?$vo['isp']:$vo['ip']); ?></td>
            <td class='text-left'><?php echo format_datetime($vo['create_at']); ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if(isset($page)): ?><p><?php echo $page; ?></p><?php endif; ?>
    <script>
        window.form.render();
        window.laydate.render({range: true, elem: '#range-date', format: 'yyyy/MM/dd'});
    </script>
</form>

    </div>
    
</div>