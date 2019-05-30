<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:81:"/www/wwwroot/pig.77cco.com/public/../application/home/view/file/uploaddialog.html";i:1554712253;s:67:"/www/wwwroot/pig.77cco.com/application/home/view/public/header.html";i:1554712253;s:45:"../application/common/view/public/header.html";i:1554712305;}*/ ?>
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

<body style="background:#fff; padding:40px 0;">
  <div id="uploadDialogContent">
  	<div class="upload_img_tab">
    	<a class="current" href="javascript:;" onClick="switchTab(this,1);">本地上传</a>
        <a href="javascript:;" onClick="switchTab(this,3);">最近上传</a>
    </div>
    <div class="tab_content" id="tab1" style="padding:20px; display:block">
      <form id="form"  method="post" class="form-horizontal form-center">
        <div class="mult_imgs">
                <div class="upload-img-view" id='mutl_picture'>
                   
                </div>
                <div class="controls uploadrow2" data-max="9" title="点击上传图片">
                  <input type="file" id="upload_picture">
                </div>
            </div>    
      </form>
      <br/>
     <span class="search-form" style="color: #da4224;"><b class="fa fa-info-circle"></b> 建议图片小于2M</span>
	</div>
    <div class="tab_content load_piclist_box" id="tab2" style="padding:20px;"></div>
	<div class="tab_content load_piclist_box" id="tab3" style="padding:20px;"></div>
  </div>
  <div class="upload_dialog_bar"><a id="conBtn" class="btn btn-primary" href="javascript:;" onClick="confirmImage();">确定</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="javascript:;" onClick="window.parent.$.Dialog.close();">取消</a></div>
</body>
<script type="text/javascript">
	
var maxCount =  "<?php echo \think\Request::instance()->param('max')?\think\Request::instance()->param('max'):'1'; ?>";
var fieldName =  "<?php echo \think\Request::instance()->param('field')?\think\Request::instance()->param('field'):''; ?>";
var btnWidth =  "<?php echo \think\Request::instance()->param('btnWidth')?\think\Request::instance()->param('btnWidth'):'100'; ?>";
var btnHeiht =  "<?php echo \think\Request::instance()->param('btnHeiht')?\think\Request::instance()->param('btnHeiht'):'100'; ?>";

$('#upload_picture').uploadify({
	"height"          : 100,
	"width"           : 100,
	"swf"             : JS_PATH+"/plugins/uploadify/uploadify.swf",
	"fileObjName"     : "download",
	"buttonText"      : "上传图片",
	"uploader"        : UPLOAD_PICTURE,
	'cancelImg'		  : 'JS/jquery.uploadify-v2.1.0/cancel.png',
	'removeTimeout'	  : 1,
	'fileTypeExts'	  : '*.jpg; *.png; *.gif;',
	"onUploadSuccess" : function(file, data, response) {
		var data = $.parseJSON(data);
		if(data.code){
			src = data.url || ROOT + data.path;
			$addImg = $('<div class="upload-pre-item22 check" onclick="toggleCheck(this);"><img width="100" height="100" src="' + src + '"/>'
				+'<input type="hidden" name="picId[]" value="'+data.id+'"/><!--<em onClick="if(confirm(\'确认删除？\')){$(this).parent().remove();}">&nbsp;</em>--><span class="ck-ico"></span></div>');
			$("#mutl_picture").append($addImg);
			if(maxCount==1){
				$("#mutl_picture .upload-pre-item22").each(function(index, element) {
					$(element).removeClass('check');
				});
				$addImg.addClass('check');
			}
		} else {
			window.parent.updateAlert(data.info);
			setTimeout(function(){
				window.parent.$('#top-alert').find('button').click();
			},1500);
		}
	},
	"onUploadError" : function(file, data, response) {
		window.parent.updateAlert(data.info);
	}
});
function switchTab(obj,index){
	$('#tab'+index).show().siblings('.tab_content').hide();
	$(obj).addClass('current').siblings().removeClass('current');
	if(index!=1 && !$(obj).hasClass('loaded')){
		if(index==2){
			//加载系统
			$(obj).addClass('loaded');
			$('#tab2').load("<?php echo url('/home/File/userPics',array('type'=>2)); ?>");
		}else if(index==3){
			//加载最近
			$(obj).addClass('loaded');
			$('#tab3').load("<?php echo url('/home/File/userPics'); ?>");
		}
	}
}
//切换图标分类
function switchPicCate(obj,tabIndex){
	$('#tab'+tabIndex).empty();
	$('#tab'+tabIndex).load($(obj).data('href'));
}
//选中图片
function toggleCheck(obj){
	var curItems = $('.tab_content:visible .check');
	var checkCount = curItems.size();
	if(maxCount>1){
		if(checkCount==maxCount && !$(obj).hasClass('check')){
			window.parent.updateAlert('图片不能超过'+maxCount+'张!');
			return;
		}
		$(obj).toggleClass('check');
	}else{
		if(!$(obj).hasClass('check')){
			$(obj).addClass('check').siblings().removeClass('check');
		}
	}
}
function confirmImage(){
	var curItems = $('.tab_content:visible .check');
	var checkCount = curItems.size();
	if(checkCount==0){
		window.parent.updateAlert('请选择图片!');
		return;
	}
	curItems.each(function(index, element) {
		var picId = $(element).find('input[type="hidden"]').val();
		var src = $(element).find('img').attr('src');
        if(maxCount>1){
			$addImg = $('<div class="upload-pre-item22"><img width="'+btnHeiht+'" height="'+btnHeiht+'" src="' + src + '"/>'
				+'<input type="hidden" name="'+fieldName+'[]" value="'+picId+'"/><em onClick="if(confirm(\'确认删除？\')){$(this).parent().remove();}">&nbsp;</em></div>');
			window.parent.$("#mutl_picture_"+fieldName).append($addImg);
			
			
			window.parent.$("#mutl_picture_"+fieldName).dragsort('destroy');
			window.parent.$("#mutl_picture_"+fieldName).dragsort({
			    itemSelector: ".upload-pre-item22", dragSelector: ".upload-pre-item22", dragBetween: false, placeHolderTemplate: "<div class='upload-pre-item22'></div>",dragSelectorExclude:'em',dragEnd: function() {$(".upload-pre-item22").attr('style','')}
		    });
		}else{	
			window.parent.$('#cover_id_'+fieldName).parent().find('.upload-img-box').html(
				'<div class="upload-pre-item2" style="width:100%;height:'+btnHeiht+'px;max-height:'+btnHeiht+'px"><img width="100%" height="100" src="' + src + '"/></div><em class="edit_img_icon">&nbsp;</em>'
			).show();
			window.parent.$('#cover_id_'+fieldName).val(picId);
			window.parent.$('.weixin-cover-pic').attr('src',src);
			var callback = window.parent.$('#cover_id_'+fieldName).data('callback');
			
			if(callback){
				eval('window.parent.'+callback+'("'+fieldName+'",'+picId+',"'+src+'")');
			}
		}
	});
	window.parent.$.Dialog.close();
}
</script>
</html>
