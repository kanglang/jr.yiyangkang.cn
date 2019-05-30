<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 定义上传目录
define('UPLOAD_PATH', __DIR__ . '/../public');
// 定义应用缓存目录
define('RUNTIME_PATH', __DIR__ . '/../runtime/');
// 定义下载目录
define('DOWNLOAD_PATH',  __DIR__ . '/../public/uploads/download/');
// 定义data目录
define('DATA_PATH', __DIR__ . '/../data/');
// 开启调试模式
define('APP_DEBUG', true);

define('ZPSHOP_CACHE_TIME',1); // zpshop 缓存时间  31104000

define ( 'HTTP_PREFIX', isset ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] == 'on' ? 'https://' : 'http://' );
define ( 'SITE_DOMAIN', strip_tags ( $_SERVER ['HTTP_HOST'] ) );
define ( 'SITE_URL', HTTP_PREFIX . SITE_DOMAIN  );
//define ( 'API_URL', HTTP_PREFIX . SITE_DOMAIN.'/api/');
define ( 'API_URL', HTTP_PREFIX . SITE_DOMAIN.'/zhishirongyao/public/index.php/api/');

define ( 'DOWNLOAD_URL', SITE_URL . "/uploads/download/"  );
define ( 'IN_WEIXIN', false  );

define ( 'SYSTEM_TOKEN', 'weiphp' );
/**
 * 微信接入验证
 * 在入口进行验证而不是放到框架里验证，主要是解决验证URL超时的问题
 */
if (! empty ( $_GET ['echostr'] ) && ! empty ( $_GET ["signature"] ) && ! empty ( $_GET ["nonce"] )) {
	$signature = $_GET ["signature"];
	$timestamp = $_GET ["timestamp"];
	$nonce = $_GET ["nonce"];
	
	$tmpArr = array (
			SYSTEM_TOKEN,
			$timestamp,
			$nonce 
	);
	sort ( $tmpArr, SORT_STRING );
	$tmpStr = sha1 ( implode ( $tmpArr ) );
	
	if ($tmpStr == $signature) {
		echo $_GET ["echostr"];
	}
	exit ();
}

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
