<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"F:\MaLong\item\faka\application\templates\pc\manage\default\backup\index.html";i:1539744085;s:72:"F:\MaLong\item\faka\application\templates\pc\manage\default\content.html";i:1539744038;}*/ ?>
<div class="ibox">
    
    <?php if(isset($title)): ?>
    <div class="ibox-title notselect">
        <h5><?php echo $title; ?></h5>
        
<div class="nowrap pull-right" style="margin-top:10px">
    <button data-modal='<?php echo url("backall"); ?>' data-title="添加备份" class='layui-btn layui-btn-small'><i
            class='fa fa-plus'></i> 添加备份
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
        

<form onsubmit="return false;" data-auto="true" method="post">
    <input type="hidden" value="resort" name="action"/>
    <table class="layui-table" lay-skin="line" lay-size="sm">
        <thead>
        <tr>
            <th>序号</th>
            <th>文件名</th>
            <th>创建时间</th>
            <th>大小</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list as $k => $v): ?>
        <tr>
            <td><?php echo $k+1; ?></td>
            <td><?php echo $v['name']; ?></td>
            <td><?php echo $v['time']; ?></td>
            <td><?php echo $v['size']; ?></td>
            <td>
                <a data-update="-1" data-field='file' data-value='<?php echo $v['name']; ?>' data-action='/manage/backup/deletebak?file=<?php echo $v['name']; ?>' href="javascript:void(0)">删除</a>
                <a href="javascript:void(0)" onclick="recover(this,'<?php echo $v['name']; ?>')">恢复</a>
                <a style="display:none" id="btn-recover" data-title="恢复" data-modal='/manage/backup/recover?file=<?php echo $v['name']; ?>' href="javascript:void(0)">恢复</a>
                <a href="/manage/backup/downloadBak?file=<?php echo $v['name']; ?>">下载</a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</form>
<script>
    layui.use('form', function(){
        var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
        form.render();
    });
    /*删除*/
    function recover(obj, file) {
        layer.confirm('确认要恢复这个备份吗？', function (index) {
            $('#btn-recover').trigger('click');
        });
    }
</script>

    </div>
    
</div>