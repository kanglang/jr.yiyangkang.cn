<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:80:"/www/wwwroot/jr.yiyangkang.cn/public/../application/admin/view/config/group.html";i:1554712250;s:71:"/www/wwwroot/jr.yiyangkang.cn/application/admin/view/public/header.html";i:1554712249;s:45:"../application/common/view/public/header.html";i:1554712305;s:71:"/www/wwwroot/jr.yiyangkang.cn/application/admin/view/public/footer.html";i:1554712249;s:45:"../application/common/view/public/footer.html";i:1554712305;}*/ ?>
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
<link rel="stylesheet" type="text/css" href="/static/admin/webupload/webuploader.css">
<link rel="stylesheet" type="text/css" href="/static/admin/webupload/style.css">
<link rel="stylesheet" type="text/css" media="all" href="/sldate/daterangepicker-bs3.css" />

<style type="text/css">
/* TAB */
.nav-tabs.nav>li>a {
    padding: 10px 25px;
    margin-right: 0;
}
.nav-tabs.nav>li>a:hover,
.nav-tabs.nav>li.active>a {
    border-top: 3px solid #1ab394;
    padding-top: 8px;
}
.nav-tabs>li>a {
    color: #A7B1C2;
    font-weight: 500;  
    margin-right: 2px;
    line-height: 1.42857143;
    border: 1px solid transparent;
    border-radius: 0;
}

</style>

<body class="gray-bg">
<div class="wrapper wrapper-content animated">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>网站配置</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">           
                    <div class="panel-body">                      
                        <div class="builder-tabs builder-form-tabs">
                            <ul class="nav nav-tabs">
                                <?php if(is_array(\think\Config::get('config_group_list')) || \think\Config::get('config_group_list') instanceof \think\Collection || \think\Config::get('config_group_list') instanceof \think\Paginator): $i = 0; $__LIST__ = \think\Config::get('config_group_list');if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group): $mod = ($i % 2 );++$i;?>
                                    <li <?php if($id == $key): ?>class="active"<?php endif; ?>><a href="<?php echo url('?id='.$key); ?>"><?php echo $group; ?>配置</a></li>
                                <?php endforeach; endif; else: echo "" ;endif; ?>           
                            </ul>
                        </div>
                        <div class="form-group"></div>
                    
                    <div class="builder-container">
                        <div class="row">
                            <div class="col-xs-12">
                                <form action="<?php echo url('save'); ?>" method="post" class="form-horizontal">  
                                    <div class="hr-line-dashed"></div>                                
                                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$config): $mod = ($i % 2 );++$i;?>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"><?php echo $config['title']; ?>：</label>
                                            <div class="input-group col-sm-4">
                                                <?php switch($config['type']): case "0": ?>
                                                    <input type="text" class="form-control" name="config[<?php echo $config['name']; ?>]" value="<?php echo $config['value']; ?>">
                                                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> <?php echo $config['remark']; ?></span>
                                                <?php break; case "1": ?>
                                                    <input type="text" class="form-control" name="config[<?php echo $config['name']; ?>]" value="<?php echo $config['value']; ?>">
                                                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> <?php echo $config['remark']; ?></span>
                                                <?php break; case "2": ?>
                                                    <textarea class="form-control" type="text" rows="4" name="config[<?php echo $config['name']; ?>]"><?php echo $config['value']; ?></textarea>
                                                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> <?php echo $config['remark']; ?></span>
                                                <?php break; case "3": ?>
                                                    <textarea class="form-control" type="text" rows="4" name="config[<?php echo $config['name']; ?>]"><?php echo $config['value']; ?></textarea>
                                                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> <?php echo $config['remark']; ?></span>
                                                <?php break; case "4": ?>
                                                    <select class="form-control m-b chosen-select" name="config[<?php echo $config['name']; ?>]">
                                                        <?php $_result=parse_config_attr($config['extra']);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                                            <option value="<?php echo $key; ?>" <?php if($config['value'] == $key): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                                    </select>
                                                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> <?php echo $config['remark']; ?></span>
                                                <?php break; case "5": ?>
                                                <div class="input-group col-sm-4">
                                                    <input type="hidden" id="data_photo" name="portrait" value="">
                                                    <input type="hidden" id="recharge_qrcode" name="config[<?php echo $config['name']; ?>]" value="<?php echo $config['value']; ?>">
                                                    <div id="fileList" class="uploader-list" style="float:right"></div>
                                                    <div id="imgPicker" style="float:left">选择图片</div>
                                                    <img id="img_data"  height="200px" width="200px" style="float:right;margin-top: 10px;" src="<?php echo $config['value']; ?>"/>
                                                </div>
                                                <?php break; endswitch; ?>                                           
                                            </div>
                                            <div class="hr-line-dashed"></div>
                                        </div>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-3">
                                            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> 保存信息</button>&nbsp;&nbsp;&nbsp;
                                            <a class="btn btn-danger" href="javascript:history.go(-1);"><i class="fa fa-close"></i> 返回</a>
                                        </div>
                                    </div>                               
                                </form>
                            </div>
                        </div>
                    </div>              
                </div>
				</div>

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
<script type="text/javascript" src="/static/admin/webupload/webuploader.min.js"></script>

<script type="text/javascript">

    var config = {
        '.chosen-select': {},                    
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
        var $list = $('#fileList');
    //上传图片,初始化WebUploader
    var uploader = WebUploader.create({
     
        auto: true,// 选完文件后，是否自动上传。   
        swf: '/static/admin/webupload/Uploader.swf',// swf文件路径 
        server: "<?php echo url('Upload/upload_recharge'); ?>",// 文件接收服务端。
        duplicate :true,// 重复上传图片，true为可重复false为不可重复
        pick: '#imgPicker',// 选择文件的按钮。可选。

        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/jpg,image/jpeg,image/png'
        },

        'onUploadSuccess': function(file, data, response) {
            $("#data_photo").val(data._raw);
            $("#img_data").attr('src', '/uploads/recharge/' + data._raw).show();
            var image = '/uploads/recharge/' + data._raw;
            $("#recharge_qrcode").val(image);
            console.log(image)
        }
    });

    uploader.on( 'fileQueued', function( file ) {
        $list.html( '<div id="' + file.id + '" class="item">' +
            '<h4 class="info">' + file.name + '</h4>' +
            '<p class="state">正在上传...</p>' +
        '</div>' );
    });

    // 文件上传成功
    uploader.on( 'uploadSuccess', function( file ) {
        $( '#'+file.id ).find('p.state').text('上传成功！');
    });

    // 文件上传失败，显示上传出错。
    uploader.on( 'uploadError', function( file ) {
        $( '#'+file.id ).find('p.state').text('上传出错!');
    }); 
</script>
</body>
</html>
