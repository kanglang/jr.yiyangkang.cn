<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:74:"/www/wwwroot/pig.77cco.com/public/../application/shop/view/user/level.html";i:1554712286;s:67:"/www/wwwroot/pig.77cco.com/application/shop/view/public/header.html";i:1554712283;s:45:"../application/common/view/public/header.html";i:1554712305;}*/ ?>
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
                    <form method="post" id="handleposition" action="<?php echo U('shop/User/levelHandle'); ?>">                    
                        <!--通用信息-->
                    <div class="tab-content col-md-10">                 	  
                        <div class="tab-pane active" id="tab_tongyong">                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="col-sm-2">等级名称：</td>
                                    <td class="col-sm-4">
                                        <input type="text" class="form-control" name="level_name" value="<?php echo (isset($info['level_name']) && ($info['level_name'] !== '')?$info['level_name']:''); ?>" >
                                        <span id="err_attr_name" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                    <td class="col-sm-4">设置会员等级名称
                                    </td>
                                </tr> 
                               <!--  <tr>
                                    <td>设置升级条件：</td>
                                    <td >
                                        <input type="text" class="form-control" name="straight_push" value="<?php echo (isset($info['straight_push']) && ($info['straight_push'] !== '')?$info['straight_push']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">设置满足升级条件的直推人数(个)</td>
                                </tr> 
                                <tr>
                                    <td>设置升级条件：</td>
                                    <td >
                                        <input type="text" class="form-control" name="team_income" value="<?php echo (isset($info['team_income']) && ($info['team_income'] !== '')?$info['team_income']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">设置满足升级条件的累计充值福分(个)</td>
                                </tr>
                                <tr>
                                    <td>一级推广奖</td>
                                    <td >
                                        <input type="text" class="form-control" name="extension_one" value="<?php echo (isset($info['extension_one']) && ($info['extension_one'] !== '')?$info['extension_one']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">请填写一级推广奖的比例(%)</td>
                                </tr> 
                                <tr>
                                    <td>二级推广奖</td>
                                    <td >
                                        <input type="text" class="form-control" name="extension_tow" value="<?php echo (isset($info['extension_tow']) && ($info['extension_tow'] !== '')?$info['extension_tow']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">请填写二级推广奖的比例(%)</td>
                                </tr> 
                                <tr>
                                    <td>三级推广奖</td>
                                    <td >
                                        <input type="text" class="form-control" name="extension_three" value="<?php echo (isset($info['extension_three']) && ($info['extension_three'] !== '')?$info['extension_three']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">请填写三级推广奖的比例(%)</td>
                                </tr> -->
                                <tr>
                                    <td>团队奖</td>
                                    <td >
                                        <input type="text" class="form-control" name="team_award" value="<?php echo (isset($info['team_award']) && ($info['team_award'] !== '')?$info['team_award']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">请填写团队奖的比例(%)</td>
                                </tr>  
                             <!--    <tr>
                                    <td>消费额度：</td>
                                    <td >
                         				<input type="text" class="form-control" name="amount" value="<?php echo (isset($info['amount']) && ($info['amount'] !== '')?$info['amount']:''); ?>">
                                    </td>
                                    <td class="col-sm-4">设置会员等级所需要的消费额度</td>
                                </tr>
                                <tr>
                                    <td>消费最低数量：</td>
                                    <td >
                                        <input type="text" class="form-control" name="lowest_num" value="<?php echo (isset($info['lowest_num']) && ($info['lowest_num'] !== '')?$info['lowest_num']:'1'); ?>">
                                    </td>
                                    <td class="col-sm-4">设置会员等级所需要最低消费数量</td>
                                </tr>
                                <tr>
                                    <td>折扣率：</td>
                                    <td>
                               			<input type="text" class="form-control" name="discount" value="<?php echo (isset($info['discount']) && ($info['discount'] !== '')?$info['discount']:''); ?>">     
                                    </td>
                                    <td class="col-sm-4">折扣率单位为百分比，如输入90，表示该会员等级的用户可以以商品原价的90%购买</td>
                                </tr>  
                                <tr>
                                    <td>等级描述：</td>
                                    <td>
                             			<textarea rows="5" cols="30" name="describe"><?php echo (isset($info['describe']) && ($info['describe'] !== '')?$info['describe']:''); ?></textarea>
                                    </td>
                                    <td class="col-sm-4">会员等级描述信息</td>
                                </tr>  -->                             
                                </tbody> 
                                <tfoot>
                                	<tr>
                                	<input type="hidden" name="act" value="<?php echo (isset($act) && ($act !== '')?$act:''); ?>">
                                		<input type="hidden" name="level_id" value="<?php echo (isset($info['level_id']) && ($info['level_id'] !== '')?$info['level_id']:''); ?>">
                                	
                                	
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
</body>
</html>