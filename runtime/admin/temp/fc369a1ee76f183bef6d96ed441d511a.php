<?php /*a:3:{s:64:"/private/var/www/admin-project/app/admin/view/contact/index.html";i:1599814552;s:62:"/private/var/www/admin-project/app/admin/view/public//top.html";i:1599815783;s:65:"/private/var/www/admin-project/app/admin/view/public//bottom.html";i:1599814551;}*/ ?>
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
        .show_banner{
            display: inline-block;
            width: 80%;
            height: 100%;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div style="margin-top: 10px;">
    <span class="layui-breadcrumb">
      <a href="javascript:;" class="menu_link" menu_link="/admin/Contact/index" menu_id="24">联系我们管理</a>
      <a><cite>列表</cite></a>
    </span>
</div>

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 15px;">
    <legend>联系我们管理</legend>
</fieldset>

<div class="demoTable layui-form">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">搜索：</label>
            <div class="layui-input-inline">
                <input class="layui-input" name="search" id="search_content" placeholder="公司名称/英文/地址/电话" autocomplete="off">
            </div>
        </div>
        <div class="layui-inline">
            <div class="layui-input-inline">
                <button id="search" type="button" class="layui-btn" data-type="reload">搜索</button>
                <div class="layui-btn-group demoTable" style="margin:5px 0">
                    <button class="layui-btn layui-btn-normal " data-type="add">新增</button>
                </div>
            </div>
        </div>
    </div>
</div>

<table class="layui-hide" id="contact_list" lay-filter="contact_list"></table>

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

    layui.use(['table'], function(){
        var table = layui.table;

        //第一个实例
        table.render({
            id: 'tblId'
            ,elem: '#contact_list'
            ,url: '/admin/Contact/index' //数据接口
            ,page: true //开启分页
            ,cols: [ [ //表头
                { fixed: 'left',field:'id',sort: true, title: 'ID', width:'8%', align:'center'}
                ,{fixed: 'left',field: 'title', title: '公司名称',width:'15%', align:'center'}
                ,{field: 'en_title', title: '英文名称', align:'center',width:'15%'}
                ,{field: 'phone', title: '电话',width:'15%', align:'center'}
                ,{field: 'email', title: '邮箱',width:'15%', align:'center'}
                ,{field: 'post_code', title: '邮政编码', align:'center',width:'12%'}
                ,{field: 'address', title: '地址',width:'15%', align:'center'}
                ,{field: 'website', title: '网址',width:'15%', align:'center'}
                ,{fixed: 'right',title: '操作', width:'15%',align:'center', toolbar: '#barDemo'}
            ] ]
        });


        var $ = layui.$, active = {
            add: function(){
                // layer.alert('新增');
                layer.open({
                    type: 2,
                    title: '新增联系我们',
                    shadeClose: true,
                    area: ['700px', '60%'],
                    content: '/admin/Contact/add' //iframe的url
                });
                // $("body",parent.document).find('iframe').attr('src','/Menu/add');
            },
            reload: function(){
                var type = $('#type');
                //执行重载
                table.reload('tblId', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        type: type.val()
                    }
                }, 'data');
            }
        };

        //监听行工具事件
        table.on('tool(contact_list)', function(obj){
            var data = obj.data;
            if(obj.event === 'del'){
                layer.confirm('真的删除行么', function(index){
                    $.post("/admin/Contact/delete", {id:data.id}, function (data) {
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
                    title: '编辑联系我们',
                    shadeClose: true,
                    area: ['700px', '60%'],
                    content: '/admin/Contact/edit?id='+data.id //iframe的url
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