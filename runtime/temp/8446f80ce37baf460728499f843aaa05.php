<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"/www/wwwroot/jr.yiyangkang.cn/public/../application/shop/view/user/detail.html";i:1554712286;s:70:"/www/wwwroot/jr.yiyangkang.cn/application/shop/view/public/header.html";i:1554712283;s:45:"../application/common/view/public/header.html";i:1554712305;}*/ ?>
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
    <!--<div class="container-fluid">-->
    <div class="row">
      <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3 class="panel-title"><i class="fa fa-list"></i> 用户信息</h3>
            </div>
            <div class="ibox-content">
                <form action="" method="post" onsubmit="return checkUserUpdate(this);">
                    <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td class="col-sm-2">会员昵称:</td>
                        <td ><input type="text" class="form-control" name="nickname" value="<?php echo $user['nickname']; ?>"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>用户福分:</td>
                        <td><?php echo $user['pay_points']; ?> <span style="margin-left:100px;"></span></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>账户推广财分:</td>
                        <td><?php echo $user['user_money']; ?> <span style="margin-left:100px;"></span></td>
                        <td></td>
                    </tr>
                     <tr>
                        <td>账户SHRIMP:</td>
                        <td><?php echo $user['doge_money']; ?> <span style="margin-left:100px;"></span></td>
                        <td></td>
                    </tr>
                     <tr>
                        <td>账户FISH:</td>
                        <td><?php echo $user['pig_currency']; ?> <span style="margin-left:100px;"></span></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>会员等级:</td>
                        <td>
                            <select name="level" class="form-control">
                                <option value="">请选择</option>
                                <?php if(is_array($level) || $level instanceof \think\Collection || $level instanceof \think\Paginator): $i = 0; $__LIST__ = $level;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo $vo['level_id']; ?>" <?php if($vo['level_id'] == $user['level']): ?>selected<?php endif; ?>><?php echo $vo['level_name']; ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                        <td>会员等级</td>
                    </tr>
                    <tr>
                        <td>登录密码密码:</td>
                        <td><input type="password" class="form-control" name="password"></td>
                        <td>留空表示不修改密码</td>
                    </tr>
                    <tr>
                        <td>确认登录密码:</td>
                        <td><input type="password" class="form-control" name="password2" ></td>
                        <td>留空表示不修改密码</td>
                    </tr>
                    <tr>
                        <td>二级密码:</td>
                        <td><input type="password" class="form-control" name="paypwd" ></td>
                        <td>留空表示不修改密码</td>
                    </tr>
                    <tr>
                        <td>确认二级密码:</td>
                        <td><input type="password" class="form-control" name="paypwd2" ></td>
                        <td>留空表示不修改密码</td>
                    </tr>
                <!--     <tr>
                        <td>性别:</td>
                        <td id="order-status">
                            <input name="sex" type="radio" value="0" <?php if($user['sex'] == 0): ?>checked<?php endif; ?> >保密
                            <input name="sex" type="radio" value="1" <?php if($user['sex'] == 1): ?>checked<?php endif; ?> >男
                            <input name="sex" type="radio" value="2" <?php if($user['sex'] == 2): ?>checked<?php endif; ?> >女
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>优惠码:</td>
                        <td>
                            <input class="form-control" type="text" name="code" value="<?php echo $user['code']; ?>">
                        </td>
                        <td></td>
                    </tr> -->
                    
                    <tr>
                        <td>手机:</td>
                        <td>
                            <input type="text" class="form-control" name="mobile" value="<?php echo $user['mobile']; ?>">
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>身份证号:</td>
                        <td>
                            <input type="text" class="form-control" name="identity" value="<?php echo $user['identity']; ?>">
                        </td>
                        <td></td>
                    </tr>
                   <!--  <tr>
                        <td>排单:</td>
                        <td>
                            <input type="text" class="form-control" name="rule_sort" value="<?php echo $user['rule_sort']; ?>">
                        </td>
                        <td>0为禁止排单</td>
                    </tr>-->
                    <tr>
                        <td>冻结用户:</td>
                        <td>
                            <input name="is_lock" type="radio" value="1" <?php if($user['is_lock'] == 1): ?>checked<?php endif; ?> >是
                            <input name="is_lock" type="radio" value="0" <?php if($user['is_lock'] == 0): ?>checked<?php endif; ?> >否
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>是否为管理员:</td>
                        <td>
                            <input name="is_admin" type="radio" value="1" <?php if($user['is_admin'] == 1): ?>checked<?php endif; ?> >是
                            <input name="is_admin" type="radio" value="0" <?php if($user['is_admin'] == 0): ?>checked<?php endif; ?> >否
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>注册时间:</td>
                        <td>
                            <?php echo date('Y-m-d H:i',$user['reg_time']); ?>
                        </td>
                        <td></td>
                    </tr>
                        
                  <!--   <tr>
                        <td>是否分销:</td>
                        <td id="order-status">                            
                            <input name="is_distribut" type="radio" value="1" <?php if($user['is_distribut'] == 1): ?>checked<?php endif; ?> >是
                            <input name="is_distribut" type="radio" value="0" <?php if($user['is_distribut'] == 0): ?>checked<?php endif; ?> >否
                        </td>
                        <td></td>
                    </tr>  -->                   
                    <tr>
                        <table  class="table table-bordered">
                            
                           <!--  <tr>
                                    <td>用户余额:</td>
                                    <td><?php echo $user['user_money']; ?></td>                                
                                    <td>累积分佣金额:</td>
                                    <td><?php echo $user['distribut_money']; ?></td>    
                                </tr>                            
                            
                             <tr>
                                <td>上一级编号</td>
                                    <td>                            
                                        <?php if($user["first_leader"] > 0): ?>
                                                <a href="<?php echo url('detail',array('id'=>$user['first_leader'])); ?>"><?php echo $user['first_leader']; ?></a>
                                        <?php else: ?>
                                                <?php echo $user['first_leader']; endif; ?>                           
                                    </td>                                
                                    <td>一级下线数</td>
                                    <td><?php echo $user['first_lower']; ?></td>    
                                </tr>
                                
                                <tr>
                                    <td>上二级编号</td>
                                    <td>                            
                                        <?php if($user["second_leader"] > 0): ?>
                                                <a href="<?php echo url('detail',array('id'=>$user['second_leader'])); ?>"><?php echo $user['second_leader']; ?></a>
                                        <?php else: ?>
                                                <?php echo $user['second_leader']; endif; ?>                           
                                    </td>
                                    <td>二级下线数</td>
                                    <td><?php echo $user['second_lower']; ?></td>                                
                                </tr>
                                <tr>
                                    <td>上三级编号</td>
                                    <td>                            
                                        <?php if($user["third_leader"] > 0): ?>
                                                <a href="<?php echo url('detail',array('id'=>$user['third_leader'])); ?>"><?php echo $user['third_leader']; ?></a>
                                        <?php else: ?>
                                                <?php echo $user['third_leader']; endif; ?>                           
                                    </td>                                 
                                    <td>三级下线数</td>
                                    <td><?php echo $user['third_lower']; ?></td>                                   
                           </tr>  -->                   
                        </table>
                    </tr>             
                    <tr>
                        <td></td>
                        <td>
                            <button type="submit" class="btn btn-info">
                                <i class="ace-icon fa fa-check bigger-110"></i> 保存
                            </button>
                            <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default pull-right" data-original-title="返回"><i class="fa fa-reply"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </form>

            </div>
        </div>
 	  </div> 
    </div>    <!-- /.content -->
   </section>
</div>
<script>
    function checkUserUpdate(){
        var mobile = $('input[name="mobile"]').val();
        var password = $('input[name="password"]').val();
        var password2 = $('input[name="password2"]').val();
        var pwd = $('input[name="paypwd"]').val();
        var pwd2 = $('input[name="paypwd2"]').val();

        var error ='';
        if(password != password2){
            error += "两次密码不一样\n";
        }
        if(pwd != pwd2){
            error += "两次二级密码不一样\n";
        }
        if(!checkMobile(mobile)){
            error += "手机号码填写有误\n";
        }
        if(error){
            layer.alert(error, {icon: 2});  //alert(error);
            return false;
        }
        return true;

    }
</script>

</body>
</html>