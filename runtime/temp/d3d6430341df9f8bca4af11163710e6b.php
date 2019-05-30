<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"/www/wwwroot/jr.yiyangkang.cn/public/../application/shop/view/user/ajaxindex.html";i:1554712287;}*/ ?>

                    <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"></td>
    
                                    <td class="text-center">
                                        <a href="javascript:sort('user_id');">ID</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('nickname');">会员昵称</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('level');">会员等级</a>
                                    </td>
                                    <td class="text-center">
                                        总资产
                                    </td>
                                    <td class="text-center">
                                        合约收益
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0);">一级下线数</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0);">二级下线数</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0);">三级下线数</a>
                                    </td>                                    
                                    <td class="text-center">
                                        <a href="javascript:sort('mobile');">手机号码</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('user_money');">推广财分</a>
                                    </td>
                                    <!-- <td class="text-center">
                                        <a href="javascript:sort('frozen_money');">冻结余额</a>
                                    </td> -->
                                    <td class="text-center">
                                        <a href="javascript:sort('pay_points');">福分</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('doge_money');">SHRIMP</a>
                                    </td>
                                     <td class="text-center">
                                        <a href="javascript:sort('pig_currency');">FISH</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('reg_time');">注册日期</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:sort('is_lock');">冻结用户</a>
                                    </td>
                                     <td class="text-center">
                                        银行卡信息
                                    </td>

                                    <td class="text-center">操作</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(is_array($userList) || $userList instanceof \think\Collection || $userList instanceof \think\Paginator): $i = 0; $__LIST__ = $userList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="selected[]" value="<?php echo $list['user_id']; ?>">
                                            <input type="hidden" name="shipping_code[]" value="flat.flat">
                                        </td>
                                        <td class="text-center"><?php echo $list['user_id']; ?></td>
                                        <td class="text-center"><?php echo $list['nickname']; ?></td>
                                        <td class="text-center"><?php echo $level[$list['level']]; ?></td>
                                        <td class="text-center"><?php echo $list['allprice']; ?></td>
                                        <td class="text-center"><?php echo $list['contract_revenue']; ?></td>
                                        <td class="text-center"><?php echo (isset($first_leader[$list['user_id']]['count']) && ($first_leader[$list['user_id']]['count'] !== '')?$first_leader[$list['user_id']]['count']:"0"); ?></td>
                                        <td class="text-center"><?php echo (isset($second_leader[$list['user_id']]['count']) && ($second_leader[$list['user_id']]['count'] !== '')?$second_leader[$list['user_id']]['count']:"0"); ?></td>
                                        <td class="text-center"><?php echo (isset($third_leader[$list['user_id']]['count']) && ($third_leader[$list['user_id']]['count'] !== '')?$third_leader[$list['user_id']]['count']:"0"); ?></td>
                                        <td class="text-center"><?php echo $list['mobile']; ?></td>
                                        <td class="text-center"><?php echo $list['user_money']; ?></td>
                                        <!-- <td class="text-center"><?php echo $list['frozen_money']; ?></td> -->
                                        <td class="text-center"><?php echo $list['pay_points']; ?></td>
                                        <td class="text-center"><?php echo $list['doge_money']; ?></td>
                                        <td class="text-center"><?php echo $list['pig_currency']; ?></td>
                                        <td class="text-center"><?php echo date('Y-m-d H:i',$list['reg_time']); ?></td>
                                        <td class="text-center"><?php if($list['is_lock']==1): ?><span style="color: blue">是</span><?php else: ?><span style="color: red">否</span><?php endif; ?></td>
                                        <td class="text-center"><a href="<?php echo U('shop/user/bankdetail',array('id'=>$list['user_id'])); ?>" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="查看详情"><i class="fa fa-eye"></i></td>
                                        <td class="text-center">
                                            <a href="<?php echo U('shop/user/detail',array('id'=>$list['user_id'])); ?>" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="查看详情"><i class="fa fa-eye"></i></a>
                                            <!-- <a href="<?php echo U('shop/user/address',array('id'=>$list['user_id'])); ?>" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="收货地址"><i class="fa fa-home"></i></a> -->
                                            <a href="javascript:;" onclick="change_leader(<?php echo $list['user_id']; ?>)" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="更改上级"><i class="fa fa-pencil"></i></a>
                                            <a href="<?php echo U('shop/user/account_log',array('user_id'=>$list['user_id'])); ?>" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="账户"><i class="glyphicon glyphicon-yen"></i></a>
                                            <!-- <a href="<?php echo U('shop/user/delete',array('id'=>$list['user_id'])); ?>" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a> -->
                                        </td>
                                    </tr>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <div class="row">
                        <div class="pull-right"><?php echo $page; ?></div>
                    </div>
<script>
    $(".pagination  a").click(function(){
        var page = $(this).data('p');
        ajax_get_table('search-form2',page);
    });
</script>