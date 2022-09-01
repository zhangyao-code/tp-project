<?php /*a:3:{s:67:"/private/var/www/admin-project/app/admin/view/free_study/index.html";i:1599814549;s:62:"/private/var/www/admin-project/app/admin/view/public//top.html";i:1599815783;s:65:"/private/var/www/admin-project/app/admin/view/public//bottom.html";i:1599814551;}*/ ?>
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
      <a href="javascript:;" class="menu_link" menu_link="/admin/FreeStudy/index" menu_id="23">免费学</a>
      <a><cite>列表</cite></a>
    </span>
</div>

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 15px;">
    <legend>免费学管理</legend>
</fieldset>

<div class="demoTable layui-form">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">搜索：</label>
            <div class="layui-input-inline">
                <input class="layui-input" name="search" id="search_content" placeholder="姓名/电话/留言内容" autocomplete="off">
            </div>
            <label class="layui-form-label">处理状态：</label>
            <div class="layui-input-inline">
                <select name="status" id="status">
                    <option value="">请选择</option>
                    <option value="1">未处理</option>
                    <option value="2">已处理</option>
                </select>
            </div>
        </div>
        <div class="layui-inline">
            <div class="layui-input-inline">
                <button id="search" type="button" class="layui-btn" data-type="reload">搜索</button>
            </div>
        </div>
    </div>
</div>

<table class="layui-hide" id="study_list" lay-filter="study_list"></table>

<script type="text/html" id="barDemo">
    {{# if(d.status == 1){ }}
    <a class="layui-btn layui-btn-xs" lay-event="edit">处理</a>
    {{# } else { }}
    <a class="layui-btn layui-btn-disabled layui-btn-xs" href="javascript:;">已处理</a>
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
            ,elem: '#study_list'
            ,url: '/admin/FreeStudy/index' //数据接口
            ,page: true //开启分页
            ,cols: [ [ //表头
                { fixed: 'left',field:'id',sort: true, title: 'ID', width:'10%', align:'center'}
                ,{fixed: 'left',field: 'name', title: '姓名',width:'15%', align:'center'}
                ,{field: 'telphone', title:'电话', align:'center',width:'12%'}
                ,{field: 'course', title: '感兴趣课程',width:'10%', align:'center'}
                ,{field: 'nearby', title: '就近校区',width:'10%', align:'center'}
                ,{field: 'level', title: '目前水平',width:'10%', align:'center'}
                ,{field: 'remark', title: '留言',width:'24%', align:'center'}
                ,{fixed: 'right',title: '操作', width:'10%',align:'center', toolbar: '#barDemo'}
            ] ]
        });


        var $ = layui.$, active = {
            reload: function(){
                var status = $('#status').val();
                var search = $('#search_content').val();
                //执行重载
                table.reload('tblId', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        search: search,
                        status:status
                    }
                }, 'data');
            }
        };

        //监听行工具事件
        table.on('tool(study_list)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                layer.confirm('确定已处理', function(index){
                    $.post("/admin/FreeStudy/change_status", {id:data.id}, function (data) {
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