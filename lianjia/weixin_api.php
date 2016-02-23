<?php
/**
* @file weixin_api.php
* @brief 微信调用接口
* @author jackie <jackie@digiocean.cc>
* @version v1.0
* @date 2016-02-17
 */

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

if( !empty( $_GET['echostr'] ) && checkSignature() ){
    echo $_GET['echostr'];
    exit;
}
response();

function response(){
    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    if( !empty( $postStr ) ){
        $postObj = simplexml_load_string( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );

        //消息类型
        $type = (string)$postObj->MsgType;
        error_log( json_encode( $postObj ), 3, "log.log" );

        /*
         * 若为语音消息，调用语音消息处理方法
         */
        if( $type == 'voice' ){
            $weixin = new \Weixin\Api( $postObj, APPID, APPSECRET );
            $dir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . date( 'Y-m-d', time() ) . DIRECTORY_SEPARATOR  . "openid_" . (string)$postObj->FromUserName . DIRECTORY_SEPARATOR;
            mkdir( $dir, 0777, true );
            $file = date('YmdHis', time() );
            $path = $weixin->getMediaFile( $dir . $file );
            $save = $path . ".wav";
            $cmd = "D:/ffmpeg/bin/ffmpeg.exe  -i $path $save";
            error_log( "\r\n$cmd\r\n", 3, "log.log" );
            error_log( "\r\n" . json_encode( ini_get( 'safe_mode' ) ). "\r\n", 3, "log.log" );
            $tmp = `$cmd`;
            error_log( "\r\n$tmp\r\n", 3, "log.log" );

            $wav = new \Wave( $save );
            $dB = $wav->avg_db();
            error_log( "\r\ndB: $dB\r\n", 3, "log.log" );

            $reply = $weixin->replyText( $dB );
            echo $reply;
            error_log( "reply: $reply\r\n", 3, "log.log" );
            exit;
        }

        //关注公众号触发事件
        if( $type == 'event' && (string)$postObj->Event == 'subscribe' ){
            $weixin = new \Weixin\Api( $postObj, APPID, APPSECRET );
            echo $weixin->replyText( "欢迎" );
            exit;
        }
    }
}

function checkSignature(){
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];

    $token = TOKEN;
    $tmpArr = array( $token, $timestamp, $nonce );
    sort( $tmpArr, SORT_STRING );
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );

    if( $tmpStr == $signature ) {
        return true;
    }else{
        return false;
    }
}

?>
