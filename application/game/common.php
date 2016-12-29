<?php
function userAgent(){
    if (preg_match('/QQBrowser/i',$_SERVER['HTTP_USER_AGENT' ])){
        $field = 'qq';
    }else if(preg_match('/MicroMessenger/i',$_SERVER['HTTP_USER_AGENT' ])){
        $field = 'weixin';
    }else{
        $field = 'other';
    }
    return $field;
}