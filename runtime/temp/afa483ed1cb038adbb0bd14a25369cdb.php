<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:66:"/home/xgh/zouzebin/public/../application/shop/view/pig/pigadd.html";i:1554117475;s:59:"/home/xgh/zouzebin/application/shop/view/public/header.html";i:1554117475;s:45:"../application/common/view/public/header.html";i:1554117475;}*/ ?>
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

<!--<link href="__SHOP_URL__/static/css/page.css" rel="stylesheet" type="text/css">-->

<link href="__SHOP_URL__/static/font/css/font-awesome.min.css" rel="stylesheet" />


<script type="text/javascript" src="__SHOP_URL__/static/js/admin.js"></script>
<script type="text/javascript" src="__SHOP_URL__/static/js/jquery.validation.min.js"></script>

<script type="text/javascript" src="__SHOP_URL__/static/js/common.js"></script>
<script type="text/javascript" src="__SHOP_URL__/static/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="__SHOP_URL__/static/js/jquery.mousewheel.js"></script>


<link href="__SHOP_URL__/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
<script src="__SHOP_URL__/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="__SHOP_URL__/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>

<script src="__SHOP_URL__/js/myFormValidate.js"></script>
<!--<script src="__SHOP_URL__/js/myAjax2.js"></script>-->
<script src="__SHOP_URL__/js/myAjax.js"></script>
<script src="__SHOP_URL__/js/global.js"></script>
<script type="text/javascript" src="__SHOP_URL__/js/layer/layer.js"></script>
<script type="text/javascript">
    function delfunc(obj){
    	layer.confirm('确认删除？', {
    		  btn: ['确定','取消'] //按钮
    		}, function(){
    		    // 确定
   				$.ajax({
   					type : 'post',
   					url : $(obj).attr('data-url'),
   					data : {act:'del',del_id:$(obj).attr('data-id')},
   					dataType : 'json',
   					success : function(data){
						layer.closeAll();
   						if(data==1){
   							layer.msg('操作成功', {icon: 1});
   							window.location.reload();
   							//$(obj).parent().parent().parent().remove();
   						}else{
   							layer.msg(data, {icon: 2,time: 2000});
   						}
   					}
   				})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }

    function selectAll(name,obj){
    	$('input[name*='+name+']').prop('checked', $(obj).checked);
    }

    function delAll(obj,name){
    	var a = [];
    	$('input[name*='+name+']').each(function(i,o){
    		if($(o).is(':checked')){
    			a.push($(o).val());
    		}
    	})
    	if(a.length == 0){
    		layer.alert('请选择删除项', {icon: 2});
    		return;
    	}
    	layer.confirm('确认删除？', {btn: ['确定','取消'] }, function(){
    			$.ajax({
    				type : 'get',
    				url : $(obj).attr('data-url'),
    				data : {act:'del',del_id:a},
    				dataType : 'json',
    				success : function(data){
						layer.closeAll();
    					if(data == 1){
    						layer.msg('操作成功', {icon: 1});
    						$('input[name*='+name+']').each(function(i,o){
    							if($(o).is(':checked')){
    								$(o).parent().parent().remove();
    							}
    						})
    					}else{
    						layer.msg(data, {icon: 2,time: 2000});
    					}
    				}
    			})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }

    /**
     * 全选
     * @param obj
     */
    function checkAllSign(obj){
        $(obj).toggleClass('trSelected');
        if($(obj).hasClass('trSelected')){
            $('#flexigrid > table>tbody >tr').addClass('trSelected');
        }else{
            $('#flexigrid > table>tbody >tr').removeClass('trSelected');
        }
    }
    /**
     * 批量公共操作（删，改）
     * @returns {boolean}
     */
    function publicHandleAll(type){
        var ids = '';
        $('#flexigrid .trSelected').each(function(i,o){
//            ids.push($(o).data('id'));
            ids += $(o).data('id')+',';
        });
        if(ids == ''){
            layer.msg('至少选择一项', {icon: 2, time: 2000});
            return false;
        }
        publicHandle(ids,type); //调用删除函数
    }
    /**
     * 公共操作（删，改）
     * @param type
     * @returns {boolean}
     */
    function publicHandle(ids,handle_type){
        layer.confirm('确认当前操作？', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    // 确定
                    $.ajax({
                        url: $('#flexigrid').data('url'),
                        type:'post',
                        data:{ids:ids,type:handle_type},
                        dataType:'JSON',
                        success: function (data) {
                            layer.closeAll();
                            if (data.status == 1){
                                layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                    location.href = data.url;
                                });
                            }else{
                                layer.msg(data.msg, {icon: 2, time: 2000});
                            }
                        }
                    });
                }, function (index) {
                    layer.close(index);
                }
        );
    }
</script>  



<!--login_task-->
<script type="text/javascript">
    function closes(){
        is_close = 1;
        document.getElementById('ordfoo').style.display = 'none';
    }

    // 没有点击收货确定的按钮让他自己收货确定
    var timestamp = Date.parse(new Date());
    $.ajax({
        type:'post',
        url:"<?php echo url('Systems/login_task'); ?>",
        data:{timestamp:timestamp},
        timeout : 100000000, //超时时间设置，单位毫秒
        success:function(){
            // 执行定时任务
        }
    });
</script>
<!-- 新订单提醒-e -->


<link rel="stylesheet" type="text/css" media="all" href="/sldate/daterangepicker-bs3.css" />
<!-- <script type="text/javascript" src="/sldate/moment.js"></script>
<script type="text/javascript" src="/sldate/daterangepicker.js"></script> -->

<link rel="stylesheet" type="text/css" href="/static/admin/webupload/webuploader.css">
<link rel="stylesheet" type="text/css" href="/static/admin/webupload/style.css">
<!-- <script type="text/javascript">
       $(document).ready(function() {
          $('#reservation').daterangepicker(null, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
          });
       });

</script> -->
<style>
.file-item{float: left; position: relative; width: 110px;height: 110px; margin: 0 20px 20px 0; padding: 4px;}
.file-item .info{overflow: hidden;}
.uploader-list{width: 100%; overflow: hidden;}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
    <section class="content ">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
  
            </div>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 添加会员等级</h3>
                </div>
                <div class="panel-body ">   
                    <!--表单数据-->
                    <form method="post" id="handleposition" action="<?php echo U('shop/Pig/pigHandle'); ?>">                    
                        <!--通用信息-->
                    <div class="tab-content col-md-10">                 	  
                        <div class="tab-pane active" id="tab_tongyong">                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="col-sm-2">名称</td>
                                    <td class="col-sm-4">
                                        <input type="text" class="form-control" name="goods_name" value="<?php echo (isset($info['goods_name']) && ($info['goods_name'] !== '')?$info['goods_name']:''); ?>" >
                                        <span id="err_attr_name" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                    <td class="col-sm-4">填写名称
                                    </td>
                                </tr> 
                                <tr>
                                    <td>最小金额：</td>
                                    <td >
                                        <input type="text" class="form-control" name="small_price" value="<?php echo (isset($info['small_price']) && ($info['small_price'] !== '')?$info['small_price']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">请填写最小金额(元)</td>
                                </tr> 
                                <tr>
                                    <td>最大金额：</td>
                                    <td >
                                        <input type="text" class="form-control" name="large_price" value="<?php echo (isset($info['large_price']) && ($info['large_price'] !== '')?$info['large_price']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">请填写最大金额(元)</td>
                                </tr>
                                <tr>
                                    <td>领养开始时间</td>
                                    <td >
                                        <input type="text" id="test3" class="form-control" name="start_time" value="<?php echo (isset($info['start_time']) && ($info['start_time'] !== '')?$info['start_time']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">请选择领养开始时间</td>
                                </tr> 
                                <tr>
                                    <td>领养结束时间</td>
                                    <td >
                                        <input type="text" id="test4" class="form-control" name="end_time" value="<?php echo (isset($info['end_time']) && ($info['end_time'] !== '')?$info['end_time']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">请选择领养结束时间</td>
                                </tr>
                                 <tr>
                                    <td>预约福分数量</td>
                                    <td >
                                        <input type="text" class="form-control" name="reservation" value="<?php echo (isset($info['reservation']) && ($info['reservation'] !== '')?$info['reservation']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">请填写预约福分数量</td>
                                </tr>
                                 <tr>
                                    <td>领养福分数量</td>
                                    <td >
                                        <input type="text" class="form-control" name="adoptive_energy" value="<?php echo (isset($info['adoptive_energy']) && ($info['adoptive_energy'] !== '')?$info['adoptive_energy']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">请填写领养福分数量</td>
                                </tr> 
                                <tr>
                                    <td>合约收益天数</td>
                                    <td >
                                        <input type="text" class="form-control" name="contract_days" value="<?php echo (isset($info['contract_days']) && ($info['contract_days'] !== '')?$info['contract_days']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">请填写合约收益天数</td>
                                </tr>
                                <tr>
                                    <td>合约收益比例</td>
                                    <td >
                                        <input type="text" class="form-control" name="income_ratio" value="<?php echo (isset($info['income_ratio']) && ($info['income_ratio'] !== '')?$info['income_ratio']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">请填写合约收益比例(%)</td>
                                </tr>  
                                <tr>
                                    <td>可挖FISH</td>
                                    <td >
                                        <input type="text" class="form-control" name="pig_currency" value="<?php echo (isset($info['pig_currency']) && ($info['pig_currency'] !== '')?$info['pig_currency']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">请填写可挖FISH数量(枚)</td>
                                </tr>
                                 <tr>
                                    <td>可挖SHRIMP</td>
                                    <td >
                                        <input type="text" class="form-control" name="doge_money" value="<?php echo (isset($info['doge_money']) && ($info['doge_money'] !== '')?$info['doge_money']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">请填写可挖SHRIMP(%)</td>
                                </tr>
                         
                                <tr>
                                    <td>图片：</td>
                                    <td >
                                        <input type="hidden"  id="data_photo" name="images"  value="<?php echo (isset($info['images']) && ($info['images'] !== '')?$info['images']:''); ?>" />
                                        <div id="fileList" class="uploader-list" style="float:right"></div>
                                        <div id="imgPicker" style="float:left;">选择图片</div>
                                        <img id="img_data" height="100px" style="float:left;margin-left: 50px;margin-top: -10px;" onerror="this.src='/static/admin/images/no_img.jpg'" src="/uploads/images/<?php echo (isset($info['images']) && ($info['images'] !== '')?$info['images']:''); ?>"/>
                                        </td>                                 
                                </tr> 
                                </tbody> 
                                <tfoot>
                                	<tr>
                                	<input type="hidden" name="act" value="<?php echo (isset($act) && ($act !== '')?$act:''); ?>">
                                		<input type="hidden" name="id" value="<?php echo (isset($info['id']) && ($info['id'] !== '')?$info['id']:''); ?>">
                                	<td class="text-right" colspan="3" style="text-align: center;"><input class="btn btn-primary" type="buuton" onclick="adsubmit()" value="保存"></td></tr>
                                </tfoot>                               
                                </table>
                        </div>                           
                    </div>              
			    	</form><!--表单数据-->
                </div>
            </div>
        </div>
    </section>
</div>
<script>
function adsubmit(){
	$('#handleposition').submit();
}
</script>

<script src="__STATIC__/admin/laydate/laydate.js"></script>
<script type="text/javascript">

//时间选择器
   laydate.render({
    elem: '#test3'
    ,type: 'time'
  });

  laydate.render({
    elem: '#test4'
    ,type: 'time'
  });



</script>
<script type="text/javascript" src="__STATIC__/admin/webupload/webuploader.min.js"></script>

<script type="text/javascript">
    var $list = $('#fileList');
    //上传图片,初始化WebUploader
    var uploader = WebUploader.create({
     
        auto: true,// 选完文件后，是否自动上传。   
        swf: '__STATIC__/admin/webupload/Uploader.swf',// swf文件路径 
        server: "<?php echo url('__STATIC__/admin/Upload/upload'); ?>",// 文件接收服务端。
        duplicate :true,// 重复上传图片，true为可重复false为不可重复
        pick: '#imgPicker',// 选择文件的按钮。可选。

        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/jpg,image/jpeg,image/png'
        },

        'onUploadSuccess': function(file, data, response) {
            $("#data_photo").val(data._raw);
            $("#img_data").attr('src', '/uploads/images/' + data._raw).show();
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