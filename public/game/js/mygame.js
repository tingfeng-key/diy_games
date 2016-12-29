/**
 * Created by tingfeng on 2016/11/24.
 */
$(function(){
    console.log($(window).height(),$('header').height(), $('footer').height());
    $('.content').css({
        'height': $(window).height()-$('header').height()-$('footer').height() - 20,
        'overflow-y': 'auto',
        'overflow-x': 'hidden'
    })
    $('.delete_game').click(function(){
        var ajaxLoad,
            self = this,
            id = $(this).data('id');
        layer.open({
            content: '您确定要删除该定制的游戏吗？'
            ,btn: ['先不删除', '我确定']
            ,no: function(index){
                $.ajax({
                    url: deleteGameUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: {id: id},
                    beforeSend: function(){
                        ajaxLoad = layer.open({
                            type: 2
                            ,content: '客官稍等'
                        });
                    }
                })
                    .done(function(res) {
                        layer.close(ajaxLoad);
                        $(self).parents('.am-g').fadeOut(500, function(){
                            $(self).parents('.am-g').remove();
                        });
                        layer.open({
                            content: res.data
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                    });
            }
        });
    });
});