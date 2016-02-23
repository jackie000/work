<?php
header("Content-Type:text/html;charset=utf-8");
error_reporting( E_ERROR | E_WARNING | E_PARSE );
date_default_timezone_get( 'Asia/Chongqing' );
include 'db.php';
$name = $_GET['name'];
$res = $db->select( 'check_in','*', ['name[~]'=>$name] );

$result = array();
if( $res ){
    foreach( $res as $k=>$v ){
        $result[] = $v['phone'];
    }
}

echo json_encode( array( 'error_code'=>1, 'error_msg'=>"返回数据成功", "data"=>$result ) );
exit;

?>
