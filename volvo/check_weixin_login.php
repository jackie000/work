<?php
session_start();
header("Content-Type:text/html;charset=utf-8");
error_reporting( E_ERROR | E_WARNING | E_PARSE );
date_default_timezone_get( 'Asia/Chongqing' );
if( !isset( $_SESSION['weixin_openid'] ) || !isset( $_SESSION['db_user_id'] ) ){
    echo 0;
}else{
    echo 1;
}
exit;
?>
