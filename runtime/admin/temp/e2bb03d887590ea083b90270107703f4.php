<?php /*a:3:{s:61:"/private/var/www/admin-project/app/admin/view/role/index.html";i:1599814552;s:62:"/private/var/www/admin-project/app/admin/view/public//top.html";i:1599815783;s:65:"/private/var/www/admin-project/app/admin/view/public//bottom.html";i:1599814551;}*/ ?>
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

    </style>
</head>
<body>
<div style="margin-top: 10px;">
    <span class="layui-breadcrumb">
      <a href="javascript:;" class="menu_link" menu_link="/admin/Role/index" menu_id="5">角色管理</a>
      <a><cite>列表</cite></a>
    </span>
</div>

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 15px;">
    <legend>角色管理</legend>
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

<table class="layui-hide" id="role_list" lay-filter="role_list"></table>


<script type="text/html" id="role_node_list" >
    <!-- 节点列表 -->
    {{#  layui.each(d.role_node, function(index, item){ }}
        <span class="role_node">{{ item}}</span>
    {{#  }); }}
</script>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    {{# if(d.id != 1){}}
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    {{# } }}
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

    layui.use(['table'], function(){
        var table = layui.table;

        //第一个实例
        table.render({
            id: 'tblId'
            ,elem: '#role_list'
            ,url: '/admin/Role/index' //数据接口
            ,page: true //开启分页
            ,cols: [ [ //表头
                { field:'id',sort: true, title: 'ID', width:'10%', align:'center'}
                ,{field: 'role_name', title: '角色名称',width:'15%', align:'center'}
                ,{field: 'role_node', title: '角色权限', align:'center',width:'40%',templet: '#role_node_list'}
                ,{field: 'desc', title: '角色简介',width:'20%', align:'center'}
                ,{fixed: 'right',title: '操作', width:'15%',align:'center', toolbar: '#barDemo'}
            ] ]
        });


        var $ = layui.$, active = {
            add: function(){
                // layer.alert('新增');
                layer.open({
                    type: 2,
                    title: '新增角色',
                    shadeClose: true,
                    area: ['460px', '90%'],
                    content: '/admin/Role/add' //iframe的url
                });
                // $("body",parent.document).find('iframe').attr('src','/Menu/add');
            }
        };

        //监听行工具事件
        table.on('tool(role_list)', function(obj){
            var data = obj.data;
            if(obj.event === 'del'){
                layer.confirm('真的删除行么', function(index){
                    $.post("/admin/Role/delete", {id:data.id}, function (data) {
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
                    area: ['460px', '90%'],
                    content: '/admin/Role/edit?id='+data.id //iframe的url
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