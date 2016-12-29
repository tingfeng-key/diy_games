/**
 * Created by tingfeng on 2016/12/23.
 */
var layer;
layui.use('form', function() {
    layer = layui.layer;
});
$(function(){
    $('.loginPanel').css({
        'width': '50%',
        'margin': '0 auto',
        'margin-top': $(document).height() / 4
    });
    $('form').submit(function(){
        var vals = $(this).serializeArray();
        $.ajax({
            url: checkLoginUrl,
            type: 'POST',
            dataType: 'json',
            data: vals,
        })
        .done(function(res) {
            layer.msg(res.msg);
            if(res.code){
                setTimeout(function(){
                    window.location.reload();
                }, 600);
            }
        });
        return false;
    });
});