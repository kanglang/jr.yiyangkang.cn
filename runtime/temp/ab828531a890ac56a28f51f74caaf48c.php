<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:87:"/www/wwwroot/jr.yiyangkang.cn/public/../application/shop/view/user/editWithdrawals.html";i:1554712286;s:70:"/www/wwwroot/jr.yiyangkang.cn/application/shop/view/public/header.html";i:1554712283;s:45:"../application/common/view/public/header.html";i:1554712305;}*/ ?>
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
            <div class="pull-right">

                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
            </div>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 提现申请</h3>
                </div>
                <div class="ibox-content">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_tongyong" data-toggle="tab">申请用户</a></li>
                    </ul>
                    <!--表单数据-->
                    <form method="post" id="editForm">
                        <!--通用信息-->
                    <div class="tab-content">                 	  
                        <div class="tab-pane active" id="tab_tongyong">
                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>用户id</td>
                                    <td>
                                        <a href="<?php echo U('shop/user/detail',array('id'=>$data['user_id'])); ?>"><?php echo $data['user_id']; ?></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>用户名</td>
                                    <td>
                                        <?php echo $data['user_name']; ?>
                                    </td>
                                </tr>
                                 <tr>
                                    <td>钱包地址</td>
                                    <td>
                                        <?php echo $data['wallet_address']; ?>
                                    </td>
                                </tr>
                             <!--    <tr>
                                    <td>用户FISH币</td>
                                    <td>
                                        <?php echo $user['pig_currency']; ?>
                                    </td>
                                </tr>
                                 <tr>
                                    <td>用户虾虾币</td>
                                    <td>
                                        <?php echo $user['doge_money']; ?>
                                    </td>
                                </tr> -->
                                 <tr>
                                    <td>提现币种</td>
                                    <td>
                                        <?php if($data['type']==1): ?>FISH币<?php else: ?>虾虾币<?php endif; ?>
                                    </td>
                                </tr>                               
                                <tr>
                                    <td>提现额度</td>
                                    <td>
                                        <?php echo $data['money']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>提现手续费</td>
                                    <td>
                                        <?php echo $data['taxfee']; ?>
                                    </td>
                                </tr>                                        
                                                             
                                <tr>
                                    <td>申请时间</td>
                                    <td>
                                        <?php echo date("Y-m-d H:i",$data['create_time']); ?>
                                    </td>
                                </tr>  
                                <tr>
                                    <td>状态</td>
                                    <td>
                                        <?php echo $tx_status_arr[$data['status']]; ?>  
                                    </td>
                                </tr>                                                                                   
                                <tr>
                                    <td>备注</td>
                                    <td>
                                        <textarea rows="4" cols="60" id="remark" name="remark"><?php echo $data['remark']; ?></textarea>
                                        <span id="err_remark" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                </tr> 
                                <tr>
                                    <td>提现流程:</td>
                                    <td>
                                        1:用户前台申请提现<br/>
                                        2:管理员审核生成转账记录 ( 生成时自动扣除用户平台币种 ) <br/>
                                        3:财务线下转账给用户<br/>
                                        或 2 , 3步可以调换,先转账后生成记录.<br/>
                                    </td>
                                </tr>                                   
                                </tbody>                                
                                </table>
                        </div>                           
                    </div>              
                    <div class="pull-right">
                        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                        <input type="hidden" name="type" value="<?php echo $data['type']; ?>">
                        <input type="hidden" name="money" value="<?php echo $data['money']; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $data['user_id']; ?>">
                        <input type="hidden" id="status" name="status" value="<?php echo $data['status']; ?>">
                        <?php if(in_array($data['status'],array(0,2))): ?>
                            <button class="btn btn-primary" data-toggle="tooltip" type="button" onclick="confirm_withdrawals();">去生成转账记录</button>
                        <?php endif; if($data['status'] == 0): ?>
                            <button class="btn btn-primary" data-toggle="tooltip" type="button" onclick="cancel_withdrawals();">拒绝提现</button>
                        <?php endif; if($data['status'] == 1): ?>
                            <!--<button class="btn btn-primary" data-toggle="tooltip" type='submit'>修改备注</button>-->
                        <?php endif; ?>
                        
                    </div>
                 </form><!--表单数据-->
                </div>
            </div>
        </div>    <!-- /.content -->
    </section>
</div>
<script>
// 确定提现
    function confirm_withdrawals()
    {
        if ($.trim($('#remark').val()).length == 0) {
            layer.alert('请填写转账备注', {icon: 2});
            return false;
        }
        layer.confirm('确定扣除<?php echo $data['money']; ?>币,确定吗?', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    // 确定
                    $('#status').val('1');
                    $.ajax({
                        type : "POST",
                        url:"<?php echo url('User/withdrawals_update'); ?>",
                        data : $('#editForm').serialize(),
                        dataType: "json",
                        success: function(data){
                            if(data.status == 1){
                                layer.alert(data.msg, {icon: 1});
                                location.reload();
                            }else{
                                layer.alert(data.msg, {icon: 2});
                            }
                        }
                    });
                }, function (index) {
                    layer.close(index);
                }
        );
    }
    // 拒绝提现
    function cancel_withdrawals() {
        if ($.trim($('#remark').val()).length == 0) {
            layer.alert('请填写拒绝备注', {icon: 2});
            return false;
        }
        layer.confirm('确定要拒绝用户提现吗?', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    // 确定
                    $('#status').val('-1');
                    $.ajax({
                        type : "POST",
                        url:"<?php echo url('User/withdrawals_update'); ?>",
                        data : $('#editForm').serialize(),
                        dataType: "json",
                        success: function(data){
                            if(data.status == 1){
                                layer.alert(data.msg, {icon: 1});
                                location.reload();
                            }else{
                                layer.alert(data.msg, {icon: 2});
                            }
                        }
                    });
                }, function (index) {
                    layer.close(index);
                }
        );

    }
</script>
</body>
</html>