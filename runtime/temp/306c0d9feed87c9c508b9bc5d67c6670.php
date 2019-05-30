<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"/www/wwwroot/jr.yiyangkang.cn/public/../application/shop/view/pig/pigsert.html";i:1554712282;}*/ ?>
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
                                        <a href="javascript:sort('nickname');">预约人的手机号</a>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('pig_level');">鱼等级</a>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('reservation_status');">状态</a>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('email');">花费的福分</a>
                                    </td>
                                 
                                    <td class="text-left">
                                        <a href="javascript:sort('email');">预约时间</a>
                                    </td>
                                 
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
                                        <td class="text-left"><?php if($list['reservation_status'] == 1): ?><span style="color: blue">已抢到</span><?php elseif($list['reservation_status']==0): ?><span style="color: red">未抢到</span><?php endif; ?></td>
                                        <td class="text-left"><?php echo $list['pay_points']; ?></td>
                                    
                                        <td class="text-left"><?php if($list['reservation_time']): ?><?php echo date("Y-m-d H:i:s",$list['reservation_time']); else: endif; ?></td>
                                  
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