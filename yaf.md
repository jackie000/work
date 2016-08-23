### 引入composer的自动载入文件
 * yaf默认是按自己的方式来加载所有php文件的，在指定的几个目录找不到文件后就不识别其它文件。在php.ini中的yaf.use_spl_autoload关闭的情况下, 即使类没有找到, Yaf_Loader::autoload也会返回TRUE, 剥夺其后面的自动加载函数的执行权利.
    所以如果想要加载其它文件，就先去php.ini中设置

 * yaf.use_spl_autoload=true

 ```php 
 //在yaf生成的框架代码的Bootstrap类中引入vendor/autoload.php
 class Bootstrap extends Yaf_Bootstrap_Abstract{
     public function _initLoader() {
         Yaf_Loader::import(APPLICATION_PATH . "/vendor/autoload.php");
     }
 }
```
