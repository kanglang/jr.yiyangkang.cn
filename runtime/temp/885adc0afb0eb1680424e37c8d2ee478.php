<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"/home/xgh/zouzebin/public/../application/home/view/file/userpics.html";i:1552356843;}*/ ?>

<ul class="upload_piclist">

	<?php if(is_array($picList) || $picList instanceof \think\Collection || $picList instanceof \think\Paginator): $i = 0; $__LIST__ = $picList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
		<li class="upload-pre-item22" data-id="<?php echo $vo['id']; ?>" onClick="toggleCheck(this)">
		<?php if(empty($type)): ?>
			<img src="<?php echo get_cover_url($vo['id'],1); ?>" width="100" height="100"/>
		<?php else: ?>
			<img src="<?php echo $vo['cover_url']; ?>" width="100" height="100"/>
		<?php endif; ?>

		<span class="ck-ico"></span><input type="hidden" value="<?php echo $vo['id']; ?>"/>

		</li>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</ul>

