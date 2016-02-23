<?php
session_start();
header("Content-Type:text/html;charset=utf-8");
error_reporting( E_ERROR | E_WARNING | E_PARSE );
date_default_timezone_get( 'Asia/Chongqing' );
$siginup = 0;
include 'db.php';
$num = $db->count( 'signup',['openid'=>$_SESSION['weixin_openid']]);
if( $num > 0 ){
    $signup = 1;
}else{
    $signup = 0;
}
//1:已经报名
if( $signup == 1 ){
    echo 1;
}else{
    echo 0;
}
exit;
