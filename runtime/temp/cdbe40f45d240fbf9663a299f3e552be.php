<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:72:"/home/xgh/zouzebin/public/../application/shop/view/user/account_log.html";i:1554117475;s:59:"/home/xgh/zouzebin/application/shop/view/public/header.html";i:1554117475;s:45:"../application/common/view/public/header.html";i:1554117475;}*/ ?>
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
		<div class="box-header">
			<nav class="navbar navbar-default">
				<div class="collapse navbar-collapse">
					  <form method="post" role="search" class="navbar-form form-inline" id="search-form" action="#">
						<div class="col-xs-2">
							<input type="text" placeholder="用户id" name="user_id" id="user_id"  class="form-control" value="<?php echo \think\Request::instance()->param('user_id'); ?>"> 
						</div>
                        <div class="col-xs-3">         
                                <div class="input-group margin">
                                    <div class="input-group-addon">
                                        选择时间  <i class="fa fa-calendar"></i>
                                    </div>
                                   <input type="text" class="form-control pull-right" name="timegap" value="<?php echo \think\Request::instance()->param('timegap'); ?>" id="start_time">
                                </div>
                        </div>
                   <!--      <div class="col-sm-3">
                            <div class="input-group">
                                <select class="form-control m-b chosen-select" name="type" >
                                    <option value="0">选择类型</option>
                                    <option value="1" <?php if(\think\Request::instance()->param('type') == 1): ?>selected<?php endif; ?>>下单消费</option>
                                    <option value="2" <?php if(\think\Request::instance()->param('type') == 2): ?>selected<?php endif; ?>>业绩返佣</option>
                                </select>
                            </div>
                        </div>   -->
						<button class="btn btn-info" type="submit"><i class="fa fa-search"></i> 筛选</button>
					   </form>
				</div>
			</nav>
		</div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php if(\think\Request::instance()->param('user_id')): ?>
            <div class="navbar navbar-default">
                <div class="row navbar-form">
                    <div class="pull-right">
                        <a data-original-title="资金调节" class="btn btn-primary" title="" data-toggle="tooltip" onclick="change_money('<?php echo url('shop/User/account_edit',array('user_id'=>\think\Request::instance()->param('user_id'))); ?>')"><i class="fa fa-pencil"></i>资金调节</a>
                        <a data-original-title="返回" class="btn btn-default" title="" data-toggle="tooltip" href="javascript:history.go(-1)"><i class="fa fa-reply"></i></a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 账户资金记录列表</h3>
                </div>


                <div class="ibox-content">


                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <td class="text-left">
                                    用户
                                </td>
                                <td class="text-left">
                                    手机号
                                </td>
                                <td class="text-left">
                                    变动时间
                                </td>
                                <td class="text-left">
                                    描述
                                </td>
                                <td class="text-left">
                                    推广财分
                                </td>
                               <!--  <td class="text-left">
                                冻结资金
                                 </td> -->
                                <td class="text-left">
                                    福分
                                </td>
                                <td class="text-left">
                                    SHRIMP
                                </td>
                                <td class="text-left">
                                    FISH
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
                                <tr>
                                    <td class="text-left"><?php echo $list['user_id']; ?></td>
                                    <td class="text-left"><?php echo $list['mobile']; ?></td>
                                    <td class="text-left"><?php echo date("Y-m-d H:i:s",$list['change_time']); ?></td>

                                    <td class="text-left"><?php echo $list['desc']; ?></td>
                                    <td class="text-left">
                                        <?php if($list['user_money'] > 0): ?>+<?php endif; ?>
                                        <?php echo $list['user_money']; ?>
                                    </td>
                                 <!--    <td class="text-left">
                                        <?php if($list['frozen_money'] > 0): ?>+<?php endif; ?>
                                        <?php echo $list['frozen_money']; ?>
                                    </td> -->
                                    <td class="text-left">
                                        <?php if($list['pay_points'] > 0): ?>+<?php endif; ?>
                                        <?php echo $list['pay_points']; ?>
                                    </td>
                                    <td class="text-left">
                                        <?php if($list['doge_money'] > 0): ?>+<?php endif; ?>
                                        <?php echo $list['doge_money']; ?>
                                    </td>
                                    <td class="text-left">
                                        <?php if($list['pig_currency'] > 0): ?>+<?php endif; ?>
                                        <?php echo $list['pig_currency']; ?>
                                    </td>
                                </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                        <div class="pull-right">
                            <?php echo $page; ?>
                        </div>
                        <div class="pull-right">
                            总计<?php echo $count; ?>条
                        </div>
                    </div>


                </div>
            </div>
        </div>        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script type="text/javascript">

function change_money(url){
    layer.open({
        type: 2,
        title: '资金变动',
        shadeClose: true,
        shade: 0.2,
        area: ['75%', '75%'],
        content: url, 
    });
}
$(document).ready(function() {
    $('#start_time').daterangepicker({
        format:"YYYY-MM-DD",
        singleDatePicker: false,
        showDropdowns: true,
        minDate:'2016-01-01',
        maxDate:'2030-01-01',
        startDate:'2016-01-01',
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        ranges: {
           '今天': [moment(), moment()],
           '昨天': [moment().subtract('days', 1), moment().subtract('days', 1)],
           '最近7天': [moment().subtract('days', 6), moment()],
           '最近30天': [moment().subtract('days', 29), moment()],
           '上一个月': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
        },
        opens: 'right',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        locale : {
            applyLabel : '确定',
            cancelLabel : '取消',
            fromLabel : '起始时间',
            toLabel : '结束时间',
            customRangeLabel : '自定义',
            daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
            monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月','七月', '八月', '九月', '十月', '十一月', '十二月' ],
            firstDay : 1
        }
    });
});
</script>

