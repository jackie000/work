<?php
//define("APPID", "wx394cfa00e0aa4b6f");
define("APPID", "wxaa967689278aaa96");
//define("APPSECRET", "ba08e5a2e90ba6fbaa56e170fa404a90");
define("APPSECRET", "39da7c89ba897a2b24d5cd28851c8308");

class WeixinAuthorize{

    public function gotoOauth( $backUrl ){
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize';
        $url .= '?' . http_build_query( array( 'appid'=>APPID, 'redirect_uri'=>$backUrl, 'response_type'=>'code', 'scope'=>'snsapi_userinfo', 'state'=>'STATE' ) ) . '#wechat_redirect';
        //echo $url;
        header( "Location: $url" );
        exit;

    }

    public function getAccessTokenByCode(){
        if( isset( $_GET['code'] ) ){
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
            $url .= '?' . http_build_query( array( 'appid'=>APPID, 'secret'=>APPSECRET, 'code'=>$_GET['code'], 'grant_type'=>'authorization_code' ) );
            $result = json_decode( $this->http_request( $url ), true );

            error_log( json_encode($result) . "\n", 3, "weixin_back_access.log" );
            if( isset( $result['errcode'] ) ){
                return false;
            }elseif( isset( $result['access_token'] ) ){
                return $result;
            }
        }
        return false;

    }

    public function getUserInfo( $accessToken, $openid ){
        $url = 'https://api.weixin.qq.com/sns/userinfo';
        $url .= '?' . http_build_query( array( 'access_token'=>$accessToken, 'openid'=>$openid, 'lang'=>'zh_CN' ) );
        $result = json_decode( $this->http_request( $url ), true );
        if( isset( $result['errcode'] ) ){
            return false;
        }else{
            return $result;
        }
    }


    public function http_request( $url ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
?>
