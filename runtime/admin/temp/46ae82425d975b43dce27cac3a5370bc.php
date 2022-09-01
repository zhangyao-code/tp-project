<?php /*a:4:{s:62:"/private/var/www/admin-project/app/admin/view/index/index.html";i:1600051702;s:62:"/private/var/www/admin-project/app/admin/view/public//top.html";i:1599815783;s:63:"/private/var/www/admin-project/app/admin/view/public//left.html";i:1599814551;s:65:"/private/var/www/admin-project/app/admin/view/public//bottom.html";i:1599814551;}*/ ?>
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
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo"><img src="/static/logo.png" style="width: 80%;height: 100%;"></div>

        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    <img src="<?php echo htmlentities($user_data['avatar']); ?>" class="layui-nav-img">
                    <?php echo htmlentities($user_data['username']); ?>
                </a>
<!--                <dl class="layui-nav-child">-->
<!--                    <dd><a href="">基本资料</a></dd>-->
<!--                    <dd><a href="">安全设置</a></dd>-->
<!--                </dl>-->
            </li>
            <li class="layui-nav-item"><a href="/admin/Login/logout">退出</a></li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree"  lay-filter="test">
            <?php if(is_array($menu_list) || $menu_list instanceof \think\Collection || $menu_list instanceof \think\Paginator): $i = 0; $__LIST__ = $menu_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <li <?php if($key == 0): ?>class="layui-nav-item layui-nav-itemed"<?php else: ?>class="layui-nav-item"<?php endif; ?>>
                <?php if($vo['sub_menu_num'] == 1): ?>
                    <a class="" href="javascript:;"><i class="layui-icon <?php echo htmlentities($vo['menu_class']); ?>"></i><cite><?php echo htmlentities($vo['menu_name']); ?></cite></a>
                    <dl class="layui-nav-child">
                        <?php if(is_array($vo['sub_menu']) || $vo['sub_menu'] instanceof \think\Collection || $vo['sub_menu'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['sub_menu'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                        <dd><a href="javascript:;" class="menu_link" menu_link="<?php echo htmlentities($v['menu_link']); ?>" menu_id="<?php echo htmlentities($v['id']); ?>"><?php echo htmlentities($v['menu_name']); ?></a></dd>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </dl>
                <?php else: ?>
                    <a class="menu_link" href="javascript:;" menu_link="<?php echo htmlentities($vo['menu_link']); ?>" menu_id="<?php echo htmlentities($v['id']); ?>"><i class="layui-icon <?php echo htmlentities($vo['menu_class']); ?>"></i><cite><?php echo htmlentities($vo['menu_name']); ?></cite></a>
                <?php endif; ?>
            </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
</div>

    <div class="layui-body">
        <!-- 内容主体区域 -->
       <iframe src="<?php echo htmlentities($menu_list['0']['sub_menu']['0']['menu_link']); ?>" menu_id="<?php echo htmlentities($menu_list['0']['sub_menu']['0']['id']); ?>" id="iframe" style="width: 100%;height: 100%;border: 0;" onload="loadFrame(this)"></iframe>
    </div>

    <div class="layui-footer" style="text-align: center;">
        <!-- 底部固定区域 -->
        Copyright © <?php echo htmlentities($year); ?> 卓高科技技术部出品
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
</body>
</html>