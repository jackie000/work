<?php
session_start();
header("Content-Type:text/html;charset=utf-8");
error_reporting( E_ERROR | E_WARNING | E_PARSE );
require 'medoo.php';
date_default_timezone_set('Asia/Chongqing');
$signupUser = array('Lars'=>'','付强'=>'18019786001','毛开阳'=>'18019786212','苏毅'=>'18019786399','李剑波'=>'18019786522','胡佩君'=>'18620995684','Martin Persson'=>'18019786262','马丁'=>'18019786262','Hans Hygrell'=>'18019786569','韩思瑞'=>'18019786569','黄文涛'=>'18621731959','范珂'=>'',
    '李耀淦'=>'18019786238','王润冬'=>'18019786445','陶正生'=>'13764465525','汪铖'=>'18019786374','徐颖一'=>'18019786557','姚沁'=>'18019786135',
    '梁林'=>'18019786521','李勇'=>'18611480738','许军'=>'18019786533','李锂'=>'15000528857','唐蓓莉'=>'18019786216','张洋'=>'13911988347',
    '朱梅'=>'15301685638','张联'=>'18019786041','叶长权'=>'18019786221','王大伟'=>'18019786210','戴松'=>'18019786254','叶伟'=>'18608020111',
    '常军'=>'18608020111','李顺军'=>'13611691665','王静'=>'18019786486','张萌'=>'18221763357','宁述勇'=>'18616512525','张铁英'=>'18604431800',
    '吴志超'=>'13331158958','迟新月'=>'18019786239','朱惠东'=>'18019786304','胡永乐'=>'18019786263','刘暘'=>'18019786276','乔彬'=>'13522144196','柳燕'=>'18019786566','Alwin'=>'','Hans'=>'18019786569',
    'Henrik'=>'','Martin'=>'','Oscar'=>'','Sven'=>'','白彬毅'=>'','罗冬飞'=>'','袁小林'=>'','张科伟'=>'13817648562','唐夏森'=>'18019786235','李宇翔'=>'18019786528',
    '胡斌'=>'15779819099','童建晓'=>'18019786509','虞铭'=>'18019786258','周旭展'=>'18019786564','单忠亮'=>'18019786579','李达'=>'18019786567','马学文'=>'18019786591','梅彧'=>'18019786116',
    '钦培吉'=>'18019786441','桑之军'=>'18019786488','崔静'=>'18019786482','文飞'=>'13926210677','吴琛'=>'18019786125','徐红'=>'18019786205','严建荣'=>'18019786597','于柯鑫'=>'13701222957',
    '黄海涛'=>'13911095869','许林峰'=>'13911159319','薛志伟'=>'13634402205','杨堃'=>'13821261316',' 朱明'=>'18680229877','莫剑'=>'18019786578','戴治平'=>'18019786573','侯朝晖'=>'13925106338',
    '崔海龙'=>'18602033588','李宁'=>'13802840399','师猛'=>'18620066052','左立'=>'13632235313','姜爽'=>'18501667805','谢欣'=>'',
    '贾璐'=>'18801127079','戴俊良'=>'18019786316','徐洁'=>'18019786424',
    '蔡禛杰'=>'18501609527','万雪松'=>'13910146991','于洁'=>'13520251162','张亚林'=>'13910072474','贾晓静'=>'13810410268','付兴'=>'13601105662',

    ''
);

$db = new medoo([
    'database_type'=>'mysql',
    'database_name'=>'volvo_online',
    'server'=>'localhost',
    'username'=>'root',
    'password'=>'cXzKy86fw3caJEDz',
    'charset'=>'utf8',
    'port'=>3306,
    'option'=>[
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);

$company = $_POST['company'];
$name    = $_POST['name'];
$phone   = $_POST['phone'];
$arrive   = $_POST['arrive'];
$back  = $_POST['back'];

$res = $db->select( 'check_in','*', ['name[~]'=>$name] );
if( $res ){
    $flg = false;
    foreach( $res as $k=>$v ){
        if( $v['name'] == $name ){
            if( $v['phone'] == "" ){
                $flg = true;
                break;
            }else{
                if( $v['phone'] == $phone ){
                    $flg = true;
                    break;
                }
            }
        }
    }
    if( $flg == false ){
        echo json_encode( array('error_code'=>1,'error_msg'=>"您的信息不允许报名!") );
        exit;
    }

}else{
    echo json_encode( array('error_code'=>1,'error_msg'=>"您的信息不允许报名!") );
    exit;
}
/*
if( !array_key_exists( $name, $signupUser ) ){
    echo json_encode( array('error_code'=>1,'error_msg'=>"您的信息不允许报名!") );
    exit;
}else{
    if( $signupUser[$name] != "" && $phone != $signupUser[$name] ){
        echo json_encode( array('error_code'=>1,'error_msg'=>"与预留手机号不匹配!") );
        exit;
    }
}
 */

/*
if( !preg_match( "/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email ) ){
    echo json_encode( array('error_code'=>1,'error_msg'=>"请输出正确的邮箱地址!") );
    exit;
}
 */


//$company = "lexus";
//$name = "zhangjie";
//$position = "tech";
//$phone = "13601170527";
//$email = "zhangjie@digiocean.cc";
//$_SESSION['weixin_openid'] = 1;
$lastId = $db->insert( "signup",[
    'openid'=>$_SESSION['weixin_openid'],
    'company'=>$company,
    'name'=>$name,
    'phone'=>$phone,
    'arrive'=>$arrive,
    'back'=>$back,
    'create_time'=>date('Y-m-d H:i:s', time())
]);
//var_dump( $db->log() );
//echo $lastId . " last ID;";
echo json_encode( array('error_code'=>0,'error_msg'=>"报名成功!") );
exit;
?>
