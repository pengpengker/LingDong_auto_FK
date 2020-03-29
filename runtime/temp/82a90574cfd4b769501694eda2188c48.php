<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:74:"F:\MaLong\item\faka\application\templates\pc\admin\default\node\index.html";i:1539744053;s:71:"F:\MaLong\item\faka\application\templates\pc\admin\default\content.html";i:1539744034;}*/ ?>
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
        
<table class="table table-hover">
    <thead>
    <tr>
        <th style="width:20px"></th>
        <th class='text-left'>系统节点结构</th>
        <th class='text-left'></th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($nodes as $key=>$vo): ?>
    <tr>
        <td style="width:20px"></td>
        <td class='text-left nowrap'>
            <?php echo $vo['spl']; ?><?php echo $vo['node']; if(auth("$classuri/save")): ?>
            &nbsp;<input class='layui-input layui-input-inline title-input' name='title' data-node="<?php echo $vo['node']; ?>"
                         value="<?php echo $vo['title']; ?>" style='height:28px;line-height:28px;width:auto'/>
            <?php endif; ?>
        </td>
        <td class='text-left nowrap'>
            <?php if(auth("$classuri/save")): ?>
            <label data-tips-text="勾选后需要登录后才能访问">
                <?php if(substr_count($vo['node'],'/')==2): if(!(empty($vo['is_login']) || (($vo['is_login'] instanceof \think\Collection || $vo['is_login'] instanceof \think\Paginator ) && $vo['is_login']->isEmpty()))): ?>
                <input checked='checked' class="check-box login_<?php echo $key; ?>"
                       type='checkbox' value='1' name='is_login' data-node="<?php echo $vo['node']; ?>"
                       onclick="!this.checked&&($('.auth_<?php echo $key; ?>')[0].checked=!!this.checked)"/>
                <?php else: ?>
                <input class="check-box login_<?php echo $key; ?>" type='checkbox' value='1' name='is_login' data-node="<?php echo $vo['node']; ?>"
                       onclick="!this.checked&&($('.auth_<?php echo $key; ?>')[0].checked=!!this.checked)"/>
                <?php endif; ?>
                加入登录控制
                <?php endif; ?>
            </label>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <label data-tips-text="勾选后需配置用户权限后才能访问">
                <?php if(substr_count($vo['node'],'/')==2): if(!(empty($vo['is_auth']) || (($vo['is_auth'] instanceof \think\Collection || $vo['is_auth'] instanceof \think\Paginator ) && $vo['is_auth']->isEmpty()))): ?>
                <input name='is_auth' data-node="<?php echo $vo['node']; ?>" checked='checked' class="check-box auth_<?php echo $key; ?>"
                       type='checkbox' onclick="this.checked&&($('.login_<?php echo $key; ?>')[0].checked=!!this.checked)"
                       value='1'/>
                <?php else: ?>
                <input name='is_auth' data-node="<?php echo $vo['node']; ?>" class="check-box auth_<?php echo $key; ?>" type='checkbox' value='1'
                       onclick="this.checked&&($('.login_<?php echo $key; ?>')[0].checked=!!this.checked)"
                />
                <?php endif; ?>
                加入权限控制
                <?php endif; ?>
            </label>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <label data-tips-text="勾选后配置菜单时节点可自动选择">
                <?php if(substr_count($vo['node'],'/')==2): if(!(empty($vo['is_menu']) || (($vo['is_menu'] instanceof \think\Collection || $vo['is_menu'] instanceof \think\Paginator ) && $vo['is_menu']->isEmpty()))): ?>
                <input name='is_menu' data-node="<?php echo $vo['node']; ?>" checked='checked' class='check-box menu_<?php echo $key; ?>'
                       type='checkbox' value='1'/>
                <?php else: ?>
                <input name='is_menu' data-node="<?php echo $vo['node']; ?>" class='check-box menu_<?php echo $key; ?>' type='checkbox' value='1'/>
                <?php endif; ?>
                加入菜单节点选择器
                <?php endif; ?>
            </label>
            <?php endif; ?>
        </td>
        <td style="width:100%"></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php if(auth("$classuri/save")): ?>
<script>
    $(function () {
        $('input.title-input').on('blur', function () {
            var data = {list: [{name: this.name, value: this.value, node: this.getAttribute('data-node')}]};
            $.form.load('<?php echo url("save"); ?>', data, 'POST', function (ret) {
                if (ret.code === 0) {
                    setTimeout(function () {
                        $.form.reload();
                    }, 3000);
                    $.msg.auto(ret);
                }
                return false;
            });
        });
        $('input.check-box').on('click', function () {
            var data = {list: []};
            $(this).parent().parent().find('input').map(function () {
                data.list.push({name: this.name, value: this.checked ? 1 : 0, node: this.getAttribute('data-node')});
            });
            $.form.load('<?php echo url("save"); ?>', data, 'POST', function (ret) {
                if (ret.code === 0) {
                    setTimeout(function () {
                        $.form.reload();
                    }, 3000);
                    $.msg.auto(ret);
                }
                return false;
            });
        });
    });
</script>
<?php endif; ?>

    </div>
    
</div>