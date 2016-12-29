/**
 * Created by tingfeng-key.com on 2016/10/27.
 */
$(function(){
    onSize();
    //左侧父级菜单效果
    $('.parent').on('click', function(){
        $(this).next().toggle(300);
    });
    //左侧菜单处理
    $('.node').on('click', function(event){
        event.preventDefault();
        var url = $(this).attr('href'),
            title = $(this).find('span').text();
        if(false === add_tab(url,title)){
            return ;
        }
        add_iframe(url);
        switch_iframe(url);
    });
    //点击tab标题切换效果
    $('.tabs').on('click', '.tab_title', function(){
        var url = $(this).parent().data('url');
        switch_iframe(url);
    });
    //删除效果
    $('.tabs').on('click', '.tab-close', function(){
        var url = $(this).parent().data('url');
        remove(url);
    });
    $(window).resize(function(){
        onSize();
    });
});

function onSize(){
    $('.iframes,.right').css({
        'width': $(document).width() * 0.8,
        'height': $(document).height() * 0.8,
    });
}
/**
 * 添加tab
 * @param url
 * @param title
 * @returns {boolean}
 */
function add_tab(url, title){
    var tabHtml = $('#tpl_tab').html()
        .replace('{name}', url)
        .replace('{title}', title),
        isUrl = $('iframe[data-name="'+url+'"]');
    if(isUrl.length > 0){
        switch_iframe(url);
        $('iframe[data-name="'+url+'"]').attr('src', url);
        return false;
    }
    $('.tabs').append(tabHtml);
}
/**
 * 添加iframe
 * @param url
 */
function add_iframe(url){
    var iframeHtml = $('#tpl_iframe').html().replace('{src}', url)
        .replace('{name}', url);
    $('.iframes').append(iframeHtml);
}
/**
 * 切换iframe显示
 * @param url
 */
function switch_iframe(url){
    $('iframe.iframe_display')
        .removeClass('iframe_display')
        .addClass('iframe_nodisplay');
    $('iframe[data-name="'+url+'"]')
        .removeClass('iframe_nodisplay')
        .addClass('iframe_display');
}
/**
 * 添加和编辑数据
 * @param id
 */
function add(obj,id){
    var actionUrl = getEditUrl(id);
    var title = getTitle(obj);
    if(false === add_tab(actionUrl,title)){
        return ;
    }
    add_iframe(actionUrl);
    switch_iframe(actionUrl);
}
/**
 * 获取tab标题
 * @param obj
 * @returns {*|jQuery}
 */
function getTitle(obj){
    return ($(obj).attr('title') !== undefined)?$(obj).attr('title'):$(obj).text();
}
/**
 * 提交表单
 * @param obj
 * @param id
 * @returns {boolean}
 */
function submitData(obj, id){
    console.log(123);
    var actionUrl = getEditUrl(id,true);
    var data = $(obj).serializeArray();
    $.ajax({
            url: actionUrl,
            type: 'POST',
            dataType: 'json',
            data: data,
        })
        .done(function(res) {
            if(res.code == 1 && res.data == 1){
                msg('成功');
                remove();
            }else{
                msg('失败');
            }
        })
        .fail(function() {
            msg('其他错误');
        })
        .always(function() {
            //console.log("complete");
        });

    return false;
}
/**
 * 移除iframe和tab
 */
function remove(url){
    if(url === undefined){
        var url = $('iframe.iframe_display').data('name');
        $('iframe[data-name="'+url+'"]').remove();
        $('.tab[data-url="'+url+'"]').remove();
    }else{
        $('iframe[data-name="'+url+'"]').remove();
        $('.tab[data-url="'+url+'"]').remove();
    }
    if($('iframe.iframe_display').length < 1){
        var lastUrl = $('iframe:last-child').data('name');
        switch_iframe(lastUrl);
        $('iframe.iframe_display').attr('src', lastUrl);
    }else{
        var lastUrl = $('iframe.iframe_display').data('name');
        switch_iframe(lastUrl);
        $('iframe.iframe_display').attr('src', lastUrl);
    }

}

function refresh_iframe(){
    $('iframe.iframe_display').attr('src', $('iframe.iframe_display').data('name'));
}
/**
 * 获取控制器URL
 * @returns {*|jQuery}
 */
function getControllerUrl(){
    var url = $('iframe.iframe_display').data('name');
    url = url.substring(0, url.lastIndexOf('/'));
    return url;
}
/**
 * 获取编辑页面URL
 * @param id
 * @param bool
 * @returns {*}
 */
function getEditUrl(id,bool){
     var actionUrl,url = getControllerUrl();
     if(bool !== undefined){
         actionUrl = $('iframe.iframe_display').data('name');
     }else if(id === undefined || id == 0){
        actionUrl = url+'/edit';
     }else{
        actionUrl = url+'/edit/id/'+id;
     }
     return actionUrl;
    return getControllerUrl();
}
/**
 * 提示信息
 * @param msg
 */
function msg(msg){
    layui.use('layer', function(){
        var layer = layui.layer;
        layer.msg(msg);
    });
}

/**
 * 删除数据
 * @param id
 */
function del(id){
    layui.use('layer', function(){
        var layer = layui.layer;
        layer.confirm('您确定要删除数据吗?', {icon: 3, title:'提示'}, function(index){
            var actionUrl = getControllerUrl()+'/del';
            $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: {id:id},
                })
                .done(function(res) {
                    if(res.code == 1 && res.data == 1){
                        msg('成功');
                        refresh_iframe();
                    }else{
                        msg('失败');
                    }
                })
                .fail(function() {
                    msg('其他错误');
                })
            layer.close(index);
        });
    });
}