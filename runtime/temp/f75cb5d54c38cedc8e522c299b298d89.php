<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:80:"/www/wwwroot/pig.77cco.com/public/../application/shop/view/user/withdrawals.html";i:1554712286;s:67:"/www/wwwroot/pig.77cco.com/application/shop/view/public/header.html";i:1554712283;s:45:"../application/common/view/public/header.html";i:1554712305;}*/ ?>
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
    <div class="container-fluid">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h3 class="panel-title"><i class="fa fa-list"></i>提现申请</h3>
        </div>
        <div class="ibox-content">    
		<div class="navbar navbar-default">                    
                <form id="search-form2" class="navbar-form form-inline"  method="get" action="<?php echo url('/shop/User/withdrawals'); ?>">
                <div class="form-group">
                  <label for="input-order-id" class="control-label">状态:</label>
                <div class="form-group">
                  <select class="form-control" id="status" name="status">                       
                    <option value="">全部</option>                    
                    <option value="0"<?php if(\think\Request::instance()->param('status') == '0'): ?>selected<?php endif; ?>>申请中</option>
                    <option value="1"<?php if(\think\Request::instance()->param('status')  == 1): ?>selected<?php endif; ?>>申请成功</option>
                    <option value="-1"<?php if(\think\Request::instance()->param('status')  == -1): ?>selected<?php endif; ?>>申请失败</option>
                  </select>
                </div>
                  <label for="input-order-id" class="control-label">用户ID:</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="user_id" placeholder="用户id" value="<?php echo \think\Request::instance()->param('user_id'); ?>" name="user_id" />
                  </div>

                  <label for="input-order-id" class="control-label">用户手机号:</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="mobile" placeholder="用户手机号" value="<?php echo \think\Request::instance()->param('mobile'); ?>" name="mobile" />
                  </div>
                  
                <!--   <label for="input-order-id" class="control-label">收款账号:</label>                
                  <div class="input-group">
                    <input type="text" class="form-control" id="input-order-id" placeholder="收款账号" value="<?php echo \think\Request::instance()->param('bank_card'); ?>" name="account_bank" />                    
                  </div>
                  
                  <label for="input-order-id" class="control-label">收款账户名:</label>                
                  <div class="input-group">
                    <input type="text" class="form-control" id="input-order-id" placeholder="收款账户名" value="<?php echo \think\Request::instance()->param('realname'); ?>" name="account_name" />                    
                  </div> -->
                  
                   <div class="input-group margin">                    
                    <div class="input-group-addon">
                        申请时间<i class="fa fa-calendar"></i>
                    </div>
                       <input type="text" id="start_time" value="<?php echo $create_time; ?>" name="create_time" class="form-control pull-right">
                  </div>                  
                </div>
                <div class="form-group">    
                    <button class="btn btn-primary" id="button-filter search-order" type="submit"><i class="fa fa-search"></i> 筛选</button>    
                </div>                                 
                </form>    
          </div>
                        
          <div id="ajax_return"> 
                 
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="sorting text-left">ID</th>
                                <th class="sorting text-left">用户id</th>
                                <th class="sorting text-left">用户手机号</th>
                                <th class="sorting text-left">申请时间</th>                                                                
                                <th class="sorting text-left">钱包地址</th>                                                                
                                <th class="sorting text-left">提现额度</th>
                                <th class="sorting text-left">提现手续费</th>
                                <th class="sorting text-left">提现币种</th>
                            
                                <th class="sorting text-left">状态</th>                                
                                <th class="sorting text-left">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                <tr>
                                    <td class="text-left"><?php echo $v['id']; ?></td>                                   
                                    <td class="text-left">
                                        <a href="<?php echo url('shop/user/detail',array('id'=>$v['user_id'])); ?>">
                                             <?php echo $v['user_id']; ?>
                                        </a>
                                    </td>
                                    <td class="text-left"><?php echo $v['mobile']; ?></td>                                    
                                    <td class="text-left"><?php echo date("Y-m-d",$v['create_time']); ?></td>                                    
                                    <td class="text-left"><?php echo $v['wallet_address']; ?></td>
                                    <td class="text-left"><?php echo $v['money']; ?></td>
                                    <td class="text-left"><?php echo $v['taxfee']; ?></td>
                                    <td class="text-left"><?php if($v['type']==1): ?>FISH币<?php else: ?>虾虾币<?php endif; ?></td>                              
                                    <td class="text-left">                                         
                                        <?php echo $tx_status_arr[$v['status']]; ?> 
                                    </td>                                    
                                    <td class="text-left">
                                        <a href="<?php echo url('shop/User/editWithdrawals',array('id'=>$v['id'],'p'=>\think\Request::instance()->param('p'))); ?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <?php if(in_array($v['status'],array(0,2))): ?>
                                            <a href="javascript:void(0);" onclick="del('<?php echo $v["id"]; ?>')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                
                <div class="row">
                    <div class="col-sm-6 text-left"></div>
                    <div class="col-sm-6 text-right"><?php echo $show; ?></div>
                </div>
          
          </div>
        </div>
      </div>
    </div>
    <!-- /.row --> 
  </section>
  <!-- /.content --> 
</div>
<!-- /.content-wrapper --> 
 <script>
 // 删除操作
function del(id)
{
	if(!confirm('确定要删除吗?'))
		return false;		
		$.ajax({
			url:"<?php echo url('user/delWithdrawals'); ?>?id="+id,
			success: function(v){	
                            var v =  eval('('+v+')');                                 
                            if(v.hasOwnProperty('status') && (v.status == 1))
                               location.href='<?php echo url('shop/User/withdrawals'); ?>';
                            else                                
			        layer.msg(v.msg, {icon: 2,time: 1000}); //alert(v.msg);
			}
		}); 
	 return false;
}
 
$(document).ready(function() {
	$('#start_time').daterangepicker({
		format:"YYYY/MM/DD",
		singleDatePicker: false,
		showDropdowns: true,
		minDate:'2016/01/01',
		maxDate:'2030/01/01',
		startDate:'2016/01/01',
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
</body>
</html>