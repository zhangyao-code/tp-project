<?php /*a:3:{s:63:"/private/var/www/admin-project/app/admin/view/website/edit.html";i:1599814551;s:62:"/private/var/www/admin-project/app/admin/view/public//top.html";i:1599815783;s:65:"/private/var/www/admin-project/app/admin/view/public//bottom.html";i:1599814551;}*/ ?>
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
    <style>
        body{
            width: 97%;
            padding-left: 20px;
        }
    </style>
</head>
<body>
<div style="margin-top: 10px;">
    <span class="layui-breadcrumb">
      <a href="javascript:;" class="menu_link" menu_link="/admin/Website/edit" menu_id="16">网站设置</a>
      <a><cite>网站配置</cite></a>
    </span>
</div>

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 15px;">
    <legend>网站配置</legend>
</fieldset>

<div class="layui-tab layui-tab-card">
    <ul class="layui-tab-title">
        <li class="layui-this">网站设置</li>
        <li>关于机构</li>
        <li>选择原因</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <form class="layui-form form-item" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">网站标题</label>
                    <div class="layui-input-inline">
                        <input type="text" name="title" autocomplete="off" value="<?php echo htmlentities($config['title']); ?>" lay-verify="required" placeholder="请输入网站标题" class="layui-input">
                    </div>
                    <label class="layui-form-label">网站关键词</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="keyword" value="<?php echo htmlentities($config['keyword']); ?>" lay-verify="required" placeholder="请输入网站标题">
                    </div>
                    <label class="layui-form-label">版权所有</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="copyright" value="<?php echo htmlentities($config['copyright']); ?>" lay-verify="required" placeholder="请输入版权所有">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">网站描述</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="description" value="<?php echo htmlentities($config['description']); ?>" lay-verify="required" placeholder="请输入网站描述">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">联系电话</label>
                    <div class="layui-input-inline">
                        <input type="text" name="phone" autocomplete="off" value="<?php echo htmlentities($config['phone']); ?>" lay-verify="required" placeholder="请输入联系电话" class="layui-input">
                    </div>
                    <label class="layui-form-label">邮箱</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="email" value="<?php echo htmlentities($config['email']); ?>" lay-verify="email" placeholder="请输入邮箱">
                    </div>
                    <label class="layui-form-label">QQ</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="qq" value="<?php echo htmlentities($config['qq']); ?>"  lay-verify="required" placeholder="请输入QQ">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">传真</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="fax" value="<?php echo htmlentities($config['fax']); ?>"  lay-verify="required" placeholder="请输入传真">
                    </div>
                    <label class="layui-form-label">地址</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="address" value="<?php echo htmlentities($config['address']); ?>"  lay-verify="required" placeholder="请输入地址">
                    </div>
                    <label class="layui-form-label">公司</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="company" value="<?php echo htmlentities($config['company']); ?>"  lay-verify="required" placeholder="请输入公司名称">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">logo</label>
                    <div class="layui-input-inline">
                        <div class="layui-upload-drag" id="test10">
                            <img src="<?php echo htmlentities($config['logo']); ?>" style="width: 100%;height: 100%;">
                        </div>
                        <input type="hidden" name="logo" id="logo" value="<?php echo htmlentities($config['logo']); ?>" lay-verify="required">
                    </div>
                    <label class="layui-form-label">微信二维码</label>
                    <div class="layui-input-inline">
                        <div class="layui-upload-drag" id="test8">
                            <img src="<?php echo htmlentities($config['wechat_qrcode']); ?>" style="width: 100%;height: 100%;">
                        </div>
                        <input type="hidden" name="wechat_qrcode" id="wechat_qrcode" value="<?php echo htmlentities($config['wechat_qrcode']); ?>" lay-verify="required">
                    </div>
                    <label class="layui-form-label">邮编</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="post_code" value="<?php echo htmlentities($config['post_code']); ?>"  lay-verify="required" placeholder="请输入邮编">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="layui-tab-item">
            <form class="layui-form form-item" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">机构遍布数</label>
                    <div class="layui-input-inline">
                        <input type="text" name="everywhere" autocomplete="off" value="<?php echo htmlentities($config['everywhere']); ?>" lay-verify="required" placeholder="请输入遍布国家和省级市区数" class="layui-input">
                    </div>
                    <label class="layui-form-label">已结业数</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="grad_num" value="<?php echo htmlentities($config['grad_num']); ?>" lay-verify="required" placeholder="请输入机构已结业付费数">
                    </div>
                    <label class="layui-form-label">精英教师数</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="quality_num" value="<?php echo htmlentities($config['quality_num']); ?>"  lay-verify="required" placeholder="请输入精英教师数">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">纯名师教育</label>
                    <div class="layui-input-inline">
                        <input type="text" name="pure_num" autocomplete="off" value="<?php echo htmlentities($config['pure_num']); ?>" lay-verify="required" placeholder="请输入纯名师教育数" class="layui-input">
                    </div>
                    <label class="layui-form-label">战略合作数</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="cooper_num" value="<?php echo htmlentities($config['cooper_num']); ?>" lay-verify="required" placeholder="请输入战略合作数">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">关于机构</label>
                    <div class="layui-input-block" >
                        <script id="editor" name="content" lay-verify="required" type="text/plain" placeholder="请输入关于机构"><?php echo $config['organ']; ?></script>
<!--                        <textarea id="demo" name="organ" lay-verify="required|content" style="display: none;"><?php echo htmlentities($config['organ']); ?></textarea>-->
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="layui-tab-item">
            <form class="layui-form form-item" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">选择原因</label>
                    <div class="layui-input-block" >
                        <script id="reason" name="reason" lay-verify="required" type="text/plain" placeholder="请输入为什么选择的原因"><?php echo $config['reason']; ?></script>
<!--                        <textarea id="reason" name="reason" lay-verify="required|content" placeholder="请输入为什么选择的原因" style="display: none;"><?php echo htmlentities($config['reason']); ?></textarea>-->
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo2">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
<script type="text/javascript" charset="utf-8" src="/static/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/static/ueditor/ueditor.all.min.js"> </script>
<script>
    var ue = UE.getEditor('editor');
    UE.getEditor('reason');
    layui.use(['form','upload','layedit'], function(){
        var form = layui.form
            ,upload=layui.upload
            ,layedit=layui.layedit
            ,layer = layui.layer;

        layedit.set({
            uploadImage: {
                url: '/admin/Website/upload?folder=layedit'
            }
        });
        // var detail=layedit.build('demo',{height:230});
        // var reason=layedit.build('reason',{height:230});
        // form.verify({
        //     content: function(value) {
        //         layedit.sync(detail);
        //     }
        // });

        //拖拽上传
        upload.render({
            elem: '#test10'
            ,url: '/admin/Website/upload?folder=logo' //改成您自己的上传接口
            ,accept:'image'
            ,done: function(res){
                layer.msg('上传成功');
                $('#test10').html('<img src="'+res.data.src+'" style="width: 100%;height: 100%;">');
                $('#logo').val(res.data.src);
            }
        });

        //拖拽上传
        upload.render({
            elem: '#test8'
            ,url: '/admin/Website/upload?folder=logo' //改成您自己的上传接口
            ,accept:'image'
            ,done: function(res){
                layer.msg('上传成功');
                $('#test8').html('<img src="'+res.data.src+'" style="width: 100%;height: 100%;">');
                $('#wechat_qrcode').val(res.data.src);
            }
        });

        //监听提交
        form.on('submit(demo)', function(data){
            console.log('field',data.field);
            $.post("/admin/Website/edit?action=site", data.field, function (data) {
                if(data.code == 200){
                    window.parent.location.reload();
                }
                layer.msg(data.msg,{time:2000});
            });
            return false;
        });

        //监听提交
        form.on('submit(demo1)', function(data){
            console.log('field',data.field);
            $.post("/admin/Website/edit?action=organ", data.field, function (data) {
                if(data.code == 200){
                    window.parent.location.reload();
                }
                layer.msg(data.msg,{time:2000});
            });
            return false;
        });

        //监听提交
        form.on('submit(demo2)', function(data){
            console.log('field',data.field);
            $.post("/admin/Website/edit?action=reason", data.field, function (data) {
                if(data.code == 200){
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