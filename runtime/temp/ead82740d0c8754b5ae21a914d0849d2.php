<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"/www/wwwroot/pig.77cco.com/public/../application/shop/view/pig/pigindex.html";i:1554712283;}*/ ?>
                    <div>数量：<?php if($count): ?><?php echo $count; else: ?>0<?php endif; ?></div>
                    <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <!-- <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"></td> -->
    
                                    <td class="text-right">
                                        <a href="javascript:sort('id');">ID</a>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('nickname');">出售人手机号</a>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('pig_level');">鱼等级</a>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('total_amount');">出售状态</a>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('email');">福分</a>
                                    </td>
                                    
                                    <td class="text-left">
                                        <a href="javascript:void(0);">收购人手机号</a>
                                    </td>

                                     <td class="text-left">
                                        <a href="javascript:void(0);">指定人用户ID</a>
                                    </td>
                                   
                                    <td class="text-right">操作</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(is_array($userList) || $userList instanceof \think\Collection || $userList instanceof \think\Paginator): $i = 0; $__LIST__ = $userList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
                                    <tr>
                                    <!--     <td class="text-center">
                                            <input type="checkbox" name="selected[]" value="<?php echo $list['id']; ?>">
                                            <input type="hidden" name="shipping_code[]" value="flat.flat">
                                        </td> -->
                                        <td class="text-right"><?php echo $list['id']; ?></td>
                                        <td class="text-left"><?php echo $list['name']; ?></td>
                                        <td class="text-left"><?php echo $list['pig_level']; ?></td>
                                        <td class="text-left"><?php if($list['is_able_sale'] == 1): ?>可出售<?php else: ?>不可出售<?php endif; ?></td>
                                        <td class="text-left"><?php echo $list['price']; ?></td>
                                        <td class="text-left"><?php echo $list['first_name']; ?></td>
                                        <td class="text-left"><?php echo $list['appoint_user_id']; ?></td>
                                        
                        
                                        <td class="text-right">
                                            <!-- <a href="<?php echo U('shop/user/detail',array('id'=>$list['user_id'])); ?>" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="查看详情"><i class="fa fa-eye"></i></a>
                                            <a href="<?php echo U('shop/user/address',array('id'=>$list['user_id'])); ?>" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="收货地址"><i class="fa fa-home"></i></a> -->

                                            <!-- <a href="javascript:;" onclick="change_pig(<?php echo $list['id']; ?>)" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="更改上级"><i class="fa fa-pencil"></i></a> -->
                                            
                                            <a class="btn btn-primary" href="<?php echo U('Pig/change',array('act'=>'edit','id'=>$list['id'])); ?>"><i class="fa fa-pencil"></i>指定ID</a>
                                      
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