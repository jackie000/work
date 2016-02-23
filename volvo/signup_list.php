<?php
include 'check_login.php';
include 'db.php';

$query = array();
$p = $_GET['p'];
if( $p == "" || $p <= 0 ){
    $p = 1;
}

$s = $_GET['s'];
if( $s == "" ){
    $s = 20;
}
$query['s'] = $s;

$where = array();

$startDate = $_GET['startDate'];
$endDate   = $_GET['endDate'];

if( $startDate != "" && $endDate == "" ){
    $where['create_time[>=]'] = $startDate;
    $query['startDate'] = $startDate;
}

if( $startDate == "" && $endDate != "" ){
    $where['create_time[<=]'] = $endDate;
    $query['endDate'] = $endDate;
}

if( $startDate != "" && $endDate != "" ){
    $where['create_time[<>]'] = [ $startDate, $endDate];
    $query['startDate'] = $startDate;
    $query['endDate'] = $endDate;
}
$count = $db->count("signup",$where);
$pageSize = ceil( $count / $s );
$pStart = ( $p-1 ) == 0 ? 1 : ($p-1) * $s;
$pEnd   = ( $p * $s  ) > $count ? $count : $p * $s;

$where['LIMIT'] = [ ($p-1) * $s, $s ];
$res = $db->select("signup","*", $where);

$fields = array('公司全称','姓名','电话','到达航班','返回航班','报名时间');

$pgNumber = 5;
$pgStart = 1;
$pgEnd = 5;

if( $p>3 ){
    $pgStart = $p - 2;
    $pgEnd   = $p + 2;
}

if( $pgEnd > $pageSize ){
    $pgEnd = $pageSize;
}

$pgUrl = "signup_list.php?" . http_build_query( $query );
$exUrl = "export_signup.php?" . http_build_query( $query );
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>报名人员信息</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- DATA TABLES -->
        <link href="css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="js/jquery-ui.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <div class="wrapper row-ocanvas row-ocanvas-left">
            <!-- Left side column. contains the logo and sidebar -->

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">                
                <!-- Content Header (Page header) -->
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">报名信息</h3>                                    
                                </div><!-- /.box-header -->
                                <form action="signup_list.php" method="GET">
                                <div style="margin:0 10px;">
                                报名开始时间:<input type="text" name="startDate" id="startDate" style="width:80px;" value="<?php echo $startDate;?>">
                                    报名结束时间:<input type="text" name="endDate" id="endDate" style="width:80px;" value="<?php echo $endDate;?>">
                                    &nbsp;&nbsp;<input type="submit" value="Search">
                                    <input class="pull-right" type="button" value="将结果导出Excel" name="export">
                                </div> 
                                </form>
                                <div class="box-body table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <?php
                                                    foreach( $fields as $n ){
                                                ?>
                                                    <th><?php echo $n;?></th>
                                                <?php
                                                    }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if( count($res)>0 ){
                                                foreach( $res as $item ){
                                            ?>
                                            <tr>
                                            <td><?php echo $item['company'];?></td>
                                            <td><?php echo $item['name'];?></td>
                                            <td><?php echo $item['phone'];?></td>
                                            <td><?php echo $item['arrive'];?></td>
                                            <td><?php echo $item['back'];?></td>
                                            <td><?php echo $item['create_time'];?></td>
                                            </tr>
                                            <?php
                                                }
                                            }else{
                                            ?>
                                            <tr>
                                                <td colspan="6">No data!</td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                    </table>
                                </div><!-- /.box-body -->
                                <div class="box-footer clearfix">
                                    <?php
                                        if( $count > 0 ){
                                    ?>
                                    <ul class="pagination pagination-sm no-margin pull-left">
                                        <li>showing <?php echo $pStart;?>-<?php echo $pEnd;?> of <?php echo $count;?> items</li>
                                    </ul>
                                    <?php
                                        }
                                    if( $pageSize>1 ){
                                    ?>
                                    <ul class="pagination pagination-sm no-margin pull-right">
                                    <li><a href="<?php echo $pgUrl . "&p=1";?>">&laquo;</a></li>
                                        <?php 
                                            for( $i=$pgStart; $i<=$pgEnd; $i++ ){
                                        ?>
                                        <li class="<?php echo $p == $i ? "active" : "";?>"><a href="<?php echo $pgUrl . "&p=" . $i;?>"><?php echo $i;?></a></li>
                                        <?php
                                            }
                                        ?>
                                        <li><a href="<?php echo $pgUrl . "&p=" . $pageSize;?>">&raquo;</a></li>
                                    </ul>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div><!-- /.box -->
                        </div>
                    </div>

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->


        <!-- jQuery 2.0.2 -->
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery-migrate-1.1.1.js"></script>
        <!-- Bootstrap -->
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <!-- DATA TABES SCRIPT -->
        <script src="js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="js/AdminLTE/app.js" type="text/javascript"></script>
        <script src="js/jquery-ui-datepicker.js"></script>

        <!-- page script -->
<script type="text/javascript">
$(function() {
    $("#startDate").datepicker();
    $("#endDate").datepicker();
    $('#example2').dataTable({
    "bPaginate": true,
        "bLengthChange": false,
        "bFilter": false,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": false
});
$("input[name=export]").click(function(){
    window.location.href="<?php echo $exUrl;?>";
});
});
</script>
    </body>
</html>
