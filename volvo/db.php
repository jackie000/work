<?php
require 'medoo.php';
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
?>
