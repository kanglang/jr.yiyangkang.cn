<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:36:"./template/reg/default/user/reg.html";i:1554712889;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">
    <link rel="stylesheet" href="../../../../dist/assets/css/zpui.css"/>
    <link rel="stylesheet" href="../../../../dist/assets/css/all.css"/>
    <script src="../../../../dist/assets/js/page.js"></script>
    <style type="text/css">
        .send{
            height: 2.5em;
            text-decoration:none;
            line-height: 2.5em;
            padding:0px 3px;
            background: #f8584f;
            color: #fff;
            text-align: center;
            display: block;
            float: right;
            border-radius:5px;
        }
    </style>
    <title>注册</title>
</head>
<body>
<div class="page">
    <div class="page-hd">
        <div class="header bor-1px-b">
            <div class="header-left">
                <a href="javascript:history.go(-1)" class="left-arrow"></a>
            </div>
            <div class="header-title">注册</div>
            <div class="header-right">
                <a href="#"></a>
            </div>
        </div>
    </div>

    <div class="page-bd login">
        <!-- 页面内容 -->
        <div class="top">
            <img src="" alt="" class="logo" />
        </div>

        <!--         <div class="weui-cell after-left__0">
                        <div class="weui-cell__hd"><img class="hd_icon" src="../../../../dist/assets/images/icon_ren.png" alt=""></div>
                        <div class="weui-cell__bd">
                        <input type="text" class="weui-input" name="mobile" v-model="mobile" placeholder="请输入手机号码" style="width:60%;padding:5px;" >
                        </div>
                    </div> -->
        <div class="weui-cells weui-cells_form">
            <div class="Box pwd"><span class="fs24 color_9">手机号</span></div>
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <p><input class="weui-input fs28 fw_b mobile" type="text" id="mobile" placeholder="请输入手机号" style="width:60%;padding:5px;"/>
                        <button  class="send"  id="btnSendCode" onclick="sendMessage()" >发送验证码</button></p>
                </div>
                <div class="weui-cell__ft"></div>
            </div>

            <div class="Box pwd"><span class="fs24 color_9">验证码</span></div>
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <input
                            class="weui-input fs28 fw_b code"
                            type="text"
                            placeholder="请输入验证码"
                    />
                </div>
            </div>

            <div class="Box pwd"><span class="fs24 color_9">邀请人</span></div>
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <input
                            class="weui-input fs28 fw_b invite"
                            type="text"
                            placeholder="请输入邀请人"
                    />
                </div>
            </div>

            <div class="Box pwd"><span class="fs24 color_9">密码</span></div>
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <input
                            class="weui-input fs28 pwdInput fw_b password"
                            type="password"
                            placeholder="请输入密码"
                    />
                </div>
                <div class="weui-cell__ft"><img src="../../../../dist/assets/images/ere_no.png" data-style="no" alt="" class="ereimg"></div>
            </div>
            <div class="Box pwd"><span class="fs24 color_9">确认密码</span></div>
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <input
                            class="weui-input fs28 pwdInput fw_b confirm_password"
                            type="password"
                            placeholder="请输入密码"
                    />
                </div>
                <div class="weui-cell__ft"><img src="../../../../dist/assets/images/ere_no.png" data-style="no" alt="" class="ereimg2"></div>
            </div>
        </div>
        <div class="butBox"><div class="but">注册</div></div>
        <a href="<?php echo U('Reg/download/downloadapp'); ?>" class="findpwd fw_b fs26 color_r">点击直接下载APP</a>
    </div>
</div>

<script src="../../../../dist/assets/js/lib/jquery-2.1.4.js"></script>
<script src="../../../../dist/assets/js/jquery-weui.min.js"></script>
<script src="../../../../dist/assets/js/lib/fastclick.js"></script>
<script src="../../../../dist/assets/js/layer.js"></script>
<script src="../../../../dist/assets/js/ajaxplugin.js"></script>
<script>
    $(function() {
        FastClick.attach(document.body);
    });

    var url = '/api/Nologin/config';
    var mehod = 'post';
    a_load(url,{},mehod,function(data){
        console.log(data.data);
        $('.logo').attr('src',data.data.logo);
    });
    var InterValObj;
    var count = 60;
    var curCount;
    var query = window.location.search.substring(1);
    var invite = <?php echo $first_leader; ?>;
    if (invite) {
        $('.invite').val(invite);
        $('.invite').attr('disabled',true);
    }
    function sendMessage() {
        curCount = count;
        var mobile = $("#mobile").val();
        var code = $(".code").val();
        var reg1 = /\d{1,}/;
        var reg2 = /^1[34578]\d{9}$/;

        if(!reg1.test(mobile)){
            layer.open({
                content: '请输入手机号码!'
                ,skin: 'msg'
                ,time: 1 //2秒后自动关闭
            });
            return false;
        } else if (!reg2.test(mobile)){
            layer.open({
                content: '请输入正确的手机号码!'
                ,skin: 'msg'
                ,time: 1 //2秒后自动关闭
            });
            return false;
        }

        var url       = '/api/Sms/send_validate_code'
        var data      = {}
        var mehod = 'post';
        data.mobile   = $('.mobile').val();
        data.scene   = 1
        __ajax(url,data,mehod,function(data){
            if (data.status == 200) {
                layer.open({
                    content: data.message,
                    skin: 'msg',
                    time: 1 //2秒后自动关闭
                });
                $("#btnSendCode").attr("disabled", "true");
                InterValObj = window.setInterval(SetRemainTime, 1000);
            }else{
                layer.open({
                    content: data.message,
                    skin: 'msg',
                    time: 1 //2秒后自动关闭
                });
                return false;
            }
        })
    }
    //timer处理函数
    function SetRemainTime() {
        if (curCount <= 0) {
            window.clearInterval(InterValObj);//停止计时器
            $("#btnSendCode").removeAttr("disabled");//启用按钮
            $("#btnSendCode").html("发送验证码");
        }
        else {
            curCount--;
            $("#btnSendCode").html(curCount + "秒重新发送");
        }
    }
    $('.but').click(function(){
        var mobile = $("#mobile").val();
        var code   = $(".code").val();
        var password = $('.password').val();
        var reg1   = /\d{1,}/;
        var reg2   = /^1[34578]\d{9}$/;
        var invite = $('.invite').val();
        if(!reg1.test(mobile)){
            layer.open({
                content: '请输入手机号码'
                ,skin: 'msg'
                ,time: 1 //2秒后自动关闭
            });
            return false;
        } else if (!reg2.test(mobile)){
            layer.open({
                content: '请输入正确的手机号码'
                ,skin: 'msg'
                ,time: 1 //2秒后自动关闭
            });
            return false;
        }else if (!code){
            layer.open({
                content: '请输入验证码'
                ,skin: 'msg'
                ,time: 1 //2秒后自动关闭
            });
            return false;
        }else if (!invite){
            layer.open({
                content: '请输入邀请人'
                ,skin: 'msg'
                ,time: 1 //2秒后自动关闭
            });
            return false;
        }else if (!password){
            layer.open({
                content: '请输入密码'
                ,skin: 'msg'
                ,time: 1 //2秒后自动关闭
            });
            return false;
        }

        var url       = '/api/login/register'
        var data      = {}
        var mehod     = 'post';
        data.mobile   = $('.mobile').val();
        data.password = $('.password').val();
        data.code     = $('.code').val();
        data.invite   = $('.invite').val();
        data.confirm_password = $('.confirm_password').val();
        // console.log(data.invite);return;
        if (data.password != data.confirm_password) {
            layer.open({
                content: '密码输入不一致'
                ,skin: 'msg'
                ,time: 1 //2秒后自动关闭
            });
        }
        __ajax(url,data,mehod,function(data){

            if(data.status == 200){
                layer.open({
                    content: data.message
                    ,skin: 'msg'
                    ,time: 1 //2秒后自动关闭
                });
                sessionStorage.setItem('user_info', JSON.stringify(data.data))
                window.setTimeout(function(){
                    window.location.href = "<?php echo url('reg/Download/downloadapp'); ?>"
                },1000);
            }else{
                layer.open({
                    content: data.message
                    ,skin: 'msg'
                    ,time: 1 //2秒后自动关闭
                });
            }
        });
    })
</script>
<script>
    $(function(){
        $('.ereimg').on('click',function(){
            let imgattr=$(this).attr('data-style');
            if(imgattr=='no'){
                $(this).attr('src','../../../../dist/assets/images/ere_off.png')
                $(this).attr('data-style','off')
                $('.password').attr('type','text')
            }else{
                $(this).attr('src','../../../../dist/assets/images/ere_no.png')
                $(this).attr('data-style','no')
                $('.password').attr('type','password')
            }
        })
        $('.ereimg2').on('click',function(){
            let imgattr=$(this).attr('data-style');
            if(imgattr=='no'){
                $(this).attr('src','../../../../dist/assets/images/ere_off.png')
                $(this).attr('data-style','off')
                $('.confirm_password').attr('type','text')
            }else{
                $(this).attr('src','../../../../dist/assets/images/ere_no.png')
                $(this).attr('data-style','no')
                $('.confirm_password').attr('type','password')
            }
        })
    })
</script>
</body>
</html>