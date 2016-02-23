<?php
var_dump( ini_get( 'safe_mode' ) );
var_dump( ini_get( 'safe_mode_exec_dir' ) );
//$data = exec( "d:" . DIRECTORY_SEPARATOR . "ffmpeg" . DIRECTORY_SEPARATOR .  "bin" . DIRECTORY_SEPARATOR . "ffmpeg.ext -version" );
$data = exec( "d:/ffmpeg/bin/ffmpeg.exe -version" );
var_dump( $data );
//echo phpinfo();
exit;
define("TOKEN", "xxxzhangjie");
define("APPID", "wxf178cc450765a73a");
define("APPSECRET", "d4624c36b6795d1d99dcf0547af5443d");
ini_set("display_errors","Off");
header("Content-Type:text/html;charset=utf-8");
error_reporting( E_ERROR | E_WARNING | E_PARSE );
date_default_timezone_get( 'Asia/Chongqing' );

spl_autoload_register(function($class){
    if( $class ){
        $file = str_replace('\\', '/', $class);
        $file .= '.php';
        if( file_exists( $file ) ){
            include $file;
        }
    }
});


$postObj = new stdClass();
$postObj->MediaId = 'S0q6QLDIVmEIxVgk-w74L2Uu0Oh44obIAkxN9wY8CB4GgX6ddoI02CUUt-RD0r8h';

$weixin = new \Weixin\Api( $postObj, APPID, APPSECRET );
//$weixin->getAccessToken();
$dir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . date( 'Y-m-d', time() ) . DIRECTORY_SEPARATOR  . "openid_" . (string)$postObj->FromUserName . DIRECTORY_SEPARATOR;
mkdir( $dir, 0777, true );
$file = date('YmdHis', time() );
$path = $weixin->getMediaFile( $dir . $file );
$save = $path . ".wav";
$cmd = "ffmpeg -i $path $save";
error_log( "\r\n$cmd\r\n", 3, "log.log" );
error_log( "\r\n" . json_encode( ini_get( 'safe_mode' ) ). "\r\n", 3, "log.log" );
$tmp = `$cmd`;
error_log( "\r\n$tmp\r\n", 3, "log.log" );

$wav = new \Wave( $save );
$dB = $wav->avg_db();
error_log( "\r\ndB: $dB\r\n", 3, "log.log" );

echo $dB;
exit;
?>
