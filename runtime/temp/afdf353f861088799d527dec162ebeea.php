<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:84:"/www/wwwroot/jr.yiyangkang.cn/public/../application/shop/view/user/account_edit.html";i:1554712287;s:70:"/www/wwwroot/jr.yiyangkang.cn/application/shop/view/public/header.html";i:1554712283;s:45:"../application/common/view/public/header.html";i:1554712305;}*/ ?>
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
    <section class="content">
    <!-- Main content -->
    <div class="container-fluid">
    <div class="container-fluid">
        <!--新订单列表 操作信息-->
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3 class="panel-title"><i class="fa fa-list"></i> 管理资金</h3>
            </div>
            <div class="ibox-content">

            <form class ="myform" method="post" action="">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <div class="row">
                            <td class="text-right col-sm-2">推广财分：</td>
                            <td colspan="3">
                                <select name="money_act_type_1" id="money_act_type_1" class="input-sm">
                                    <option value="1">增加</option>
                                    <option value="0">减少</option>
                                </select>
                                <input type="text" name="user_money" class="input-sm" value="<?php echo (isset($_REQUEST['user_money']) && ($_REQUEST['user_money'] !== '')?$_REQUEST['user_money']:'0'); ?>">可用推广财分：￥<?php echo $user['user_money']; ?>
                            </td>
                        </div>
                    </tr>
                    <tr>
                        <div class="row">
                            <td class="text-right col-sm-2">福分：</td>
                            <td colspan="3">
                                <select name="point_act_type_2" id="point_act_type_2" class="input-sm">
                                    <option value="1">增加</option>
                                    <option value="0">减少</option>
                                </select>
                                <input type="text" name="pay_points" value="0" class="input-sm">
                                可用福分：<?php echo $user['pay_points']; ?>
                            </td>
                        </div>
                    </tr>
                     <tr>
                        <div class="row">
                            <td class="text-right col-sm-2">SHRIMP：</td>
                            <td colspan="3">
                                <select name="point_act_type_3" id="point_act_type_3" class="input-sm">
                                    <option value="1">增加</option>
                                    <option value="0">减少</option>
                                </select>
                                <input type="text" name="doge_money" value="0" class="input-sm">
                                可用SHRIMP：<?php echo $user['doge_money']; ?>
                            </td>
                        </div>
                    </tr>
                     <tr>
                        <div class="row">
                            <td class="text-right col-sm-2">FISH：</td>
                            <td colspan="3">
                                <select name="point_act_type_4" id="point_act_type_4" class="input-sm">
                                    <option value="1">增加</option>
                                    <option value="0">减少</option>
                                </select>
                                <input type="text" name="pig_currency" value="0" class="input-sm">
                                可用SHRIMP：<?php echo $user['pig_currency']; ?>
                            </td>
                        </div>
                    </tr>
                <!--     <tr>
                        <div class="row">
                            <td class="text-right col-sm-2">冻结金额：</td>
                            <td colspan="3">
                                <select name="frozen_act_type" id="frozen_act_type" class="input-sm">
                                    <option value="1">增加</option>
                                    <option value="0">减少</option>
                                </select>
                                <input type="text" name="frozen_money" value="0" class="input-sm">
                                冻结资金：<?php echo $user['frozen_money']; ?>
                                <br/>
                                单位元, 当操作冻结资金时，金额那一栏不用填写数值。
                            </td>
                        </div>
                    </tr> -->

                 <!--    <tr>
                        <div class="row">
                            <td class="text-right col-sm-2">操作备注：</td>
                            <td colspan="3">
                                    <textarea name="desc" placeholder="请输入操作备注" rows="3" class="form-control"><?php echo \think\Request::instance()->param('desc'); ?></textarea>
                            </td>
                        </div>
                    </tr> -->
                    <tr>
                        <div class="row">
                            <td class="text-right col-sm-2">操作备注：</td>
                            <td colspan="3">
                                    <textarea name="desc" placeholder="请输入操作备注" rows="3" class="form-control">后台充值</textarea>
                            </td>
                        </div>
                    </tr>

                    <tr>
                        <div class="row">
                            <td colspan="4">
                                <div class="form-group text-center">
                                    <button class="btn btn-primary ajax-post" target-form="myform" type="submit" id="disabled">提交</button>
                                    <button onclick="history.go(-1)"  class="btn btn-primary" type="button">返回</button>
                                </div>
                            </td>
                        </div>
                    </tr>

                    </tbody>
                </table>
            </form>

            </div>
        </div>

    </div>    <!-- /.content -->
        </section>
</div>


