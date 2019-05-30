<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:78:"/www/wwwroot/pig.77cco.com/public/../application/admin/view/article/index.html";i:1554712249;s:68:"/www/wwwroot/pig.77cco.com/application/admin/view/public/header.html";i:1554712249;s:45:"../application/common/view/public/header.html";i:1554712305;s:68:"/www/wwwroot/pig.77cco.com/application/admin/view/public/footer.html";i:1554712249;s:45:"../application/common/view/public/footer.html";i:1554712305;}*/ ?>
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

<link rel="stylesheet" type="text/css" media="all" href="__CSS__/plugins/datapicker/datepicker3.css" />
<script type="text/javascript" src="__JS__/plugins/datapicker/bootstrap-datepicker.js"></script>

<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- Panel Other -->
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>文章列表</h5>
        </div>
        <div class="ibox-content">
            <!--搜索框开始-->           
            <div class="row">
                <div class="col-sm-12">   
                <div  class="col-sm-2" style="width: 100px">
                    <div class="input-group" >  
                        <a href="<?php echo url('add_article'); ?>"><button class="btn btn-primary" type="button">发布文章</button></a> 
                    </div>
                </div>                                            
                    <form name="admin_list_sea" class="form-search" method="get" action="<?php echo url('index'); ?>">
                        <div class="col-sm-3" id="data_5">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="input-mini form-control" placeholder="开始时间" name="start_time" id="start_time" value="<?php echo \think\Request::instance()->param('start_time'); ?>"/>
                                <span class="input-group-addon">-</span>
                                <input type="text" class="input-mini form-control" placeholder="结束时间" name="end_time" id="end_time" value="<?php echo \think\Request::instance()->param('end_time'); ?>" />
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <select class="form-control m-b chosen-select" name="cate_id" id="cate_id">
                                <option value="">==请选择分类==</option>
                                <?php if(!empty($cate)): if(is_array($cate) || $cate instanceof \think\Collection || $cate instanceof \think\Paginator): if( count($cate)==0 ) : echo "" ;else: foreach($cate as $key=>$vo): ?>
                                        <option value="<?php echo $vo['id']; ?>" <?php if(\think\Request::instance()->param('cate_id')==$vo['id']): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" id="key" class="form-control" name="key" value="<?php echo \think\Request::instance()->param('key'); ?>" placeholder="输入需查询的文章名称" />   
                                <span class="input-group-btn"> 
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button> 
                                </span>
                            </div>
                        </div>
                    </form>                         
                </div>
            </div>
            <!--搜索框结束-->
            <div class="hr-line-dashed"></div>
            <div class="example-wrap">
                <div class="example">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="long-tr">
                                <th width="3%">ID</th>
                                <th width="15%">标题</th>
                                <th width="5%">所属分类</th>
                                <th width="5%">封面</th>
                                <th width="10%">创建时间</th>
                                <th width="10%">更新时间</th>
                                <th width="5%">状态</th>
                                <th width="5%">是否推荐</th>
                                <th width="10%">操作</th>
                            </tr>
                        </thead>
                            <?php if(is_array($list_data) || $list_data instanceof \think\Collection || $list_data instanceof \think\Paginator): $i = 0; $__LIST__ = $list_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <tr class="long-td">
                                    <td><?php echo $vo['id']; ?></td>
                                    <td><?php echo $vo['title']; ?></td>
                                    <td><?php echo $vo['name']; ?></td>                                
                                    <td><img src="<?php echo $vo['photo']; ?>" style="height: 30px" onerror="this.src='/static/admin/images/no_img.jpg'"/></td> 
                                    <td><?php echo $vo['create_time']; ?></td> 
                                    <td><?php echo $vo['update_time']; ?></td>
                                    <td>
                                        <?php if(($vo['status']==1)): ?>
                                            <a href="javascript:;" onclick="article_state(<?php echo $vo['id']; ?>);">
                                                <div id="zt<?php echo $vo['id']; ?>"><span class="label label-info">开启</span></div>
                                            </a>
                                        <?php else: ?>
                                            <a href="javascript:;" onclick="article_state(<?php echo $vo['id']; ?>);">
                                                <div id="zt<?php echo $vo['id']; ?>"><span class="label label-danger">禁用</span></div>
                                            </a>
                                        <?php endif; ?>
                                    </td> 
                                    <td>
                                        <?php if(($vo['is_tui']==1)): ?>
                                            是
                                        <?php else: ?>
                                            否
                                        <?php endif; ?>
                                    </td>                               
                                    <td>

                                        <?php if(($vo['type']==1)): ?>
                                            <a href="<?php echo url('edit_article',['id'=>$vo['id']]); ?>" class="btn btn-primary btn-xs">
                                            <i class="fa fa-paste"></i> 编辑</a>
                                        <?php else: ?>
                                            <a href="<?php echo url('edit_video',['id'=>$vo['id']]); ?>" class="btn btn-primary btn-xs">
                                            <i class="fa fa-paste"></i> 编辑</a>
                                        <?php endif; ?>
                                            &nbsp;&nbsp;
                                        <a href="javascript:;" onclick="del_article(<?php echo $vo['id']; ?>)" class="btn btn-danger btn-xs">
                                            <i class="fa fa-trash-o"></i> 删除</a>
                                    </td>
                                </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
           
                        <tbody id="list-content"></tbody>
                    </table><!-- 分页 -->
                                <div id="Pages">  <?php echo $page; ?> </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>



<script src="__JS__/bootstrap.min.js?v=3.3.6"></script>
<script src="__JS__/content.min.js?v=1.0.0"></script>
<script src="__JS__/plugins/chosen/chosen.jquery.js"></script>
<script src="__JS__/plugins/iCheck/icheck.min.js"></script>
<script src="__JS__/plugins/layer/laydate/laydate.js"></script>
<script src="__JS__/plugins/switchery/switchery.js"></script><!--IOS开关样式-->
<script src="__JS__/jquery.form.js"></script>
<script src="__JS__/layer/layer.js"></script>
<script src="__JS__/laypage/laypage.js"></script>
<script src="__JS__/laytpl/laytpl.js"></script>
<!-- <script src="__JS__/public.js"></script> -->
<script>
    $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>

<script type="text/javascript">

$('#data_5 .input-daterange').datepicker({
        format:'yyyy-mm-dd',
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        minViewMode:0
    });

function Ajaxpage(){
    location.reload(true);
}

//删除文章
function del_article(id){
    lunhui.confirm(id,'<?php echo url("del_article"); ?>');

}

//文章状态
function article_state(id){
    lunhui.status(id,'<?php echo url("article_state"); ?>');
}

</script>
</body>
</html>