<?php /*a:3:{s:69:"/private/var/www/admin-project/app/admin/view/course/course_list.html";i:1599814552;s:62:"/private/var/www/admin-project/app/admin/view/public//top.html";i:1599815783;s:65:"/private/var/www/admin-project/app/admin/view/public//bottom.html";i:1599814551;}*/ ?>
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
      <a href="javascript:;" class="menu_link" menu_link="/admin/Course/course_list" menu_id="18">课程管理</a>
      <a><cite>课程列表</cite></a>
    </span>
</div>

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 15px;">
    <legend>课程列表</legend>
</fieldset>

<div class="demoTable layui-form">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">课程分类：</label>
            <div class="layui-input-inline">
                <select name="type_id" id="type_id">
                    <option value="">请选择</option>
                    <?php if(is_array($type_list) || $type_list instanceof \think\Collection || $type_list instanceof \think\Paginator): $i = 0; $__LIST__ = $type_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                     <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['title']); ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-inline">
            <div class="layui-input-block" style="margin-left: 0;">
                <button id="search" type="button" class="layui-btn" data-type="reload">搜索</button>
                <div class="layui-btn-group demoTable" style="margin:5px 0">
                    <button class="layui-btn layui-btn-normal " data-type="add">新增</button>
                </div>
                <a href="javascript:;" class="menu_link layui-btn layui-btn-danger" menu_link="/admin/Course/course_type" menu_id="18">课程分类</a>
            </div>
        </div>
    </div>
</div>

<table class="layui-hide" id="course_list" lay-filter="course_list"></table>

<script type="text/html" id="show_banner">
    <!-- 图片 -->
    <img class="show_banner" src="{{d.img_src}}" onclick="show_img('{{ d.img_src }}')">
</script>

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
            ,elem: '#course_list'
            ,url: '/admin/Course/course_list' //数据接口
            ,page: true //开启分页
            ,cols: [ [ //表头
                { fixed: 'left',field:'id',sort: true, title: 'ID', width:'10%', align:'center'}
                ,{fixed: 'left',field: 'title', title: '课程标题',width:'18%', align:'center'}
                ,{field: 'type_title', title: '所属分类',width:'15%', align:'center'}
                ,{field: 'img_src', title: '图片', align:'center',width:'18%',templet: '#show_banner'}
                ,{field: 'speaker', title: '主讲',width:'10%', align:'center'}
                ,{field: 'desc', title: '简介',width:'15%', align:'center'}
                ,{fixed: 'right',title: '操作', width:'15%',align:'center', toolbar: '#barDemo'}
            ] ]
        });


        var $ = layui.$, active = {
            add: function(){
                // layer.alert('新增');
                layer.open({
                    type: 2,
                    title: '新增课程',
                    shadeClose: true,
                    area: ['800px', '95%'],
                    content: '/admin/Course/add_course' //iframe的url
                });
                // $("body",parent.document).find('iframe').attr('src','/Menu/add');
            },
            reload: function(){
                var type_id = $('#type_id');
                //执行重载
                table.reload('tblId', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        type_id: type_id.val()
                    }
                }, 'data');
            }
        };

        //监听行工具事件
        table.on('tool(course_list)', function(obj){
            var data = obj.data;
            if(obj.event === 'del'){
                layer.confirm('真的删除行么', function(index){
                    $.post("/admin/Course/delete_course", {id:data.id}, function (data) {
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
                    title: '编辑课程',
                    shadeClose: true,
                    area: ['800px', '90%'],
                    content: '/admin/Course/edit_course?id='+data.id //iframe的url
                });
            }
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