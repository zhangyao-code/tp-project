<?php /*a:3:{s:61:"/private/var/www/admin-project/app/admin/view/node/index.html";i:1599814550;s:62:"/private/var/www/admin-project/app/admin/view/public//top.html";i:1599815783;s:65:"/private/var/www/admin-project/app/admin/view/public//bottom.html";i:1599814551;}*/ ?>
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
      <a href="javascript:;" class="menu_link" menu_link="/admin/Node/index" menu_id="4">节点管理</a>
      <a><cite>列表</cite></a>
    </span>
</div>

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 15px;">
    <legend>节点管理</legend>
</fieldset>

<!--<div class="demoTable">-->
<!--    <div class="layui-form-item">-->
<!--        <div class="layui-inline">-->
<!--            <label class="layui-form-label">ID：</label>-->
<!--            <div class="layui-input-inline">-->
<!--                <input class="layui-input" maxlength="40" name="id" id="id" placeholder="请输入菜单ID" autocomplete="off">-->
<!--            </div>-->
<!--        </div>-->

<!--        <div class="layui-inline">-->
<!--            <label class="layui-form-label">菜单名称：</label>-->
<!--            <div class="layui-input-inline">-->
<!--                <input class="layui-input" name="menu_name" id="menu_name" placeholder="请输入菜单名称" autocomplete="off">-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="layui-inline">-->
<!--            <div class="layui-input-inline">-->
<!--                <button id="search" type="button" class="layui-btn" data-type="reload">搜索</button>-->
<!--                <button type="button" class="layui-btn layui-btn-warm" data-type="reset">重置</button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<div class="layui-btn-group demoTable" style="margin:5px 0">
    <button class="layui-btn layui-btn-normal " data-type="add">新增</button>
</div>

<table class="layui-hide" id="node_list" lay-filter="node_list"></table>


<!--<script type="text/html" id="show_icon">-->
<!--    &lt;!&ndash; 菜单图标 &ndash;&gt;-->
<!--    <i class="layui-icon {{d.menu_class}}"></i>-->
<!--</script>-->

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
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

    layui.use(['table','treeTable'], function(){
        var table = layui.table,
            treeTable=layui.treeTable;

        // 渲染表格
        var node_table=function(){
            treeTable.render({
                elem: '#node_list',
                tree: {
                    iconIndex: 1
                },
                cols: [[
                    {field: 'id', title: 'ID',sort: true,align:'center',fixed:'left'},
                    {field: 'node_name', title: '节点名称',align:'center'},
                    {field: 'node_link', title: '方法',align:'center'},
                    {field: 'node_pid', title: '父级',align:'center'},
                    {field: 'add_time', title: '添加时间',align:'center', sort: true},
                    {title: "操作", width: 150, align: "center", fixed: "right", toolbar: "#barDemo"}
                ]]
                ,reqData: function (data, callback) {  // 懒加载也可以用url方式，这里用reqData方式演示
                    setTimeout(function () {  // 故意延迟一下
                        // var menu_name=$('#menu_name').val()?$('#menu_name').val():'';
                        var id=data ? data.id : '';
                        var url = '/admin/Node/index?id='+id;
                        $.get(url, function (res) {
                            callback(res.data);
                        });
                    }, 800);
                },
                style: 'margin-top:0;'
            });
        };

        node_table();

        var $ = layui.$, active = {
            add: function(){
                // layer.alert('新增');
                layer.open({
                    type: 2,
                    title: '新增节点',
                    shadeClose: true,
                    area: ['460px', '60%'],
                    content: '/admin/Node/add' //iframe的url
                });
                // $("body",parent.document).find('iframe').attr('src','/Menu/add');
            }
        };

        //监听行工具事件
        treeTable.on('tool(node_list)', function(obj){
            var data = obj.data;
            if(obj.event === 'del'){
                layer.confirm('真的删除行么', function(index){
                    $.post("/admin/Node/delete", {id:data.id}, function (data) {
                        if(data.code == 200){
                            node_table();
                        }
                        layer.msg(data.msg,{time:2000});
                    })
                });
            } else if(obj.event === 'edit'){
                layer.open({
                    type: 2,
                    title: '编辑节点',
                    shadeClose: true,
                    area: ['460px', '60%'],
                    content: '/admin/Node/edit?id='+data.id //iframe的url
                });
            }
        });

        $('.demoTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>


</body>
</html>