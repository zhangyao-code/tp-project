<?php /*a:4:{s:62:"/private/var/www/admin-project/app/index/view/index/index.html";i:1599814547;s:62:"/private/var/www/admin-project/app/index/view/public//top.html";i:1599814547;s:63:"/private/var/www/admin-project/app/index/view/public//head.html";i:1599814547;s:65:"/private/var/www/admin-project/app/index/view/public//bottom.html";i:1599814547;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php echo htmlentities($config['title']); ?></title>
<meta name="keywords" content="<?php echo htmlentities($config['keyword']); ?>">
<meta name="description" content="<?php echo htmlentities($config['description']); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link rel="stylesheet" type="text/css" href="/web/skin/css/lib2.css">
<link rel="stylesheet" type="text/css" href="/web/skin/css/style.8390.css">
<link rel="stylesheet" type="text/css" href="/web/skin/css/des.8390.css">
<link rel="stylesheet" type="text/css" href="/web/skin/css/wgreen.css" id="themeCssPath">
<link rel="stylesheet" type="text/css" href="/web/skin/css/8390.css" id="ucssurl">
<script type="text/javascript" src="/web/skin/script/jquery.min.js"></script>
<script type="text/javascript" src="/web/skin/script/8390.js"></script>

<!--<script type="text/javascript">-->
<!--    if (window.location.toString().indexOf('pref=padindex') != -1) {-->

<!--    } else {-->
<!--        if (/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))) {-->
<!--            if (window.location.href.indexOf("?mobile") < 0) {-->
<!--                try {-->
<!--                    if (/Android|Windows Phone|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)) {-->
<!--                        window.location.href = "/m/";-->
<!--                    } else if (/iPad/i.test(navigator.userAgent)) {-->
<!--                    } else {-->
<!--                    }-->
<!--                } catch (e) {-->
<!--                }-->
<!--            }-->
<!--        }-->
<!--    }-->
<!--</script>-->

    <script type="text/javascript" src="/web/skin/script/org.js" data-main="indexMain"></script>
    <script type="text/javascript" src="/web/skin/script/8390.js"></script>
</head>
<body id="longPage" class="gh0  longPage  bodyindex  cn">

<div class="bodyMask"></div>
<div id="header">
    <div class="wrapper">
        <div class="content">
            <div id="headTop"><a href="/" id="logo"><img src="<?php echo htmlentities($config['logo']); ?>" height="44"/></a>
                <div id="hcontact" class="fr"><i class="fa fa-phone"></i>
                    <p><span class="telNum"><?php echo htmlentities($config['phone']); ?></span><br/>
                        <a href="mailto:<?php echo htmlentities($config['email']); ?>"><?php echo htmlentities($config['email']); ?></a></p>
                </div>
                <div id="search-header" class="searchGroup">
                    <div class="search_wrap">
                        <div class="searchOnOff"><i class="fa fa-search" aria-hidden="true"></i></div>
                        <div class="searchBox">
                            <div class="searchFormGroup">
                                <input type="text" id="search_content" aria-label="搜索" placeholder="搜索" autocorrect="off"
                                       autocapitalize="off" autocomplete="off" spellcheck="false"/>
                                <div class="searchSub"><span>搜索</span><i class="fa fa-search" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="searchClose"><i class="fa fa-times" aria-hidden="true"></i></div>
                        </div>
                    </div>
                </div>
                <div id="openBtn" class="fl btn">
                    <div class="lcbody">
                        <div class="lcitem top">
                            <div class="rect top"></div>
                        </div>
                        <div class="lcitem center hide">
                            <div class="rect bottom"></div>
                        </div>
                        <div class="lcitem bottom">
                            <div class="rect bottom"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="navWrapper">
                <div class="content">
                    <ul class="nav movedx" data-movedx-mid="1">
                        <li class="navitem">
                            <a <?php if($menu_id == 1): ?>class="active"<?php endif; ?> href="/" target="_self"> <span
                                data-title="首页">首页</span> </a>
                        </li>
                        <li class="navitem">
                            <?php if(!(empty($course_type) || (($course_type instanceof \think\Collection || $course_type instanceof \think\Paginator ) && $course_type->isEmpty()))): ?>
                            <a <?php if($menu_id == 2): ?>class="active"<?php endif; ?> href="javascript:;" target="_self"> <span
                                data-title="课程体系">课程体系</span> <i class="fa fa-angle-down"></i></a>
                            <ul class="subnav">
                                <?php if(is_array($course_type) || $course_type instanceof \think\Collection || $course_type instanceof \think\Paginator): $i = 0; $__LIST__ = $course_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <li><a href='/index/Course/index?type_id=<?php echo htmlentities($vo['id']); ?>' target="_self"><?php echo htmlentities($vo['title']); ?></a></li>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                            <?php else: ?>
                            <a <?php if($menu_id == 2): ?>class="active"<?php endif; ?> href="/index/Course/index" target="_self"> <span
                                    data-title="课程体系">课程体系</span></a>
                            <?php endif; ?>
                        </li>
                        <li class="navitem">
                            <a <?php if($menu_id == 3): ?>class="active"<?php endif; ?> href="/index/FreeLive/index" target="_self"> <span
                                data-title="免费直播课">免费直播课</span></a>
                        </li>
                        <li class="navitem">
                            <a <?php if($menu_id == 4): ?>class="active"<?php endif; ?> href="/index/Strength/index" target="_self"> <span
                                data-title="实力保证">实力保证</span></a>
                        </li>
                        <li class="navitem">
                            <?php if(!(empty($course_type) || (($course_type instanceof \think\Collection || $course_type instanceof \think\Paginator ) && $course_type->isEmpty()))): ?>
                            <a <?php if($menu_id == 5): ?>class="active"<?php endif; ?> href="javascript:;" target="_self"> <span
                                data-title="名师堂">名师堂</span> <i class="fa fa-angle-down"></i></a>
                            <ul class="subnav">
                                <?php if(is_array($teach_class) || $teach_class instanceof \think\Collection || $teach_class instanceof \think\Paginator): $i = 0; $__LIST__ = $teach_class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <li><a href='/index/Teacher/index?class_id=<?php echo htmlentities($vo['id']); ?>' target="_self"><?php echo htmlentities($vo['title']); ?></a></li>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                            <?php else: ?>
                            <a <?php if($menu_id == 5): ?>class="active"<?php endif; ?> href="/index/Teacher/index" target="_self"> <span
                                    data-title="名师堂">名师堂</span></a>
                            <?php endif; ?>
                        </li>
                        <li class="navitem">
                            <a <?php if($menu_id == 6): ?>class="active"<?php endif; ?> href="javascript:;" target="_self"> <span
                                data-title="关于我们">关于我们</span> <i class="fa fa-angle-down"></i></a>
                            <ul class="subnav">
                                <li><a href="/index/About/index" target="_self">关于机构</a></li>
                                <li><a href="/index/About/news_list" target="_self">新闻</a></li>
                            </ul>
                        </li>
                        <li class="navitem">
                            <a <?php if($menu_id == 7): ?>class="active"<?php endif; ?> href="/index/Index/free_study" target="_self"> <span
                                data-title="免费学">免费学</span></a>
                        </li>
                        <li class="navitem">
                            <a <?php if($menu_id ==8): ?>class="active"<?php endif; ?> href="/index/Index/contact" target="_self"> <span
                                data-title="联系我们">联系我们</span></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<div id="sitecontent" class="ff_sitecontent">

    <div id="indexPage" class="ff_indexPage" data-scroll-ease="Expo.easeInOut" data-scroll-speed="1" data-control="0"
         data-control-wheel="0" data-singlescreen="0">
        <?php if(!(empty($banner_list) || (($banner_list instanceof \think\Collection || $banner_list instanceof \think\Paginator ) && $banner_list->isEmpty()))): ?>
        <div id="topSlider" class="ff_topSlider mslider module">
            <div class="content_wrapper">
                <div class="content_list owl-carousel owl-theme" data-slider-height="500" data-slider-auto="1"
                     data-slider-mode="0" data-slider-pause="4" data-slider-ease="ease-out" data-slider-speed="1"
                     style="height:500px">
                    <?php if(is_array($banner_list) || $banner_list instanceof \think\Collection || $banner_list instanceof \think\Paginator): $i = 0; $__LIST__ = $banner_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <div class="item_block">
                        <div class="item_bg image" style="background-image:url(<?php echo htmlentities($vo['img_src']); ?>)"></div>
                        <a target="_blank" href="/index/Course/index" style="width: 100%; height: 100%;"> </a></div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div class="sliderArrow">
                    <div></div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <?php endif; ?>

        <!--广告部分 开始 如需删除从此段开始往下到-->
        <?php if(!(empty($image_list) || (($image_list instanceof \think\Collection || $image_list instanceof \think\Paginator ) && $image_list->isEmpty()))): ?>
        <div class="mlist ad01 module ff_noSlider"
             style="
   background-position: initial;background-size: cover;background-repeat: no-repeat;
  ">
            <div class="bgmask"></div>
            <div class="module_container">
                <div class="container_header wow">
                    <p class="title">ad01</p>
                </div>
                <div class="container_content">
                    <div class="content_wrapper">
                        <div class="tab_content">
                            <div class="content_list clearfix"><!--小广告 开始-->
                                <?php if(is_array($image_list) || $image_list instanceof \think\Collection || $image_list instanceof \think\Paginator): $i = 0; $__LIST__ = $image_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <div id="item_block_<?php echo htmlentities($key); ?>" class="item_block_<?php echo htmlentities($key); ?> item_block wow" style="animation-delay:.<?php echo htmlentities($key); ?>s">
                                    <a href="/index/Course/index" class="item_box">
                                        <div href="/index/Course/index" class="item_img" target="_blank"><img
                                                src="<?php echo htmlentities($vo['img_src']); ?>"/>
                                            <div class="item_mask"></div>
                                        </div>
                                    </a>
                                </div>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <?php endif; ?>
        <!--广告部分 结束 到此段结束！-->
        <?php if(!(empty($course_list) || (($course_list instanceof \think\Collection || $course_list instanceof \think\Paginator ) && $course_list->isEmpty()))): ?>
        <div class="mlist project module ff_noSlider"
             style="
   background-position: center center; background-size: initial; background-repeat: no-repeat;
   ">
            <div class="bgmask"></div>
            <div class="module_container">
                <div class="container_header wow">
                    <p class="title">课程体系</p>
                    <p class="subtitle">直播授课 / 1对1批改 / 及时答疑 / 无限回放</p>
                </div>
                <div class="container_category wow movedx" data-movedx-mid="2" data-movedx-distance="15">
                    <a href="/index/Course/index" class="active">
                        <span>全部</span>
                    </a>
                    <?php if(!(empty($course_type) || (($course_type instanceof \think\Collection || $course_type instanceof \think\Paginator ) && $course_type->isEmpty()))): if(is_array($course_type) || $course_type instanceof \think\Collection || $course_type instanceof \think\Paginator): $i = 0; $__LIST__ = $course_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <a href="/index/Course/index?type_id=<?php echo htmlentities($vo['id']); ?>">
                        <span><?php echo htmlentities($vo['title']); ?></span>
                    </a>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    <a href="/index/Course/index" class="ff_more">
                        <span>MORE</span>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="container_content">
                    <div class="content_wrapper">
                        <div class="content_list clearfix">
                            <?php if(is_array($course_list) || $course_list instanceof \think\Collection || $course_list instanceof \think\Paginator): $i = 0; $__LIST__ = $course_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <div id="item_block_0" class="item_block_0 item_block wow" style="animation-delay:.0s">
                                <a href="/index/Course/detail?id=<?php echo htmlentities($vo['id']); ?>" class="item_box">
                                    <div href="/index/Course/detail?id=<?php echo htmlentities($vo['id']); ?>" class="item_img" target="_blank">
                                        <img src="<?php echo htmlentities($vo['img_src']); ?>"/>
                                        <div class="item_mask"></div>
                                    </div>
                                    <div class="item_wrapper clearfix">
                                        <div class="item_info clearfix">
                                            <p class="title ellipsis"><?php echo htmlentities($vo['title']); ?></p>
                                            <p class="subtitle ellipsis"></p>
                                        </div>
                                        <div class="item_des">
                                            <p class="description"><?php echo htmlentities($vo['desc']); ?></p>
                                        </div>
                                        <span href="/index/Course/detail?id=<?php echo htmlentities($vo['id']); ?>" class="details hide"> MORE
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </a>
                                <a href="/index/Course/detail?id=<?php echo htmlentities($vo['id']); ?>" class="details"> MORE
                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                                </a>
                                <?php if($vo['speaker']): ?>
                                <div class="item_tags clearfix">
                                    <i class="fa fa-tags"></i>
                                    <a href="/index/Course/detail?id=<?php echo htmlentities($vo['id']); ?>"> 主讲：<?php echo htmlentities($vo['speaker']); ?> </a>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <?php endif; if(!(empty($free_live) || (($free_live instanceof \think\Collection || $free_live instanceof \think\Paginator ) && $free_live->isEmpty()))): ?>
        <div class="mlist videom module bgShow  ff_slider" style="
   background-position: initial;background-size: contain;background-repeat: no-repeat;
   background-image:url(/web/skin/pic/1523609383712.jpg);">
            <div class="bgmask"></div>
            <div class="module_container">
                <div class="container_header wow"><p class="title">免费直播课</p>
                    <p class="subtitle">激发用户的学习需求，帮助用户唤启更好的自己</p></div>
                <div class="container_content">
                    <div class="content_wrapper slider" data-slider-num='1' data-slider-loop="1">
                        <div class="content_list clearfix">
                            <?php if(is_array($free_live) || $free_live instanceof \think\Collection || $free_live instanceof \think\Paginator): $i = 0; $__LIST__ = $free_live;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <div data-href="<?php echo htmlentities($vo['live_src']); ?>" data-index="<?php echo htmlentities($key); ?>" class="item_block_<?php echo htmlentities($key); ?> item_block wow"
                                 style="animation-delay:.<?php echo htmlentities($key); ?>s">
                                <div data-href="<?php echo htmlentities($vo['live_src']); ?>" class="item_box videom-box">
                                    <div class="item_img" target="_blank"><img
                                            src="<?php echo htmlentities($vo['img_src']); ?>"/>
                                        <div class="item_mask"></div>
                                    </div>
                                    <div class="item_wrapper clearfix">
                                        <div class="item_info clearfix">
                                            <p class="title ellipsis"><?php echo htmlentities($vo['title']); ?></p>
                                            <?php if($vo['speaker']): ?>
                                            <p class="subtitle ellipsis">主讲：<?php echo htmlentities($vo['speaker']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="item_des">
                                            <div class="description" style="width: 80%;">
                                                <p><?php echo htmlentities($vo['desc']); ?></p>
                                                <p><br/>
                                                </p>
                                            </div>
                                        </div>
                                        <span data-href="<?php echo htmlentities($vo['live_src']); ?>" class="details hide"> MORE <i
                                                class="fa fa-angle-right" aria-hidden="true"></i> </span></div>
                                </div>
                            </div>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <?php endif; if(!(empty($strength) || (($strength instanceof \think\Collection || $strength instanceof \think\Paginator ) && $strength->isEmpty()))): ?>
        <div class="mlist service module bgShow  ff_noSlider"
             style="
   background-position: initial;background-size: cover;background-repeat: no-repeat;
   background-image:url(/web/skin/pic/1523930503784.png);">
            <div class="bgmask"></div>
            <div class="module_container">
                <div class="container_header wow"><p class="title">实力保证</p>
                    <p class="subtitle">知名精英人士倾力推荐</p></div>
                <div class="container_content">
                    <div class="content_wrapper">
                        <div class="content_list clearfix">
                            <?php if(is_array($strength) || $strength instanceof \think\Collection || $strength instanceof \think\Paginator): $i = 0; $__LIST__ = $strength;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <div id="item_block_<?php echo htmlentities($key); ?>" class="item_block_<?php echo htmlentities($key); ?> item_block wow" style="animation-delay:.<?php echo htmlentities($key); ?>s">
                                <a href="/index/Strength/detail?id=<?php echo htmlentities($vo['id']); ?>" class="item_box">
                                    <div href="/index/Strength/detail?id=<?php echo htmlentities($vo['id']); ?>" class="item_img" target="_blank">
                                        <img src="<?php echo htmlentities($vo['img_src']); ?>"/>
                                        <div class="item_mask"></div>
                                    </div>

                                    <div class="item_wrapper clearfix">
                                        <div class="item_info clearfix">
                                            <p class="title ellipsis"><?php echo htmlentities($vo['title']); ?></p>
                                            <p class="subtitle ellipsis"></p>
                                        </div>
                                        <div class="item_des">
                                            <p class="description"><?php echo htmlentities($vo['desc']); ?></p>
                                        </div>
                                    <span href="/index/Strength/detail?id=<?php echo htmlentities($vo['id']); ?>" class="details hide"> MORE<i class="fa fa-angle-right"
                                                                                               aria-hidden="true"></i> </span>
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <?php endif; if($config['reason']): ?>
        <div class="mcustomize module bgShow horizontal tril"
             style="
   background-image:url(/web/skin/pic/1523930363536.png);   background-position: initial;background-size: contain;background-repeat: no-repeat;
     ">
            <div class="bgmask"></div>
            <div class="module_container">
                <div class="container_content">
                    <div class="contentbody">
                        <div class="wrapper">
                            <?php echo $config['reason']; ?>
                        </div>
                    </div>
                    <div class="mediabody wow"><a target="_blank" href="/index/About/index">
                        <div class="image" style="background-image:url(/web/skin/pic/1523608567921.png)"></div>
                        <div class="mask"></div>
                        <div class="link_icon"><i class="fa fa-link" aria-hidden="true"></i></div>
                    </a></div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <?php endif; if(!(empty($teach_list) || (($teach_list instanceof \think\Collection || $teach_list instanceof \think\Paginator ) && $teach_list->isEmpty()))): ?>
        <div class="mlist team module ff_slider"
             style="
   background-position: initial;background-size: cover;background-repeat: no-repeat;
  ">
            <div class="bgmask"></div>
            <div class="module_container">
                <div class="container_header wow"><p class="title">名师堂</p>
                    <p class="subtitle">知名精英人士名师团队</p></div>
                <div class="container_category wow movedx" data-movedx-mid="2" data-movedx-distance="15"><a
                        href="/index/Teacher/index" class="ff_more"><span>MORE</span></a></div>
                <div class="container_content">
                    <div class="content_wrapper slider" data-slider-num='2' data-slider-loop="1">
                        <div class="content_list clearfix">
                            <?php if(is_array($teach_list) || $teach_list instanceof \think\Collection || $teach_list instanceof \think\Paginator): $i = 0; $__LIST__ = $teach_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <div id="item_block_<?php echo htmlentities($key); ?>" class="item_block_<?php echo htmlentities($key); ?> item_block wow" style="animation-delay:.<?php echo htmlentities($key); ?>s"><a
                                    href="/index/Teacher/detail?id=<?php echo htmlentities($vo['id']); ?>" class="item_box">
                                <div href="/index/Teacher/detail?id=<?php echo htmlentities($vo['id']); ?>" class="item_img" target="_blank"><img
                                        src="<?php echo htmlentities($vo['img_src']); ?>"/>
                                    <div class="item_mask"></div>
                                </div>
                                <div class="item_wrapper clearfix">
                                    <div class="item_info clearfix">
                                        <p class="title ellipsis"><?php echo htmlentities($vo['title']); ?></p>
                                        <p class="subtitle ellipsis"><?php echo htmlentities($vo['post']); ?></p>
                                    </div>
                                    <div class="item_des">
                                        <p class="description"><?php echo htmlentities($vo['desc']); ?></p>
                                    </div>
                                </div>
                            </a> <a href="/index/Teacher/detail?id=<?php echo htmlentities($vo['id']); ?>" class="details"> MORE<i class="fa fa-angle-right"
                                                                                         aria-hidden="true"></i> </a>
                            </div>
                            <?php endforeach; endif; else: echo "" ;endif; ?>

                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <?php endif; ?>

        <div class="mcounter module bgShow   "
             style="
   background-position: initial;background-size: contain;background-repeat: no-repeat;
         background-image:url(<?php echo htmlentities($spread['img_src']); ?>);">
            <div class="bgmask"></div>
            <div id="counterBgdx" style="position:absolute; width:100%; height:100%"></div>
            <div class="module_container">
                <div class="container_content">
                    <ul class="content_list">
                        <li>
                            <div>
                                <p class="number"><span class="counterDX" data-counter-value="<?php echo htmlentities($config['grad_num']); ?>"><?php echo htmlentities($config['grad_num']); ?></span><span
                                        class="unit">+</span></p>
                                <p class="title">已结业付费学员</p>
                            </div>
                        </li>
                        <li>
                            <div>
                                <p class="number"><span class="counterDX" data-counter-value="<?php echo htmlentities($config['everywhere']); ?>"><?php echo htmlentities($config['everywhere']); ?></span><span
                                        class="unit">+</span></p>
                                <p class="title">遍布国家及省级市区</p>
                            </div>
                        </li>
                        <li>
                            <div>
                                <p class="number"><span class="counterDX" data-counter-value="<?php echo htmlentities($config['quality_num']); ?>"><?php echo htmlentities($config['quality_num']); ?></span><span
                                        class="unit">+</span></p>
                                <p class="title">优质精英教师</p>
                            </div>
                        </li>
                        <li>
                            <div>
                                <p class="number"><span class="counterDX" data-counter-value="<?php echo htmlentities($config['pure_num']); ?>"><?php echo htmlentities($config['pure_num']); ?></span><span
                                        class="unit">%</span></p>
                                <p class="title">纯名师教育</p>
                            </div>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <?php if(!(empty($news_list) || (($news_list instanceof \think\Collection || $news_list instanceof \think\Paginator ) && $news_list->isEmpty()))): ?>
        <div class="mlist news module ff_slider"
             style="
   background-position: initial;background-size: cover;background-repeat: no-repeat;
  ">
            <div class="bgmask"></div>
            <div class="module_container">
                <div class="container_header wow"><p class="title">新闻</p>
                    <p class="subtitle">News</p></div>
                <div class="container_category wow movedx" data-movedx-mid="2" data-movedx-distance="15"><a
                        href="/index/About/news_list" class="ff_more"><span>MORE</span></a></div>
                <div class="container_content">
                    <div class="content_wrapper slider" data-slider-num='3' data-slider-loop="1">
                        <div class="content_list clearfix">
                            <?php if(is_array($news_list) || $news_list instanceof \think\Collection || $news_list instanceof \think\Paginator): $i = 0; $__LIST__ = $news_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <div id="item_block_<?php echo htmlentities($key); ?>" class="item_block_<?php echo htmlentities($key); ?> item_block wow" style="animation-delay:.<?php echo htmlentities($key); ?>s"><a
                                    href="/index/About/detail?id=<?php echo htmlentities($vo['id']); ?>" class="item_box">
                                <div href="/index/About/detail?id=<?php echo htmlentities($vo['id']); ?>" class="item_img" target="_blank"><img
                                        src="<?php echo htmlentities($vo['img_src']); ?>"/>
                                    <div class="item_mask"></div>
                                </div>
                                <div class="item_wrapper clearfix">
                                    <div class="item_info clearfix">
                                        <p class="title ellipsis"><?php echo htmlentities($vo['title']); ?></p>
                                        <p class="subtitle ellipsis"></p>
                                    </div>
                                    <div class="date_wrap"><span class="year"><?php echo htmlentities($vo['year']); ?></span><i
                                            class="time-connect">-</i><span class="m"><?php echo htmlentities($vo['month']); ?></span><i
                                            class="time-connect">-</i><span class="d"><?php echo htmlentities($vo['day']); ?></span></div>
                                    <div class="item_des">
                                        <p class="description"><?php echo htmlentities($vo['desc']); ?></p>
                                    </div>
                                    <span href="/index/About/detail?id=<?php echo htmlentities($vo['id']); ?>" class="details hide"> MORE<i
                                            class="fa fa-angle-right" aria-hidden="true"></i> </span></div>
                            </a> <a href="/index/About/detail?id=<?php echo htmlentities($vo['id']); ?>" class="details"> MORE<i class="fa fa-angle-right"
                                                                                         aria-hidden="true"></i> </a>
                            </div>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <?php endif; ?>
        <!--战略合作 开始 如需删除从此段开始往下到-->
        <?php if(!(empty($cooper_list) || (($cooper_list instanceof \think\Collection || $cooper_list instanceof \think\Paginator ) && $cooper_list->isEmpty()))): ?>
        <div class="mlist imagelink module ff_slider"
             style="
   background-position: initial;background-size: cover;background-repeat: no-repeat;
  ">
            <div class="bgmask"></div>
            <div class="module_container">
                <div class="container_header wow"><p class="title">战略合作</p>
                    <p class="subtitle">与<?php echo htmlentities($config['cooper_num']); ?>家机构进行战略合作</p></div>
                <div class="container_content">
                    <div class="content_wrapper slider" data-slider-num='6' data-slider-loop="1">
                        <ul class="content_list">
                            <?php if(is_array($cooper_list) || $cooper_list instanceof \think\Collection || $cooper_list instanceof \think\Paginator): $i = 0; $__LIST__ = $cooper_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <li id="item_block_<?php echo htmlentities($key); ?>" class="item_block wow" style="animation-delay:.<?php echo htmlentities($key); ?>s"><a
                                    href="/index/Index/cooper_detail?id=<?php echo htmlentities($vo['id']); ?>" target="_blank" class="item_img" title="Cinder">
                                <div href="/index/Index/cooper_detail?id=<?php echo htmlentities($vo['id']); ?>" class="item_box" target="_blank"><img
                                        src="<?php echo htmlentities($vo['img_src']); ?>"/></div>
                                <div class="item_wrapper clearfix">
                                    <div class="item_info clearfix">
                                        <p class="title ellipsis"><?php echo htmlentities($vo['title']); ?></p>
                                        <p class="subtitle ellipsis"></p>
                                    </div>
                                    <div class="item_des">
                                        <p class="description"></p>
                                    </div>
                                </div>
                            </a></li>
                            <?php endforeach; endif; else: echo "" ;endif; ?>

                        </ul>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <?php endif; ?>
        <!--战略合作 结束 到此段结束！-->

        <div id="mcontact" class="mcontact module bgShow "
             style="
  background-image:url(/web/skin/pic/1523931162459.jpg);   background-position: initial;background-size: contain;background-repeat: no-repeat;
   ">
            <div class="module_container">
                <div class="container_header wow">
                    <p class="title">免费试听课程</p>
                    <p class="subtitle">课程顾问为您服务,与全球1000万会员一起提高学习掌握技能</p>
                </div>
                <div class="container_content">
                    <div class="content_wrapper">
                        <div id="contactlist" class="contactlist">
                            <div id="contactinfo" class="contactinfo wow">

                                <h3 class="ellipsis contact_name"><?php echo htmlentities($config['company']); ?></h3>
                                <p class="ellipsis contact_add"><?php if($config['address']): ?>地址：<?php echo htmlentities($config['address']); ?><?php endif; ?></p>
                                <p class="ellipsis contact_zip"><?php if($config['post_code']): ?>邮编：<?php echo htmlentities($config['post_code']); ?><?php endif; ?></p>
                                <p class="ellipsis contact_tel"><?php if($config['phone']): ?>电话：<?php echo htmlentities($config['phone']); ?><?php endif; ?></p>
                                <p class="ellipsis contact_mob"><?php if($config['telphone']): ?>手机：<?php echo htmlentities($config['telphone']); ?><?php endif; ?></p>
                                <p class="ellipsis contact_fax"><?php if($config['fax']): ?>传真：<?php echo htmlentities($config['fax']); ?><?php endif; ?></p>
                                <p class="ellipsis contact_eml"><?php if($config['email']): ?>邮箱：<?php echo htmlentities($config['email']); ?><?php endif; ?></p>
                                <div class="ff_social"><a class="fl" target="_blank" href="http://weibo.com/"><i
                                        class="fa fa-weibo"></i></a><a class="fl" target="_blank"
                                                                       href="tencent://message/?uin=<?php echo htmlentities($config['qq']); ?>&Site=qq&Menu=yes"><i
                                        class="fa fa-qq"></i></a> <a id="mpbtn" class="fl" target="_blank"
                                                                     href="<?php echo htmlentities($config['wechat_qrcode']); ?>"><i
                                        class="fa fa-weixin"></i></a></div>
                            </div>
                            <div id="contactform" class="contactform wow">
                                <div style="position: relative;z-index: 1;padding-top: 40px;padding-bottom: 100px;padding: 212px 100px 80px 100px;border-radius: 8px;background: #fff;">
                                    <p class="contactform_name">
                                        <input type="text" class="inputtxt" id="name" name="name" placeholder="姓名"
                                               autocomplete="off"  />
                                    </p>
                                    <p class="contactform_eml">
                                        <input type="text" class="inputtxt" id="email" name="email" placeholder="邮箱"
                                               autocomplete="off"/>
                                    </p>
                                    <p class="contactform_tel">
                                        <input type="text" class="inputtxt" id="telphone" name="telphone" placeholder="电话"
                                               autocomplete="off"/>
                                    </p>
                                    <p class="contactform_content">
                                        <textarea class="inputtxt" id="remark" name="remark" placeholder="内容"
                                                  autocomplete="off"></textarea>
                                    </p>
                                    <p class="contactform_submit">
                                        <input class="inputtxt submit" id="submit_free" type="submit" value="提交"  />
                                    </p>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="footer">
    <p><span class="ellipsis"><?php echo htmlentities($config['copyright']); ?></span> </p>
</div>
<div id="shares"><a href="http://service.weibo.com/share/share.php?" target="_blank" id="sweibo"> <i
        class="fa fa-weibo"></i> </a> <a href="javascript:;" id="sweixin"> <i class="fa fa-mobile"></i> </a> <a
        href="javascript:;" id="gotop"> <i class="fa fa-angle-up"></i> </a></div>
<div class="fixed" id="fixed_weixin">
    <div class="fixed-container">
        <div id="qrcode"></div>
        <p>扫描二维码分享到微信</p>
    </div>
</div>
<div id="online_open"><i class="fa fa-comments-o"></i></div>
<div id="online_lx">
    <div id="olx_head">在线咨询 <i class="fa fa-times fr" id="online_close"></i></div>
    <ul id="olx_qq">
        <li><a href="tencent://message/?uin=<?php echo htmlentities($config['qq']); ?>&Site=qq&Menu=yes"> <i class="fa fa-qq"></i><?php echo htmlentities($config['qq']); ?> </a></li>
    </ul>
    <div id="olx_tel">
        <div><i class="fa fa-phone"></i>联系电话</div>
        <p> <?php echo htmlentities($config['phone']); ?><br/>
        </p>
    </div>
</div>
<script>
    $('.searchSub').on('click',function(){
        var url=window.location.pathname;
        var search=$('#search_content').val();
        console.log('search',search);
        console.log('url',url);
        location.href=url+'?search='+search;
    })
</script>

</body>
<script src="/web/skin/layer/layer.js"></script>
<script>
    $('#submit_free').on('click',function(){
        var name=$('#name').val();
        var email=$('#email').val();
        var telphone=$('#telphone').val();
        var remark=$('#remark').val();
        console.log('name',name);
        if(!name || name == ''){
            layer.msg('姓名不能为空',{time:2000});
        }else  if(!email || email == ''){
            layer.msg('邮件不能为空',{time:2000});
        }else  if(!telphone || telphone == ''){
            layer.msg('电话不能为空',{time:2000});
        }else  if(!remark || remark == ''){
            layer.msg('内容不能为空',{time:2000});
        }else {
            $.ajax({
                url: "/index/Index/free_study",
                data: {'name': name, 'email': email, 'telphone': telphone, 'remark': remark},
                dataType: "json",
                success: function (data) {
                    if (data.code == 200) {
                        $("#contactform").find("input,textarea").each(function () {
                            $(this).val("")
                        });
                    }
                    layer.msg(data.msg, {time: 2000});
                },
                error: function () {
                    layer.msg('程序错误', {time: 2000});
                }
            })
        }
    });
</script>
</html>