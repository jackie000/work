<?php
class Db {

    private static $_instance;
    private function __construct(){}

    private function __clone(){}

    public static function getInstance(){
        if( self::$_instance === null ){
            $database = array();
            $database['type'] = 'mysql';
            $database['databases'] = 'lianjia';
            $database['server'] = 'localhost';
            $database['user'] = 'root';
            $database['password'] = 'cXzKy86fw3caJEDz';
            $database['charset'] = 'utf8';
            $database['port'] = 3306;
            self::$_instance = new \Db\medoo([
                'database_type'=>$database['type'],
                'database_name'=>$database['databases'],
                'server'=>$database['server'],
                'username'=>$database['user'],
                'password'=>$database['password'],
                'charset'=>$database['charset'],
                'port'=>$database['port'],
                'option'=>[
                    PDO::ATTR_CASE =>PDO::CASE_NATURAL
                ]

            ]);
        }

        return self::$_instance;
    }

}

?>
