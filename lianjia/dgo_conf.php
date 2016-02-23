<?php
/**
 * 新活动代码的唯一配置文件
 * 
 * dgo_conf目录和v目录需要同级并列放置
 * 配置文件移到v目录外，方便v目录代码的更新部署
 * 
 * @filename	inc.php 
 * @encoding	UTF-8 
 * @author	肖武 <lh_ts24@qq.com>
 * @datetime	2014-5-27  11:46:01
 * @version	1.0
 */

/**
 * 对应本目录的url访问地址，必须斜杠结尾
 */
const DGO_ROOT_URL = 'http://wxh.do2014.cn/v/';

/**
 * 微信账号配置
 */
//const DGO_APPID = 'wxa419a10741974c38';
const DGO_APPID = 'wx394cfa00e0aa4b6f';
//const DGO_APPSECRET = 'c3e1033c770c00372fcd17cff7facad4';
const DGO_APPSECRET = 'ba08e5a2e90ba6fbaa56e170fa404a90';

/**
 * memcache 配置，需要php的memcache扩展
 */
const DGO_MC_HOST = '127.0.0.1';
const DGO_MC_PORT = '11211';

/**
 * mysql数据库配置
 */
const DGO_DB_HOST = '127.0.0.1';
const DGO_DB_USER = 'root';
const DGO_DB_PASS = 'cXzKy86fw3caJEDz';
const DGO_DB_PORT = '3306';
const DGO_DB_NAME = 'voice';//数据库名
const DGO_DB_CHARSET = 'utf8';


/**
 * 数据目录，必须要有读写权限。用于记录日志和保存语音文件
 */
const DGO_PATH_DATA = 'C:\\xampp\\htdocs\\wuxianhao\\data\\';

