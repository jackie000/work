<?php
session_start();
header("Content-Type:text/html;charset=utf-8");
error_reporting( E_ERROR | E_WARNING | E_PARSE );
date_default_timezone_get( 'Asia/Chongqing' );

if( !isset( $_SESSION['userid'] ) ){
    header( "Location: login.php" );
    exit;
}
?>
