<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"F:\learn_work_space\diy_games\public/../application/game\view\makes\lxdbz.html";i:1482913850;s:80:"F:\learn_work_space\diy_games\public/../application/game\view\Common\layout.html";i:1478250927;}*/ ?>
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
        连线大爆炸定制页面 - 肥猪游戏
    </title>
    <link rel="icon" type="image/png" href="__STATIC__/../favicon.png">
    <link rel="stylesheet" href="__CSS__/layui.css">
    <link rel="stylesheet" href="__CSS__/app.css">
    
<link rel="stylesheet" href="__CSS__/make.css">
<link rel="stylesheet" href="__CSS__/bootstrap.min.css">
<link rel="stylesheet" href="__CSS__/cropper.min.css">
<style>
    .row {
        margin: 0 auto;
    }
    @media screen and (min-width:560px){
        #addReward {
            background-image: url(__IMG__/lxdbz/add.png);
            background-size: 100%;
            height: 5rem;
            background-repeat: no-repeat;
        }
    }
    @media screen and (max-width:560px){
        #addReward {
            background-image: url(__IMG__/lxdbz/add.png);
            background-size: 100%;
            height: 3.3rem;
            background-repeat: no-repeat;
        }
    }
    @media screen and (min-width:560px){
        #delReward {
            background-image: url(__IMG__/lxdbz/delete.png);
            background-size: 100%;
            height: 5rem;
            background-repeat: no-repeat;
        }
    }
    @media screen and (max-width:560px){
        #delReward {
            background-image: url(__IMG__/lxdbz/delete.png);
            background-size: 100%;
            height: 3.3rem;
            background-repeat: no-repeat;
        }
    }
</style>

    
</head>
<body>


<section class="container-full" id="container-full">
    <form class="form" v-on:submit.prevent>
        <!--替换LOGO-->
        <section class="group">
            <section class="headImage" style="margin-top: 7rem;">
                <section class="left">
                    <img src="__IMG__/2048/headImage_7.png" alt="">
                </section>
                <section class="right" style="margin-top: 0.5rem;">
                    <sction class="title">第一步：LOGO替换</sction>
                    <hr />
                    <h3 class="tips">可以将游戏的logo替换成自己想要的图片哦</h3>
                </section>
            </section>
            <section class="row content tC logos">
                <section class="col-xs-6">
                    <section>
                        <img src="__IMG__/2048/logo_03.jpg" style="width:12rem;height: 12rem;" />
                    </section>
                    <section class="tips_font">
                        替换前
                    </section>
                </section>
                <section class="col-xs-6 right" id="v-logo">
                    <section class="selectFile">
                        <input id="upload_logo" v-on:change="uploadLogoEvent" type="file" accept="image/*" capture="camera" style="display: none" />
                        <section class="uploadImageClass">
                            <img src="__ROOT__{{ logo_path }}" id="logoAndUpload" v-on:click="triggerEvent" class="logo" >
                        </section>
                    </section>
                    <div class="tC" style="clear: both">点击按钮即可修改logo</div>
                    <div class="tC">300*300像素</div>
                    <input type="hidden" name="logo" v-model="{{ logo_path }}" value="{{ logo_path }}" />
                </section>
            </section>
        </section>
        <!--皮肤设置-->
        <section class="group">
            <section class="headImage">
                <section class="left">
                    <img src="__IMG__/2048/headImage_1.png" >
                </section>
                <section class="right" style="margin-top: -0.5rem;">
                    <sction class="title">第二步：选择皮肤</sction>
                    <hr />
                    <h3 class="tips">四种皮肤模式，随意选择！</h3>
                </section>
            </section>
            <section class="row content tC">
                <img class="color" id="color_img" src="__IMG__/2048/color{{ color_img }}.png" />
                <section class="tC colorBtnGroup">
                    <input type="hidden" name="skin" value="{{ color_img }}" />
                    <button v-for="item in colorItem"
                            v-on:click="colorEvent(item.val)"
                            v-bind:class="{ colorBtn: !item.selected, colorBtnCheck: item.selected}"
                            style="background-color: {{ item.bgc }}">
                        {{ item.name }}
                    </button>
                    <section style="font-size: 2rem;margin-top: 1rem;">
                        <span style="color:red">*</span> 上面这些按钮是可以点击的哦~！
                    </section>
                </section>
            </section>
        </section>
        <!--难度设置-->
        <section class="group">
            <section class="headImage">
                <section class="left">
                    <img src="__IMG__/2048/headImage_3.png" alt="">
                </section>
                <section class="right" style="margin-top: 2.2rem;">
                    <sction class="title">第四步：难度替换</sction>
                    <hr />
                    <h5 class="tips">替换游戏的难度，体验自虐的快感</h5>
                </section>
            </section>
            <section class="content">
                <section class="row tC">
                    <img class="color" src="__IMG__/2048/grade{{ grade }}.png" />
                </section>
                <section class="tC gradeBtnGroup">
                    <input type="hidden" name="grade" value="{{ grade }}" />
                    <button class="gradeBtns"
                            v-for="item in gradeItem"
                            v-bind:class="{ gradeBtn: !item.selected, gradeBtnCheck: item.selected}"
                            v-on:click="gradeEvent(item.val)">
                        {{ item.name }}
                    </button>
                    <section style="font-size: 2rem;margin-top: 1rem;">
                        <span style="color:red">*</span> 上面这些按钮是可以点击的哦~！
                    </section>
                </section>
            </section>
        </section>
        <!--奖励机制-->
        <section class="group grade">
            <section class="headImage">
                <section class="left">
                    <img src="__IMG__/2048/headImage_4.png" alt="">
                </section>
                <section class="right" style="color: red">
                    <sction class="title">第五步：设置奖励</sction>
                    <hr />
                    <h5 class="tips">自己设定奖励分数，达到指定分数即可获得奖励！</h5>
                </section>
            </section>
            <section class="content tC">
                <section class="group-input ">
                    <section style="color: red;">
                        设置不同阶段奖励，不填写分数即无奖励。
                    </section>
                    <section class="group rewardBtns" v-if="rewardEmpty()" v-for="item in rewardItem">
                        <input type="number" name="reward[]" value="{{ item.val }}"
                               class="rowLine1" placeholder="奖励分数" />
                        <input type="text " name="reward_url[]" value="{{ item.url }}"
                               class="rowLine2" placeholder="输入网址" />
                    </section>
                    <section class="rewardBtns rewardBtns" v-else>
                        <input type="number" value="" class="rowLine1" placeholder="奖励分数" />
                        <input type="text" value="" class="rowLine2" placeholder="输入网址" />
                    </section>
                </section>
                <section style="width: 86%;margin: 0 auto;">
                    <button v-on:click="rewardBtnDelEvent" class="rewardBtnDel" id="delReward"></button>
                    <button v-on:click="rewardBtnAddEvent" class="rewardBtnAdd" id="addReward"></button>
                </section>
            </section>
        </section>
        <!--名称设置-->
        <section class="group">
            <section class="headImage">
                <section class="left">
                    <img src="__IMG__/2048/headImage_5.png" >
                </section>
                <section class="right" style="margin-top: -0.5rem;">
                    <sction class="title">第六步：名称替换</sction>
                    <hr />
                    <h3 class="tips" style="padding-left: 0.3rem;">替换游戏的名称，取一个“愉快的”名字吧！</h3>
                </section>
            </section>
            <section class="content">
                <div class="row setName">
                    <section class="col-xs-3 tC">
                        <lable style="line-height: 0rem;">新游戏名称</lable>
                    </section>
                    <section class="col-xs-8" style="margin-top: 0.2rem;">
                        <input type="text" name="name" value="{{ game_name }}" />
                    </section>
                </div>
            </section>
        </section>
        <!--分享文字-->
        <section class="group">
            <section class="headImage">
                <section class="left">
                    <img src="__IMG__/2048/headImage_6.png" alt="">
                </section>
                <section class="right" style="margin-top: 0.3rem;">
                    <sction class="title">最后一步：分享文字</sction>
                    <hr />
                    <h5 class="tips">输入一条属于自己风格的分享内容吧！</h5>
                </section>
            </section>
            <section class="content">
                <div class="row share">
                    <section class="col-xs-3 tC">
                        <lable>分享文字</lable>
                    </section>
                    <section class="col-xs-8">
                        <textarea name="share_text" id="">{{ share_text }}</textarea>
                    </section>
                </div>
            </section>
        </section>
        <!--提交按钮-->
        <section class="group" style="margin-top: 3rem;">
            <section class="row tC complate">
                <section class="tC">
                    <button class="test" style="display: none"><a href="">试玩</a></button>
                </section>
                <section class=" tC">
                    <input v-on:submit.prevent="onSubmit"
                           v-on:click="submitBtnEvent"
                           type="submit"
                           value="制&nbsp;&nbsp;&nbsp;&nbsp;作&nbsp;&nbsp;&nbsp;&nbsp;完&nbsp;&nbsp;&nbsp;&nbsp;成" />
                </section>
            </section>
        </section>
    </form>
</section>


<section id="cropper">
    <section>
        <section class="main">
            <div style="height: 500px" id="image_parent">
                <img src="" alt="" id="local_image" />
            </div>
        </section>
        <section class="contro">
            <button class="ensure" v-on:click="submitLogo">确定</button>
            <button class="reSelect" v-on:click="triggerEvent">重新选择</button>
            <button class="cancel" v-on:click="cancelLogoSubmit">取消</button>
        </section>
    </section>
</section>

<script src="_COM_JS_/jquery.min.js"></script>
<script src="__JS__/../layui.js"></script>

<script src="__JS__/cropper.min.js"></script>
<script src="__JS__/vue.min.js"></script>
<script>
    var jsonData  = <?php echo $data; ?>,
        saveLogoUrl = "<?php echo Url('saveLogo'); ?>",
        formSubmitUrl = "<?php echo Request()->url(true); ?>",
        gameDir = '__IMG__/2048/',
        root = "__ROOT__";
</script>
<script src="__JS__/vue_lxdbz.js"></script>

</body>
</html>