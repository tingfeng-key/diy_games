//'use strict';
//变量初始化
var score = 0,
    startx = 0,
    starty = 0,
    endx = 0,
    endy = 0,
    board = [],//数字数据
    hasConflicted = [],//格子信息
    //数据相关
    colorSet = dbData.color,
    gradeData = dbData.grade,
    reward = dbData.reward,
    //时间设置
    lastTime = 0,
    timeOut = 10,
    intervalTime = 150,
    animateMoveTime = 200,
    animateMergeTime = 10,
    timerId = null,
    //适配相关
    documentWidth = document.body.clientWidth,//window.screen.availWidth,
    gridContainerWidth = 0.95 * documentWidth,
    cellSideLength = getGradeWidth(gradeData) * documentWidth,
    cellSpace = allCenter(gradeData);
//设置伪居中
function allCenter(gradeData){
    switch (gradeData){
        case 3:
            return 0.0026*gradeData*documentWidth;
            break;
        case 4:
            return 0.00245*gradeData*documentWidth;
            break;
        case 5:
            return 0.0023*gradeData*documentWidth;
            break;
        case 6:
            return 0.0021*gradeData*documentWidth;
            break;
        default:
            return 0.002*gradeData*documentWidth;
            break;
    }
}
//优化setTimeOut
function requestAnimationFrame(callback){
    var currTime = new Date().getTime();
    //clearTimeout(timerId);
    var timeToCall = Math.max(150, 100 - (currTime - lastTime));
    var timerId = window.setTimeout(function () {
            callback(currTime + timeToCall);
        },
        timeToCall);
    lastTime = currTime + timeToCall;
}
function getGradeWidth(number){
    var result;
    switch (number){
        case 3:
            result = 0.3;
            break;
        case 4:
            result = 0.22;
            break;
        case 5:
            result = 0.172;
            break;
        case 6:
            result = 0.14;
            break;
    }
    return result;
}
//获取坐标 start
function getPosTop( i , j ){
    return cellSpace + i*( cellSpace + cellSideLength );
}
function getPosLeft( i , j ){
    return cellSpace + j*( cellSpace + cellSideLength );
}
//获获坐标 end

//获取数子北京颜色
function getNumberBackgroundColor( number ){
    return gameData.bg(dbData.color, number);
}

//获得数字颜色
function getNumberColor( number ){
    return gameData.style(dbData.color, number);
}

//是否为空
function nospace( board ){

    for( var i = 0 ; i < gradeData ; i ++ )
        for( var j = 0 ; j < gradeData ; j ++ )
            if( board[i][j] == 0 )
                return false;

    return true;
}

//是否可以移动
function getCanMove(){
    var canMove = parseInt(localStorage.getItem('canMove_'+dataId));
    if(canMove !== null){
        return canMove;
    }else{
        return false;
    }
}
//检测移动 start
function canMoveLeft( board ){
    if(!getCanMove())
        return false;
    for( var i = 0 ; i < gradeData ; i ++ )
        for( var j = 1; j < gradeData ; j ++ )
            if( board[i][j] != 0 )
                if( board[i][j-1] == 0 || board[i][j-1] == board[i][j] )
                    return true;

    return false;
}

function canMoveRight( board ){
    if(!getCanMove())
        return false;
    for( var i = 0 ; i < gradeData ; i ++ )
        for( var j = gradeData-2; j >= 0 ; j -- )
            if( board[i][j] != 0 )
                if( board[i][j+1] == 0 || board[i][j+1] == board[i][j] )
                    return true;

    return false;
}

function canMoveUp( board ){
    if(!getCanMove()){
        return false;
    }
    for( var j = 0 ; j < gradeData; j ++ )
        for( var i = 1 ; i < gradeData ; i ++ )
            if( board[i][j] != 0 )
                if( board[i-1][j] == 0 || board[i-1][j] == board[i][j] )
                    return true;

    return false;
}

function canMoveDown( board ){
    if(!getCanMove())
        return false;
    for( var j = 0 ; j < gradeData ; j ++ )
        for( var i = gradeData-2 ; i >= 0 ; i -- )
            if( board[i][j] != 0 )
                if( board[i+1][j] == 0 || board[i+1][j] == board[i][j] )
                    return true;

    return false;
}
//检测移动 end

//水平移动和垂直移动 start
function noBlockHorizontal( row , col1 , col2 , board ){
    for( var i = col1 + 1 ; i < col2 ; i ++ )
        if( board[row][i] != 0 )
            return false;
    return true;
}

function noBlockVertical( col , row1 , row2 , board ){
    for( var i = row1 + 1 ; i < row2 ; i ++ )
        if( board[i][col] != 0 )
            return false;
    return true;
}
//水平移动和垂直移动 end

//不能移动
function nomove( board ){
    if( canMoveLeft( board ) ||
        canMoveRight( board ) ||
        canMoveUp( board ) ||
        canMoveDown( board ) )
        return false;

    return true;
}

//数字合并
function showNumberWithAnimation( i , j , randNumber ){

    var numberCell = $('#number-cell-' + i + "-" + j );

    numberCell.css('background-color',getNumberBackgroundColor( randNumber ) );
    numberCell.css('color',getNumberColor( randNumber ) );
    numberCell.css('font-size',getFontSize( randNumber ) );
    numberCell.text( randNumber );

    numberCell.animate({
        width:cellSideLength,
        height:cellSideLength,
        top:getPosTop( i , j ),
        left:getPosLeft( i , j )
    }, animateMergeTime);
}

//数字移动
function showMoveAnimation( fromx , fromy , tox, toy ){

    var numberCell = $('#number-cell-' + fromx + '-' + fromy );
    numberCell.animate({
        top:getPosTop( tox , toy ),
        left:getPosLeft( tox , toy )
    },animateMoveTime);
}
//更新当前分数
function updateScore( score ){
    $('#score').text( score );
}

//初始化一些操作
$(document).ready(function(){
    $('#head_text').text(dbData.name);
    $('#grid-container').css('background-color',gameData.bgs(colorSet));
    var html = '';
    for(var x = 0; x < gradeData; x++){
        for(var y = 0; y < gradeData; y++){
            html += '<section class="grid-cell" id="grid-cell-'+ x +'-'+ y +'"></section>';
        }
    }
    $('#grid-container').append(html);
    $('.grid-cell').css('background-color',gameData.cellBg(colorSet));
    localStorage.setItem('canMove_'+dataId, 1);
    prepareForMobile();
    newgame2();
});

//大屏处理
function prepareForMobile(){

    if( documentWidth > 800 ){
        gridContainerWidth = 500;
        cellSpace = 12;
        cellSideLength = 86;
    }

    $('#grid-container,#headTitle').css('width',gridContainerWidth - 2*cellSpace);
    $('#grid-container').css('height',gridContainerWidth - 2*cellSpace);
    $('#grid-container,#headTitle').css('padding', cellSpace);
    $('#grid-container,#headTitle').css('border-radius',0.02*gridContainerWidth);

    $('.grid-cell').css('width',cellSideLength);
    $('.grid-cell').css('height',cellSideLength);
    $('.grid-cell').css('border-radius',0.04*cellSideLength);
}

//点击的新游戏
function newgame(){
    //初始化棋盘格
    init();
    //在随机两个格子生成数字
    generateOneNumber();
    generateOneNumber();
    updateBoardView();
    restartReward();
}
//初始化新游戏
function newgame2(){
    for( var i = 0 ; i < gradeData ; i ++ ){
        for( var j = 0 ; j < gradeData ; j ++ ){
            var gridCell = $('#grid-cell-'+i+"-"+j);
            gridCell.css('top', getPosTop( i , j ) );
            gridCell.css('left', getPosLeft( i , j ) );
        }
    }
    var dateNow = new Date(),
        isClearData = localStorage.getItem('clearData');
    var saveTime = parseInt((localStorage.getItem('saveTime_'+dataId) == null)?0:localStorage.getItem('saveTime_'+dataId))+10;
    var timeSize = dateNow - saveTime;
    if(isClearData == '1' || timeSize <= 0){
        localStorage.removeItem('data_'+dataId);
        localStorage.removeItem('clearData');
    }
    var data = localStorage.getItem('data_'+dataId);
    if(data !== null){
        hasConflicted = JSON.parse(localStorage.getItem('hasConflicted_'+dataId));
        board = JSON.parse(data);
        score = parseInt((localStorage.getItem('score_'+dataId) !== null)?localStorage.getItem('score_'+dataId):score);
        $('#score').text(score);
        $('#bestScore').text(getLocalBest());
        updateBoardView();
    }else{
        newgame();
    }
    restartReward();
}
function restartReward(){
    if(rewardArr.length > 1){
        for(var i = 1; i < rewardArr.length; i++){
            for(var j = 0; j < (rewardArr.length - i); j++){
                rewardArr[j].score = parseInt(rewardArr[j].score);
                rewardArr[j].cross = (score >= rewardArr[j].score)?true:false;
                var k = j+1;
                rewardArr[k].score = parseInt(rewardArr[k].score);
                rewardArr[k].cross = (score >= rewardArr[k].score)?true:false;
                if(rewardArr[j].score > rewardArr[k].score){
                    var a = rewardArr[j+1];
                    rewardArr[j+1] = rewardArr[j];
                    rewardArr[j] = a;
                }
            }
        }
        return ;
    }else if(rewardArrLength === 1){
        rewardArr[0].score = parseInt(rewardArr[0].score);
        rewardArr[0].cross = (score >= rewardArr[0].score)?true:false;;
    }
}
//初始化
function init(){
    for( var i = 0 ; i < gradeData ; i ++ ){
        board[i] = new Array();
        hasConflicted[i] = new Array();
        for( var j = 0 ; j < gradeData ; j ++ ){

            var gridCell = $('#grid-cell-'+i+"-"+j);
            gridCell.css('top', getPosTop( i , j ) );
            gridCell.css('left', getPosLeft( i , j ) );

            board[i][j] = 0;
            hasConflicted[i][j] = false;
        }
    }
    score = 0;
    setLocalBest(getLocalBest(),0);
}
//获取本地最高分
function getLocalBest(){
    return (localStorage.getItem('bestScore_'+dataId) !== null)?localStorage.getItem('bestScore_'+dataId):-1;
}
//设置本地最高分
function setLocalBest(num1,num2){
    var localBest = Math.max(num1,num2);
    localStorage.setItem('bestScore_'+dataId,localBest);
    localStorage.setItem('score_'+dataId,score);
    $('#bestScore').text(localBest);
    var count = (rewardArrLength == rewardArr.length)?(rewardArrLength-1):rewardArrLength;
    for(var i = count; i > -1; --i){
        var obj = rewardArr[i];
        if((num2 >= obj.score) && (obj.score > 0) && (obj.cross === false)){
            localStorage.setItem('canMove_'+dataId, 0);
            var fori = i,
                self = this;
            layui.use('layer', function(){
                var layer = layui.layer;
                layer.open({
                    content: '马上领取奖励吗？'
                    ,title: '提示'
                    ,anim:false
                    ,btn: ['去领取', '继续玩']
                    ,yes: function(index,layero){
                        localStorage.setItem('canMove_'+dataId, 1);
                        localStorage.setItem('data_'+dataId,JSON.stringify(board));
                        localStorage.setItem('score_'+dataId,score);
                        localStorage.setItem('saveTime_'+dataId, Date.parse(new Date())/1000);
                        localStorage.setItem('hasConflicted_'+dataId, JSON.stringify(hasConflicted));
                        if(rewardArr[fori].url !== ''){
                            var url,reg=/(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/;
                            if(!reg.test(obj.url)){
                                url = 'http://'+obj.url;
                            }else{
                                url = obj.url;
                            }
                            window.location.href = url;
                        }
                        self.crossTrue(fori);
                        layer.close(index);
                    },
                    btn2: function(index,layero){
                        self.crossTrue(fori);
                        localStorage.setItem('canMove_'+dataId, 1);
                        layer.close(index);
                    },
                    end:function(){
                        self.crossTrue(fori);
                        localStorage.setItem('canMove_'+dataId, 1);
                    }
                });
            });
        }
    }
}

//检测奖励是否已经过期
function crossTrue(i){
    (rewardArr[i].cross == false)?(rewardArr[i].cross = true):'';
}
//获取字体大小
function getFontSize(number){
    //var size = eval('fontSize'+gradeData);
    return gameData.fontSize(gradeData,number);
}

//更新格子和数字
function updateBoardView() {

    $(".number-cell").remove();
    for (var i = 0; i < gradeData; i++)
        for (var j = 0; j < gradeData; j++) {
            var html = '<section class="number-cell" id="number-cell-' + i + '-' + j + '"></section>';
            $("#grid-container").append(html);
            var theNumberCell = $('#number-cell-' + i + '-' + j);
            if (board[i][j] !== 0){
                var style = {
                    'top': getPosTop(i, j),
                    'left': getPosLeft(i, j),
                    'background-color': getNumberBackgroundColor(board[i][j]),
                    'color': getNumberColor(board[i][j]),
                    'font-size': getFontSize(board[i][j]),
                    //'font-family': ((dbData.font.indexOf('"') >= 0)?dbData.font:'"'+dbData.font+'"'),
                };
                theNumberCell.css(style);
                theNumberCell.text(board[i][j]);
            }
            hasConflicted[i][j] = false;
        }
    var numberCellObj = $('.number-cell');
    numberCellObj.css({
        'width':cellSideLength,
        'height':cellSideLength,
        'line-height':cellSideLength+'px',
        'font-family': ((dbData.font.indexOf('"') >= 0)?dbData.font:'"'+dbData.font+'"'),
    });
}

//产生一个新数字：2
function generateOneNumber(){
    if( nospace( board ) )
        return false;

    //随机一个位置
    var randx = parseInt( Math.floor( Math.random()  * gradeData ) );
    var randy = parseInt( Math.floor( Math.random()  * gradeData ) );

    var times = 0;
    while( times < 50 ){
        if( board[randx][randy] == 0 )
            break;

        randx = parseInt( Math.floor( Math.random()  * gradeData ) );
        randy = parseInt( Math.floor( Math.random()  * gradeData ) );

        times ++;
    }
    if( times == 50 ){
        for( var i = 0 ; i < gradeData ; i ++ )
            for( var j = 0 ; j < gradeData ; j ++ ){
                if( board[i][j] == 0 ){
                    randx = i;
                    randy = j;
                }
            }
    }

    //随机一个数字
    var randNumber = 2;//Math.random() < 0.5 ? 2 : 2;

    //在随机位置显示随机数字
    board[randx][randy] = randNumber;
    showNumberWithAnimation( randx , randy , randNumber );

    return true;
}

//事件监听 start
$(document).keydown( function( event ){
    event.preventDefault();
    switch( event.keyCode ){
        case 37: //left
            if( moveLeft() ){
                setTimeout("generateOneNumber()",timeOut);
                setTimeout("isgameover()",timeOut);
            }
            break;
        case 38: //up
            if( moveUp() ){
                setTimeout("generateOneNumber()",timeOut);
                setTimeout("isgameover()",timeOut);
            }
            break;
        case 39: //right
            if( moveRight() ){
                setTimeout("generateOneNumber()",timeOut);
                setTimeout("isgameover()",timeOut);
            }
            break;
        case 40: //down
            if( moveDown() ){
                setTimeout("generateOneNumber()",timeOut);
                setTimeout("isgameover()",timeOut);
            }
            break;
        default: //default
            break;
    }
});
document.getElementById('grid-container').addEventListener('touchstart',function(event){
    if($.trim($(event.changedTouches[0].target).html()) !== 'NEW GAME'){
        event.preventDefault();
    }
    if (window.navigator.msPointerEnabled) {
        startx = event.pageX;
        starty = event.pageY;
    }else{
        startx = event.touches[0].clientX;
        starty = event.touches[0].clientY;
    }
});
document.getElementById('grid-container').addEventListener('touchend',function(event){
    if($.trim($(event.changedTouches[0].target).html()) !== 'NEW GAME'){
        event.preventDefault();
    }
    if ((!window.navigator.msPointerEnabled && event.touches.length > 0) ||
        event.targetTouches.length > 0) {
        return; // Ignore if still touching with one or more fingers
    }
    var endx,endy;
    if (window.navigator.msPointerEnabled) {
        endx = event.pageX;
        endy = event.pageY;
    }else{
        endx = event.changedTouches[0].clientX;
        endy = event.changedTouches[0].clientY;
    }

    var deltax = endx - startx;
    var deltay = endy - starty;
    var absx = Math.abs( deltax),
        absy = Math.abs( deltay );
    if( Math.max(absx, absy) > 10){
        if( absx > absy ) {
            if (deltax > 0) {
                if (moveRight()) {
                    setTimeout("generateOneNumber()", timeOut);
                    setTimeout("isgameover()", timeOut);
                }
            } else {
                if (moveLeft()) {
                    setTimeout("generateOneNumber()", timeOut);
                    setTimeout("isgameover()", timeOut);
                }
            }
        }else{
            if (deltay > 0) {
                if (moveDown()) {
                    setTimeout("generateOneNumber()", timeOut);
                    setTimeout("isgameover()", timeOut);
                }
            } else {
                if (moveUp()) {
                    setTimeout("generateOneNumber()", timeOut);
                    setTimeout("isgameover()", timeOut);
                }
            }
        }
    }
});
//事件监听 end

//游戏是否结束
function isgameover(){
    timerStart();
    if( nospace( board ) && nomove( board ) ){
        gameover();
    }
}

//游戏结束
function gameover(){
    alert('gameover!');
}

//移动 start
function moveLeft(){

    if( !canMoveLeft( board ) )
        return false;

    //moveLeft
    for( var i = 0 ; i < gradeData ; i ++ )
        for( var j = 1 ; j < gradeData ; j ++ ){
            if( board[i][j] != 0 ){

                for( var k = 0 ; k < j ; k ++ ){
                    if( board[i][k] == 0 && noBlockHorizontal( i , k , j , board ) ){
                        //move
                        showMoveAnimation( i , j , i , k );
                        board[i][k] = board[i][j];
                        board[i][j] = 0;
                        continue;
                    }
                    else if( board[i][k] == board[i][j] && noBlockHorizontal( i , k , j , board ) && !hasConflicted[i][k] ){
                        //move
                        showMoveAnimation( i , j , i , k );
                        //add
                        board[i][k] += board[i][j];
                        board[i][j] = 0;
                        //add score
                        score += board[i][k];
                        setLocalBest(getLocalBest(),score);
                        updateScore( score );

                        hasConflicted[i][k] = true;
                        continue;
                    }
                }
            }
        }
    timerStart();
    return true;
}

function moveRight(){
    if( !canMoveRight( board ) )
        return false;

    //moveRight
    for( var i = 0 ; i < gradeData ; i ++ )
        for( var j = gradeData-2 ; j >= 0 ; j -- ){
            if( board[i][j] != 0 ){
                for( var k = gradeData-1 ; k > j ; k -- ){

                    if( board[i][k] == 0 && noBlockHorizontal( i , j , k , board ) ){
                        //move
                        showMoveAnimation( i , j , i , k );
                        board[i][k] = board[i][j];
                        board[i][j] = 0;
                        continue;
                    }
                    else if( board[i][k] == board[i][j] && noBlockHorizontal( i , j , k , board ) && !hasConflicted[i][k] ){
                        //move
                        showMoveAnimation( i , j , i , k);
                        //add
                        board[i][k] += board[i][j];
                        board[i][j] = 0;
                        //add score
                        score += board[i][k];
                        setLocalBest(getLocalBest(),score);
                        updateScore( score );

                        hasConflicted[i][k] = true;
                        continue;
                    }
                }
            }
        }

    timerStart()
    return true;
}

function moveUp(){

    if( !canMoveUp( board ) )
        return false;

    //moveUp
    for( var j = 0 ; j < gradeData ; j ++ )
        for( var i = 1 ; i < gradeData ; i ++ ){
            if( board[i][j] != 0 ){
                for( var k = 0 ; k < i ; k ++ ){

                    if( board[k][j] == 0 && noBlockVertical( j , k , i , board ) ){
                        //move
                        showMoveAnimation( i , j , k , j );
                        board[k][j] = board[i][j];
                        board[i][j] = 0;
                        continue;
                    }
                    else if( board[k][j] == board[i][j] && noBlockVertical( j , k , i , board ) && !hasConflicted[k][j] ){
                        //move
                        showMoveAnimation( i , j , k , j );
                        //add
                        board[k][j] += board[i][j];
                        board[i][j] = 0;
                        //add score
                        score += board[k][j];
                        setLocalBest(getLocalBest(),score);
                        updateScore( score );

                        hasConflicted[k][j] = true;
                        continue;
                    }
                }
            }
        }

    timerStart();
    return true;
}

function moveDown(){
    if( !canMoveDown( board ) )
        return false;

    //moveDown
    for( var j = 0 ; j < gradeData ; j ++ )
        for( var i = gradeData-2; i >= 0 ; i -- ){
            if( board[i][j] != 0 ){
                for( var k = gradeData -1 ; k > i ; k -- ){

                    if( board[k][j] == 0 && noBlockVertical( j , i , k , board ) ){
                        //move
                        showMoveAnimation( i , j , k , j );
                        board[k][j] = board[i][j];
                        board[i][j] = 0;
                        continue;
                    }
                    else if( board[k][j] == board[i][j] && noBlockVertical( j , i , k , board ) && !hasConflicted[k][j] ){
                        //move
                        showMoveAnimation( i , j , k , j );
                        //add
                        board[k][j] += board[i][j];
                        board[i][j] = 0;
                        //add score
                        score += board[k][j];
                        setLocalBest(getLocalBest(),score);
                        updateScore( score );

                        hasConflicted[k][j] = true;
                        continue;
                    }
                }
            }
        }

    timerStart();
    return true;
}
//移动 end

function timerStart(){
    requestAnimationFrame(updateBoardView);
    //setTimeout("updateBoardView()",intervalTime);
}