<?php /*a:3:{s:61:"/private/var/www/admin-project/app/admin/view/banner/add.html";i:1599814549;s:62:"/private/var/www/admin-project/app/admin/view/public//top.html";i:1599815783;s:65:"/private/var/www/admin-project/app/admin/view/public//bottom.html";i:1599814551;}*/ ?>
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
        <label class="layui-form-label">轮播标题</label>
        <div class="layui-input-inline">
            <input type="text" name="title" autocomplete="off" placeholder="请输入轮播标题" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">轮播分类</label>
        <div class="layui-input-inline">
            <select name="type">
                <option value="1">首页轮播</option>
                <option value="6">首页轮播下图片</option>
                <option value="7">首页遍布数据图</option>
                <option value="2">免费直播课</option>
                <option value="3">实力保证</option>
                <option value="4">名师堂</option>
                <option value="5">联系我们</option>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">图片</label>
        <div class="layui-input-inline">
            <div class="layui-upload-drag" id="test10">
                <i class="layui-icon"></i>
                <p>点击上传，或将文件拖拽到此处</p>
            </div>
            <input type="hidden" name="img_src" id="img_src" lay-verify="required">
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
            ,url: '/admin/Website/upload?folder=banner' //改成您自己的上传接口
            ,accept:'image'
            ,done: function(res){
                layer.msg('上传成功');
                $('#test10').html('<img src="'+res.data.src+'" style="width: 100%;height: 100%;">');
                $('#img_src').val(res.data.src);
            }
        });

        //监听提交
        form.on('submit(demo1)', function(data){
            $.post("/admin/Banner/add", data.field, function (data) {
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