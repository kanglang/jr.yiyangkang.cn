<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:93:"/www/wwwroot/jr.yiyangkang.cn/public/../application/shop/view/sms_template/_sms_template.html";i:1554712285;s:70:"/www/wwwroot/jr.yiyangkang.cn/application/shop/view/public/header.html";i:1554712283;s:45:"../application/common/view/public/header.html";i:1554712305;}*/ ?>
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

<style>
	.box1 {width:100px; float:left; display:inline;border:red 1px solid;}
	.box2 {width:100px; float:left; display:inline;border:green 1px solid;}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
    <section class="content">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>            
            </div>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 短信模板详情</h3>
                </div>
                <div class="ibox-content">
                    
                    <!--表单数据-->
                    <form method="post" id="addEditSmsTemplate" onsubmit="return checkForm();">             
                        <!--通用信息-->
                    <div class="tab-content">                 	  
                        <div class="tab-pane active" id="tab_tongyong">
                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>短信签名:</td>
                                    <td>
                                        <div style="vertical-align:middle;display:inline">
	                                        <input type="text" value="<?php echo (isset($smsTpl['sms_sign']) && ($smsTpl['sms_sign'] !== '')?$smsTpl['sms_sign']:''); ?>" name="sms_sign" class="form-control" style="width:200px;"/>
	                                        	*</span>必须与阿里大鱼短信签名一致                                        
	                                        	<span id="err_sms_sign" style="color:#F00; display:none;">短信签名不能为空</span>
	                                      </div>
                                    </td>
                                </tr>                                
                                <tr>
                                    <td>短信模板ID:</td>
                                    <td>
                                        <input type="text" value="<?php echo (isset($smsTpl['sms_tpl_code']) && ($smsTpl['sms_tpl_code'] !== '')?$smsTpl['sms_tpl_code']:''); ?>" name="sms_tpl_code" class="form-control" style="width:250px;"/>
                                        <span >*必须与阿里大鱼短信模板ID一致</span> 
                                        <span id="err_sms_tpl_code" style="color:#F00; display:none;">短信模板ID不能为空</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>短信内容:</td>
                                    <td>
										<textarea rows="4" cols="60" name="tpl_content" id="tpl_content"><?php echo (isset($smsTpl['tpl_content']) && ($smsTpl['tpl_content'] !== '')?$smsTpl['tpl_content']:''); ?></textarea>
                                        <span id="err_tpl_content" style="color:#F00; display:none;">短信内容不能为空</span>                                        
                                    </td>
                                </tr>                                                            
                                <tr>
                                    <td>发送场景:</td>
                                    <td>
                                     
                                        <div class="col-sm-3">  
                                           <?php if(isset($send_scene_id) && $send_scene_id > 0): ?>
                                           			<?php echo $send_name; ?>
                                           			<input type="hidden" value="<?php echo $send_scene_id; ?>" name="send_scene" id="send_scene" class="form-control" style="width:250px;"/>
                                           <?php else: ?>
                                           		<select name="send_scene" id="send_scene" class="form-control" style="width:250px;margin-left:-15px;" onchange="changeContent(this.value);">
		                                            <option value="-1">请选择使用场景</option>
		                                            <?php if(is_array($send_scene) || $send_scene instanceof \think\Collection || $send_scene instanceof \think\Paginator): if( count($send_scene)==0 ) : echo "" ;else: foreach($send_scene as $k=>$v): ?>
		                                            	<option value="<?php echo $k; ?>" <?php if(isset($smsTpl['send_scene']) && $k == $smsTpl['send_scene']): ?>selected="selected"<?php endif; ?>><?php echo $v[0]; ?></option>
			                                        <?php endforeach; endif; else: echo "" ;endif; ?>
												</select>
												<span id="err_send_scene" style="color:#F00; display:none;">请选择使用场景</span>
                                           <?php endif; ?>
	                                        
	                                    </div>                                    
                                    </td>
                                </tr>                                
                                </tbody>
                                </table>
                        </div>
                    </div>
                    <div class="pull-right">
                        <input type="hidden" name="tpl_id" value="<?php echo (isset($smsTpl['tpl_id']) && ($smsTpl['tpl_id'] !== '')?$smsTpl['tpl_id']:''); ?>">
                        <button class="btn btn-primary" data-toggle="tooltip" type="submit" data-original-title="保存">保存</button>
                    </div>
			    </form><!--表单数据-->
                </div>
            </div>
        </div>    <!-- /.content -->
    </section>
</div>
<script>

function changeContent(scene){
	if(scene == -1){
		$("#addEditSmsTemplate").find("textarea[name='tpl_content']").val('');
		return;
	}
	var scenes = <?php echo json_encode($send_scene); ?>;
	var txt = scenes[scene][1];
	
	$("#addEditSmsTemplate").find("textarea[name='tpl_content']").val(txt);
}

// 判断输入框是否为空
function checkForm(){
	
	var smsSign = $("#addEditSmsTemplate").find("input[name='sms_sign']").val();					//短信签名
	var smsTplCode = $("#addEditSmsTemplate").find("input[name='sms_tpl_code']").val();		//模板ID
	var tplContent = $("#addEditSmsTemplate").find("textarea[name='tpl_content']").val();			//模板内容

	var sendscene = $("#send_scene option:selected").val();
    if($.trim(smsSign) == '')
	{
		$("#err_sms_sign").show();
		return false;
	}
	
    if($.trim(smsTplCode) == '')
	{
		$("#err_sms_tpl_code").show();
		return false;
	}
    
    if($.trim(tplContent) == '')
	{
		$("#err_tpl_content").show();
		return false;
	}
    
    if(sendscene == -1){
    	$("#err_send_scene").show();
    	return false;
    }
     
	return true;
}

</script>
</body>
</html>