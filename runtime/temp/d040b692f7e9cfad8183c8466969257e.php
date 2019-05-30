<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:48:"./template/reg/default/download/downloadapp.html";i:1554712888;}*/ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta charset="utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width, initial-scale=1" /><title>
    APP下载
</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0" />
    <link rel="stylesheet" type="text/css" href="__STATIC__/js/Swiper-3.4.2/swiper.min.css" />
    <!--<link rel="stylesheet" type="text/css" href="__STATIC__/assets/css/style.css" />-->
</head>
<body>
<style>
    body{
        margin: 0;
        padding: 0;
        background-color: #f1f4f6;
        font-family: "PingFang SC","Hiragino Sans GB","Microsoft YaHei";
        font-size: 0.6rem;
    }
    .swiper-container {
        width: 100%;
        -webkit-perspective: 1200px;
        -moz-perspective: 1200px;
        -ms-perspective: 1200px;
        perspective: 1200px
    }

    .page-ft {
        box-sizing: border-box;
        background-size: 100%;
        background-position: center bottom;
        background-repeat: no-repeat;
    }
    .card-container{
        padding: 40px 0;
        width: 95%;
        margin: 0 auto;
    }
    .card-container .swiper-slide{
        width: 60%;
        -webkit-transform-origin: 50% 400%;
        transform-origin: 50% 400%;
        box-shadow: 0 0 40px rgba(255, 106, 108, 0.4)
    }

    .swiper-slide  img{
        width: 100%;
        display: block;
    }

    .avatar-box {
        width: 3rem;
        height: 3rem;
        margin: 0 auto;
        position: relative;
    }
    .avatar-box::after{
        content: "";
        position: absolute;
        width: 4rem;
        height: 24px;
        bottom: 0;
        margin-bottom: -13px;
        left: 50%;
        margin-left:  -2rem;
        z-index: 0;
        background-size:  100% 100%;
    }
    .avatar-box  img{
        box-sizing: border-box;
        border: 2px solid #fff;
        width: 100%;
        height: 100%;
        border-radius: 15px;
        position: relative;
        z-index: 2;
    }
    .btnbox {
        text-align: center;
        margin-top: 0.5rem;
    }
    .btnbox  img {
        width: 9.7rem;
        vertical-align: middle;
    }

    .mainbox{
        text-align: center;
        background-position: center 1.2rem;
        background-size: 100%;
        background-repeat: no-repeat;
        padding-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05)
    }
    .rwmbox {
        width: 5.5rem;
        height: 5.5rem;
        border-radius: 10px;
        padding: 0.5rem;
        background-color: #fff;
        border: 1px solid #e8e7e7;
        margin: 0 auto;
    }
    .rwmbox  img{
        width: 100%;
        height: 100%;
    }
    .app-name {
        font-size: 0.65rem;
        margin: 0.5rem 0;
    }
    .app-name  span{
        padding-left: 1.1rem;
        vertical-align: middle;
    }
    .app-name i{
        font-style: inherit;
        background-color: #fdf0f7;
        border: 1px solid #f4d1cd;
        font-size: 0.5rem;
        color: #e6a8a3;
        margin-left: 5px;
        vertical-align: middle;
        padding: 1px 4px;
    }

    .page-hd {
        background-size: 100%;
        background-position: center top;
        background-repeat: no-repeat;
        padding-top: 1.7rem;
    }
    img{max-width: 100%; height: auto;}
    .weixin-tip{display: none; position: fixed; left:0; top:0; bottom:0; background: rgba(0,0,0,0.8); filter:alpha(opacity=80);  height: 100%; width: 100%; z-index: 100;}
    .weixin-tip p{text-align: center; margin-top: 10%; padding:0 5%;}
</style>
<div class="page">
    <div class="page-hd" style="background-image: url('__STATIC__/images/topbg.png')">
        <div class="mainbox">
            <div class="avatar-box">
                <img src='<?php echo $info['single_logo']; ?>' />
            </div>
            <div class="app-name">
                    <span>
                        <?php echo $info['app_name']; ?></span><i>官方</i>
            </div>
            <div class="rwmbox">
                <img src='<?php echo $info['show_logo']; ?>' />
            </div>
            <div class="btnbox"  id="JdownApp">
                <a href='<?php echo $info['downurl']; ?>'><img src='__STATIC__/images/btn.png' /></a>
            </div>
        </div>
    </div>
    <div class="page-ft" style="background-image: url('__STATIC__/images/bottombg.png')">
        <div class="swiper-container card-container">
            <div class="swiper-wrapper">


            </div>

        </div>
    </div>
</div>
<div class="weixin-tip">
        <p>
            <img src="__STATIC__/images/live_weixin.png" alt="微信打开"/>
        </p>
    </div>
<script src="__STATIC__/js/jquery.min.2.1.3.js"></script>
<script src="__STATIC__/js/Swiper-3.4.2/swiper.jquery.min.js"></script>
<script src="__STATIC__/js/newcomm.js"></script>
<script type="text/javascript">
    $('.btnbox').click(function(){
        var winHeight = $(window).height();
        function is_weixin() {
            var ua = navigator.userAgent.toLowerCase();
            if (ua.match(/MicroMessenger/i) == "micromessenger") {
                return true;
            } else {
                return false;
            }
        }
        var isWeixin = is_weixin();
        if(isWeixin){
            $(".weixin-tip").css("height",winHeight);
            $(".weixin-tip").show();
        }

    })

</script>
</body>
</html>
