/**
 * Created by tingfeng on 2016/12/28.
 */
var layer,
    vm = new Vue({
        el: '#container-full',
        data: {
            logo_path: 'game/img/2048/logo_05.png',
            color_img: 1,
            font_img: '微软雅黑',
            grade: 4,
            rewardNumber: 1,
            game_name: '连线大爆炸',
            share_text: '连线大爆炸',
            colorItem: [
                {
                    name: '1',
                    val: 1,
                    bgc: '#f5caa5',
                    selected: true,
                },
                {
                    name: '2',
                    val: 2,
                    bgc: '#e6fabe',
                    selected: false,
                },
                {
                    name: '3',
                    val: 3,
                    bgc: '#ccffff',
                    selected: false,
                },
                {
                    name: '4',
                    val: 4,
                    bgc: '#ffffbf',
                    selected: false,
                },
            ],
            gradeItem: [
                {
                    name: '放水',
                    val: 6,
                    selected: false,
                },
                {
                    name: '轻松',
                    val: 5,
                    selected: true,
                },
                {
                    name: '正常',
                    val: 4,
                    selected: false,
                },
                {
                    name: '噩梦',
                    val: 3,
                    selected: false,
                },
            ],
            template: '<section class="group rewardBtns">'+
            '<input type="number" class="rowLine1" placeholder="奖励分数" value="{{number}}"/> '+
            '<input type="text " class="rowLine2" placeholder="输入网址" value="{{url}}" />'+
            '</section>',
        },
        created: function(){
            this.logoCssChange();
            layui.use(['form', 'layer'], function () {
                var form = layui.form();
                layer = layui.layer;
            });
            if(jsonData.data == null) return ;
            var data = jsonData.data;
            this.logo_path = data.logo;
            this.color_img = data.skin;
            this.colorEvent(this.color_img);
            this.grade = data.grade;
            this.gradeEvent(data.grade);
            this.game_name = data.name;
            this.share_text = data.share_text;
            var rewardMap = JSON.parse(data.reward);
            this.rewardNumber = rewardMap.length;
            this.rewardInit();
            if(this.rewardNumber == 1){
                this.rewardBtnDelEvent();
            }
        },
        methods: {
            logoCssChange: function(){
                var imgObjUpload = $('.logo');
                $('.upload_logo,#uploadImageClass').css({
                    left: imgObjUpload.css('left'),
                    top: imgObjUpload.css('top')
                });
            },
            colorEvent: function(num){
                this.color_img = num;
                console.log(num);
                this.colorItem[this.colorDefaultEvent(num)].selected = true;
            },
            colorDefaultEvent: function(val){
                var index;
                for(var i = 0; i < this.colorItem.length; i++){
                    if(val == this.colorItem[i].val){
                        index = i;
                    }
                    this.colorItem[i].selected = false;
                }
                return index;
            },
            gradeEvent: function(num){
                this.grade = num;
                this.gradeItem[this.gradeDefaultEvent(num)].selected = true;
            },
            gradeDefaultEvent: function(val){
                var index;
                for(var i = 0; i < this.gradeItem.length; i++){
                    if(val == this.gradeItem[i].val){
                        index = i;
                    }
                    this.gradeItem[i].selected = false;
                }
                return index;
            },
            rewardBtnDelEvent: function(){
                if(this.rewardNumber >= 2){
                    $('section.rewardBtns').eq(this.rewardNumber - 1).remove();
                    this.rewardNumber--;
                }
                if(this.rewardNumber < 2){
                    $('#delReward').hide();
                }
            },
            rewardBtnAddEvent:function(){
                var html = this.template.replace("{{number}}", "").
                replace("{{url}}", "");
                $('.group-input').append(html);
                this.rewardNumber++;
                if(this.rewardNumber >= 2){
                    $('#delReward').show();
                }
                return false;
            },
            submitBtnEvent: function(){
                var data = $('.form').serializeArray(),
                    reward = this.rewardJson();
                data.push({
                    name: "reward",
                    value: reward
                });
                $.ajax({
                    url: formSubmitUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                })
                    .done(function(res) {
                        layui.use(['layer'], function(){
                            var layer = layui.layer;
                        });
                        if(res.code){
                            layer.open({
                                content: res.msg
                                ,btn: '马上试玩',
                                title: '制作成功',
                                btn1: function(index,layero){
                                    localStorage.setItem('clearData',1);
                                    window.location.href = res.data;
                                }
                            });
                        }else{
                            layer.open({
                                content: res.msg
                                ,btn: '我知道了',
                            });
                        }
                    });
            },
            rewardJson: function(){
                var rewardNum = $('.rowLine1'),
                    rewardUrl = $('.rowLine2'),
                    result = [];
                for(var i = 0; i < rewardNum.length; i++){
                    console.log($(rewardNum[i]).val());
                    result.push({
                        score: $(rewardNum[i]).val(),
                        url: $(rewardUrl[i]).val()
                    });
                }
                return JSON.stringify(result);
            },
            uploadLogoEvent: function(){
                $('.container-full').hide();
                event.preventDefault();
                var file = $('#upload_logo').get(0).files[0];; //获取file对象
                //判断file的类型是不是图片类型。
                if(!/image\/\w+/.test(file.type)){
                    alert("文件必须为图片！");
                    return false;
                }
                var readerLoad = layer.load();
                var start = new Date().getTime();
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function(e) {
                    var base64Data;
                    if(file.fileSize >= 10048576){
                        //ajaxDealImage(this.result);
                    }else{
                        dataView(this.result);
                    }
                }
            },
            triggerEvent: function(){
                $('#upload_logo').trigger('click');
            },
            rewardInit: function(){
                var content = "",
                    reward = JSON.parse(jsonData.data.reward);
                $('.rowLine1').val(reward[0].num);
                $('.rowLine2').val(reward[0].url);
                for(var i = 1; i < this.rewardNumber; i++){
                    content += this.template.
                    replace("{{number}}", reward[i].num).
                    replace("{{url}}", reward[i].url);
                }
                if(content == '') return ;
                $('.group-input').append(content);
            },
        }
    });
var layerVm = new Vue({
    "el": "#cropper",
    data: {},
    methods: {
        submitLogo: function(){
            var base64Data = $('#local_image').cropper('getCroppedCanvas').toDataURL('image/jpeg');
            $('#local_image').attr('src', base64Data);
            var ajaxLoad;
            $.ajax({
                url: saveLogoUrl,
                type: 'POST',
                dataType: 'json',
                data: {imageData: base64Data},
                beforeSend: function(){
                    ajaxLoad = layer.load();
                }
            }).done(function(res) {
                layer.close(ajaxLoad);
                if(res.code){
                    $('.logo').attr('src', root+res.msg);
                    $('input[name="logo"]').val(res.msg);
                    $('.cancel').trigger('click');
                }
                layer.open({
                    content: res.data
                    ,btn: '我知道了',
                });
            });
        },
        cancelLogoSubmit: function(){
            $('.container-full').show();
            $('#cropper').hide();
        },
        triggerEvnet: function(){
            vm.triggerEvent();
        }
    }
});
/**
 * 判断奖励是否为空
 * @returns {boolean}
 */
function rewardEmpty() {
    var data = jsonData.data;
    if(data.reward == undefined) return false;
    var reward = JSON.parse(jsonData.data.reward);
    if(reward.length < 1) return false;
    return true;
}
/**
 * 裁剪图片
 * @param data
 */
function dataView(data) {
    var loadLayer = layer.load();
    var Img = document.getElementById('local_image'),
        options = {
            aspectRatio: 16 / 16,
            checkImageOrigin: true,
            viewMode: 1,
        },
        length = $('#image_parent div').length,
        imageHeight = ($(document).height() - $(document).height() * 0.2);

    Img.src = data;
    $('#local_image').cropper('destroy')
    Img.onload = function (){
        layer.close(loadLayer);
        $('#local_image').cropper(options);
        $('#cropper').addClass('cropper2').css({'height': imageHeight, 'display': 'block'});
        $('#image_parent').css({'height': imageHeight});
    }
}