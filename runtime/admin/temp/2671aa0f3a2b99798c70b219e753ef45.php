<?php /*a:3:{s:61:"/private/var/www/admin-project/app/admin/view/user/index.html";i:1600048095;s:62:"/private/var/www/admin-project/app/admin/view/public//top.html";i:1599815783;s:65:"/private/var/www/admin-project/app/admin/view/public//bottom.html";i:1599814551;}*/ ?>
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
        .role_node{
            color: rgba(0,0,0,.85);
            margin: 0 5px 0 0;
            padding: 0 7px;
            font-size: 12px;
            line-height: 20px;
            white-space: nowrap;
            background: #fafafa;
            border: 1px solid #d9d9d9;
            border-radius: 2px;display: inline-block;
        }
        .show_user_img{
            display: inline-block;
            width: 50%;
            height: 100%;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div style="margin-top: 10px;">
    <span class="layui-breadcrumb">
      <a href="javascript:;" class="menu_link" menu_link="/admin/User/index" menu_id="2">账户管理</a>
      <a><cite>列表</cite></a>
    </span>
</div>

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 15px;">
    <legend>账户管理</legend>
</fieldset>

<div class="demoTable">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">账户：</label>
            <div class="layui-input-inline">
                <input class="layui-input" maxlength="40" name="search" id="search_content" placeholder="请输入账户名称/真实姓名" autocomplete="off">
            </div>
        </div>

        <div class="layui-inline">
            <label class="layui-form-label">电话号码：</label>
            <div class="layui-input-inline">
                <input class="layui-input" name="telphone" id="telphone" placeholder="请输入电话号码" autocomplete="off">
            </div>
        </div>
        <div class="layui-inline">
            <div class="layui-input-inline">
                <button id="search" type="button" class="layui-btn" data-type="reload">搜索</button>
<!--                <button type="button" class="layui-btn layui-btn-warm" data-type="reset">重置</button>-->
                <div class="layui-btn-group demoTable" style="margin:5px 0">
                    <button class="layui-btn layui-btn-normal " data-type="add">新增</button>
                </div>
            </div>
        </div>
    </div>
</div>

<table class="layui-hide" id="user_list" lay-filter="user_list"></table>


<script type="text/html" id="show_avatar">
    <!-- 头像 -->
    <img class="show_user_img" src="{{d.avatar}}" onclick="show_img('{{ d.avatar }}')">
</script>

<script type="text/html" id="show_status">
    <input type="checkbox" name="status" value="{{d.id}}" lay-skin="switch" lay-text="正常|停用" lay-filter="changeStatus" {{ d.status == 1 ? 'checked' : '' }}>
</script>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
     <?php if($is_show == 1): ?>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    <?php endif; ?>
</script>

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

    layui.use(['table','layer','form'], function(){
        var table = layui.table
            ,layer=layui.layer
            ,form=layui.form;

        //第一个实例
        table.render({
            id: 'tblId'
            ,elem: '#user_list'
            ,url: '/admin/User/index' //数据接口
            ,page: true //开启分页
            ,cellMinWidth: 80
            ,cols: [ [ //表头
                { fixed: 'left',field:'id',sort: true, title: 'ID', width:'8%', align:'center'}
                ,{fixed: 'left',field: 'username', title: '账户名称',align:'center',width:'10%'}
                ,{field: 'avatar', title: '头像',templet: '#show_avatar',align:'center',width:'8%'}
                ,{field: 'truename', title: '真实姓名', align:'center',width:'10%'}
                ,{field: 'telphone', title: '电话号码',align:'center',width:'13%'}
                ,{field: 'email', title: '邮箱', align:'center',width:'16%'}
                ,{field: 'role_name', title: '所属角色',align:'center',width:'9%'}
                ,{field: 'status', title: '状态',templet: '#show_status',align:'center',width:'11%',style:'cursor:pointer;'}
                ,{fixed: 'right',title: '操作', width:'16%',align:'center', toolbar: '#barDemo'}
            ] ]
        });


        var $ = layui.$, active = {
            add: function(){
                // layer.alert('新增');
                layer.open({
                    type: 2,
                    title: '新增账户',
                    shadeClose: true,
                    area: ['650px', '90%'],
                    content: '/admin/User/add' //iframe的url
                });
                // $("body",parent.document).find('iframe').attr('src','/Menu/add');
            },
            reload: function(){
                var search = $('#search_content').val();
                var telphone=$('#telphone').val();
                //执行重载
                table.reload('tblId', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        search: search,
                        telphone:telphone
                    }
                }, 'data');
            }
        };

        //监听行工具事件
        table.on('tool(user_list)', function(obj){
            var data = obj.data;
            if(obj.event === 'del'){
                layer.confirm('真的删除行么', function(index){
                    $.post("/admin/User/delete", {id:data.id,'type':0}, function (data) {
                        if(data.code == 200){
                            table.reload('tblId', {
                                page: {
                                    curr: 1 //重新从第 1 页开始
                                }
                            }, 'data');
                        }
                        layer.msg(data.msg,{time:2000});
                    })
                });
            } else if(obj.event === 'edit'){
                layer.open({
                    type: 2,
                    title: '编辑角色',
                    shadeClose: true,
                    area: ['650px', '90%'],
                    content: '/admin/User/edit?id='+data.id //iframe的url
                });
            }
        });

        //监听状态操作
        form.on('switch(changeStatus)', function(obj){
            if(obj.elem.checked){
                var type = 1;
            }else{
                var type = 2;
            }
            $.ajax({
                type:'get',
                url:'/admin/User/delete',
                data:{'id':this.value,'type':type},
                success:function(data){
                    layer.msg(data.msg);
                    if(data.code == 200){
                        table.reload('tblId', {
                            page: {
                                curr: 1 //重新从第 1 页开始
                            }
                        }, 'data');
                    }
                },
                error:function () {
                    layer.msg('请求失败');
                }
            });
        });

        $('.demoTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });


    function show_img(img_path){
        console.log('img_src',img_path);
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            area: ['60%','60%'],
            skin: 'layui-layer-nobg', //没有背景色
            shadeClose: true,
            content: '<img src="'+img_path+'" style="width: 100%;height: 100%;">',
        });
    }
</script>


</body>
</html>