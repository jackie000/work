<?php
session_start();
header("Content-Type:text/html;charset=utf-8");
error_reporting( E_ERROR | E_WARNING | E_PARSE );
date_default_timezone_get( 'Asia/Chongqing' );

//error_log( "weixin back\n", 3, "weixin_back_access.log" );
include 'WeixinAuthorize.php';
$wa = new WeixinAuthorize();

$token = $wa->getAccessTokenByCode();
if( $token !== false ){
    $userInfo = $wa->getUserInfo( $token['access_token'], $token['openid'] );
    if( $userInfo !== false ){
        $userInfo['privilege'] = json_encode( $userInfo['privilege'] );
        $data = saveWeixinAuthUser( $userInfo );
        if( $data !== false ){
            $_SESSION['weixin_openid'] = $data['openid'];
            $_SESSION['db_user_id'] = $data['id'];
            header( "Location: index.php" );
            exit;
        }else{
            //error_log( "save user false\n", 3, "weixin_back_access.log" );
        }
    }else{
        //error_log( "get user info false\n", 3, "weixin_back_access.log" );
    }
}else{
    //error_log( "token return false\n", 3, "weixin_back_access.log" );
}

function saveWeixinAuthUser( $info ){
    include 'db.php';
    $table = 'weixin_auth_users';
    $data = array();
    $data['openid'] = $info['openid'];
    $data['unionid'] = $info['unionid'];
    $data['nickname'] = $info['nickname'];
    $data['sex'] = $info['sex'];
    $data['province'] = $info['province'];
    $data['city'] = $info['city'];
    $data['country'] = $info['country'];
    $data['headimgurl'] = $info['headimgurl'];
    $data['privilege'] = $info['privilege'];
    $data['last_login_time'] = time();
    if( ( $res = $db->get( $table, ['id'], ['openid'=>$data['openid']] ) ) ){
        $db->update( $table, $data, ['id'=>$res['id']] );
        $data['id'] = $res['id'];
        return $data;

    }else{

        $lastId = $db->insert( $table, $data );
        error_log( json_encode($data) . "\n", 3, "weixin_back_access.log" );
        error_log( json_encode($db->log()) . "\n", 3, "weixin_back_access.log" );
        if( $lastId ){
            $data['id'] = $lastId;
            return $data;
        }
    }
    return false;
}
?>
