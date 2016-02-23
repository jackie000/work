<?php
/**
* @file Xml.php
* @brief 
* @author jackie <jackie@digiocean.cc>
* @version v1.0
* @date 2016-02-18
 */
namespace Weixin;

class Xml{

    public function replyText(){
        return "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>";
    }

}

?>
