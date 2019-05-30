<?php
namespace app\api\controller;
use app\common\logic\UsersLogic;
use think\Db;
use My\DataReturn;
use think\Exception;
use app\common\logic\DistributLogic;
use app\common\logic\SearchWordLogic;
use app\home\controller\check_validate_code;
use app\common\logic\JssdkLogic;
use think\Image;


class User extends Base{

    //需要检查登录的页面
    public function __construct()
    {
        parent::__construct();
       // $this->checkLogin();
    }


    public function help(){

      $post = I('post.');
      $id=intval($post["data"]["id"]);
      
      $article = M('article')->where('id', $id)->find();


      DataReturn::returnBase64Json(200,'校验成功',$article["content"]);

    }


    public function index()
    {
        // halt($this->user_id);
        $user_id = $this->user_id;
        $users = M('users')->where('user_id', $user_id)->find();
        // $pig_fee           = config('pig_fee');//pig币手续费
        // $doge_fee          = config('doge_fee');//虾虾币手续费
        $account_zfb_number    = config('account_zfb_number');//支付宝账号
        $recharge_zfb_qrcode   = config('recharge_zfb_qrcode');//支付宝二维码
        $account_wx_number     = config('account_wx_number');//微信账号
        $recharge_wx_qrcode    = config('recharge_wx_qrcode');//微信二维码
        $payment_count         = db('user_payment')->where(['user_id'=>$this->user_id])->count();//用户收款方式
        $users['price']    = db('user_exclusive_pig')->where('user_id', $user_id)->sum('price');//总资产
        $users['contract_revenue']   = db('pig_doge_money')->where(['user_id'=>$user_id,'type'=>3])->sum('contract_revenue');//合约收益
        $users['accumulated_income'] = db('account_log')->where(['user_id'=>$user_id])->sum('user_money');//累计收益
        //获取用户的等级
        $users['level_name'] = Db::name('user_level')->where('level_id',$users['level'])->value('level_name');
        //领养中
        $adopt_num = db('pig_order')
                    ->alias('a')
                    ->join('pig_goods b','a.pig_level = b.id')
                    ->field('a.*,b.goods_name,b.small_price,b.large_price,b.contract_days,b.income_ratio')
                    ->where(['purchase_user_id'=>$this->user_id,'pay_status'=>1])//领养中
                    ->count();
        //转让中
        $transfero_num = db('pig_order')
                    ->alias('a')
                    ->join('pig_goods b','a.pig_level = b.id')
                    ->join('users c','a.purchase_user_id = c.user_id')
                    ->field('a.*,b.goods_name,b.small_price,b.large_price,b.contract_days,b.income_ratio,c.nickname')
                    ->where(['sell_user_id'=>$this->user_id,'pay_status'=>1])//转让中
                    ->count();
        $users['payment_count']          = $payment_count;//用户收款方式
        $users['transfero_num']          = $transfero_num;//转让中
        $users['adopt_num']              = $adopt_num;//领养中
        $users['recharge_zfb_qrcode']    = $recharge_zfb_qrcode;//支付宝二维码
        $users['account_zfb_number']     = $account_zfb_number;//支付宝账号
        $users['account_wx_number']      = $account_wx_number;//微信账号
        $users['recharge_wx_qrcode']     = $recharge_wx_qrcode;//微信二维码
        DataReturn::returnBase64Json(200,'校验成功',$users);
    }

    /**
     * 实名认证
     */
    public  function authentication(){
        if (IS_POST) {
            $post = I('post.');
            $data['real_name'] = $post['data']['real_name'];
            $data['identity']  = $post['data']['identity'];
            $data['user_id']   = $this->user_id;
            $data['add_time']  = time();
            // if (!preg_match('/^[\x80-\xff]{1,12}$/', $data['real_name'])) {
            //     DataReturn::returnJson(0,'请输入真实姓名',[]);
            // }

            $list = db('user_identity')->where(['user_id'=>$this->user_id])->find();
            
            if ($list && $list['status'] == 0) {
                DataReturn::returnJson(0,'您已提交申请,无需重复提交!',[]);
            }

            // if ($list && $list['status'] == -1) {
            //     DataReturn::returnJson(0,'提交申请失败，请联系客服',[]);
            // }

            if(empty($data['real_name'])){

                DataReturn::returnJson(0,'真实姓名不为空',[]); 
            }

            if(empty($data['identity'])){

                DataReturn::returnJson(0,'身份证号码不为空',[]);
            }

            //检查是否存在当前身份证号
            $check = db('users')->where(['identity' => $data['identity']])->find();


            if(!empty($check)){
                DataReturn::returnJson(0,'该身份证号码已注册',[]);
            }

            $reg = '/^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/';
            if (!$data['real_name'] || !$data['identity']) {
                DataReturn::returnJson(0,'请输入真实姓名或身份证号码',[]);
            }
            if (!preg_match($reg,$data['identity'])) {
                DataReturn::returnJson(0,'请输入正确的身份证号码',[]);
            }
            $list = db('user_identity')->where(['user_id'=>$this->user_id])->find();
            if ($list && $list['status'] == 0) {
                DataReturn::returnJson(0,'您已提交申请,无需重复提交!',[]);
            }
            $res = db('user_identity')->add($data);
            if ($res) {
                DataReturn::returnJson(200,'已提交,请等待管理员审核',[]);
            }else{
                DataReturn::returnJson(500,'提交失败',[]);
            }
        }else{
             DataReturn::returnJson(500,'网络繁忙',[]);
        }
    }

     //获取分享生成的海报图
    public function share_image(){
        //加载第三方类库
        vendor('phpqrcode.phpqrcode');
        //获取个人
 /*       $url = SITE_URL.'/dist/pages/register.html?first_leader='.$this->user_info['mobile'];
        $data = [
            'first_leader'  => $this->user_info['mobile'],
        ];*/
        //获取个人
        $url = request()->domain().U('Reg/user/reg',array('first_leader'=>$this->user_info['mobile']));
        $after_path = 'uploads/qrcode/'.md5($url).'.png';
        //保存路径
        $path =  ROOT_PATH.'public/'.$after_path;
        if(!is_dir(ROOT_PATH.'public/uploads/qrcode'))
        {
            mkdir(ROOT_PATH.'public/uploads/qrcode'); //新建目录
        }
        //判断是该文件是否存在
        // if(!is_file($path))
        // {
            //实例化
            $qr = new \QRcode();
            //1:url,3: 容错级别：L、M、Q、H,4:点的大小：1到10
            $qr::png($url,'./'.$after_path, "M", 4,TRUE);
        // }
        // dump($path);
        $share_image = '.'.config('share_image');
        $share_logo = config('share_image');
        // dump($share_image);die();
        if (!file_exists($share_image) || !$share_logo) {
             $this->error('背景图片不存在!', '', []);
        }
        $nick_name = $this->user_info['nickname'] ? : '';

        $image = Image::open($share_image);
        //二维码
        $image->water('./'.$after_path , Image::WATER_CENTER, 100)
            ->text($this->user_info['mobile'],ROOT_PATH . 'public/static/assets/ttfs/microsoft.ttf',32,'#ffffff', Image::WATER_CENTER,[0,120])
            ->text($nick_name,ROOT_PATH . 'public/static/assets/ttfs/microsoft.ttf',32,'#ffffff', Image::WATER_CENTER,[0,180]);
        $image->save($path);
        $img_url = request()->domain() . '/' . $after_path;
        $data = [
            'img_url' =>$img_url,
            'url' =>$url,
        ];
        DataReturn::returnBase64Json(200,'验证成功',$data);

    }

    /**
     * 我的二维码
     */
    public function myqrcode(){
        //加载第三方类库
        vendor('phpqrcode.phpqrcode');
        //获取个人
        $url = SITE_URL.'/dist/pages/register.html?first_leader='.$this->user_info['mobile'];
        $after_path = 'uploads/qrcode/'.md5($url).'.png';
        //保存路径
        $path =  ROOT_PATH.'public/'.$after_path;
        if(!is_dir(ROOT_PATH.'public/uploads/qrcode'))
        {
            mkdir(ROOT_PATH.'public/uploads/qrcode'); //新建目录
        }
        //判断是该文件是否存在
        if(!is_file($path))
        {
            //实例化
            $qr = new \QRcode();
            //1:url,3: 容错级别：L、M、Q、H,4:点的大小：1到10
            $qr::png($url,'./'.$after_path, "M", 6,TRUE);
        }
        $data = request()->domain().'/'.$after_path;
        DataReturn::returnBase64Json(200,'验证成功',$data);
    }

    /*
     * 获取所有粉丝信息         团队
     */
    public function ajax_count_leader(){
        $user_id = $this->user_info['user_id'];
        $type = 2;

        $users_logic = new UsersLogic();
       // $data = $users_logic->get_zpdistribution_info($user_id,0,$type);
        $data = $users_logic->getOneLine($user_id);
        // dump(db()->getlastsql());
        if($data['status'] == 1){
            DataReturn::returnBase64Json(200,'获取数据成功', $data);
        }else{
            DataReturn::returnBase64Json(0,'缺少必要参数', false);
        }
    }

    //修改用户名
    public function set_nickname(){
        if (request()->isPost()) {
            $user_id = $this->user_id;
            $post = I('post.');
            $nickname = $post['data']['nickname'];
            // $check = db('users')->where(['nickname'=>$nickname,'user_id' => ['<>', $user_id]])->count();
            // if ($check) {
            //     DataReturn::returnJson(500,'用户名已存在',[]);
            // }
            $res = db('users')->where('user_id',$user_id)->update(['nickname'=>$nickname]);
            if ($res) {
                DataReturn::returnJson(200,'修改成功',[]);
            } else {
                DataReturn::returnJson(500,'修改失败',[]);
            }
        }
    }

    /**
     * 修改密码
     */
    public function password()
    {
        if (request()->isPost()) {
            // 检查验证码
            $post = I('post.');
            $postdata = $post['data'];
            $confirm_password = md5($post['data']['confirm_password']);
            $password =  md5($post['data']['new_password']);
            $mobile   =  $post['data']['mobile'];
            $code     =  $post['data']['code'];
            $sms_log  =  db('sms_log')->where(['mobile' => $mobile, 'status' => 1])->order('id desc')->field('code, add_time')->find();
            $sms_time_out =300;  //5分钟后失效
            $timeOut  = $sms_log['add_time']+ $sms_time_out;

            if($code !== $sms_log['code']){
                DataReturn::returnJson(0, "验证码不正确", []);
            }else if($timeOut < time()){
                DataReturn::returnJson(0, "验证码已超时失效", []);
            }else if(!$password || !$confirm_password){
                DataReturn::returnJson(0, "密码不能为空", []);
            }else if($password !== $confirm_password){
                DataReturn::returnJson(0, "两次密码不一致", []);
            }

            $logic = new UsersLogic();
            $user_id = $this->user_id;
            $userLogic = new UsersLogic();
            $data = $userLogic->password($user_id, $postdata['new_password'], $postdata['confirm_password']);
            DataReturn::returnBase64Json($data['status'],$data['msg'],[]);
        }
    }

    /**
     * 修改二级密码
     */
    public function paypwd()
    {
        $user_id =  $this->user_id;
        if (request()->isPost()) {
            $post = I('post.');
            $postdata = $post['data'];
            $confirm_password = md5($post['data']['confirm_password']);
            $password =  md5($post['data']['new_password']);
            $mobile   =  $post['data']['mobile'];
            // 检查验证码
            /*$code     =  $post['data']['code'];
            $sms_log  =  db('sms_log')->where(['mobile' => $mobile, 'status' => 1])->order('id desc')->field('code, add_time')->find();
            $sms_time_out =300;  //5分钟后失效
            $timeOut  = $sms_log['add_time']+ $sms_time_out;

            if($code !== $sms_log['code']){
                DataReturn::returnJson(0, "验证码不正确", []);
            }else if($timeOut < time()){
                DataReturn::returnJson(0, "验证码已超时失效", []);
            }else */if(!$password || !$confirm_password){
                DataReturn::returnJson(0, "密码不能为空", []);
            }else if($password !== $confirm_password){
                DataReturn::returnJson(0, "两次密码不一致", []);
            }

            $userLogic = new UsersLogic();
            $data = $userLogic->paypwd($user_id, $postdata['new_password'], $postdata['confirm_password']);
            DataReturn::returnBase64Json($data['status'],$data['msg'],[]);
        }
    }

    //转让
    public function transfer()
    {
        if(request()->isPost()){
            $post= input('post.');
            $user = Db::name('users')->where('user_id',$post['user_id'])->find();
            if(!$user){
                DataReturn::returnJson(500, '暂无此用户,请检查后输入', []);
            }
            $list=Db::name('users')->where('user_id',$this->user_id)->find();
            if(empty($list['paypwd']) || encrypt($post['paypwd']) != $list['paypwd'] ){
                DataReturn::returnJson(500, '支付密码错误', []);
            }
            // 启动事务
            Db::startTrans();
            // try{
                $res1 = Db::name('users')->where('user_id',$post['user_id'])->setInc('pay_points',$post['nums']);//被转让人增加福分
                $res2 = Db::name('users')->where('user_id',$post['seller_id'])->setDec('pay_points',$post['nums']);//转让者减少福分
                $res3 = accountLog1($post['seller_id'],0,0,'-'.$post['nums'],'用户:'.$post['seller_id'].'转让'.$post['nums'].'福分到用户:'.$post['user_id'],2);
                //资金流日志
                $res4 = accountLog1($post['user_id'],0,0,$post['nums'],'用户收入'.$post['seller_id'].'转让'.$post['nums'].'福分',2);//资金流日志
                // 提交事务
                if (!$res1 || !$res2 || !$res3 || !$res4) {
                    Db::rollback();
                    DataReturn::returnJson(500, '操作失败', []);
                }
                Db::commit();
                DataReturn::returnJson(200,'操作成功',[]);
            // } catch (\Exception $e) {
                // 回滚事务
                // Db::rollback();
                // DataReturn::returnJson(500, '操作失败2', []);
            // }
        }
        DataReturn::returnJson(500, '操作失败', []);
    }

    //‘我的’首页
    public function my_index(){

        try{
            //$this->user_id;
            //$prefix = request()->domain();图片前缀
            $user_id = $this->user_id;
            $users = M('users')->where('user_id',$user_id)->field('sex,birthday,mobile,address_id,head_pic,nickname,user_money,distribut_money,pay_points')->find();
            //查优惠卷
            // $users['coupon'] = M('coupon_list')->where('status',1)->where('uid',$user_id)->count();
            $users['coupon'] = M('coupon_list')
                ->alias('cl')
                ->join('coupon c','c.id = cl.cid','LEFT')
                ->where(['c.status'=>1 , 'cl.uid'=>$user_id])
                ->count();
            if(!$users){
                throw new Exception("系统繁忙，稍后再试！");

                // echo  M('coupon_list')->getlastsql();
            }
            // dump($users);die;
            DataReturn::returnJson('200','获取数据成功！',$users);
        }catch(\Exception  $e){
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    // ‘我的’我的收藏
    public function collect_list(){

        try{
            //$this->user_id;
            $prefix = request()->domain();//图片前缀
            $user_id = $this->user_id;
            // dump($user_id);
            // $aaa  = M('aaa')->data('text',$user_id)->insert();
            $count = M('goods_collect')->where('user_id',$user_id)->count();// 查询满足要求的总记录数
            $pagesize = C('PAGESIZE');  //每页显示数
            $pages = I('pages') ? I('pages') : 1;
            $start =  ($pages-1) * $pagesize;
            $collect_list = M('goods_collect')->where('user_id',$user_id)->field('goods_id')->limit($start , $pagesize)->select();
            $_list = [];
            $_collect_list = [];
            if($collect_list){
                foreach ($collect_list as $key => $val) {
                    $details = M('goods')->where('goods_id',$val['goods_id'])->field('original_img,goods_name,shop_price,goods_id')->find();
                    if(!empty($details)){
                        $_t['goods_name'] = $details['goods_name'];
                        $_t['shop_price'] = $details['shop_price'];
                        $_t['goods_id'] = $details['goods_id'];
                        $_t['original_img'] = $prefix . $details['original_img'];
                        $_collect_list[] = $_t;
                    }
                }
            }else{
                throw new Exception("系统繁忙，稍后再试！");
            }
            $_list['lists'] = $_collect_list;
            DataReturn::returnJson('200','获取数据成功！',$_list);
        }catch(\Exception  $e){
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    //‘我的’我的收藏->删除
    public function collect_list_del(){
        try{
            $goods_id = I('goods_id');
            if($goods_id){
                $where = [];
                $where['goods_id'] = $goods_id;
                $where['user_id'] = $this->user_id;
                $del = M('goods_collect')->where($where)->delete();
                if($del){
                    // Cache::rm('TPSHOP_CACHE_TIME');
                    DataReturn::returnJson('200','删除数据成功！');
               } else {
                throw new Exception("删除失败！");
               }

            }else{
                throw new Exception("系统繁忙，稍后再试！");
            }
        }catch(\Exception $e){
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    //‘我的’我的分享
    public function share_list(){
        try{
            $prefix = request()->domain();//图片前缀
            $user_id = $this->user_id;
            $pagesize = C('PAGESIZE');  //每页显示数
            $pages = I('pages') ? I('pages') : 1;
            $start =  ($pages-1) * $pagesize;
            $user = M('share')->where(['user_id'=>$user_id])->limit($start , $pagesize)->field('goods_id,original_img,goods_name,shop_price')->select();
            // dump($user);
            foreach ($user as $key => $val) {
                    $user[$key]['original_img'] = $prefix. $val['original_img'];
            }

            $data['lists'] = $user;
            DataReturn::returnJson('200','获取数据成功！',$data);
        }catch(\Exception $e){
            DataReturn::returnJson('400',$e->getMessage());
        }

    }

    //‘我的’我的分享->清空
    public function share_empty(){
        try{
            $user_id = $this->user_id;
            $res = M('share')->where(['user_id' => $user_id])->delete();
            DataReturn::returnJson('200','删除数据成功！');
        }catch(\Exception $e){
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    //‘我的’我的评论
    public function zpevaluate_list(){
        try {
            $user_id = $this->user_id;
            if($user_id){
                    $pagesize = C('PAGESIZE');  //每页显示数
                    $pages = I('pages') ? I('pages') : 1;
                    $start =  ($pages-1) * $pagesize;
                    $user = M('comment')->where(['user_id'=>$user_id])->limit($start , $pagesize)->field('user_id,goods_id,deliver_rank,goods_rank,service_rank,img,content')->select();
                    $_user = array();
                    foreach ($user as $key => $val) {

                        $_u = $val;
                        $_u['head_pic'] = M('users')->where(['user_id'=>$val['user_id']])->value('head_pic');
                        $_u['nickname'] = M('users')->where(['user_id'=>$val['user_id']])->value('nickname');
                        $rank = ($val['deliver_rank'] + $val['goods_rank'] + $val['service_rank']) / 3;
                        $_rank = round($rank,0);
                        // dump($_rank);

                        if($_rank == 0){
                            $_u['starts_img'] = request()->domain() . "/public/images/start/stars0.gif";
                        }elseif($_rank == 1){
                            $_u['starts_img'] = request()->domain() . "/public/images/start/stars1.gif";
                        }elseif($_rank == 2){
                            $_u['starts_img'] = request()->domain() . "/public/images/start/stars2.gif";
                        }elseif($_rank == 3){
                            $_u['starts_img'] = request()->domain() . "/public/images/start/stars3.gif";
                        }elseif($_rank == 4){
                            $_u['starts_img'] = request()->domain() . "/public/images/start/stars4.gif";
                        }else{
                            $_u['starts_img'] = request()->domain() . "/public/images/start/stars5.gif";
                        }
                        // dump($_u['image']);
                        $_imglist= [];
                        $_imgarray = unserialize($val['img']); // 晒单图片
                        if($_imgarray){
                            for ($i=0; $i < count($_imgarray ); $i++) {
                                $_c = request()->domain() .$_imgarray[$i];
                                $_imglist[] =$_c;
                            }
                        }

                        $_u['img'] =  $_imglist;
                        $_u['add_time'] = $val['add_time'] != 0 ? date('Y-m-d H:i:s', $val['add_time']) : '0000-00-00 00:00:00';
                        // $_u['star_images'] = star($_u['rank']);
                        $_u['original_img'] = request()->domain() . (M('goods')->where(['goods_id'=>$val['goods_id']])->value('original_img'));
                        $_u['goods_name'] = M('goods')->where(['goods_id'=>$val['goods_id']])->value('goods_name');
                        $_u['shop_price'] = M('goods')->where(['goods_id'=>$val['goods_id']])->value('shop_price');
                        // $_u['spec_key_name'] = M('order_goods')->where(['goods_id'=>$val['goods_id']])->where(['order_id'=>$val['order_id']])->value('spec_key_name');
                        $_user[] = $_u;

                    }
                }else{
                    throw new Exception("系统繁忙，稍后再试！");
                }
                $return = [];
                $return['lists'] = $_user;
                DataReturn::returnJson('200','请求数据成功！',$return);
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }


    // ‘我的’我的分销
    public function distribution_list(){
        try {
            $user_id = $this->user_id;
            $users = M('users')->where(['user_id'=>$user_id])->field('head_pic,nickname,distribut_money,pay_points,first_leader,second_leader,third_leader')->find();
            $users['head_pic'] = request()->domain() . $users['head_pic'];//头像处理
            //获取一、二、三层下线人数
            $usersLogic = new \app\common\logic\UsersLogic();
            $number_data=$usersLogic->layer_number($user_id);
            $users['first']=$number_data['first_lower'];
            $users['second']=$number_data['second_lower'];
            $users['third']=$number_data['third_lower'];
            $users['count']=$number_data['first_lower']+$number_data['second_lower']+$number_data['third_lower'];
            //已购人数
            $users['purchase']=$usersLogic->purchase_number($user_id);
            $return = [];
            $return['lists'] = $users;
            DataReturn::returnBase64Json('200','请求数据成功！',$return);
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    // ‘我的’我的分销->我的粉丝
    public function my_fans(){
        try {
            $prefix = request()->domain();//图片前缀
            $pagesize = C('PAGESIZE');  //每页显示数
            $pages = I('pages') ? I('pages') : 1;
            $start =  ($pages-1) * $pagesize;
            $user_id = $this->user_id;
            $type_value = I('type_value') ? I('type_value') : 4;
            $account = I('account');
            if($account){
                //获取所属层级
                $usersLogic = new \app\common\logic\UsersLogic();
                $layer=$usersLogic->get_layer($user_id,$account); 
                if(empty($layer)) throw new Exception('该用户不属于您的下级');
                $user_list=M('users')->field('user_id,head_pic')->where('user_id',$account)->select();
            }else{
                switch ($type_value) {
                    case 4://所有下线(只查找三级)
                        $first_ids=M('users')->where('first_leader',$user_id)->column('user_id');
                        $second_ids=$first_ids ? M('users')->where('first_leader','in',$first_ids)->column('user_id') : [];
                        $third_ids=$second_ids ? M('users')->where('first_leader','in',$second_ids)->column('user_id') : [];
                        $user_ids=array_merge((array)$first_ids,(array)$second_ids,(array)$third_ids);
                        if(empty($user_ids)) return '';
                        $user_list = M('users')->field('user_id,head_pic')->where('user_id','in',$user_ids)->limit($start , $pagesize)->select();
                        break;
                    case 1://一级下线
                        $user_list=M('users')->field('user_id,head_pic')->where('first_leader',$user_id)->limit($start , $pagesize)->select();
                        break;
                    case 2://二级下线
                        $user_list=M('users as u1')
                            ->field('u2.user_id,u2.head_pic')
                            ->join('users u2', 'u1.user_id = u2.first_leader')
                            ->where('u1.first_leader',$user_id)
                            ->limit($start , $pagesize)
                            ->select();
                        break;
                    case 3://三级下线
                        $user_list=M('users as u1')
                            ->field('u3.user_id,u3.head_pic')
                            ->join('users u2', 'u1.user_id = u2.first_leader')
                            ->join('users u3', 'u2.user_id = u3.first_leader')
                            ->where('u1.first_leader',$user_id)
                            ->limit($start , $pagesize)
                            ->select();
                        break;
                }
            }
            $_user = array();
            if(!empty($user_list)){
                //查询是否已购买
                foreach ($user_list as $val) {
                    $uids[]=$val['user_id'];
                }
                $memberorder = M('order')->where('user_id','in',$uids)->where('order_status','in',['2','4'])->distinct(true)->column('user_id');
                foreach ($user_list as $key => $val) {
                    $arr=$val;
                    //头像
                    if($val['head_pic']){
                        $arr['head_pic'] = $prefix.$val['head_pic'];
                    }else{
                        $arr['head_pic'] = api_img_url('');
                    }
                    //是否已购
                    if(in_array($val['user_id'],$memberorder)){
                        $arr['memberorder']='已购';
                    }else{
                        $arr['memberorder']='未购';
                    }
                    $_user[]=$arr;
                }
            }
            $return = [];
            $return['lists'] = $_user;
            DataReturn::returnBase64Json('200','请求数据成功！',$return);
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }


    // 粉丝详细信息
    public function vermicelli_details(){
        try {
            $user_id = I('user_id');
            $prefix = request()->domain();//图片前缀
            $user = M('users')->where('user_id',$user_id)->field('head_pic,nickname,reg_time,distribut_money,pay_points,first_leader,second_leader,third_leader')->find();
            if(!$user){
                throw new Exception("没有此用户");
            }
            $user['head_pic'] = $prefix . $user['head_pic'];
            $user['reg_time'] = $user['reg_time'] != 0 ? date('Y.m.d', $user['reg_time']) : '0000.00.00';

            //获取一、二、三层下线人数
            $usersLogic = new \app\common\logic\UsersLogic();
            $number_data=$usersLogic->layer_number($user_id);
            $user['one_fans']=$number_data['first_lower'];
            $user['two_fans']=$number_data['second_lower'];
            $user['three_fans']=$number_data['third_lower'];
            $user['all_fans']=$number_data['first_lower']+$number_data['second_lower']+$number_data['third_lower'];
            //获取所属层级
            $layer=$usersLogic->get_layer($this->user_id,$user_id);
            if($layer == 1){
                $user['layer'] = '第一层';
            }elseif($layer == 2 ){
                $user['layer'] = '第二层';
            }elseif($layer == 3){
                $user['layer'] = '第三层';
            }else{
                $user['layer'] = '不是本用户下级';
            }

            $return = [];
            $return['lists'] = $user;
            DataReturn::returnBase64Json('200','请求数据成功！',$return);
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }


    // 个人资料
    public function user_info(){
        try {
            $_user = M('users')
            ->field('user_id,sex,birthday,user_money,frozen_money,distribut_money,underling_number,pay_points,address_id,reg_time,last_login,last_ip,qq,mobile,mobile_validated,oauth,unionid,head_pic,province,city,district,nickname,level,discount,total_amount,is_lock,is_distribut,first_leader,second_leader,third_leader')
            ->where('user_id',$this->user_id)
            ->find();
            $_user['reg_time'] = date('Y-m-d H:i:s', $_user['reg_time']);
            $_user['birthday'] = date('Y-m-d', $_user['birthday']);
            $sex_text =[0=>'保密',1=>'男',2=>'女'];
            $_user['sexname'] =$sex_text[$_user['sex']] ;

            $_user['coupon'] = M('coupon_list')
                ->alias('cl')
                ->join('coupon c','c.id = cl.cid','LEFT')
                ->where(['c.status'=>1 , 'cl.uid'=>$this->user_id])
                ->count();
            // 待支付
            $_user['waitpay'] = M('order')->where('user_id',$this->user_id)->where('pay_status',0)->where('order_status',0)->where('pay_code', NEQ ,"cod")->count();
            // 待发货
            $_user['waitsend'] = M('order')->where('user_id',$this->user_id)->where('shipping_status',0)->where('order_status','in',['0','1'])->where('pay_status=1 or pay_code = "cod"')->count();
                // echo M('order')->getlastsql();die;
            // 待收货
            $_user['waitreceive'] = M('order')->where('user_id',$this->user_id)->where('shipping_status',1)->where('order_status',1)->count();
            // 待评价
            $_user['waitccomment'] = M('order')->where('user_id',$this->user_id)->where('shipping_status',1)->where('order_status',2)->count();
            //退换货
            $_user['cahnge_goods'] = M('return_goods')->where('user_id',$this->user_id)->where('type','in',['0','1'])->count();


            $return = [];
            $return['lists'] = $_user;
            DataReturn::returnBase64Json('200','请求数据成功！',$return);
            // DataReturn::returnJson('200','请求数据成功！',$return);
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    //个人资料-》修改
    public function modify_user_nickname(){
        try {
            $data = [];
            $nickname = I('nickname');
            $sex = I('sex');
            $birthday = I('birthday');
            $mobile = I('mobile');
            if($nickname){
                $data['nickname'] = $nickname;
            }
            if($birthday){
                $data['birthday'] = strtotime($birthday);
            }
            if(in_array($sex,[0,1,2]))
            {
                $data['sex'] = $sex;
            }
            if($mobile){
                $yz = M('users')->where('user_id',$this->user_id)->where('mobile',$mobile)->count();
                 if (preg_match('/^1\d{9}$/',$mobile))
                 {
                   throw new Exception('您的手机号码不正确');
                 }
                if($yz ==0){
                    $data['mobile'] = $mobile;
                }else{
                    throw new Exception("此手机号已被使用");
                }
            }
            $res = M('users')->where('user_id',$this->user_id)->update($data);
            if($res){
                DataReturn::returnJson('200','修改成功！',$return);
            }else{
                throw new Exception("修改失败！");
            }
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }

    }

    // 个人资料-》收货地址
    public function user_address(){
        try {
            $address = M('user_address')->where('user_id',$this->user_id)->order('is_default desc')->field('consignee,address_id,mobile,province,city,district,address,is_default')->select();
            $_address = array();
            foreach ($address as $key => $val) {
                $_a = $val;
                $_a['province'] = M('region')->where('id',$val['province'])->value('name');
                $_a['province_id'] = $val['province'];
                $_a['city'] = M('region')->where('id',$val['city'])->value('name');
                $_a['city_id'] = $val['city'];
                $_a['district'] = M('region')->where('id',$val['district'])->value('name');
                $_a['district_id'] = $val['district'];
                $_address[] = $_a;
            }
            $return = [];
            $return['lists'] = $_address;
            DataReturn::returnBase64Json('200','请求数据成功！',$return);
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    //设置为默认地址
    public function default_user_address(){
        try {
            $address_id = I('address_id');
            if($address_id){
                $list = M('user_address')->where('user_id',$this->user_id)->update(['is_default'=>0]);
                $res = M('user_address')->where('address_id',$address_id)->update(['is_default'=>1]);
                DataReturn::returnJson('200','设置成功！');
            }else{
                throw new Exception("系统繁忙，请稍后再试！");
            }
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    // 个人资料-》删除收货地址
    public function delete_user_address(){
        try {
            $address_id = I('address_id');
            if($address_id){
                $res = M('user_address')->where('address_id',$address_id)->delete();
                if($res){
                    DataReturn::returnJson('200','删除数据成功！',$return);
                }else{
                    throw new Exception("删除失败！");
                }
            }else{
                throw new Exception("系统繁忙，请稍后再试！");
            }
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    // 个人资料-》修改收货地址
    public function modify_user_address(){
        try {
            $data = [];
            $address_id = I('address_id');
            $consignee = I('consignee');
            $mobile = I('mobile');
            $province = I('province');
            $city = I('city');
            $district = I('district');
            $address = I('address');
            $is_default = I('is_default');
            $data['consignee'] = $consignee;
            if(preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
                $data['mobile'] = $mobile;
            }else{
                throw new Exception("手机号格式不正确！");
            }
            $data['province'] = $province;
            $data['city'] = $city;
            $data['district'] = $district;
            $data['address'] = $address;
            $data['is_default'] = $is_default;
            $default_no = 0;
            $default = 0;
            if($address_id){
               $res = M('user_address')->where('address_id',$address_id)->update($data);
                // echo M('user_address')->getlastsql();
               // dump($res);
               if($is_default == 1){
                    $default_no = M('user_address')->where('user_id',$this->user_id)->save(['is_default'=>0]);
                    $default = M('user_address')->where('user_id',$this->user_id)->where('address_id',$address_id)->save(['is_default'=>1]);
                }
                if($res || $default_no || $default){
                    DataReturn::returnJson('200','修改数据成功！');
                }else{
                    throw new Exception("暂无数据修改");
                }
            }else{
                throw new Exception("请传入address_id！");
            }

        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    // 个人资料-》添加收货地址
    public function add_user_address(){
        try {
            $data = [];
            $user_id = $this->user_id;
            $consignee = I('consignee');
            $province = I('province');
            $city = I('city');
            $district = I('district');
            $address = I('address');
            $mobile = I('mobile');
            $is_default = I('is_default');
            $data['user_id'] = $user_id;
            $data['consignee'] = $consignee;
            $res['province'] = M('region')->where('id',$province)->value('id');
            if($res['province']){
                $data['province'] = $province;
            }else{
                throw new Exception("省份id错误");
            }
            $res['city'] = M('region')->where('id',$city)->value('id');
            if($res['city']){
                $data['city'] = $city;
            }else{
                throw new Exception("市id错误");
            }
            $res['district'] = M('region')->where('id',$district)->value('id');
            if($res['district']){
                $data['district'] = $district;
            }else{
                throw new Exception("区id错误");
            }
            $data['address'] = $address;
            if(preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
                $data['mobile'] = $mobile;
            }else{
                throw new Exception("手机号格式不正确！");
            }
            $data['is_default'] = $is_default;
            $res = M('user_address')->insertGetId($data);
            if($res){
                if($is_default == 1){
                    M('user_address')->where('user_id',$this->user_id)->save(['is_default'=>0]);
                    M('user_address')->where('user_id',$this->user_id)->where('address_id',$res)->save(['is_default'=>1]);
                }
            }else{
                throw new Exception("添加收货地址失败！");
            }
            DataReturn::returnJson('200','修改数据成功！');
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    //账户与安全
    public function account_safe(){
        try {
            $res['mobile'] = M('users')->where('user_id',$this->user_id)->value('mobile');
            if($res){
                $res['is_binding'] = '已绑定';
            }else{
                $res['is_binding'] = '未绑定';
            }
            $return = [];
            $return['lists'] = $res;
            DataReturn::returnBase64Json('200','请求数据成功！',$return);
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    // 修改登录密码
    public function password_update(){
        try {
            $old_password =encrypt(I('old_password'));
            $new_password_one = I('new_password_one');
            $new_password_two = I('new_password_two');
            if($new_password_one == $new_password_two){
                $password['password'] =encrypt($new_password_one);
            }else{
                throw new Exception("两次密码输入错误！");
            }
            $res = M('users')->where('password',$old_password)->where('user_id',$this->user_id)->count();
            if($res){
                $info = M('users')->where('user_id',$this->user_id)->update($password);
                DataReturn::returnJson('200','修改密码成功！');
            }else{
                throw new Exception("原始密码输入错误！");
            }
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    //余额总收支总收支
    public function account_type(){
        $tabselectdata = [];
        $tabselectdata[] = ['id' => 1,'name' => '收入', 'value'=>1];
        $tabselectdata[] = ['id' => 2,'name' => '支出', 'value'=>2];
        $plus_count = M('account_log')->where('user_id',$this->user_id)->where('user_money','>',' 0')->where('pay_points',0)->sum('user_money');// 总收入
        $plus_count = $plus_count ? $plus_count : 0;
        $minus_count = M('account_log')->where('user_id',$this->user_id)->where('user_money','<',' 0')->where('pay_points',0)->sum('user_money');// 总支出
        $minus_count = $minus_count ? $minus_count : 0;

        $return['minus_count'] = $minus_count;
        $return['plus_count'] = $plus_count;
        $return['tabselectdata'] = $tabselectdata;

        DataReturn::returnJson('200','请求数据成功！',$return);
    }

    //余额明细
    public function account_list(){
        try {
            $pagesize = C('PAGESIZE');  //每页显示数
            $pages = I('pages') ? I('pages') : 1;
            $start =  ($pages-1) * $pagesize;
            $tabselect = I('tabselect');
            $field = 'log_id,user_money,change_time,order_sn,desc';
            $where = [];
            $where['user_id'] = $this->user_id;
            $where['pay_points'] = '0';
            switch ($tabselect) {
                case 1:
                   $where['user_money'] = ['>',"0"];// 收入
                    break;
                case 2:
                    $where['user_money'] = ['<',"0"];// 支出
                    break;
                default:
                    # code...
                    break;
            }
            $res = M('account_log')->where($where)->order('change_time desc')->limit($start , $pagesize)->field($field)->select();
            $_res = array();
            foreach ($res as $key => $val) {
                $_r = $val;
                if($val['user_money'] > 0){
                    $_r['income_and_expenditure'] = "收入";
                    $_r['text_red'] = 'text-red';
                    $_r['user_money'] = '+' . $val['user_money'] ;
                }else{
                    $_r['income_and_expenditure'] = "支出";
                    $_r['text_red'] = '';
                }
                $_r['change_time'] = $val['change_time'] != 0 ? date('Y-m-d H:i:s', $val['change_time']) : '0000-00-00 00:00:00';
                $_res[] = $_r;
            }

            $return = [];
            $return['lists'] = $_res;
            DataReturn::returnJson('200','请求数据成功！',$return);
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    //积分总收支
    public function income_expenditure(){
        $tabselectdata = [];
        $tabselectdata[] = ['id' => 1,'name' => '收入', 'value'=>1];
        $tabselectdata[] = ['id' => 2,'name' => '支出', 'value'=>2];
        $plus_count = M('account_log')->where('user_id',$this->user_id)->where('pay_points','>',' 0')->where('user_money',0)->sum('pay_points');// 总收入
        $minus_count = M('account_log')->where('user_id',$this->user_id)->where('pay_points','<',' 0')->where('user_money',0)->sum('pay_points');// 总支出
        $plus_count = $plus_count ? $plus_count : 0;
        $minus_count = $minus_count ? $minus_count : 0;

        $return['minus_count'] = $minus_count;
        $return['plus_count'] = $plus_count;
        $return['tabselectdata'] = $tabselectdata;
        DataReturn::returnJson('200','请求数据成功！',$return);
    }

    //积分明细
    public function points_list(){
        try {
            $pagesize = C('PAGESIZE');  //每页显示数
            $pages = I('pages') ? I('pages') : 1;
            $start =  ($pages-1) * $pagesize;
            $tabselect = I('tabselect');
            $where = [];
            $where['user_id'] = $this->user_id;
            $where['user_money'] = '0';
            switch ($tabselect) {
                case 1:
                   $where['pay_points'] = ['>',"0"];// 收入
                    break;
                case 2:
                    $where['pay_points'] = ['<',"0"];// 支出
                    break;
                default:
                    # code...
                    break;
            }
            $res = M('account_log')->where($where)->order('change_time desc')->limit($start , $pagesize)->field('log_id,pay_points,change_time,order_sn,desc')->select();
            $_res = array();
            foreach ($res as $key => $val) {
                $_r = $val;
                if($val['pay_points'] > 0){
                    $_r['income_and_expenditure'] = "收入";
                    $_r['text_red'] = 'text-red';
                    $_r['pay_points'] = '+' . $val['pay_points'] ;
                }else{
                    $_r['income_and_expenditure'] = "支出";
                    $_r['text_red'] = '';
                }
                $_r['change_time'] = $val['change_time'] != 0 ? date('Y-m-d H:i:s', $val['change_time']) : '0000-00-00 00:00:00';
                $_res[] = $_r;
            }

            $return = [];
            $return['lists'] = $_res;
            DataReturn::returnJson('200','请求数据成功！',$return);
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    //充值记录
    public function recharge_list(){
        try {
            $pagesize = C('PAGESIZE');  //每页显示数
            $pages = I('pages') ? I('pages') : 1;
            $start =  ($pages-1) * $pagesize;
            $res = M('recharge')->where('user_id',$this->user_id)->field('pay_name,ctime,account,pay_status,order_id,order_sn')->order('ctime desc')->limit($start , $pagesize)->select();
            $_res = array();
            foreach ($res as $key => $val) {
                $_r = $val;
                if($val['pay_status'] == 0){
                    $_r['pay_status'] = "待支付";
                }elseif($val['pay_status'] == 1){
                    $_r['pay_status'] = "充值成功";
                }else{
                    $_r['pay_status'] = "交易关闭";
                }
                $_r['ctime'] = $val['ctime'] != 0 ? date('Y-m-d H:i:s', $val['ctime']) : '0000-00-00 00:00:00';
                $_res[] = $_r;
            }

            $return = [];
            $return['lists'] = $_res;
            DataReturn::returnBase64Json('200','请求数据成功！',$return);
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    //提现手续费
    public function servicecharge()
    {
        $user_money = M('users')->where('user_id',$this->user_id)->value('user_money'); //账户余额
        $rate  = M('config')->where('name','bill_charge')->value('value');
        $data['rate'] = $rate;
        $data['user_money'] = $user_money;
        $price = I('price');
        if ($price) {
           if ($rate == 0) {
                $money = 0;
            } else {
                $money = round($price * $rate * 0.01,2);
                if ($money < 0.01) {
                    $money = 0.01;
                }
            }
            $data['taxfee'] = $money;
            $data['amount'] = $money + $price;
        }
        DataReturn::returnJson(200,'成功',$data);

    }

    /**
     * 申请提现
     */
    public function withdrawals()
    {
        try {
            $data = I('post.');
            $data = DataReturn::baseFormat($data['data']);
            // dump($data);
            $data['user_id'] = $this->user_id;
            $user = M('users')->where('user_id',$this->user_id)->find(); //账户余额
            if (!$this->user_id) {
                throw new Exception('请先登录');
            }
            if (!$data['money'] || !$data['bank_name'] || !$data['bank_card'] || !$data['realname']) {
                throw new Exception('系统繁忙，请稍后再试！');
            }
//            if(encrypt($data['paypwd']) != $user['paypwd']){
//                throw new Exception('支付密码错误');
//            }
            $data['create_time'] = time();
            $distribut_min = tpCache('basic.min'); // 最少提现额度
            if ($data['money'] < $distribut_min) {
                throw new Exception('每次最少提现额度' . $distribut_min);
            }
            $amount = $data['money'] + $data['taxfee'];
            if ($amount > $user_money) {
                throw new Exception('提现金额超过账户余额');
            }
            $withdrawal = M('withdrawals')->where(['user_id' => $this->user_id, 'status' => 0])->sum('money');
            if ($user_money < ($withdrawal + $amount)) {
                throw new Exception('您有提现申请待处理，本次提现余额不足');
            }
            $add = M('withdrawals')->add($data);
            if (!$add) {
                throw new Exception('提交失败,联系客服!');
            }
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
        DataReturn::returnJson('200','已提交申请');
    }
    //提现记录
    public function withdrawals_list(){
        try {
            $pagesize = C('PAGESIZE');  //每页显示数
            $pages = I('pages') ? I('pages') : 1;
            $start =  ($pages-1) * $pagesize;
            $res = M('withdrawals')->where('user_id',$this->user_id)->field('id,create_time,money,status')->order('create_time desc')->limit($start , $pagesize)->select();
            $_res = array();
            foreach ($res as $key => $val) {
                $_r = $val;
                $status = ['0'=>'申请中','1'=>'审核通过','2'=>'付款成功','3'=>'付款失败','-1'=>'审核失败','-2'=>'删除作废'];
                $_r['status'] = $status[$val['status']];
                $_r['create_time'] = $val['create_time'] != 0 ? date('Y-m-d H:i:s', $val['create_time']) : '0000-00-00 00:00:00';
                $_res[] = $_r;
            }

            $return = [];
            $return['lists'] = $_res;
            DataReturn::returnBase64Json('200','请求数据成功！',$return);
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    // 优惠卷
    public function coupon_list(){
        try {
            $tabselect = I('tabselect');
            $pagesize = C('PAGESIZE');  //每页显示数
            $pages = I('pages') ? I('pages') : 1;
            $start =  ($pages-1) * $pagesize;
            // dump($tabselect);
            $where['uid'] = $this->user_id;
            if($tabselect == 0){//0未使用1已使用2已过期
                $where['cl.status'] = 0;
                $where['c.status'] = 1;
            }elseif($tabselect == 1){
                $where['cl.status'] = 1;
                $where['c.status'] = 1;
            }elseif($tabselect == 2){
                $where['cl.status'] = 2;
                $where['c.status'] = 1;
            }
            $res = M('coupon_list')
                ->alias('cl')
                ->join('coupon c','c.id = cl.cid','LEFT')
                ->where(['cl.uid'=>$this->user_id])
                ->field('c.money,c.condition,c.name,c.use_type,c.use_end_time,c.status')
                ->limit($start , $pagesize)
                ->order('cl.send_time desc')
                ->where($where)
                ->select();
            // echo M('coupon_list')->getlastsql();exit;
            // dump($res);die;
            $_res = array();
            foreach ($res as $key => $val) {
                $_r = $val;
                if($tabselect ==2){
                    $_r['expired'] = 'expired';
                }else{
                    $_r['expired'] = '';
                }
                if($tabselect ==1){
                    $_r['use_end_time'] = '已使用';
                }else{
                    $_r['use_end_time'] = $val['use_end_time'] != 0 ? '限' . date('Y-m-d H:i:s', $val['use_end_time']) . '前使用': '0000-00-00 00:00:00';
                }
                if($val['status'] == 0){
                    $_r['status'] = '无效优惠卷';
                }
                $_r['money'] = ceil($val['money']);
                // $_r['status'] = $val['status'];
                // dump(ceil($_r['money']));
                $_r['condition'] = '满'. $val['condition'] . '元使用';
                $_r['name'] = $val['name'];
                $use_type = ['0'=>'全店通用','1'=>'指定商品可用','2'=>'指定分类商品可用'];
                $_r['use_type'] = $use_type[$val['use_type']];
                $_res[] = $_r;
            }

            $return = [];
            $return['lists'] = $_res;
            DataReturn::returnJson('200','请求数据成功！',$return);
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }


    //用户分享商品
    public function share_goods(){
        try {
            $goods_id = I('goods_id');
            if($goods_id){
                $goods_integral = tpCache('basic.goods_integral'); // 会员分享赠送积分
                $count = M('share')->where(["user_id"=>$this->user_id,"goods_id"=>$goods_id])->count();
                if ($count > 0){
                    throw new Exception('商品已分享');
                }else{
                    $goods_info = M('goods')->where('goods_id',$goods_id)->field('goods_name,original_img,shop_price')->find();
                    $goods_info['goods_id']=$goods_id;
                    $goods_info['user_id']=$this->user_id;
                    $goods_info['share_t']=time();
                    $goods_info['integral']= $goods_integral;  //分享积分
                    $re = M('share')->insert($goods_info);
                    if($re){
                        M('users')->where(['user_id'=>$this->user_id])->setInc('pay_points',$goods_integral);
                        DataReturn::returnJson('200','分享成功');
                    }
                }
            }else{
               throw new Exception('系统繁忙,稍后再试！');
            }
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }



    //快递插件
    public function shipping(){
        $data = M('plugin')->where(['type'=>'shipping','status'=>1])->field('code,name')->select();
        DataReturn::returnJson(200,'获取数据成功',$data);
    }
    //查快递
    public function check_express(){
        $shipping_code = I('shipping_code'); //快递公司
        $invoice_no = I('invoice_no'); //快递单号
        if (!$shipping_code || !$invoice_no) {
            DataReturn::returnJson(500,'系统出错');
        }
        // $mobel = new SearchWordLogic;
        // if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $shipping_code)<1){
        //     DataReturn::returnJson(500,'物流公司只能是中文');
        // }
        // $shipping_code = $mobel->getPinyinFull($shipping_code);
        $logistics = queryExpress($shipping_code, $invoice_no);
        if ($logistics['status'] == 200) {
            foreach ($logistics['data'] as $key => $value) {
                $time = strtotime($value['time']);
                $_t   =[
                    'specificdate' => date('Y.m.d', $time),
                    'timedivision' => date('H:i', $time),
                    'context'      => $value['context'],
                ];
                $list[] = $_t;
            }
            $status      = 200;
            $message     = '查询成功';
            $region_list = get_region_list();
            $data = [
                'shipping_name' => $delivery['shipping_name'],
                'invoice_no'    => $delivery['invoice_no'],
                'list'          => $list,
                'consignee'     => $order['consignee'],
                'address'       => $region_list[$order['province']] . $region_list[$order['city']] . $region_list[$order['district']] . $order['address'],
            ];
        } else {
            $message = $logistics['message'];
            $status  = 500;
            $data    = [
                'list' => '',
            ];
        }
        DataReturn::returnJson($status, $message, $data);
    }

    //手机发送验证码
    public function mobile_code(){
        try {
                $mobile = I('mobile');
                if($mobile == "")throw new Exception('手机号不能为空'.$resp['msg']);
                if(!check_mobile($mobile))throw new Exception('手机号格式不正确'.$resp['msg']);
                $session_id = session_id();
                // dump($session_id);die;
                $scene = 2;

                //发送短信验证码
                $res = checkEnableSendSms($scene);
                if($res['status'] != 1){
                    throw new Exception($res['msg']);

                }
                //判断是否存在验证码
                $data = M('sms_log')->where(array('mobile'=>$mobile,'session_id'=>$session_id, 'status'=>1))->order('id DESC')->find();
                //获取时间配置
                $sms_time_out = tpCache('sms.sms_time_out');
                $sms_time_out = $sms_time_out ? $sms_time_out : 120;
                //120秒以内不可重复发送
                if($data && (time() - $data['add_time']) < $sms_time_out){
                    //$return_arr = array('status'=>-1,'msg'=>$sms_time_out.'秒内不允许重复发送');
                    throw new Exception($sms_time_out.'秒内不允许重复发送');

                }
                //随机一个验证码
                $code = rand(1000, 9999);
                $params['code'] =$code;

                //发送短信
                $resp = sendSms($scene , $mobile , $params, $session_id);
                // dump($resp);
                if($resp['status'] == 1){
                    //发送成功, 修改发送状态位成功
                    M('sms_log')->where(array('mobile'=>$mobile,'code'=>$code,'session_id'=>$session_id , 'status' => 0))->save(array('status' => 1));
                    //$return_arr = array('status'=>1,'msg'=>'发送成功,请注意查收');
                    $str_a = substr($mobile,0,3);
                    $str_b = substr($mobile,-4);
                    $datas = '我们向'.$str_a.'****'.$str_b.'发送了一个验证码';
                    DataReturn::returnJson(200,"发送成功,请注意查收",$datas);


            } else {
                throw new Exception('手机号格式错误！');
            }
        } catch (Exception $e) {
           DataReturn::returnJson('400',$e->getMessage());
        }
    }

    //绑定手机
    public function binding_phone(){
        try {
            $code = I('code');
            $mobile = I('mobile');
            $session_id = session_id();
            // dump($session_id);die;
            if(check_mobile($mobile)){
                if(!empty($code)){
                    $res = M('sms_log')->where('code',$code)->where('mobile',$mobile)->where('session_id',$session_id)->find();
                        if($res){
                            $arr['mobile'] = $mobile;
                            $arr['mobile_validated'] = 1;
                            $data = M('users')->where('user_id',$this->user_id)->update($arr);
                            $del = M('sms_log')->where('code',$code)->where('mobile',$mobile)->where('session_id',$session_id)->delete();
                            if($data){
                                DataReturn::returnJson('200','验证成功');
                            }else{
                                throw new Exception('系统繁忙,稍后再试！');
                            }

                        }else{
                            throw new Exception('系统繁忙,稍后再试！');
                        }
                }else{
                   throw new Exception('验证码不能为空！');
                }
            }else{
               throw new Exception('手机号格式错误！');
            }

        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

    //关联上下级
    public function contact_leader(){
        $parent_id = I('user_id/d');//上级id
        $user_id = $this->user_id;
        $users=M('users');
        $parent_info = $users->where(['user_id'=>$parent_id])->find();
        if($user_id==$parent_id)
            DataReturn::returnJson('400','不能成为自己的下级');
        if(empty($parent_info))
            DataReturn::returnJson('400','所绑定上级用户的信息有误');
        if($parent_info['first_leader']==$user_id)
            DataReturn::returnJson('400','您已是他的上级，不能绑定');
        $user_info = $users->where(['user_id'=>$user_id])->find();
        if($user_info['first_leader'])
            DataReturn::returnJson('400','您已经存在上级，不可以继续绑定');
        //绑定上下级关系
        $result = $users->where(['user_id'=>$user_id])->save(['first_leader'=>$parent_id]);
        if($result!==false){
            DataReturn::returnJson('200','绑定成功');
        }else{
            DataReturn::returnJson('400','绑定失败');
        }
    }

    //获取上级用户信息
    public function leader_info(){
        $parent_id = I('user_id/d');//上级id
        $info = M('users')->field('nickname,head_pic')->where(['user_id'=>$parent_id])->find();
        if(empty($info))
            DataReturn::returnJson('400','用户不存在');
        $data['nickname']=$info['nickname'];
        $data['head_pic']=$info['head_pic'] ? request()->domain().$info['head_pic'] : '';
        DataReturn::returnJson('200','',$data);
    }

    //我的小程序码
    public function mycode(){
        $user_id=$this->user_id;
        $wxcode=M('users')->where(['user_id'=>$user_id])->value('wx_code');

        if(!empty($wxcode) && file_exists($wxcode)){
            DataReturn::returnJson('200','',['imageurl'=>request()->domain().'/'.$wxcode]);
        }else{
            $paymentPlugin = M('Plugin')->where("code='miniAppPay' and  type = 'payment' ")->find(); // 找到微信支付插件的配置
            $config_value = unserialize($paymentPlugin['config_value']); // 配置反序列化
            $appid = $config_value['appid']; // * APPID
            $appsecret = $config_value['appsecret']; // * appsecret
            $post_arr = [
                // 'page'  => 'pages/contact_leader/contact_leader',
                'scene' => 'user_id$'.$user_id,
            ];

            $jssdk = new JssdkLogic($appid,$appsecret);
            $base64=$jssdk->getwxacodeunlimit($post_arr);

            if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $img)){
                $type = $img[2];
            }else{
                DataReturn::returnJson('400','获取失败');
            }
            $file = 'public/wxcode/'.date('Ymd', time()).'/';
             //检查是否有该文件夹，如果没有就创建
            if (!file_exists($file)) {
                mkdir($file, 0777, true);
            }
            $imgpath = $file . md5(time()).'.'.$type;
            //将生成的小程序码存入相应文件夹下
            file_put_contents($imgpath,base64_decode(str_replace($img[1],'',$base64)));
            //写入数据库
            M('users')->where(['user_id'=>$user_id])->update(['wx_code'=>$imgpath]);
            DataReturn::returnJson('200','',['imageurl'=>request()->domain().'/'.$imgpath]);
        }
    }

    //查询是否绑定手机号
    public function isset_mobile(){
        try {
            $user_id = $this->user_id;
            $list = M('users')->where('user_id',$user_id)->value('mobile');
            if($list){
                DataReturn::returnJson('200','已绑定手机号',$list);
            }else{
                throw new Exception('该用户没有绑定手机号');
            }
        } catch (Exception $e) {
            DataReturn::returnJson('400',$e->getMessage());
        }
    }

        // 修改密码
    //     public function paypwd()
    // {
    //     try {
    //         $user_id = $this->user_id;
    //         $user = M('users')->where('user_id', $user_id)->find();
    //         $time = time()-600;
    //         $step = I('step');
    //         $mobile = I('mobile');
    //         $code = I('code');
    //         $session_id =session_id();
    //         if($step == 1){
    //             $res = M('sms_log')->where(['session_id'=>$session_id,'mobile'=>$mobile])->where('add_time','>',$time)->order('id desc')->value('code');
    //             $res_code = M('sms_log')->where(['session_id'=>$session_id,'mobile'=>$mobile,'code'=>$code])->value('add_time');
    //             if($res_code < $time){
    //                 throw new Exception('验证码已过期，请重新发送');
    //             }
    //             if($res && $res == $code){
    //                 DataReturn::returnJson('200','验证通过');
    //             }else{
    //                 throw new Exception('验证失败，请稍后重试');
    //             }
    //         }else{
    //             $res = M('sms_log')->where(['session_id'=>$session_id,'mobile'=>$mobile])->order('id desc')->value('code');
    //             if($res != $code) throw new Exception('系统繁忙，请稍后再试！');
    //             $new_password = trim(I('new_password'));
    //             $confirm_password = trim(I('confirm_password'));
    //             // $oldpaypwd = trim(I('old_password'));
    //             if($confirm_password == ''){
    //                 throw new Exception('新支付密码不能为空');
    //             }
    //             if(strlen($new_password) < 6 || strlen($new_password) > 18){
    //                 throw new Exception('密码长度不符合规范');
    //             }
    //             //以前设置过就得验证原来密码
    //             // if(!empty($user['paypwd']) && ($user['paypwd'] != encrypt($oldpaypwd))){
    //             //     throw new Exception('原密码验证错误！');
    //             // }
    //             $userLogic = new UsersLogic();
    //             $data = $userLogic->paypwd($this->user_id, $new_password, $confirm_password);
    //             if($data['status'] == 1){
    //                 DataReturn::returnJson('200',$data['msg']);
    //              } else {
    //                 throw new Exception($data['msg']);
    //              }

    //         }
    //     } catch (Exception $e) {
    //         DataReturn::returnJson('400',$e->getMessage());
    //     }
    // }


}