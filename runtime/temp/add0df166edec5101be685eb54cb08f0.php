<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"F:\learn_work_space\diy_games\public/../application/game\view\index\index.html";i:1482468808;s:80:"F:\learn_work_space\diy_games\public/../application/game\view\Common\layout.html";i:1478250927;s:82:"F:\learn_work_space\diy_games\public/../application/game\view\Common\foot_nav.html";i:1479973902;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=240,
            height=320,
            user-scalable=no,
            initial-scale=1.0,
            maximum-scale=1.0,
            minimun-scale=1.0"
    />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content='''>
    <meta name="keywords" content= ''>
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    
    <title>
        游戏列表 - 肥猪游戏
    </title>
    <link rel="icon" type="image/png" href="__STATIC__/../favicon.png">
    <link rel="stylesheet" href="__CSS__/layui.css">
    <link rel="stylesheet" href="__CSS__/app.css">
    
    <link rel="stylesheet" href="__CSS__/amazeui-grid-utility.min.css" />
    <link rel="stylesheet" href="__CSS__/gameList.css" />
    <link rel="stylesheet" href="__CSS__/swiper.min.css" />

    
</head>
<body>


<section class="container">
    <section class="am-g mgud">
        <section class="am-u-sm-11 searchInputClass">
            <input type="text" class="searchInput" />
        </section>
        <section class="am-u-sm-1 searchButton">
            <button class="submitBtn">
                <img src="/img_m/madl_sshbtn.jpg" />
            </button>
        </section>
    </section>
    <section class="am-g mgud">
        <section class="am-u-sm-12">
            <section class="swiper-container">
                <section class="swiper-wrapper">
                    <?php if(is_array($typeData) || $typeData instanceof \think\Collection): $i = 0; $__LIST__ = $typeData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <section class="swiper-slide typeName" data-id="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></section>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </section>
            </section>
        </section>
        <hr style="border-top:1px solid #ff0000">
    </section>
    <section>
        <ul class="am-padding-0 gameInfoList">
            <?php if(is_array($data) || $data instanceof \think\Collection): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
            <li class="lineHeight">
                <section class="am-g">
                    <section class="am-u-sm-7">
                        <section class="am-g">
                            <section class="am-u-sm-5 am-padding-right-0">
                                <img class="gameLogo" src="__ROOT__<?php echo $item['logo']; ?>" />
                            </section>
                            <section class="am-u-sm-6 infoText">
                                <section><?php echo $item['name']; ?></section>
                                <section class="gameType"><?php echo $item['type_name']; ?></section>
                                <section class="hitNumber">人气：<?php echo $item['hit_number']; ?></section>
                            </section>
                        </section>
                    </section>
                    <section class="am-u-sm-4" style="line-height: 3.5rem;">
                        <section>
                            <a class="btn testPlay" href="start/<?php echo $item['default']; ?>">试玩</a>
                        </section>
                        <section>
                            <a class="btn make" href="buy/<?php echo $item['id']; ?>">￥<?php echo $item['price']; ?><br />制作</a>
                        </section>
                    </section>
                </section>
            </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </section>
    <footer class="footer_nav">
    <section class="am-g">
        <section class="am-u-sm-12">
            <section class="am-u-sm-2">
                <a href="/mobile/html5game">
                    <dl class="m_h5adl">
                        <dt>
                            <img class="m_h5a" src="/img_m/m_h5a.png" style="width:27px; height:24px;">
                        </dt>
                        <dd>游戏</dd>
                    </dl>
                </a>
            </section>
            <section class="am-u-sm-2">
                <a href="/dingzhi.html">
                    <dl class="m_h5adl">
                        <dt>
                            <img class="m_h5a" src="/img_m/dingzhi<?php if(Request()->action() == 'index'): ?>hov<?php endif; ?>.png" style="width:27px; height:24px;">
                        </dt>
                        <dd <?php if(Request()->action() == 'index'): ?>style="color: rgb(254, 105, 91);"<?php endif; ?>>定制</dd>
                    </dl>
                </a>
            </section>
            <section class="am-u-sm-2">
                <a href="">
                    <dl class="m_h5adl">
                        <dt>
                            <img class="m_h5a" src="/img_m/QQgame.png" style="width:27px; height:24px;">
                        </dt>
                        <dd>重榜</dd>
                    </dl>
                </a>
            </section>
            <section class="am-u-sm-2">
                <a href="/mobile/change_integral">
                    <dl class="m_h5adl">
                        <dt>
                            <img class="m_h5a" src="/img_m/ico_fshop.png" style="width:27px; height:24px;">
                        </dt>
                        <dd>商城</dd>
                    </dl>
                </a>
            </section>
            <section class="am-u-sm-2">
                <a href="http://fz.m.letoula.com">
                    <dl class="m_h5adl">
                        <dt>
                            <img class="m_h5a" src="/img_m/m_channel.png" style="width:27px; height:24px;">
                        </dt>
                        <dd>彩票</dd>
                    </dl>
                </a>
            </section>
            <section class="am-u-sm-2">
                <a href="/mobile/home">
                    <dl class="m_h5adl">
                        <dt>
                            <img class="m_h5a" src="/img_m/m_my<?php if(Request()->action() == 'mygame'): ?>hov<?php endif; ?>.png" style="width:27px; height:24px;">
                        </dt>
                        <dd <?php if(Request()->action() == 'mygame'): ?>style="color: rgb(254, 105, 91);"<?php endif; ?>>我的</dd>
                    </dl>
                </a>
            </section>
        </section>
    </section>
</footer>
</section>


<script src="_COM_JS_/jquery.min.js"></script>
<script src="__JS__/../layui.js"></script>

<script type="text/plant" class="tpl tplList">
<li>
    <section class="am-g">
        <section class="am-u-sm-8">
            <section class="am-g">
                <section class="am-u-sm-6 am-padding-right-0">
                    <img class="gameLogo" src="__ROOT__{{logo}}" />
                </section>
                <section class="am-u-sm-6 infoText">
                    <section>{{name}}</section>
                    <section class="gameType">{{type}}</section>
                    <section class="hitNumber">人气：{{hit_number}}</section>
                </section>
            </section>
        </section>
        <section class="am-u-sm-4">
            <section>
                <a class="btn testPlay" href="<?php echo Url('start',['id'=>''],''); ?>/{{default_id}}">试玩</a>
            </section>
            <section>
                <a class="btn make" href="<?php echo Url('make',['id'=>''],''); ?>/{{id}}">制作</a>
            </section>
        </section>
    </section>
</li>
</script>
<script src="__JS__/swiper.min.js"></script>
<script>
    var page = 0,nowUrl = "<?php echo Request()->url(true); ?>";
</script>
<script src="__STATIC__/layer_mobile/layer.js"></script>
<script src="__JS__/gameList.js"></script>

</body>
</html>