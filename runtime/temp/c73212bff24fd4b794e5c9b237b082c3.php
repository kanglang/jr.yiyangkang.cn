<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"/home/xgh/zouzebin/public/../application/shop/view/pig/ajaxindex.html";i:1554117475;}*/ ?>
                    <div>数量：<?php if($count): ?><?php echo $count; else: ?>0<?php endif; ?></div>
                    <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                   <!--  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"></td> -->
                                    <td class="text-center">
                                        <a href="javascript:sort('order_sn');">订单编号</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('consignee');">出售人昵称</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('consignee');">购买人昵称</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="">鱼的等级</a>
                                    </td>      
                                    <td class="text-center">
                                        <a href="">鱼的价格</a>
                                    </td>
									<td class="text-center">
                                        <a href="">鱼的ID</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('order_status');">交易状态</a>
                                    </td>
                                    <td class="text-center">
                                        交易凭证
                                    </td>                           
                                    <td class="text-center">
                                        <a href="javascript:sort('add_time');">订单下单时间</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('add_time');">订单结束时间</a>
                                    </td>
                                    <!-- <td class="text-center">操作</td> -->
                                </tr>
                                </thead>
                                <tbody>
                                 <?php if(is_array($orderList) || $orderList instanceof \think\Collection || $orderList instanceof \think\Paginator): $i = 0; $__LIST__ = $orderList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
                                    <tr>
                                        <td class="text-center"><?php echo $list['pig_order_sn']; ?></td>
                                        <td class="text-center"><?php echo $list['user_name']; ?></td>
                                        <td class="text-center"><?php echo $list['first_name']; ?></td>
                                        <td class="text-center"><?php echo $list['pig_level']; ?></td>
                                        <td class="text-center"><?php echo $list['pig_price']; ?></td>
                                        <td class="text-center"><?php echo $list['pig_id']; ?></td>
                                        <td class="text-center"><?php if($list['pay_status']==0): ?>冻结<?php elseif($list['pay_status'] == 1): ?><span style="color: blue">交易中</span><?php elseif($list['pay_status'] == 2): ?><span style="color: red">交易完成</span><?php endif; ?></td>
                                        <td class="text-center"><a href="<?php echo $list['img_url']; ?>"><img src="<?php echo $list['img_url']; ?>" style="height: 30px" onerror="this.src='/static/admin/images/no_img.jpg'"/></a></td>
                                        <td class="text-center"><?php if($list['establish_time']): ?><?php echo date('Y-m-d H:i',$list['establish_time']); else: endif; ?></td>
                                        <td class="text-center"><?php if($list['end_time']): ?><?php echo date('Y-m-d H:i',$list['end_time']); else: endif; ?></td>
                                        <!-- <td class="text-center">
                                           <a href="javascript:;" onclick="order_pig(<?php echo $list['order_id']; ?>)" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="查看详情"><i class="fa fa-pencil"></i></a> 
                                        </td> -->
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