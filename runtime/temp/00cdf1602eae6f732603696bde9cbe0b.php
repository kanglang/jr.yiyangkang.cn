<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:79:"/www/wwwroot/jr.yiyangkang.cn/public/../application/shop/view/order/detail.html";i:1554712290;s:70:"/www/wwwroot/jr.yiyangkang.cn/application/shop/view/public/header.html";i:1554712283;s:45:"../application/common/view/public/header.html";i:1554712305;}*/ ?>
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

<style type="text/css">
    .margin{margin-right: 10px;}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
    <section class="content">
    <div class="row">
      <div class="col-xs-12">
      	<div class="box">
           <nav class="navbar navbar-default">	     
			<div class="collapse navbar-collapse">
                <div class="navbar-form pull-right margin">
                      <?php if($order['order_status'] < 2): ?>
                         <a href="<?php echo U('shop/order/edit_order',array('order_id'=>$order['order_id'])); ?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑">修改订单</a>
                      <?php endif; if(($split == 1) and ($order['order_status'] < 2)): ?>
                         <a href="<?php echo U('shop/order/split_order',array('order_id'=>$order['order_id'])); ?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑">拆分订单</a>
                      <?php endif; ?>
                      <a href="<?php echo U('Order/order_print',array('order_id'=>$order['order_id'])); ?>" target="_blank" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="打印订单">
	                     <i class="fa fa-print"></i>打印订单
	                  </a>
                      <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
               </div>
            </div>
           </nav>
   
        <!--新订单列表 基本信息-->
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3 class="panel-title text-center">基本信息</h3>
            </div>
            <div class="ibox-content">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td>订单 ID:</td>
                        <td>订单号:</td>
                        <td>会员:</td>
                        <td>电话:</td>
                        <td>应付:</td>
                        <td>优惠码:</td>
                        <td>订单 状态:</td>
                        <td>下单时间:</td>
                        <td>支付时间:</td>
                        <td>支付方式:</td>
                    </tr>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['order_sn']; ?></td>
                        <td><a href="#" target="_blank"><?php echo $order['consignee']; ?></a></td>
                        <td><?php echo $order['mobile']; ?></td>
                        <td><?php echo $order['order_amount']; ?></td>
                        <td><?php echo !empty($order['code'])?$order['code']:''; ?></td>
                        <td id="order-status"><?php echo $order_status[$order['order_status']]; ?> / <?php echo $pay_status[$order['pay_status']]; if($order['pay_code'] == 'cod'): ?><span style="color: red">(货到付款)</span><?php endif; ?> / <?php echo $shipping_status[$order['shipping_status']]; ?></td>
                    	<td><?php echo date('Y-m-d H:i',$order['add_time']); ?></td>
                    	<td><?php if($order['pay_time'] != 0): ?>
                                <?php echo date('Y-m-d H:i',$order['pay_time']); else: ?>
                            N
                         <?php endif; ?>
                        </td>             
                        <td id="pay-type">
                            <?php echo (isset($order['pay_name']) && ($order['pay_name'] !== '')?$order['pay_name']:'其他方式'); ?>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <!--新订单列表 收货人信息-->
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3 class="panel-title text-center">收货信息</h3>
            </div>
            <div class="ibox-content">
                <table class="table table-bordered">
                    <tbody><tr>
					<td>收货人:</td>
					<td>联系方式:</td>
					<td>地址:</td>
					<td>邮编:</td>
					<td>配送方式:</td>
			 
                    </tr>
                    <tr>
                        <td><?php echo $order['consignee']; ?></td>
                        <td><?php echo $order['mobile']; ?></td>
                        <td><?php echo $order['address2']; ?></td>
                        <td>
                            <?php if($order['zipcode'] != ''): ?>
                                <?php echo $order['zipcode']; else: ?>
                                N
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo $order['shipping_name']; if($order['shipping_status'] == 1): ?>
                                <a href="<?php echo U('shop/Order/shipping_print',array('order_id'=>$order['order_id'],'code'=>$order['shipping_code'])); ?>" target="_blank" class="btn btn-primary input-sm" onclick="">打印快递单</a>
                            <?php endif; ?>
                        </td>                      
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--新订单列表 商品信息-->
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3 class="panel-title text-center">商品信息 </h3>
            </div>
            <div class="ibox-content">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td class="text-left">商品</td>
                        <td class="text-left">属性</td>
                        <td class="text-right">数量</td>
                        <td class="text-right">单品价格</td>
                        <td class="text-right">会员折扣价</td>
                        <td class="text-right">单品小计</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($orderGoods) || $orderGoods instanceof \think\Collection || $orderGoods instanceof \think\Paginator): $i = 0; $__LIST__ = $orderGoods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$good): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td class="text-left"><a href="<?php echo U('Home/Goods/goodsInfo',array('id'=>$good['goods_id'])); ?>" target="_blank"><?php echo $good['goods_name']; ?></a>
                            </td>
                            <td class="text-left"><?php echo (isset($good['spec_key_name']) && ($good['spec_key_name'] !== '')?$good['spec_key_name']:''); ?></td><!--$good.goods_attr-->
                            <td class="text-right"><?php echo $good['goods_num']; ?></td>
                            <td class="text-right"><?php echo $good['goods_price']; ?></td>
                            <td class="text-right"><?php echo $good['member_goods_price']; ?></td>
                            <td class="text-right"><?php echo $good['goods_total']; ?></td>
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>

                    <tr>
                        <td colspan="4" class="text-right">小计:</td>
                        <td class="text-right"><?php echo $order['goods_price']; ?></td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <!--新订单列表 费用信息-->
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3 class="panel-title text-center">费用信息
                    <a class="btn btn-primary btn-xs" data-original-title="修改费用" title="" data-toggle="tooltip" href="<?php echo U('shop/Order/editprice',array('order_id'=>$order['order_id'])); ?>">
                    <i class="fa fa-pencil"></i>
                </a></h3>
            </div>
            <div class="ibox-content">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td class="text-right">小计:</td>
                        <td class="text-right">运费:</td>
                        <td class="text-right">积分 (-<?php echo $order['integral']; ?>):</td>
                        <td class="text-right">余额抵扣</td>
                        <td class="text-right">优惠券抵扣</td>
                        <td class="text-right">价格调整:</td>
                        <td class="text-right">应付:</td>
                    </tr>
                    <tr>
                        <td class="text-right"><?php echo $order['goods_price']; ?></td>
                        <td class="text-right">+<?php echo $order['shipping_price']; ?></td>
                        <td class="text-right">-<?php echo $order['integral_money']; ?></td>
                        <td class="text-right">-<?php echo $order['user_money']; ?></td>
                        <td class="text-right">-<?php echo $order['coupon_price']; ?></td>
                        <td class="text-right">减:<?php echo $order['discount']; ?></td>
                        <td class="text-right"><?php echo $order['order_amount']; ?></td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <!--新订单列表 操作信息-->
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3 class="panel-title text-center">操作信息</h3>
            </div>
            <div class="ibox-content">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <div class="row">
                            <td class="text-right col-sm-2"><p class="margin">操作备注：</p></td>
                            <td colspan="3">
                                <form id="order-action">
                                    <textarea name="note" placeholder="请输入操作备注" rows="3" class="form-control"></textarea>
                                </form>
                            </td>
                        </div>
                    </tr>
                    <tr>
                        <div class="row">
                            <td class="text-right col-sm-2"><p class="margin">当前可执行操作：</p></td>
                            <td colspan="3">
                                <div class="input-group">
                                	<?php if(is_array($button) || $button instanceof \think\Collection || $button instanceof \think\Paginator): if( count($button)==0 ) : echo "" ;else: foreach($button as $k=>$vo): if($k == 'pay_cancel'): ?>
                                			<a class="btn btn-primary margin" href="javascript:void(0)" data-url="<?php echo U('Order/pay_cancel',array('order_id'=>$order['order_id'])); ?>" onclick="pay_cancel(this)"><?php echo $vo; ?></a>
                                		<?php elseif($k == 'delivery'): ?>                                                 
                                			<a class="btn btn-primary margin" href="<?php echo U('Order/delivery_info',array('order_id'=>$order['order_id'])); ?>"><?php echo $vo; ?></a>
                                		<?php elseif($k == 'refund'): ?>
                                			<!--退货商品列表-->
											<!-- <input class="btn btn-primary" type="button" onclick="selectGoods2(<?php echo $order['order_id']; ?>)" value="退货申请"> 	-->
                                		<?php else: ?>
                                		<button class="btn btn-primary margin" onclick="ajax_submit_form('order-action','<?php echo U('shop/order/order_action',array('order_id'=>$order['order_id'],'type'=>$k)); ?>');" type="button" id="confirm">
                                		<?php echo $vo; ?></button>
                                		<?php endif; endforeach; endif; else: echo "" ;endif; ?>                                
                                </div>
                            </td>
                        </div>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!--新订单列表 操作记录信息-->
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3 class="panel-title text-center">操作记录</h3>
            </div>
            <div class="ibox-content">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td class="text-center">操作者</td>
                        <td class="text-center">操作时间</td>
                        <td class="text-center">订单状态</td>
                        <td class="text-center">付款状态</td>
                        <td class="text-center">发货状态</td>
                        <td class="text-center">描述</td>
                        <td class="text-center">备注</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($action_log) || $action_log instanceof \think\Collection || $action_log instanceof \think\Paginator): $i = 0; $__LIST__ = $action_log;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$log): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td class="text-center"><?php echo $log['action_user']; ?></td>
                            <td class="text-center"><?php echo date('Y-m-d H:i:s',$log['log_time']); ?></td>
                            <td class="text-center"><?php echo $order_status[$log['order_status']]; ?></td>
                            <td class="text-center"><?php echo $pay_status[$log['pay_status']]; if($order['pay_code'] == 'code'): ?><span style="color: red">(货到付款)</span><?php endif; ?></td>
                            <td class="text-center"><?php echo $shipping_status[$log['shipping_status']]; ?></td>
                            <td class="text-center"><?php echo $log['status_desc']; ?></td>
                            <td class="text-center"><?php echo $log['action_note']; ?></td>
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
          </div>
        </div>
	  </div>
    </div> 
   </section>
</div>
</body>
<script>
function pay_cancel(obj){
    var url =  $(obj).attr('data-url');
    layer.open({
        type: 2,
        title: '退款操作',
        shadeClose: true,
        shade: 0.8,
        area: ['45%', '50%'],
        content: url, 
    });
}
//取消付款
function pay_callback(s){
	if(s==1){
		layer.msg('操作成功', {icon: 1});
		layer.closeAll('iframe');
		location.href =	location.href;
	}else{
		layer.msg('操作失败', {icon: 3});
		layer.closeAll('iframe');
		location.href =	location.href;		
	}
}

// 弹出退换货商品
function selectGoods2(order_id){
	var url = "<?php echo url('Order/get_order_goods'); ?>?order_id="+order_id;
	layer.open({
		type: 2,
		title: '选择商品',
		shadeClose: true,
		shade: 0.8,
		area: ['60%', '60%'],
		content: url, 
	});
}    
// 申请退换货
function call_back(order_id,goods_id)
{
	var url = "<?php echo url('Order/add_return_goods'); ?>?order_id="+order_id+"&goods_id="+goods_id;	
	location.href = url;
}
</script>
</html>