<?php
/**
* @file Api.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @version v1.0
* @date 2016-02-18
 */
namespace Weixin;
class Api{

    private $obj;
    private $appId;
    private $appSecret;

    private $xml;

    private $apiHostUrl = 'https://api.weixin.qq.com/cgi-bin';
    private $apiFileUrl = 'http://file.api.weixin.qq.com/cgi-bin';

    public function __construct( $obj, $appId, $appSecret ){
        $this->obj = $obj;
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->xml = new \Weixin\Xml();

    }

    public function replyText( $txt ){
        $text = $this->xml->replyText();
        error_log( "text: $text\r\n", 3, "log.log" );
        $data = array( (string)$this->obj->FromUserName, (string)$this->obj->ToUserName, time(), $txt );
        return vsprintf( $text, $data );
    }


    public function getAccessToken(){
        $cache = \Cache::getInstance();
        $key = md5( 'weixin.token' );
        $res = $cache->get( $key );
        if( $res ){
            return $res['value'];
        }else{
            $url = $this->apiHostUrl . '/token?' . http_build_query( array( 'grant_type'=>'client_credential', 'appid'=>$this->appId, 'secret'=>$this->appSecret ) );


            error_log( "\r\naccess token url: " . $url . "\r\n", 3, "log.log" );
            $msg = $this->get( $url );
            error_log( "\r\naccess token" . json_encode( $msg ) . "\r\n", 3, "log.log" );
            if( isset( $msg['access_token'] ) ){
                $cache->set( $key, $msg['access_token'] );
                return $msg['access_token'];
            }
            return false;
        }
    }


    public function getMediaFile( $downloadPath ){
        $url = $this->apiHostUrl . "/media/get?";
        $accessToken = $this->getAccessToken();
        $url .= http_build_query( array( 'access_token'=>$accessToken, 'media_id'=>(string)$this->obj->MediaId ) );
        $ret = file_get_contents( $url );
        if( strpos( substr( $ret, 0, 100 ), 'errcode' ) ){
            error_log( "get media file return false \n", 3, "log.log" );
            error_log( json_encode($ret ) . "\n", 3, "log.log" );
            return false;
        }else{
            file_put_contents( $downloadPath, $ret );
            error_log( "file path $downloadPath \n", 3, "log.log" );
            return $downloadPath;
        }
    }


    public function getUserInfo(){

    }


    private function get( $url ){
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
        $result = curl_exec( $ch );
        error_log( "\r\n" . json_encode($result) . "\r\n", 3, "log.log" );
        if( $result === false ){

        }
        curl_close( $ch );
        return json_decode( $result, true );

    }

}


?>
