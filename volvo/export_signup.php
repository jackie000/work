<?php
header("Content-Type:text/html;charset=utf-8");
include 'check_login.php';
include 'db.php';

$where = array();

$startDate = $_GET['startDate'];
$endDate   = $_GET['endDate'];

if( $startDate != "" && $endDate == "" ){
    $where['create_time[>=]'] = $startDate;
}

if( $startDate == "" && $endDate != "" ){
    $where['create_time[<=]'] = $endDate;
}

if( $startDate != "" && $endDate != "" ){
    $where['create_time[<>]'] = [ $startDate, $endDate];
}


$res = $db->select("signup","*", $where);
$fields = array('公司全称','姓名','电话','到达航班','返回航班','报名时间');
header( "Content-type:application/vnd.ms-excel" );
header( "Content-Disposition:attachment;filename=signup.xls" );
echo mb_convert_encoding(implode("\t", $fields), "gb2312", "UTF-8") . "\n";

foreach( $res as $item ){
    echo mb_convert_encoding( $item['company'], "gb2312", "UTF-8"). "\t";
    echo mb_convert_encoding( $item['name'], "gb2312", "UTF-8") . "\t";
    echo mb_convert_encoding( $item['phone'], "gb2312", "UTF-8") . "\t";
    echo mb_convert_encoding( $item['arrive'], "gb2312", "UTF-8") . "\t";
    echo mb_convert_encoding( $item['back'], "gb2312", "UTF-8") . "\t";
    echo $item['create_time'] . "\t";
    echo "\n";
}
exit;

?>
