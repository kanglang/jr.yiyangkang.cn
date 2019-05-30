<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:83:"/www/wwwroot/pig.77cco.com/public/../application/admin/view/config/edit_config.html";i:1554712250;s:68:"/www/wwwroot/pig.77cco.com/application/admin/view/public/header.html";i:1554712249;s:45:"../application/common/view/public/header.html";i:1554712305;s:68:"/www/wwwroot/pig.77cco.com/application/admin/view/public/footer.html";i:1554712249;s:45:"../application/common/view/public/footer.html";i:1554712305;}*/ ?>
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
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>编辑配置</h5>
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
                    <form class="form-horizontal" name="edit_config" id="edit_config" method="post" action="<?php echo url('edit_config'); ?>">
                    <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">配置标识：</label>
                            <div class="input-group col-sm-4">
                                <input id="name" type="text" class="form-control" name="name" value="<?php echo $info['name']; ?>">
                                <span class="help-block m-b-none"> 用于config函数调用，只能使用英文且不能重复</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">配置标题：</label>
                            <div class="input-group col-sm-4">
                                <input id="title" type="text" class="form-control" name="title" value="<?php echo $info['title']; ?>">
                                <span class="help-block m-b-none"> 用于后台显示的配置标题</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">配置类型：</label>
                            <div class="input-group col-sm-4">
                                <select class="form-control m-b chosen-select" name="type" id="select_type">
                                    <option value="0">==请选择==</option>
                                        <?php if(is_array(\think\Config::get('config_type_list')) || \think\Config::get('config_type_list') instanceof \think\Collection || \think\Config::get('config_type_list') instanceof \think\Paginator): $i = 0; $__LIST__ = \think\Config::get('config_type_list');if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$type): $mod = ($i % 2 );++$i;?> 
                                            <option value="<?php echo $key; ?>"><?php echo $type; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                                <span class="help-block m-b-none"> （系统会根据不同类型解析配置值）</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">配置分组：</label>
                            <div class="input-group col-sm-4">
                                <select class="form-control m-b chosen-select" name="group" id="select_group">
                                    <option value="0">==请选择==</option>
                                        <?php if(is_array(\think\Config::get('config_group_list')) || \think\Config::get('config_group_list') instanceof \think\Collection || \think\Config::get('config_group_list') instanceof \think\Paginator): $i = 0; $__LIST__ = \think\Config::get('config_group_list');if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group): $mod = ($i % 2 );++$i;?>
                                            <option value="<?php echo $key; ?>"><?php echo $group; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                                <span class="help-block m-b-none"> （配置分组 用于批量设置 不分组则不会显示在系统设置中）</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">配置值：</label>
                            <div class="input-group col-sm-4">
                                <!--<input id="value" type="text" class="form-control" name="value" value="<?php echo $info['value']; ?>">-->
                                <textarea type="text" rows="5" name="value" id="value" class="form-control" ><?php echo $info['value']; ?></textarea>
                                <span class="help-block m-b-none"> 用于config函数调用显示的值</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">配置项：</label>
                            <div class="input-group col-sm-4">
                                <textarea type="text" rows="5" name="extra" id="extra" class="form-control" ><?php echo $info['extra']; ?></textarea>
                                <span class="help-block m-b-none"> 如果是枚举型 需要配置该项</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">配置描述：</label>
                            <div class="input-group col-sm-4">
                                <textarea type="text" rows="5" name="remark" id="remark" class="form-control" ><?php echo $info['remark']; ?></textarea>
                                <span class="help-block m-b-none"> 配置详细说明</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">排序：</label>
                            <div class="input-group col-sm-4">
                                <input id="sort" type="text" class="form-control" value="0" name="sort" value="<?php echo $info['sort']; ?>">
                                <span class="help-block m-b-none"> 用于显示的顺序</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">状&nbsp;态：</label>
                            <div class="col-sm-6">
                                <div class="radio ">                                        
                                    <input type="checkbox" name='status' value="1" class="js-switch" checked />&nbsp;&nbsp;默认开启                                     
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-3">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> 保存</button>&nbsp;&nbsp;&nbsp;
                                <a class="btn btn-danger" href="javascript:history.go(-1);"><i class="fa fa-close"></i> 返回</a>
                            </div>
                        </div>
                    </form>
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

<script type="text/javascript">

    $("#select_type").find("option[value='<?php echo $info['type']; ?>']").attr("selected", true);
    $("#select_group").find("option[value='<?php echo $info['group']; ?>']").attr("selected", true);


    //提交
    $(function(){
        $('#edit_config').ajaxForm({
            beforeSubmit: checkForm, 
            success: complete, 
            dataType: 'json'
        });
        
        function checkForm(){
            if( '' == $.trim($('#name').val())){
                layer.msg('请输入配置标识',{icon:2,time:1500,shade: 0.1}, function(index){
                layer.close(index);
                });
                return false;
            }
            
            if( '' == $.trim($('#value').val())){
                layer.msg('请输入配置值',{icon:2,time:1500,shade: 0.1}, function(index){
                layer.close(index);
                });
                return false;
            }

        }


        function complete(data){
            if(data.code==1){
                layer.msg(data.msg, {icon: 6,time:1500,shade: 0.1}, function(index){
                    window.location.href="<?php echo url('config/index'); ?>";
                });
            }else{
                layer.msg(data.msg, {icon: 5,time:1500,shade: 0.1});
                return false;   
            }
        }
     
    });



    //IOS开关样式配置
   var elem = document.querySelector('.js-switch');
        var switchery = new Switchery(elem, {
            color: '#1AB394'
        });
    var config = {
        '.chosen-select': {},                    
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

</script>
</body>
</html>