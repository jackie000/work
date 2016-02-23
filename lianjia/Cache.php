<?php
class Cache{

    private static $_instance;
    private $db;

    public function __construct(){
        $this->db = \Db::getInstance();
    }

    public function __clone(){}

    public static function getInstance(){

        if( self::$_instance === null ){
            self::$_instance = new \Cache();
        }

        return self::$_instance;
    }

    public function set( $key, $value, $expire=3600 ){
        $res = $this->get( $key );
        if( $res ){
            $this->db->update( 'cache', ['value'=>json_encode($value), 'expire'=>$expire, 'store_time'=>time() ], ['id'=>$res['id'] ] );
        }else{
            $this->db->insert( 'cache',[
                'key'=>$key,
                'value'=>json_encode( $value ),
                'expire'=>$expire,
                'store_time'=>time()
            ]);
        }

    }

    public function get( $key ){
        $res = $this->db->get( 'cache', ['id','key','value','expire','store_time'], ['key'=>$key] );
        if( $res ){
            if( $res['expire'] + $res['store_time'] > time() ){
                $res['value'] = json_decode( $res['value'], true );
                return $res;
            }else{
                $this->db->delete( 'cache',['id'=>$res['id'] ]);
            }
        }

        return false;
    }
}
?>
