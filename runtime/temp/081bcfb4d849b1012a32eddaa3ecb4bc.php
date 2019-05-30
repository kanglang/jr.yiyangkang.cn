<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:75:"/www/wwwroot/pig.77cco.com/public/../application/admin/view/user/index.html";i:1554712250;s:68:"/www/wwwroot/pig.77cco.com/application/admin/view/public/header.html";i:1554712249;s:45:"../application/common/view/public/header.html";i:1554712305;s:68:"/www/wwwroot/pig.77cco.com/application/admin/view/public/footer.html";i:1554712249;s:45:"../application/common/view/public/footer.html";i:1554712305;}*/ ?>
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
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- Panel Other -->
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>用户列表</h5>
        </div>
        <div class="ibox-content">
            <!--搜索框开始-->           
            <div class="row">
                <div class="col-sm-12">   
                <div  class="col-sm-2" style="width: 100px">
                    <div class="input-group" >  
                        <a href="<?php echo url('userAdd'); ?>"><button class="btn btn-outline btn-primary" type="button">添加用户</button></a> 
                    </div>
                </div>                                            
                    <form name="admin_list_sea" class="form-search" method="get" action="<?php echo url('index'); ?>">
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" id="key" class="form-control" name="key" value="<?php echo $val; ?>" placeholder="输入需查询的用户名" />   
                                <span class="input-group-btn"> 
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button> 
                                </span>
                            </div>
                        </div>
                    </form>                         
                </div>
            </div>
            <!--搜索框结束-->
            <div class="hr-line-dashed"></div>

            <div class="example-wrap">
                <div class="example">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="long-tr">
                                <th>ID</th>
                                <th>管理员名称</th>
                                <th>头像</th>
                                <th>管理员角色</th>
                                <th>登录次数</th>
                                <th>上次登录ip</th>
                                <th>上次登录时间</th>
                                <th>真实姓名</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                            <tbody id="list-content">
                                <?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
                                    <tr>
                                        <td class="text-center"><?php echo $list['id']; ?></td>
                                        <td class="text-center"><?php echo $list['username']; ?></td>
                                        <td>
                                            <img src="/uploads/face/<?php echo $list['portrait']; ?>" class="img-circle" style="width:35px;height:35px" onerror="this.src='/static/admin/images/head_default.gif'"/>                
                                        </td>
                                        <td  class="text-center"><?php echo $list['title']; ?></td>                                  
                                        <td class="text-center"><?php echo $list['loginnum']; ?></td>
                                        <td class="text-center"><?php echo $list['last_login_ip']; ?></td>
                                        <td class="text-center"><?php echo date('Y-m-d H:i',$list['last_login_time']); ?></td>
                                        <td class="text-center"><?php echo $list['real_name']; ?></td>
                                        <td  class="text-center">
                                            <?php if($list['id'] != 1): if($list['status'] == 1): ?>
                                                <a class="red" href="javascript:;" onclick="user_state(<?php echo $list['id']; ?>);">
                                                    <div id="<?php echo $list['id']; ?>"><span class="label label-info">开启</span></div>
                                                </a>
                                                <?php else: ?>
                                                <a class="red" href="javascript:;" onclick="user_state(<?php echo $list['id']; ?>);">
                                                    <div id="zt<?php echo $list['id']; ?>"><span class="label label-danger">禁用</span></div>
                                                </a>
                                                <?php endif; endif; ?>
                                        </td>
                                        <td  class="text-center">
                                        <a href="javascript:;" onclick="userEdit(<?php echo $list['id']; ?>)" <?php if($admin_id != 1): ?> style="display: none;" <?php endif; ?> class="btn btn-primary btn-xs">
                                            <i class="fa fa-paste"></i> 编辑</a>&nbsp;&nbsp;
                                            <?php if($list['id'] != 1): ?>
                                        <a href="javascript:;" onclick="userDel(<?php echo $list['id']; ?>)" class="btn btn-danger btn-xs">
                                            <i class="fa fa-trash-o"></i> 删除</a>
                                            <?php endif; ?>
                                </td>
                                    </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                    <div style="text-align: right;">
                        共<?php echo $count; ?>个用户
                        <!-- 分页 -->
                    </div>
                    <div id="Pages"> <?php echo $page; ?> </div>
                </div>
            </div>
            <!-- End Example Pagination -->
        </div>
    </div>
</div>
<!-- End Panel Other -->
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

<script type="text/javascript">

//编辑用户
function userEdit(id){
    location.href = './userEdit/id/'+id+'.html';
}

//删除用户
function userDel(id){
    lunhui.confirm(id,'<?php echo url("userDel"); ?>');
}

//用户状态
function user_state(id){
    lunhui.status(id,'<?php echo url("user_state"); ?>');
}

</script>
</body>
</html>