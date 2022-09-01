<?php /*a:3:{s:59:"/private/var/www/admin-project/app/admin/view/user/add.html";i:1599814551;s:62:"/private/var/www/admin-project/app/admin/view/public//top.html";i:1599815783;s:65:"/private/var/www/admin-project/app/admin/view/public//bottom.html";i:1599814551;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>后台管理系统</title>
<link rel="stylesheet" href="/static/layui/css/layui.css">
<style>
    .layui-nav-child a{
        margin-left: 24px;
    }
    .layui-nav-item cite{
        margin-left: 10px;
    }
    .form-item{
        margin-top: 2%;
    }
</style>
</head>
<body>

<form class="layui-form form-item" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">账户名称</label>
        <div class="layui-input-inline">
            <input type="text" name="username" lay-verify="required" autocomplete="off" placeholder="请输入账户名称" class="layui-input">
        </div>
        <label class="layui-form-label">密码</label>
        <div class="layui-input-inline">
            <input type="password" name="password" lay-verify="required" autocomplete="off" placeholder="请输入账户密码" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">真实姓名</label>
        <div class="layui-input-inline">
            <input type="text" name="truename" lay-verify="required" autocomplete="off" placeholder="请输入账户真实名称" class="layui-input">
        </div>
        <label class="layui-form-label">所属角色</label>
        <div class="layui-input-inline">
            <select name="role_id" lay-verify="required">
                <option>请选择所属角色</option>
                <?php if(is_array($role_list) || $role_list instanceof \think\Collection || $role_list instanceof \think\Paginator): $i = 0; $__LIST__ = $role_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['role_name']); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">电话号码</label>
        <div class="layui-input-inline">
            <input type="text" name="telphone" autocomplete="off" lay-verify="phone" placeholder="请输入电话号码" class="layui-input">
        </div>
        <label class="layui-form-label">邮箱</label>
        <div class="layui-input-inline">
            <input type="text" name="email" autocomplete="off" lay-verify="email" placeholder="请输入邮箱" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">头像</label>
        <div class="layui-input-inline">
            <div class="layui-upload-drag" id="test10">
                <i class="layui-icon"></i>
                <p>点击上传，或将文件拖拽到此处</p>
            </div>
            <input type="hidden" name="avatar" id="avatar" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>

<script src="/static/layui/layui.js"></script>
<script src="/static/js/jquery-3.4.1.min.js"></script>
<script>
    //JavaScript代码区域
    layui.use('element', function(){
        var element = layui.element;


    });

    $('.menu_link').on('click',function(){
        var url=$(this).attr('menu_link');
        var menu_id=$(this).attr('menu_id');
        if($('iframe').attr('src')){
            $('iframe').attr('src',url);
            $('iframe').attr('menu_id',menu_id);
        }else{
            parent.window.document.getElementById("iframe").setAttribute('src',url);
            parent.window.document.getElementById("iframe").setAttribute('menu_id',menu_id);
        }
    });

    function loadFrame(obj){
        var menu_id=parent.window.document.getElementById("iframe").getAttribute('menu_id');
        $('.menu_link').each(function(){
            if(menu_id == $(this).attr('menu_id')){
                $(this).parent().addClass('layui-this');
            }else{
                $(this).parent().removeClass('layui-this');
            }
        })
    }
</script>
<script>
    layui.use(['form','upload'], function(){
        var form = layui.form
            ,upload=layui.upload
            ,layer = layui.layer;

        //拖拽上传
        upload.render({
            elem: '#test10'
            ,url: '/admin/User/upload' //改成您自己的上传接口
            ,accept:'image'
            ,done: function(res){
                layer.msg('上传成功');
                $('#test10').html('<img src="'+res.data.src+'" style="width: 100%;height: 100%;">');
                $('#avatar').val(res.data.src);
            }
        });

        //监听提交
        form.on('submit(demo1)', function(data){
            $.post("/admin/User/add", data.field, function (data) {
                if(data.code == 200){
                    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                    parent.layer.close(index);
                    window.parent.location.reload();
                }
                layer.msg(data.msg,{time:2000});
            });
            return false;
        });
    });
</script>

</body>
</html>