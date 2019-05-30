<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:77:"/home/xgh/zouzebin/public/../application/admin/view/article/edit_article.html";i:1554117475;s:60:"/home/xgh/zouzebin/application/admin/view/public/header.html";i:1554117475;s:45:"../application/common/view/public/header.html";i:1554117475;s:60:"/home/xgh/zouzebin/application/admin/view/public/footer.html";i:1554117475;s:45:"../application/common/view/public/footer.html";i:1554117475;}*/ ?>
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
<style>
.file-item{float: left; position: relative; width: 110px;height: 110px; margin: 0 20px 20px 0; padding: 4px;}
.file-item .info{overflow: hidden;}
.uploader-list{width: 100%; overflow: hidden;}
</style>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>编辑活动</h5>
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
                    <form class="form-horizontal m-t" name="edit" id="edit" method="post" action="">
                    <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">标题：</label>
                            <div class="input-group col-sm-4">
                                <input id="title" type="text" class="form-control" name="title" value="<?php echo $article['title']; ?>">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">所属分类：</label>
                            <div class="input-group col-sm-4">
                                <select class="form-control m-b chosen-select" name="cate_id" id="cate_id">
                                    <option value="">==请选择==</option>
                                    <?php if(!empty($cate)): if(is_array($cate) || $cate instanceof \think\Collection || $cate instanceof \think\Paginator): if( count($cate)==0 ) : echo "" ;else: foreach($cate as $key=>$vo): ?>
                                            <option value="<?php echo $vo['id']; ?>" <?php if($article['cate_id'] == $vo['id']): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">描述：</label>
                            <div class="input-group col-sm-4">
                                <textarea type="text" rows="5" name="remark" id="remark" placeholder="输入文章描述" class="form-control"><?php echo $article['remark']; ?></textarea>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">封面：</label>
                            <div class="controls uploadrow2" data-max="1" title="点击修改图片" rel="p_cover">
                                    <input type="hidden" id="data_photo" name="photo" value="<?php echo $article['photo']; ?>">
                                    <input type="hidden" name="cover" id="cover_id_p_cover" data-callback="uploadImgCallback" value=""/>
                                    <div class="upload-img-box" rel="img"  >
                                      <div class="upload-pre-item2">
                                            <img width="100" height="100" src="<?php echo $article['photo']; ?>"/>
                                                                                          </div>
                                        <em class="edit_img_icon">&nbsp;</em>
                                    </div>
                              </div>
                        </div>
						
                        <div class="hr-line-dashed"></div>                          
                        <div class="form-group">
                            <label class="col-sm-3 control-label " for="myEditor">内容：</label>
                            <div class="input-group col-sm-9">
                                <textarea name="content" style="width:90%" id="myEditor"><?php echo $article['content']; ?></textarea>
                                <?php echo hook('adminArticleEdit', array('name'=>'content','value'=>'')); ?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">是否推荐：</label>
                            <div class="input-group col-sm-4">
                                <div class="radio i-checks">
                                    <?php if($article['is_tui'] == 1): ?>
                                        <input type="radio" name='is_tui' value="1" checked="checked" />是&nbsp;&nbsp;
                                        <input type="radio" name='is_tui' value="0" />否
                                    <?php else: ?>
                                        <input type="radio" name='is_tui' value="1" />是&nbsp;&nbsp;
                                        <input type="radio" name='is_tui' value="0" checked="checked" />否
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">状&nbsp;&nbsp;态：</label>
                            <div class="col-sm-6">
                                <div class="radio ">                                        
                                    <input type="checkbox" name='status' value="<?php echo (isset($article['status']) && ($article['status'] !== '')?$article['status']:1); ?>" 
									class="js-switch" <?php if(@$article['status'] === 0): ?>un<?php endif; ?>checked />&nbsp;&nbsp;默认开启                                     
                                </div>
                            </div>
                        </div>
						<input type="hidden" name='status' value="<?php echo (isset($article['status']) && ($article['status'] !== '')?$article['status']:1); ?>" >
        
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

$(function(){
    
    //初始化上传图片插件
    initUploadImg();
});

function uploadImgCallback(name,id,src){
    $('.editing img').attr('src',src);
    $('#data_photo').val(src);
    $('.editing input[name="cover_id"]').val(id);
}

function uploadImgCallback2(name,id,src){
    $('.editing img').attr('src',src);
    $('#data_file').val(src);
    $('.editing input[name="file_id"]').val(id);
}
</script>
<script type="text/javascript">

    $(function(){
        $('#edit').ajaxForm({
            beforeSubmit: checkForm, // 此方法主要是提交前执行的方法，根据需要设置
            success: complete, // 这是提交后的方法
            dataType: 'json'
        });
		
		$('input[type="checkbox"][name=status]').change(function() {
			$('input[name=status]').val($(this).get(0).checked ? 1: 0);
		});

        function checkForm(){
            if( '' == $.trim($('#title').val())){
                layer.msg('标题不能为空', {icon: 5,time:1500,shade: 0.1}, function(index){
                    layer.close(index);
                });
                return false;
            } else if( '' === $.trim($('#cate_id').val())){
                layer.msg('请选择分类', {icon: 5,time:1500,shade: 0.1}, function(index){
                    layer.close(index);
                });
                return false;
            } /*else if( '' == $.trim($('#data_photo').val())){
                layer.msg('请上传封面图片', {icon: 5,time:1500,shade: 0.1}, function(index){
                    layer.close(index);
                });
                return false;
            }*/

     }

        function complete(data){
            if(data.code == 1){
                layer.msg(data.msg, {icon: 6,time:1500,shade: 0.1}, function(index){
                    layer.close(index);
                    window.location.href="<?php echo url('article/index'); ?>";
                });
            }else{
                layer.msg(data.msg, {icon: 5,time:1500,shade: 0.1}, function(index){
                    layer.close(index);
                });
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
