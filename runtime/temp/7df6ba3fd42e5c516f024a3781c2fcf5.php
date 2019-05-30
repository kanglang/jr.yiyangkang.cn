<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"/www/wwwroot/jr.yiyangkang.cn/public/../application/shop/view/pickup/ajaxPickupList.html";i:1554712292;}*/ ?>
<form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <td style="width: 1px;" class="text-center">
                <!--
                    <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                -->    
                </td>                
                <td class="text-right">
                    <a href="javascript:void(0);">ID</a>
                </td>
                <td class="text-left">
                    <a href="javascript:void(0);">自提点名称</a>
                </td>
                <td class="text-left">
                    <a href="javascript:void(0);">自提点地址</a>
                </td>                                
                <td class="text-left">
                    <a href="javascript:void(0);">自提点电话</a>
                </td>                
                <td class="text-left">
                    <a href="javascript:void(0);">自提点联系人</a>
                </td>
                <td class="text-left">
                    <a href="javascript:void(0);">省</a>
                </td>                
                <td class="text-left">
                    <a href="javascript:void(0);">市</a>
                </td>
                <td class="text-left">
                    <a href="javascript:void(0);">区</a>
                </td>
                <td class="text-left">
                    <a href="javascript:void(0);">供应商</a>
                </td>   

                <td class="text-right">操作</td>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($pickupList) || $pickupList instanceof \think\Collection || $pickupList instanceof \think\Paginator): $i = 0; $__LIST__ = $pickupList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td class="text-center">
                       <!-- <input type="checkbox" name="selected[]" value="6">-->
                        <input type="hidden" name="shipping_code[]" value="flat.flat">
                    </td>
                    <td class="text-right"><?php echo $list['pickup_id']; ?></td>
                    <td class="text-left"><?php echo getSubstr($list['pickup_name'],0,33); ?></td>
                    <td class="text-left"><?php echo getSubstr($list['pickup_address'],0,33); ?></td>
                    <td class="text-left"><?php echo $list['pickup_phone']; ?></td>
                    <td class="text-left"><?php echo $list['pickup_contact']; ?></td>
                    <td class="text-left"><?php echo $list['province_name']; ?></td>
                    <td class="text-left"><?php echo $list['city_name']; ?></td>
                    <td class="text-left"><?php echo $list['district_name']; ?></td>
                    <td class="text-left"><?php echo (isset($list['suppliers_name']) && ($list['suppliers_name'] !== '')?$list['suppliers_name']:'无供应商'); ?></td>
                    <td class="text-right">
                        <a href="<?php echo url('Pickup/edit_address',array('pickup_id'=>$list['pickup_id'])); ?>" class="btn btn-primary" title="编辑"><i class="fa fa-pencil"></i></a>
                        <a href="javascript:void(0);" onclick="del(<?php echo $list['pickup_id']; ?>)" class="btn btn-danger" title="删除"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>
</form>
<div class="row">
    <div class="col-sm-3 text-left"></div>
    <div class="col-sm-9 text-right"><?php echo $page; ?></div>
</div>
<script>
    // 点击分页触发的事件
    $(".pagination  a").click(function(){
        cur_page = $(this).data('p');
        ajax_get_table('search-form2',cur_page);
    });

    // 删除操作
    function del(id)
    {
        if(!confirm('确定要删除吗?'))
            return false;
        $.ajax({
            url:"<?php echo url('Pickup/del'); ?>?pickup_id="+id,
            success: function(v){
                var v =  eval('('+v+')');
                if(v.hasOwnProperty('status') && (v.status == 1))
                    ajax_get_table('search-form2',cur_page);
                else
                    layer.msg(v.msg, {icon: 2,time: 1000}); //alert(v.msg);
            }
        });
        return false;
    }
</script>