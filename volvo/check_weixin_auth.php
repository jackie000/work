<?php
session_start();
header("Content-Type:text/html;charset=utf-8");
error_reporting( E_ERROR | E_WARNING | E_PARSE );
date_default_timezone_get( 'Asia/Chongqing' );
if( !isset( $_SESSION['weixin_openid'] ) || !isset( $_SESSION['db_user_id'] ) ){
    include 'WeixinAuthorize.php';
    $wa = new WeixinAuthorize();
    $wa->gotoOauth( 'http://www.digiocean.cc/volvo/check_weixin_auth_back.php' );
    //$wa->gotoOauth( 'http://www.digiocean.cc/' );
}
?>
