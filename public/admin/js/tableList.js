/**
 * Created by tingfeng-key.com on 2016/10/28.
 */
$(function(){
    $.fn.list = {
        url: null,//异步请求URL
        page: 1,//当前页面
        pageSize: 10,//显示的数据量
        pageObj: null,//分页对象
        datas: null,//数据,
        ajaxSubmitData: null,
        formObj: null,//搜索信息
        selectObj: null,//选择器对象
        tableObj: null,//表格对象
        thead: null,//表头对象
        tbody: null,//表主体对象
        tfoot: null,//表尾对象
        colums: null,//列数据信息
        tableClass: 'tableList layui-table',//表格可用类名
        //初始化
        init: function (option) {
            this.url();
            this.pageObj = option.pageSelect;
            this.formObj = option.searchForm;
            this.url = (option.url == undefined)?this.url:option.url;
            this.pageSize = (option.pageSize == undefined)?this.pageSize:option.pageSize;
            this.data = option.data;
            this.colums = option.colums;
            this.selectObj = option.select;
            try{
                this.layout();
                this.formSubmit();
            }catch (e){
                this.consoleError('系统错误：'+e);
            }

        },
        //布局
        layout: function(){
            this.table();
            this.tableObj = $(this.selectObj).find('table');
            this.theadObj = $(this.tableObj).find('thead');
            this.tfootObj = $(this.tableObj).find('tfoot');
            this.tbodyObj = $(this.tableObj).find('tbody');
            this.thead();
            this.getAjax();
            this.tfoot();
        },
        table: function(){
            var htmlStr = '<table class="'+this.tableClass+'"><thead></thead><tbody></tbody><tfoot></tfoot></table>';
            $(this.selectObj).html(htmlStr);
        },
        thead: function(){
            var htmlStr = '<tr>';
            for(var i = 0; i < this.colums.length; i++){
                if(typeof this.colums[i] === 'object'){
                    var text = (this.colums[i].text !== undefined)?this.colums[i].text:'';
                    var style = (this.colums[i].style !== undefined)?' style="'+this.colums[i].style+'"':'';
                    var classs = (this.colums[i].class !== undefined)?' class="'+this.colums[i].class+'"':'';
                    htmlStr += '<td'+classs+style+'>'+text+'</td>';
                }else{
                    htmlStr += '<td>'+this.colums[i]+'</td>';
                }
            }
            htmlStr += '</tr>';
            $(this.theadObj).html(htmlStr);
        },
        tbody: function(){
            if(this.datas == null)
                return false;

            var htmlStr = '';
            for(var i = 0; i < this.datas.length; i++){
                htmlStr += '<tr>';
                var data = this.datas[i],
                    count = this.colums.length;
                for(var j = 0; j < count; j++){
                    var colum = this.colums[j];
                    if(colum.deal !== undefined){
                        text = colum.deal(data);
                    }else{
                        text = data[colum.field];
                    }
                    htmlStr += '<td>'+text+'</td>';
                }
                htmlStr += '</tr>';
            }
            $(this.tbodyObj).html(htmlStr);
        },
        tfoot: function(){

        },
        //默认异步请求Url为当前URL
        url: function(){
            this.url = window.location.pathname;
        },
        //异步请求数据
        getAjax: function(){
            var self = this;
            $.ajax({
                url: this.url,
                type: 'post',
                dataType: 'json',
                data: self.ajaxForm(),
            })
            .done(function(res) {
                if((res.code !== undefined && res.code)){
                    self.datas = res.data.data;
                    self.tbody();
                    self.pages(res.data.current_page, res.data.total);
                }else{
                    this.datas = null;
                    $(this.tbodyObj).html('<tr><td>暂无数据</td></tr>');
                    self.page = 0;
                    self.pages(0,0);
                    self.consoleError(res);
                }
            })
            .fail(function() {
                self.consoleLog("Ajax request Url fail");
            })
            .always(function() {
                self.consoleLog("complete");
            });

        },
        ajaxForm: function(){
            this.ajaxSubmitData = $(this.formObj).serializeArray();
            this.ajaxSubmitData.push({name:'page',value:this.page});
            this.ajaxSubmitData.push({name:'pageSize',value:this.pageSize});
            return this.ajaxSubmitData;
        },
        formSubmit: function(){
            var self = this;
            $(this.formObj).submit(function(){
                self.getAjax();
                return false;
            });
        },
        //分页统一处理
        pages: function(current_page,count){
            var self = this;
            layui.use('laypage', function(){
                layui.laypage({
                    cont: $(self.pageObj),
                    pages: (count/self.pageSize),
                    curr: current_page,
                    jump: function(obj, first){
                        if(first === true) return false;
                        self.page = obj.curr;
                        self.getAjax();
                    },
                });
            });
        },
        consoleLog: function(e){
            //console.log(e);
        },
        consoleError: function(e){
            //console.error(e);
        },
    }
})