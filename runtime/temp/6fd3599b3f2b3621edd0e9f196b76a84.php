<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:79:"/www/wwwroot/jr.yiyangkang.cn/public/../application/shop/view/goods/_goods.html";i:1554712289;s:70:"/www/wwwroot/jr.yiyangkang.cn/application/shop/view/public/header.html";i:1554712283;s:45:"../application/common/view/public/header.html";i:1554712305;}*/ ?>
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

<!--物流配置 css -start-->
<style>
    ul.group-list {
        width: 96%;min-width: 1000px; margin: auto 5px;list-style: disc outside none;
    }
    ul.group-list li {
        white-space: nowrap;float: left;
        width: 150px; height: 25px;
        padding: 3px 5px;list-style-type: none;
        list-style-position: outside;border: 0px;margin: 0px;
    }
</style>
<!--物流配置 css -end-->


<div class="wrapper wrapper-content animated fadeInRight">
    
    <section class="content">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>

            </div>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3 class="panel-title"><i class="fa fa-list"></i>商品详情</h3>
                </div>
                <div class="ibox-content">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="javascript:void(0);" data-index="1" class="tab current"><span>通用信息</span></a></li>
            <!-- <li><a href="#tab_goods_desc" data-toggle="tab">描述信息</a></li>-->
                        <li><a href="javascript:void(0);" data-index="2" class="tab "><span>商品相册</span></a></li>
                        <li><a href="javascript:void(0);" data-index="3" class="tab "><span>商品模型</span></a></li>                       
                        <li><a href="javascript:void(0);" data-index="4" class="tab "><span>商品物流</span></a></li>
                        <!--<li><a href="javascript:void(0);" data-index="5" class="tab "><span>积分折扣</span></a></li>-->
                    </ul>
                    <!--表单数据-->
                    <form method="post" id="addEditGoodsForm">
                    
                        <!--通用信息-->
                    <div class="tab-content">                     
                        <div class="tab-pane active tab_div_1" id="tab_tongyong">
                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>商品名称:</td>
                                    <td>
                                        <input type="text" value="<?php echo $goodsInfo['goods_name']; ?>" name="goods_name" class="form-control" style="width:550px;"/>
                                        <span id="err_goods_name" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>商品简介:</td>
                                    <td>
                                      <textarea rows="3" cols="80" name="goods_remark"><?php echo $goodsInfo['goods_remark']; ?></textarea>
                                        <span id="err_goods_remark" style="color:#F00; display:none;"></span>
                                         
                                    </td>                                                                       
                                </tr>
                                <tr>
                                    <td>商品货号</td>
                                    <td>                                                                               
                                        <input type="text" value="<?php echo $goodsInfo['goods_sn']; ?>" name="goods_sn" class="form-control" style="width:350px;"/>
                                        <span id="err_goods_sn" style="color:#F00; display:none;"></span>
                                    </td>
                                </tr>
                                <tr style="display: none">
                                    <td>SPU</td>
                                    <td>                                                                               
                                        <input type="text" value="<?php echo $goodsInfo['spu']; ?>" name="spu" class="form-control" style="width:350px;"/>
                                        <span id="err_goods_spu" style="color:#F00; display:none;"></span>
                                    </td>
                                </tr>
                                <tr style="display: none">
                                    <td>SKU</td>
                                    <td>                                                                               
                                        <input type="text" value="<?php echo $goodsInfo['sku']; ?>" name="sku" class="form-control" style="width:350px;"/>
                                        <span id="err_goods_sku" style="color:#F00; display:none;"></span>
                                    </td>
                                </tr>                                
                                <tr>
                                    <td>商品分类:</td>
                                    <td>
                                      <div class="col-xs-3">
                                      <select name="cat_id" id="cat_id" onchange="get_category(this.value,'cat_id_2','0');" class="form-control" style="width:250px;margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>                                      
                                             <?php if(is_array($cat_list) || $cat_list instanceof \think\Collection || $cat_list instanceof \think\Paginator): if( count($cat_list)==0 ) : echo "" ;else: foreach($cat_list as $k=>$v): ?>                                                                                          
                                               <option value="<?php echo $v['id']; ?>" <?php if(isset($level_cat['1']) && $v['id'] == $level_cat['1']): ?>selected="selected"<?php endif; ?> >
                                                  <?php echo $v['name']; ?>
                                               </option>
                                             <?php endforeach; endif; else: echo "" ;endif; ?>
                                      </select>
                                      </div>
                                      <div class="col-xs-3">
                                      <select name="cat_id_2" id="cat_id_2" onchange="get_category(this.value,'cat_id_3','0');" class="form-control" style="width:250px;margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>
                                      </select>  
                                      </div>
                                      <div class="col-xs-3">                        
                                      <select name="cat_id_3" id="cat_id_3" class="form-control" style="width:250px;margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>
                                      </select> 
                                      </div>    
                                      <span id="err_cat_id" style="color:#F00; display:none;"></span>                                 
                                    </td>
                                </tr>                                 
                                <tr>
                                    <td>扩展分类:</td>
                                    <td>
                                      <div class="col-xs-3">
                                      <select name="extend_cat_id" id="extend_cat_id" onchange="get_category(this.value,'extend_cat_id_2','0');" class="form-control" style="width:250px;margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>                                      
                                             <?php if(is_array($cat_list) || $cat_list instanceof \think\Collection || $cat_list instanceof \think\Paginator): if( count($cat_list)==0 ) : echo "" ;else: foreach($cat_list as $k=>$v): ?>                                                                                          
                                               <option value="<?php echo $v['id']; ?>" <?php if(isset($level_cat2['1']) && $v['id'] == $level_cat2['1']): ?>selected="selected"<?php endif; ?> >
                                                  <?php echo $v['name']; ?>
                                               </option>
                                             <?php endforeach; endif; else: echo "" ;endif; ?>
                                      </select>
                                      </div>
                                      <div class="col-xs-3">
                                      <select name="extend_cat_id_2" id="extend_cat_id_2" onchange="get_category(this.value,'extend_cat_id_3','0');" class="form-control" style="width:250px;margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>
                                      </select>  
                                      </div>
                                      <div class="col-xs-3">                        
                                      <select name="extend_cat_id_3" id="extend_cat_id_3" class="form-control" style="width:250px;margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>
                                      </select> 
                                      </div>    
                                      <span id="err_cat_id" style="color:#F00; display:none;"></span>                                 
                                    </td>
                                </tr>                                 
                                
                                <tr>
                                    <td>商品品牌:</td>
                                    <td>
                  <select name="brand_id" id="brand_id" class="form-control" style="width:250px;">
                                           <option value="">所有品牌</option>
                                            <?php if(is_array($brandList) || $brandList instanceof \think\Collection || $brandList instanceof \think\Paginator): if( count($brandList)==0 ) : echo "" ;else: foreach($brandList as $k=>$v): ?>
                                               <option value="<?php echo $v['id']; ?>"  <?php if($v['id'] == $goodsInfo['brand_id']): ?>selected="selected"<?php endif; ?>>
                                                  <?php echo $v['name']; ?>
                                               </option>
                                           <?php endforeach; endif; else: echo "" ;endif; ?>
                                      </select>                                    
                                    </td>
                                </tr>
                                <tr>
                                    <td>供应商:</td>
                                    <td>
                                        <select name="suppliers_id" id="suppliers_id" class="form-control" style="width:250px;">
                                            <option value="0">不指定供应商属于本店商品</option>
                                            <?php if(is_array($suppliersList) || $suppliersList instanceof \think\Collection || $suppliersList instanceof \think\Paginator): if( count($suppliersList)==0 ) : echo "" ;else: foreach($suppliersList as $k=>$v): ?>
                                                <option value="<?php echo $v['suppliers_id']; ?>"  <?php if($v['suppliers_id'] == $goodsInfo['suppliers_id']): ?>selected="selected"<?php endif; ?>>
                                                <?php echo $v['suppliers_name']; ?>
                                                </option>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>本店售价:</td>
                                    <td>
                                        <input type="text" value="<?php echo $goodsInfo['shop_price']; ?>" name="shop_price" class="form-control" style="width:150px;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" />
                                        <span id="err_shop_price" style="color:#F00; display:none;"></span>                                                 
                                    </td>
                                </tr>  
                                <tr>
                                    <td>市场价:</td>
                                    <td>
                                        <input type="text" value="<?php echo $goodsInfo['market_price']; ?>" name="market_price" class="form-control" style="width:150px;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" />
                                        <span id="err_market_price" style="color:#F00; display:none;"></span>                                                 
                                    </td>
                                </tr>  
                                <!--<tr>-->
                                    <!--<td>总代理价:</td>-->
                                    <!--<td>-->
                                        <!--<input type="text" value="<?php echo $goodsInfo['level0_price']; ?>" name="level0_price" class="form-control" style="width:150px;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" />-->
                                        <!--<span id="err_level0_price" style="color:#F00; display:none"></span>                                                  -->
                                    <!--</td>-->
                                <!--</tr>-->
                                <!--<tr>-->
                                    <!--<td>一级代理价:</td>-->
                                    <!--<td>-->
                                        <!--<input type="text" value="<?php echo $goodsInfo['level1_price']; ?>" name="level1_price" class="form-control" style="width:150px;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" />-->
                                        <!--<span id="err_level1_price" style="color:#F00; display:none"></span>                                                  -->
                                    <!--</td>-->
                                <!--</tr>-->
                                <!--<tr>-->
                                    <!--<td>二级代理价:</td>-->
                                    <!--<td>-->
                                        <!--<input type="text" value="<?php echo $goodsInfo['level2_price']; ?>" name="level2_price" class="form-control" style="width:150px;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" />-->
                                        <!--<span id="err_level2_price" style="color:#F00; display:none"></span>                                                  -->
                                    <!--</td>-->
                                <!--</tr>-->
                                <tr>
                                    <td>成本价:</td>
                                    <td>
                                        <input type="text" value="<?php echo $goodsInfo['cost_price']; ?>" name="cost_price" class="form-control" style="width:150px;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" />
                                        <span id="err_cost_price" style="color:#F00; display:none"></span>                                                  
                                    </td>
                                </tr>       
                                <tr>
                                    <td>佣金:</td>
                                    <td>
                                        <input type="text" value="<?php echo $goodsInfo['commission']; ?>" name="commission" class="form-control" style="width:150px;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" />
                                        用于分销的分成金额
                                    </td>
                                </tr>                                  
                                <tr>
                                    <td>上传商品图片:</td>
                                    <td>
                              

                                        <div class="controls uploadrow2" data-max="1" title="点击修改图片" rel="p_cover">
                                                <input type="hidden" id="cover_id_p_cover" name="original_img" value="<?php echo $goodsInfo['original_img']; ?>"  data-callback="uploadImgCallback">
                                                <div class="upload-img-box" rel="img"  >
                                                  <div class="upload-pre-item2">
                                                        <img width="100" height="100" src="<?php echo $goodsInfo['original_img']; ?>"/>v>
                                                    <em class="edit_img_icon">&nbsp;</em>
                                                </div>
                                          </div>

                                    </td>

                                    

                                </tr>                                 
                                <tr>
                                    <td>商品重量:</td>
                                    <td>
                                        <input type="text" class="form-control" style="width:150px;" value="<?php echo $goodsInfo['weight']; ?>" name="weight" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" />
                                        &nbsp;克 (以克为单位)                                        
                                        <span id="err_weight" style="color:#F00; display:none;"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>是否包邮:</td>
                                    <td>
                                        是:<input type="radio" <?php if($goodsInfo['is_free_shipping'] == 1): ?>checked="checked"<?php endif; ?> value="1" name="is_free_shipping" /> 
                                        否:<input type="radio" <?php if($goodsInfo['is_free_shipping'] == 0): ?>checked="checked"<?php endif; ?> value="0" name="is_free_shipping" /> 
                                    </td>
                                </tr>                                
                                <tr>
                                    <td>库存数量:</td>
                                    <td>
                                        <?php if($goodsInfo['goods_id'] > 0): ?>
                                            <input type="text" value="<?php echo $goodsInfo['store_count']; ?>" class="form-control" style="width:150px;" name="store_count" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" />
                                        <?php else: ?>
                                            <input type="text" value="<?php echo (isset($tpshop_config['basic_default_storage']) && ($tpshop_config['basic_default_storage'] !== '')?$tpshop_config['basic_default_storage']:''); ?>" class="form-control" style="width:150px;" name="store_count" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" />                                         
                                        <?php endif; ?>
                                        
                                        <span id="err_store_count" style="color:#F00; display:none;"></span>                                                  
                                    </td>
                                </tr>
                                <tr>
                                    <td>赠送积分:</td>
                                    <td>
                                        <input type="text" class="form-control" style="width:150px;" value="<?php echo $goodsInfo['give_integral']; ?>" name="give_integral" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" />
                                        <span id="err_give_integral" style="color:#F00; display:none;"></span>                                                  
                                    </td>
                                </tr>
                                <tr>
                                    <td>兑换积分:</td>
                                    <td>
                                        <input type="text" class="form-control" style="width:150px;" value="<?php echo $goodsInfo['exchange_integral']; ?>" name="exchange_integral" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" />
                                        <span id="err_exchange_integral" style="color:#F00; display:none;"></span>
                                    </td>
                                </tr>
                                <!--
                                <tr>
                                    <td>设置:</td>
                                    <td>
                                      <input type="checkbox" checked="checked" value="<?php echo $goodsInfo['is_on_sale']; ?>" name="is_on_sale"/> 上架&nbsp;&nbsp;
                                  <input type="checkbox" checked="checked" value="<?php echo $goodsInfo['is_free_shipping']; ?>" name="is_free_shipping"/> 包邮&nbsp;&nbsp;
                                        <input type="checkbox" checked="checked" value="<?php echo $goodsInfo['is_recommend']; ?>" name="is_recommend"/>推荐&nbsp;&nbsp;
                                        <input type="checkbox" checked="checked" value="<?php echo $goodsInfo['is_new']; ?>" name="is_new"/>新品&nbsp;&nbsp;
                                    </td>
                                </tr>
                                -->
                                <tr>
                                    <td>商品关键词:</td>
                                    <td>
                                        <input type="text" class="form-control" style="width:550px;" value="<?php echo $goodsInfo['keywords']; ?>" name="keywords"/>用空格分隔
                                        <span id="err_keywords" style="color:#F00; display:none;"></span>
                                    </td>
                                </tr>                                    
                                <tr>
                                    <td>商品详情描述:</td>
                                    <td width="85%">
                                      <textarea name="goods_content" style="width:90%" id="myEditor"><?php echo $goodsInfo['goods_content']; ?></textarea>
                                      <?php echo hook('adminArticleEdit', array('name'=>'goods_content','value'=>'')); ?>

                                    </td>                                                                       
                                </tr>   
                                </tbody>                                
                                </table>
                        </div>
                         <!--其他信息-->
                         
                        <!-- 商品相册-->
                        <div class="tab-pane tab_div_2" id="tab_goods_images">
                          图片推荐比例：250*250
                            <table class="table table-bordered">
                                <tbody>
                                <tr>                                    
                                    <td>                                    
                                    <?php if(is_array($goodsImages) || $goodsImages instanceof \think\Collection || $goodsImages instanceof \think\Paginator): if( count($goodsImages)==0 ) : echo "" ;else: foreach($goodsImages as $k=>$vo): ?>
                                        <div style="width:100px; text-align:center; margin: 5px; display:inline-block;" class="goods_xc">
                                            <input type="hidden" value="<?php echo $vo['image_url']; ?>" name="goods_images[]">
                                            <a onclick="" href="<?php echo $vo['image_url']; ?>" target="_blank"><img width="100" height="100" src="<?php echo $vo['image_url']; ?>"></a>
                                            <br>
                                            <a href="javascript:void(0)" onclick="ClearPicArr2(this,'<?php echo (isset($vo['image_url']) && ($vo['image_url'] !== '')?$vo['image_url']:""); ?>')">删除</a>
                                        </div>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                    
                                        <div style="display: inline-block;" class="goods_xc controls uploadrow2" data-max="1" title="点击上传图片" rel="p_cover2">
                                            <input type="hidden" name="goods_images[]" value="" />
                                            <input type="hidden" id="cover_id_p_cover2" name="goods_images2" value=""  data-callback="uploadImgCallback2">
                                        </div>
                                        <!--隐藏div-->
                                        <div class="goods_xc" style="width:100px; text-align:center; margin: 5px; display:none;">
                                            <input type="hidden" name="goods_images[]" value="" />
                                            <a href="javascript:void(0);"><img src="" width="100" height="100" /></a>
                                            <a href="javascript:void(0)" onclick="ClearPicArr2(this,'<?php echo (isset($vo['image_url']) && ($vo['image_url'] !== '')?$vo['image_url']:""); ?>')">删除</a>
                                        </div>                                        
                                    </td>
                                </tr>                                              
                                </tbody>
                            </table>
                        </div>
                         <!--商品相册--> 
   
                        <!-- 商品规格-->
                        <div class="tab-pane tab_div_3" id="tab_goods_spec">
                            <table class="table table-bordered" id="goods_spec_table">                                
                                <tr>
                                    <td>商品模型:</td>
                                    <td>                                        
                                      <select name="goods_type" id="spec_type" class="form-control" style="width:250px;">
                                        <option value="0">选择商品模型</option>
                                        <?php if(is_array($goodsType) || $goodsType instanceof \think\Collection || $goodsType instanceof \think\Paginator): if( count($goodsType)==0 ) : echo "" ;else: foreach($goodsType as $k=>$vo): ?>
                                            <option value="<?php echo $vo['id']; ?>"<?php if($goodsInfo['goods_type'] == $vo['id']): ?> selected="selected" <?php endif; ?> ><?php echo $vo['name']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                      </select>
                                    </td>
                                </tr>                            
                            </table>
                            <div class="row">
                              <!-- ajax 返回规格-->
                              <div id="ajax_spec_data" class="col-xs-8" style="border:1px solid #ddd;"></div>
                              <div id="" class="col-xs-4" style="border:1px solid #ddd;">
                                  <table class="table table-bordered" id="goods_attr_table">                                
                                    <tr>
                                        <td><b>商品属性</b>：</td>
                                    </tr>                                
                                </table>
                              </div>
                            </div>
                        </div>
                        <!-- 商品规格-->

                        <!-- 商品属性-->

                        <!-- 商品属性-->

                        <!-- 商品物流-->
                        <div class="tab-pane tab_div_4" id="tab_goods_shipping">
                            <h4><b>物流配送：</b><input type="checkbox" onclick="choosebox(this)">全选</h4>
                            <table class="table table-bordered table-hover" id="goods_shipping_table">
                                <?php if(is_array($plugin_shipping) || $plugin_shipping instanceof \think\Collection || $plugin_shipping instanceof \think\Paginator): if( count($plugin_shipping)==0 ) : echo "" ;else: foreach($plugin_shipping as $kk=>$shipping): ?>
                                    <tr>
                                        <td class="title left" style="padding-right:50px;">
                                            <b><?php echo $shipping['name']; ?>：</b>
                                            <label class="right"><input type="checkbox" value="1" cka="mod-<?php echo $kk; ?>">全选</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <ul class="group-list">
                                                <?php if(is_array($shipping_area) || $shipping_area instanceof \think\Collection || $shipping_area instanceof \think\Paginator): if( count($shipping_area)==0 ) : echo "" ;else: foreach($shipping_area as $key=>$vv): if($vv['shipping_code'] == $shipping['code']): ?>
                                                        <li><label><input type="checkbox" name="shipping_area_ids[]" value="<?php echo $vv['shipping_area_id']; ?>" <?php if(in_array($vv['shipping_area_id'],$goods_shipping_area_ids)): ?>checked='checked='<?php endif; ?> ck="mod-<?php echo $kk; ?>"><?php echo $vv['shipping_area_name']; ?></label></li>
                                                    <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                                <div class="clear-both"></div>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </table>
                        </div>
                        <!-- 商品物流-->

                        <!--积分折扣-->
                    <!--<div class="tab-pane tab_div_5">
                          <dl class="row">
                              <dt class="tit">
                                  <label>价格阶梯</label>
                              </dt>
                              <dd class="opt">
                                  <div class="alisth0" id="alisth_0">
                                      单次购买个数达到<input type="text" class="input-number addprine" name="ladder_amount[]" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" style="width: 100px;">&nbsp;
                                      价格<input type="text" class="input-number addprine" name="ladder_price[]" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" style="width: 100px;">
                                      <a class="p_plus" href="javascript:;"><strong>[+]</strong></a>
                                  </div>
                              </dd>
                              <script>
                                  $(function(){
                                      $('.p_plus').click(function() {
                                          var html = "<div class='alisth'>"
                                                  + "单次购买个数达到"
                                                  + "<input type='text' class='input-number addprine' name='ladder_amount[]' style='width: 100px;' value=''/>"
                                                  + "&nbsp;&nbsp;价格"
                                                  + "<input type='text' class='input-number addprine' name='ladder_price[]' style='width: 100px;' value=''>"
                                                  + "<a class='p_plus' onclick='$(this).parent().remove();'>&nbsp;<strong>[-]</strong></a>"
                                                  + "</div>";
                                          $('#alisth_0').after(html);
                                      });
                                  })
                              </script>
                          </dl>  
                                      
                          <dl class="row">
                              <dt class="tit">
                                  <label for="record_no">赠送积分</label>
                              </dt>
                              <dd class="opt">
                                  <input type="text" value="" name="give_integral" class="t_mane" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')">  订单完成后赠送积分
                                  <span class="err" id="err_give_integral" style="color:#F00; display:none;"></span>
                              </dd>
                          </dl>  
                          
                          <dl class="row">
                              <dt class="tit">
                                  <label for="record_no">兑换积分</label>
                              </dt>
                              <dd class="opt">
                                  <input type="text" value="" name="exchange_integral" class="t_mane" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')">  如果设置0，则不支持积分抵扣
                                  <span class="err" id="err_exchange_integral" style="color:#F00; display:none;"></span>
                              </dd>
                          </dl>
                          
                      </div>-->

                    </div>
                    

                    <div class="pull-right">
                        <input type="hidden" name="goods_id" value="<?php echo $goodsInfo['goods_id']; ?>">
                        <input type="hidden" name="__token__" value="<?php echo \think\Request::instance()->token(); ?>" />                        
                        <button class="btn btn-primary" onclick="ajax_submit_form('addEditGoodsForm','<?php echo U('Goods/addEditGoods?is_ajax=1'); ?>');" title="" data-toggle="tooltip" type="button" data-original-title="保存">保存</button>
                    </div>
          </form><!--表单数据-->
                </div>
            </div>
        </div>    <!-- /.content -->
    </section>
</div>
<script>


    $(function(){
    
        //初始化上传图片插件
        initUploadImg();
    });

    function uploadImgCallback(name,id,src){
        $('.editing img').attr('src',src);
        $('#cover_id_p_cover').val(src);
        //$('.editing input[name="cover_id"]').val(id);
    }

    function uploadImgCallback2(name,id,src){
      console.log(name)

/* 
      var html = '<div class="goods_xc" style="width:100px; text-align:center; margin: 5px; display:inline-block;">'+
          '<input type="hidden" name="goods_images[]" value="'+src+'">'+
          '<a href="'+src+'" onclick="" target="_blank"><img src="'+src+'" width="100" height="100"></a>'+
          '<a href="javascript:void(0)" onclick="ClearPicArr2(this,'+src+')">删除</a>'+
      '</div>';
      console.log(html)
      $(".goods_xc:eq(0)").before(html);  // 插入一个 新图片
     */
    
      var  last_div = $(".goods_xc:last").prop("outerHTML");  
      $(".goods_xc:eq(0)").before(last_div);  // 插入一个 新图片
      $(".goods_xc:eq(0)").find('a:eq(0)').attr('href',src).attr('onclick','').attr('target', "_blank");// 修改他的链接地址
      $(".goods_xc:eq(0)").find('img').attr('src',src);// 修改他的图片路径
          $(".goods_xc:eq(0)").find('a:eq(1)').attr('onclick',"ClearPicArr2(this,'"+src+"')").text('删除');
      $(".goods_xc:eq(0)").find('input').val(src); // 设置隐藏域 要提交的值
      
      //除了最后一个
      $(".goods_xc:not(:last)").css('display','inline-block')
    }


    $(document).ready(function(){
        $(":checkbox[cka]").click(function(){
            var $cks = $(":checkbox[ck='"+$(this).attr("cka")+"']");
            if($(this).is(':checked')){
                $cks.each(function(){$(this).prop("checked",true);});
            }else{
                $cks.each(function(){$(this).removeAttr('checked');});
            }
        });
    });

    function choosebox(o){
        var vt = $(o).is(':checked');
        if(vt){
            $('input[type=checkbox]').prop('checked',vt);
        }else{
            $('input[type=checkbox]').removeAttr('checked');
        }
    }

 
    // 上传商品相册回调函数
    function call_back2(paths){
        
        var  last_div = $(".goods_xc:last").prop("outerHTML");  
        for (i=0;i<paths.length ;i++ )
        {                    
            $(".goods_xc:eq(0)").before(last_div);  // 插入一个 新图片
                $(".goods_xc:eq(0)").find('a:eq(0)').attr('href',paths[i]).attr('onclick','').attr('target', "_blank");// 修改他的链接地址
            $(".goods_xc:eq(0)").find('img').attr('src',paths[i]);// 修改他的图片路径
                $(".goods_xc:eq(0)").find('a:eq(1)').attr('onclick',"ClearPicArr2(this,'"+paths[i]+"')").text('删除');
            $(".goods_xc:eq(0)").find('input').val(paths[i]); // 设置隐藏域 要提交的值
        }          
    }
    /*
     * 上传之后删除组图input     
     * @access   public
     * @val      string  删除的图片input
     */
    function ClearPicArr2(obj,path)
    {
      $(obj).parent().remove(); //删除 html上的图片
      //return;
      $.ajax({
        type:'GET',
        url:"<?php echo U('shop/Uploadify/delupload'); ?>",
        data:{action:"del", filename:path},
        success:function(){
               $(obj).parent().remove(); // 删除完服务器的, 再删除 html上的图片        
        }
    });
    // 删除数据库记录
      $.ajax({
                    type:'GET',
                    url:"<?php echo U('shop/Goods/del_goods_images'); ?>",
                    data:{filename:path},
                    success:function(){
                          //     
                    }
    });   
    }
 


/** 以下 商品属性相关 js*/

// 属性输入框的加减事件
function addAttr(a)
{
  var attr = $(a).parent().parent().prop("outerHTML");  
  attr = attr.replace('addAttr','delAttr').replace('+','-');  
  $(a).parent().parent().after(attr);
}
// 属性输入框的加减事件
function delAttr(a)
{
   $(a).parent().parent().remove();
}
 

/** 以下 商品规格相关 js*/
$(document).ready(function(){ 
    // 商品模型切换时 ajax 调用  返回不同的属性输入框
    $("#spec_type").change(function(){        
        var goods_id = '<?php echo $goodsInfo['goods_id']; ?>';
        var spec_type = $(this).val();
            $.ajax({
                    type:'GET',
                    data:{goods_id:goods_id,spec_type:spec_type}, 
                    url:"<?php echo U('shop/Goods/ajaxGetSpecSelect'); ?>",
                    success:function(data){                            
                           $("#ajax_spec_data").html('')
                           $("#ajax_spec_data").append(data);
         ajaxGetSpecInput();  // 触发完  马上触发 规格输入框
                    }
            });           
            //商品类型切换时 ajax 调用  返回不同的属性输入框     
            $.ajax({
                 type:'GET',
                 data:{goods_id:goods_id,type_id:spec_type}, 
                 url:"/index.php/shop/Goods/ajaxGetAttrInput",
                 success:function(data){                            
                         $("#goods_attr_table tr:gt(0)").remove()
                         $("#goods_attr_table").append(data);
                 }        
           });
    });
  // 触发商品规格
  $("#spec_type").trigger('change'); 
  
    $("input[name='exchange_integral']").blur(function(){
        var shop_price = parseInt($("input[name='shop_price']").val());
        var exchange_integral = parseInt($(this).val());
        if (shop_price * 100 < exchange_integral) {
          
        }
    });
});

/** 以下是编辑时默认选中某个商品分类*/
$(document).ready(function(){
  <?php if(!empty($level_cat['2']) && $level_cat['2'] > 0): ?>
     // 商品分类第二个下拉菜单
     get_category('<?php echo $level_cat[1]; ?>','cat_id_2','<?php echo $level_cat[2]; ?>');  
  <?php endif; if(!empty($level_cat['3']) && $level_cat['3'] > 0): ?>
    // 商品分类第二个下拉菜单
     get_category('<?php echo $level_cat[2]; ?>','cat_id_3','<?php echo $level_cat[3]; ?>');   
  <?php endif; ?>

    //  扩展分类
  <?php if(!empty($level_cat2['2']) && $level_cat2['2'] > 0): ?>
     // 商品分类第二个下拉菜单
     get_category('<?php echo $level_cat2[1]; ?>','extend_cat_id_2','<?php echo $level_cat2[2]; ?>'); 
  <?php endif; if(!empty($level_cat2['3']) && $level_cat2['3'] > 0): ?>
    // 商品分类第二个下拉菜单
     get_category('<?php echo $level_cat2[2]; ?>','extend_cat_id_3','<?php echo $level_cat2[3]; ?>');  
  <?php endif; ?>

});


$(document).ready(function(){
     
        //插件切换列表
        $('.nav-tabs').find('.tab').click(function(){
            $('.nav-tabs').find('.tab').each(function(){
                $(this).parent().removeClass('active');
            });
            $(this).parent().addClass('active');
      var tab_index = $(this).data('index');      
      $(".tab_div_1, .tab_div_2, .tab_div_3, .tab_div_4, .tab_div_5").hide();     
      $(".tab_div_"+tab_index).show();
    });   
            
    });


</script>
</body>
</html>