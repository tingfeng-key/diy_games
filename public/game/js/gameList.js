/**
 * Created by tingfeng on 2016/11/22.
 */
$(function(){
    var mySwiper = new Swiper ('.swiper-container', {
        direction: 'horizontal',
        slidesPerView: 5,
        slidesOffsetBefore: -15,
        slidesOffsetAfter : 65,
        loop: false,
        // 如果需要滚动条
        //scrollbar: '.swiper-scrollbar',
    });
    $('.typeName').click(function(){
        mySwiper.slideTo($(this).index(), 1000, false);
        var loadObj;
        var page = 0;
        $(this).addClass('typeListCheck')
            .siblings('section').removeClass('typeListCheck');
        $('.gameInfoList').empty();
        ajaxData(restartList);
    });
    $('.submitBtn').click(function(){
        $('.gameInfoList').empty();
        ajaxData(restartList);
    });
});
//请求数据
function ajaxData(callback) {
    var data = {
        name: $('.searchInput').val(),
        type: $('.typeListCheck').data('id'),
        pageSize: 10,
        page: page,
    }
    $.ajax({
        url: 'list',
        type: 'POST',
        dataType: 'json',
        data: data,
        beforeSend: function(){
            loadObj = layer.open({
                type: 2
                ,content: '加载中'
            });
        }
    }).done(function(res){
        if(res.data.length > 0){
            callback(res.data);
            var htmlString = '';
            var tpl = $('.tplList').html();
            $.each(res.data, function(index, item){
                htmlString += tpl.replace('{{logo}}', item.logo)
                    .replace('{{name}}', item.name)
                    .replace('{{type}}', item.type_name)
                    .replace('{{hit_number}}', item.hit_number)
                    .replace('{{default_member_id}}', item.default_member_id)
                    .replace('{{id}}', item.id);
            });
            $('.gameInfoList').append(htmlString);
        }else{
            emptyDataCall();
        }
    }).complete(function(){
        layer.close(loadObj);
    });
}
//空数据
function emptyDataCall() {

}
function restartList() {
    
}