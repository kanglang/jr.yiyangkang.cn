<?php
namespace app\api\controller;

use app\common\logic\UsersLogic;
use app\common\logic\OrderLogic;
use app\common\logic\CartLogic;
use app\api\controller\Sms;
use My\DataReturn;
use think\Session;
use app\common\controller\Recommend;




class Login extends Base
{

     private $announcement;
    
    public function __construct(){
        // parent::__construct();
        init_config();//初始配置表数据

        $this->announcement=129; //首页公告文章id

    }

    public function login(){
        if (request()->ispost()) {
            $post = I('post.');
            $where = [
                'mobile'=>$post['data']['mobile'],
                'password'=>md5($post['data']['password']),
            ];
            $remember_pwd = $post['data']['remember_pwd'];
            $user_info = db('users')->where($where)->find();
            if(empty($user_info)){
                DataReturn::returnJson(0, "用户不存在或账号密码错误", []);
            }
            if ($user_info['is_lock'] == 1) {
                DataReturn::returnJson(0, "此用户已锁定!", []);
            }
            if ($remember_pwd) {
                //记住密码
                //返回处理的session_id
                $session['expire_time'] = time() + 604800;//7天
            }
            $session['user_id'] = $user_info['user_id'];
            $session['expire_time'] = time() + C('session.expire');//一天
            session('user',$session);
            session('announcement',$session);//公告写进session
            

            //登陆成功后业务处理
            // $cartLogic = new CartLogic();
            // $cartLogic->setUserId($user_info['user_id']);
            // $cartLogic->doUserLoginHandle();// 用户登录后 需要对购物车 一些操作
            // $orderLogic = new OrderLogic();
            // $orderLogic->setUserId($user_info['user_id']);//登录后将超时未支付订单给取消掉
            // $orderLogic->abolishOrder();
            DataReturn::returnBase64Json(200, "登录成功", $user_info);
        }else{
            DataReturn::returnJson(0, "请输入用户名和密码", []);
        }
    }

    public function login_demo()
    {
        $data = DataReturn::baseFormat(input('data'));
        //返回处理的session_id
        $session['user_id'] = 2581;
        $session['expire_time'] = time() + C('session.expire');
        session('user',$session);
        DataReturn::returnBase64Json(200,'登录成功',$data);
    }

    //注册用户,返回信息
    public function register(){
        $post = I('post.');
        $confirm_password = md5($post['data']['confirm_password']);
        $data = [
            'mobile'=>$post['data']['mobile'],
            'code'=>$post['data']['code'],
            'password'=>md5($post['data']['password']),
            'reg_time'=>time(),
            'create_time'=>time(),
            'last_login_time'=>time(),
        ];
        $sms_log = db('sms_log')->where(['mobile' => $data['mobile'], 'status' => 1])->order('id desc')->field('code, add_time')->find();
        $sms_time_out =300;  //5分钟后失效
        $timeOut = $sms_log['add_time']+ $sms_time_out;

        if($data['code'] !== $sms_log['code']){
            DataReturn::returnJson(0, "验证码不正确", []);
        }else if($timeOut < time()){
            DataReturn::returnJson(0, "验证码已超时失效", []);
        }else if(!$data['password'] || !$confirm_password){
            DataReturn::returnJson(0, "密码不能为空", []);
        }else if($data['password'] !== $confirm_password){
            DataReturn::returnJson(0, "两次密码不一致", []);
        }
        $user_info = db('users')->where(['mobile'=>$data['mobile']])->find();
        if(empty($user_info)){
            $invite = $post['data']['invite'];
            if(empty($invite)){
                DataReturn::returnJson(0, "没有邀请人不能注册", []);
            }
            $invite = db('users')->where("mobile",$invite)->find();
            if(!$invite){
                DataReturn::returnJson(0, "邀请人不存在!", []);
            }
            $data['first_leader'] = $invite['user_id'];  //推荐人id,一级
            if(!empty($data['first_leader'])){
                $first_leader= db('users')->where("user_id ",$data['first_leader'])->find();
                $data['second_leader'] = $first_leader['first_leader'];//二级
                $data['third_leader'] = $first_leader['second_leader'];//三级
                //他上线分销的下线人数要加1
                db('users')->where(array('user_id' => $data['first_leader']))->setInc('underling_number');
                db('users')->where(array('user_id' => $data['second_leader']))->setInc('underling_number');
                db('users')->where(array('user_id' => $data['third_leader']))->setInc('underling_number');
            }
            $user_id = db('users')->insertGetId($data);//插入用户数据
            
            //为推广财分加盐
            $addsalt = new \app\api\controller\Addsalt();
            $salt_money=0;
            $usermoneysalt=$addsalt->userMoneyaddsalt($user_id,$salt_money);
            $update_data = array(
             'usermoneysalt'     => $usermoneysalt,
            );
            $update = db('users')->where('user_id',$user_id)->update($update_data);

            Recommend::up_level($invite['user_id']);
            if($user_id){
                $register_send_power = config('register_send_power'); // 会员注册赠送福分
                if($register_send_power > 0){
                    accountLog($user_id, 0,$register_send_power, '会员注册赠送福分' , 0 , 0, '',6); // 记录日志流水
                }
            }
            $user_info = db('users')->where(['user_id'=>$user_id])->find();
            //返回处理的session_id
            $session['user_id'] = $user_info['user_id'];
            $session['expire_time'] = time() + C('session.expire');
            session('user',$session);
        }else{
            DataReturn::returnJson(0, "该手机号已经存在，请更换手机号", []);
        }
        DataReturn::returnBase64Json(200, "注册成功", $user_info);
    }

    //重置密码
    public function reset_password(){
        if (request()->isPost()) {
            $post = I('post.');
//            dump($post);exit;
            $confirm_password = md5($post['data']['confirm_password']);
            $data = [
                'mobile'=>$post['data']['mobile'],
                'code'=>$post['data']['code'],
                'password'=>md5($post['data']['new_password']),
                'reg_time'=>time(),
                'last_login_time'=>time(),
            ];
            $users = db('users')->where(['mobile'=> $data['mobile'],])->value('mobile');
            if(empty($users)){
                DataReturn::returnJson(0,'请输入注册的手机号',[]);
            }
            $sms_log = db('sms_log')->where(['mobile' => $data['mobile'], 'status' => 1])->order('id desc')->field('code, add_time')->find();
            $sms_time_out =120;  //2分钟后失效
            $timeOut = $sms_log['add_time']+ $sms_time_out;

            if($data['code'] !== $sms_log['code']){
                DataReturn::returnJson(0, "验证码不正确", []);
            }else if($timeOut < time()){
                DataReturn::returnJson(0, "验证码已超时失效", []);
            }else if(!$data['password'] || !$confirm_password){
                DataReturn::returnJson(0, "密码不能为空", []);
            }else if($data['password'] !== $confirm_password){
                DataReturn::returnJson(0, "两次密码不一致", []);
            }
            $user_id = db('users')->where(['mobile' => $data['mobile']])->update($data);//更新用户数据
            if($user_id){
                DataReturn::returnBase64Json(1, "修改成功", []);
            }else{
                DataReturn::returnJson(0, "修改失败", []);
            }

//            $post = I('post.');
//            $postdata = $post['data'];
//
//            // 检查验证码
//            $code = $postdata['code'];
//            $mobile = $postdata['mobile'];
//             $type = input('type','sms');
//             $scene = input('scene', -1);
//             $sms = new Sms();
//             $sms_rs = $sms->check_validate_code($code,$mobile,$type,$scene);
//             if($sms_rs['status'] != 200){
//                 DataReturn::returnJson($sms_rs['status'],$sms_rs['msg'],$sms_rs['data']);
//             }
//
//            $logic = new UsersLogic();
//            $user_id = db('users')->where('mobile',$mobile)->value('user_id');
//            $userLogic = new UsersLogic();
//            $data = $userLogic->password($user_id, $postdata['new_password'], $postdata['confirm_password']);
//            DataReturn::returnBase64Json($data['status'],$data['msg'],[]);
        }
    }

    //推出登录
    public function loginOut(){
        session(null);
        DataReturn::returnJson(200, "退出成功", []);
    }

    public function isLogin(){
        $user = session('user');
        if($user){
            DataReturn::returnJson(200, "已经登录", []);
        }else{
            DataReturn::returnJson(0, "没有登录", []);
        }
    }

    /*获取微信登录凭证*/
    protected function getWxLoginToken()
    {
        $second = 60; //设置超时
        $paymentPlugin = M('Plugin')->where("code='miniAppPay' and  type = 'payment' ")->find(); // 找到小程序支付插件的配置

        $config = unserialize($paymentPlugin['config_value']);

        //参数
        $url = sprintf('https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',$config['appid'],$config['appsecret'],$this->code);

        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //设置返回值

        $res = curl_exec($ch);//运行curl，结果以json形式返回
        $data = json_decode($res,true);
        curl_close($ch);
        return $data;
    }

   //获取公告session
    public function getannouncementsession(){
         $announcement = session('announcement');
         if($announcement){
             return $announcement;
         }
          return false;
    }

   //删除公告session
    public function  delannouncementsession(){
          session('announcement', null);
    }


   public  function  getannouncement(){
    
     $announcement = db('article')->where(['id' => $this->announcement])->field('content,title')->find();
     
     if(!$announcement){
        DataReturn::returnJson(1, "找不到文章，请查看文章id", ['announcement'=>"请到后台编辑公告",'title'=>'请到后台编辑公告']);
     }

     if(!$this->getannouncementsession()){
        DataReturn::returnJson(0, "重新登录后才能看到公告", []);

     };
     $this->delannouncementsession();//获取一次公告后删除session     下次登录再可
      DataReturn::returnBase64Json(1, "返回公告成功", ['announcement'=>$announcement["content"],'title'=>
        $announcement["title"]]);

   }
 
    

}