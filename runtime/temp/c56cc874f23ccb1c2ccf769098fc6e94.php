<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:68:"/home/xgh/zouzebin/public/../application/admin/view/index/index.html";i:1554117475;s:60:"/home/xgh/zouzebin/application/admin/view/public/header.html";i:1554117475;s:45:"../application/common/view/public/header.html";i:1554117475;s:60:"/home/xgh/zouzebin/application/admin/view/public/footer.html";i:1554117475;s:45:"../application/common/view/public/footer.html";i:1554117475;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo config('WEB_SITE_TITLE'); ?></title>
    <link href="__CSS__/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="__CSS__/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="__CSS__/animate.min.css" rel="stylesheet">
    <link href="__CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__CSS__/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="__CSS__/plugins/switchery/switchery.css" rel="stylesheet">
    <link href="__CSS__/style.min.css?v=4.1.0" rel="stylesheet">
    <link href="__CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="__CSS__/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">


    <!-- 自定义公用css -->
    <link href="__CSS__/public.css?v=<?=date('Y-m-d');?>" rel="stylesheet">

    <script src="__JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__JS__/public.js?v=<?=date('Y-m-d');?>"></script>
    <script src="__JS__/plugins/uploadify/jquery.uploadify.min.js"></script>
    <script src="__JS__/plugins/zclip/ZeroClipboard.min.js"></script>
    <script src="__JS__/dialog.js"></script>
    <script src="__JS__/jquery.dragsort-0.5.2.min.js"></script> 
    <script src="__JS__/masonry.pkgd.min.js"></script> 
	<script src="__STATIC__/js/jquery.validate.js"></script> 
	
	<!--时间控件-->
	<link rel="stylesheet" type="text/css" media="all" href="__CSS__/plugins/datapicker/datepicker3.css" />
	<script type="text/javascript" src="__JS__/plugins/datapicker/bootstrap-datepicker.js"></script>
    
    <link rel="stylesheet" type="text/css" media="all" href="__JS__/plugins/datetimepicker/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="__JS__/plugins/datetimepicker/bootstrap-datetimepicker.min.js"></script>


    <style type="text/css">
    .long-tr th{
        text-align: center
    }
    .long-td td{
        text-align: center
    }
    </style>

    <script type="text/javascript">
        var  SITE_URL = "__SITE_URL__";
        var  IMG_PATH = "__IMG__";
        var  JS_PATH = "__JS__";
        var  STATIC = "__STATIC__";
        var  ROOT = "__ROOT__";
        var  UPLOAD_PICTURE = "<?php echo url('home/File/uploadPicture',array('session_id'=>get_session_id())); ?>";
        var  UPLOAD_FILE = "<?php echo url('home/File/upload',array('session_id'=>get_session_id())); ?>";
        var  UPLOAD_DIALOG_URL = "<?php echo url('home/File/uploadDialog',array('session_id'=>get_session_id())); ?>";

    </script>

    <div id="top-alert" class="top-alert-tips alert-error" style="display: none;">
  <a class="close" href="javascript:;"><b class="fa fa-times-circle"></b></a>
  <div class="alert-content"></div>
</div>
</head>

<body class="gray-bg">
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-sm-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>待处理订单</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?php echo $count['handle_order']; ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>商品数量</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?php echo $count['goods']; ?></h1>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>会员总数</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?php echo $count['users']; ?></h1>
                </div>
            </div>
        </div>
        
        <div class="col-sm-12">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>新增订单</th>
                    <th>今天访问</th>
                    <th>新增会员</th>
                    <th>游戏情况</th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td><?php echo $count['new_order']; ?></td>
                    <td><?php echo $count['today_login']; ?></td>
                    <td><?php echo $count['new_users']; ?></td>
                    <td><?php echo $game_info; ?></td>
                </tr>
                </tbody>
            </table>
        </div>


</div>


    <!-- 中间折线 -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-cogs"></i> 系统信息
                </div>
                <div class="panel-body">
                    <p><i class="fa fa-sitemap"></i> 框架版本：ThinkPHP<?php echo $info['think_v']; ?>
                    </p>
                    <p><i class="fa fa-windows"></i> 服务环境：<?php echo $info['web_server']; ?>
                    </p>
                    <p><i class="fa fa-warning"></i> 上传附件限制：<?php echo $info['onload']; ?>
                    </p>
                    <p><i class="fa fa-credit-card"></i> PHP 版本：<?php echo $info['phpversion']; ?>
                    </p>
                </div>
            </div>
           
        </div>
    </div>

</div>


<script src="__JS__/bootstrap.min.js?v=3.3.6"></script>
<script src="__JS__/content.min.js?v=1.0.0"></script>
<script src="__JS__/plugins/chosen/chosen.jquery.js"></script>
<script src="__JS__/plugins/iCheck/icheck.min.js"></script>
<script src="__JS__/plugins/layer/laydate/laydate.js"></script>
<script src="__JS__/plugins/switchery/switchery.js"></script><!--IOS开关样式-->
<script src="__JS__/jquery.form.js"></script>
<script src="__JS__/layer/layer.js"></script>
<script src="__JS__/laypage/laypage.js"></script>
<script src="__JS__/laytpl/laytpl.js"></script>
<!-- <script src="__JS__/public.js"></script> -->
<script>
    $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>

</body>
</html>