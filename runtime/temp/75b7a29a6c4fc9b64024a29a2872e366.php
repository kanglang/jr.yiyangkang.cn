<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:85:"/www/wwwroot/jr.yiyangkang.cn/public/../application/shop/view/order/ajaxdelivery.html";i:1554712291;}*/ ?>

                    <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"></td>
                                    <td class="text-center">
                                        <a href="javascript:sort('order_sn');">订单编号</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('add_time');">下单时间</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('consignee');">收货人</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('consignee');">联系电话</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('order_id');">所选物流</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="">物流费用</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="#">支付时间</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('total_amount');">订单总价</a>
                                    </td>
                                    <td class="text-center">操作</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(is_array($orderList) || $orderList instanceof \think\Collection || $orderList instanceof \think\Paginator): $i = 0; $__LIST__ = $orderList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" name="selected[]" value="6">
                                            <input type="hidden" name="shipping_code[]" value="flat.flat"></td>
                                        <td class="text-center"><?php echo $list['order_sn']; ?></td>
                                        <td class="text-center"><?php echo date('Y-m-d H:i',$list['add_time']); ?></td>
                                        <td class="text-center"><?php echo $list['consignee']; ?></td>
                                        <td class="text-center"><?php echo $list['mobile']; ?></td>
                                        <td class="text-center"><?php echo $list['shipping_name']; ?></td>
                                        <td class="text-center"><?php echo $list['shipping_price']; ?></td>
										<td class="text-center">
											<?php if($list['pay_time'] > 0): ?>
											  <?php echo date('Y-m-d H:i',$list['pay_time']); else: ?>
											       货到付款 
											<?php endif; ?>
										</td>
                                        <td class="text-center"><?php echo $list['total_amount']; ?></td>
                                        <td class="text-center">
                                        	<?php if($list['shipping_status'] != 1): ?>
                                            <a href="<?php echo U('shop/order/delivery_info',array('order_id'=>$list['order_id'])); ?>" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="查看详情">去发货</a>
                                        	<?php else: ?>
                                        		<a href="<?php echo U('shop/order/delivery_info',array('order_id'=>$list['order_id'])); ?>" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="查看详情"><i class="fa fa-eye"></i></a>
                                        		<a href="<?php echo U('Order/shipping_print',array('order_id'=>$list['order_id'])); ?>" target="_blank" data-toggle="tooltip" class="btn btn-default" title="打印快递单">
						                      		<i class="fa fa-print"></i>快递单
										    	</a>
                                        	<?php endif; ?>
                                        	<a href="<?php echo U('Order/order_print',array('order_id'=>$list['order_id'],'template'=>'picking')); ?>" target="_blank" data-toggle="tooltip" class="btn btn-default" title="打印配货单">
						                      <i class="fa fa-print"></i>配货单
										    </a>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-sm-6 text-left"></div>
                        <div class="col-sm-6 text-right"><?php echo $page; ?></div>
                    </div>
<script>
    $(".pagination  a").click(function(){
        var page = $(this).data('p');
        ajax_get_table('search-form2',page);
    });
</script>