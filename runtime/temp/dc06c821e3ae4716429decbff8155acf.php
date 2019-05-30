<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"/home/xgh/zouzebin/public/../application/shop/view/distribut/ajax_lower.html";i:1554117475;}*/ ?>
<ul>
<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $k=>$v): ?>  
        <li>
            <span class="tree_span" data-tab="<?php echo $v['user_level']; ?>" data-id="<?php echo $v['user_id']; ?>">
                 <i class="icon-folder-open"></i>
                 <?php echo $v['user_id']; ?>:
                 <?php if($v['nickname'] != null): ?>
                         <?php echo $v['nickname']; elseif($v['mobile'] != null): ?>
                     <?php echo $v['mobile']; endif; ?>

                <span style="color: red">
                    <?php echo $v['level']; ?> - <?php echo $v['top_count']; ?>
                </span>
             </span>                                                
        </li>
<?php endforeach; endif; else: echo "" ;endif; ?>
</ul>