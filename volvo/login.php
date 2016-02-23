<?php
session_start();
if( isset( $_SESSION['userid'] ) ){
    header("Location: signup_list.php");
    exit;
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
    if( $_POST['userid'] == "adminvolvo" && $_POST['password'] == "adminvolvo" ){

        $_SESSION['userid'] = $_POST['userid'];
        header("Location: signup_list.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>Admin volvo | Log in</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="bg-black">
        <div class="margin text-center">
        </div>
        <div class="box-body">
            <?php

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
    if( $_POST['userid'] != "adminvolvo" || $_POST['password'] != "adminvolvo" ){
            ?>
            <div class="alert alert-danger alert-dismissable">
                <i class="fa fa-ban"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <b>错误!</b>登录账户或密码错误！
            </div>
            <?php
    }
}
?>
        </div>

        <div class="form-box" id="login-box">
            <div class="header">后台管理登录</div>
            <form action="login.php" method="post">
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="text" name="userid" class="form-control" placeholder="登录账户"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="密码"/>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="remember_me"/> 记住我
                    </div>
                </div>
                <div class="footer">
                    <button type="submit" class="btn bg-olive btn-block">登录</button>
                </div>
            </form>

            <div class="margin text-center">
            </div>
        </div>

        <!-- jQuery 2.0.2 -->
        <script src="js/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
    </body>
</html>


