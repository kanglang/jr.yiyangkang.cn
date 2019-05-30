<?php

namespace app\api\controller;
use app\common\logic\PigFlashBuy;
use think\Db;
use My\DataReturn;
use app\common\controller\Recommend;
use app\common\logic\Game;
use app\api\controller\Addsalt;

class Business extends Base{

    public function __construct()
    {
        parent::__construct();
        $this->checkLogin();
    }

    //领养记录
    public function adopt_log(){
        //领养中
        $adoption = db('pig_order')
                    ->alias('a')
                    ->join('pig_goods b','a.pig_level = b.id')
                    ->field('a.*,b.goods_name,b.small_price,b.large_price,b.contract_days,b.income_ratio')
                    ->where(['purchase_user_id'=>$this->user_id,'pay_status'=>1])//领养中
                    ->order('establish_time desc')
                    ->select();
        $pig_conversion = config('pig_conversion');
        //已领养
        $adopted  = db('user_exclusive_pig')
                    ->alias('a')
                    ->join('pig_goods b','a.pig_id = b.id')
                    ->join('pig_order c','a.order_id = c.order_id')
                    ->field('a.*,b.goods_name,b.small_price,b.large_price,b.contract_days,b.income_ratio,c.pig_order_sn,c.order_id,c.sell_user_id,c.pay_status')
                    ->where(['a.user_id'=>$this->user_id,'a.is_able_sale'=>0])//已领养
                    ->order('a.buy_time desc')
                    ->select();
                    // dump($adopted);
        //已销毁
        $destroy  = db('user_exclusive_pig_del')
                    ->alias('a')
                    ->join('pig_goods b','a.pig_id = b.id')
                    ->join('pig_order c','a.order_id = c.order_id')
                    ->field('a.*,b.goods_name,b.small_price,b.large_price,b.contract_days,b.income_ratio,c.pig_order_sn,c.order_id,c.sell_user_id,c.pay_status')
                    ->where(['a.user_id'=>$this->user_id,'a.type'=>'del'])//已领养
                    ->order('a.buy_time desc')
                    ->select();           
        //申诉中
        $appeal = db('pig_order')
                    ->alias('a')
                    ->join('pig_appeal c','a.order_id = c.order_id')
                    ->field('a.*,c.remark')
                    ->where(['a.pay_status'=>0,'a.purchase_user_id'=>$this->user_id])//申诉中
                    ->order('a.appeal_time desc')
                    ->select();
        $adoption_list  = [];//领养中
        $adopted_list   = [];//已领养
        $appeal_list    = [];//申诉中
        $destroy_list   = [];//已销毁

        if ($adoption) {//领养中
            foreach ($adoption as $key => $value) {
                $value['establish_time'] = date('Y-m-d H:i:s',$value['establish_time']);
                $value['remaining_time'] = $value['end_time'] - time();//领养剩余时间
                $value['profit'] = ($value['income_ratio']/$value['contract_days']/100)*$value['pig_price'];
                $value['pig_currency'] = $pig_conversion*$value['pig_price'];
                $adoption_list[] = $value;
            }
        }

        if ($adopted) {//已领养
            foreach ($adopted as $key => $value) {
                $value['buy_time'] = date('Y-m-d H:i:s',$value['buy_time']);
                $value['profit'] = ($value['income_ratio']/$value['contract_days']/100)*$value['price'];
                $value['pig_currency'] = $pig_conversion*$value['price'];
                $adopted_list[] = $value;
            }
        }
        
        if ($appeal) {//申诉中
            foreach ($appeal as $key => $value) {
                $value['establish_time'] = date('Y-m-d H:i:s',$value['establish_time']);
                $value['appeal_time']    = date('Y-m-d H:i:s',$value['appeal_time']);
                $appeal_list[] = $value;
            }
        }

        if($destroy){ //已销毁
             foreach ($destroy as $key => $value) {
                $value['buy_time'] = date('Y-m-d H:i:s',$value['buy_time']);
                $value['deltime'] = date('Y-m-d H:i:s',$value['deltime']);
                $value['profit'] = ($value['income_ratio']/$value['contract_days']/100)*$value['price'];
                $value['pig_currency'] = $pig_conversion*$value['price'];
                $destroy_list[] = $value;
            }
        }

        $res = [
            'adoption'  =>$adoption_list,//领养中
            'adopted'   =>$adopted_list,//已领养
            'appeal'    =>$appeal_list,//申诉中
            'destroy'   =>$destroy_list//已销毁
        ];
        DataReturn::returnBase64Json(200,'ok',$res);
    }

    //转让记录
    public function transfer_log(){
        // dump(111);die;
        $pig_conversion = config('pig_conversion');
        //待转让
        $transferon = db('user_exclusive_pig')
                    ->alias('a')
                    ->join('pig_goods b','a.pig_id = b.id')
                    ->field('a.*,b.goods_name,b.small_price,b.large_price,b.contract_days,b.income_ratio')
                    ->where(['user_id'=>$this->user_id,'is_able_sale'=>1])//待转让
                    ->order('buy_time desc')
                    ->select();
                    // halt($transferon);
        //转让中
        $transfer  = db('pig_order')
                    ->alias('a')
                    ->join('pig_goods b','a.pig_level = b.id')
                    ->join('users c','a.purchase_user_id = c.user_id')
                    ->field('a.*,b.goods_name,b.small_price,b.large_price,b.contract_days,b.income_ratio,c.nickname')
                    ->where(['sell_user_id'=>$this->user_id,'pay_status'=>1])//转让中
                    ->order('establish_time desc')
                    ->select();
        //已转让
        $transfered = db('pig_order')
                    ->alias('a')
                    ->join('pig_goods b','a.pig_level = b.id')
                    ->field('a.*,b.goods_name,b.small_price,b.large_price,b.contract_days,b.income_ratio')
                    ->where(['sell_user_id'=>$this->user_id,'pay_status'=>2])//已转让
                    ->order('establish_time desc')
                    ->select();
        //申诉中
        $appeal = db('pig_order')
                    ->alias('a')
                    ->join('pig_appeal c','a.order_id = c.order_id')
                    ->field('a.*,c.remark')
                    ->where(['a.pay_status'=>0,'c.user_id'=>$this->user_id])//申诉中
                    ->order('a.appeal_time desc')
                    ->select();
        // dump(db()->getLastsql());die();
        $transferon_list    = [];
        $transfer_list      = [];
        $transfered_list    = [];
        $appeal_list        = [];

        if ($transferon) {
            foreach ($transferon as $key => $value) {
                $value['buy_time'] = date('Y-m-d H:i:s',$value['buy_time']);
                $value['profit'] = ($value['income_ratio']/$value['contract_days']/100)*$value['price'];
                $value['pig_currency'] = $pig_conversion*$value['price'];
                $transferon_list[] = $value;
            }
        }

        if ($transfer) {
            foreach ($transfer as $key => $value) {
                $value['establish_time'] = date('Y-m-d H:i:s',$value['establish_time']);
                $value['remaining_time'] = $value['end_time'] - time();//转让剩余时间
                $value['profit'] = ($value['income_ratio']/$value['contract_days']/100)*$value['pig_price'];
                $value['pig_currency'] = $pig_conversion*$value['pig_price'];
                $transfer_list[] = $value;
            }
        }
        
        if ($transfered) {
            foreach ($transfered as $key => $value) {
                $value['establish_time'] = date('Y-m-d H:i:s',$value['establish_time']);
                $value['profit'] = ($value['income_ratio']/$value['contract_days']/100)*$value['pig_price'];
                $value['pig_currency'] = $pig_conversion*$value['pig_price'];
                $transfered_list[] = $value;
            }
        }
        if ($appeal) {
            foreach ($appeal as $key => $value) {
                $value['establish_time'] = date('Y-m-d H:i:s',$value['establish_time']);
                $value['appeal_time'] = date('Y-m-d H:i:s',$value['appeal_time']);
                $appeal_list[] = $value;
            }
        }

        $res = [
            'transferon'    =>$transferon_list,
            'transfer'      =>$transfer_list,
            'transfered'    =>$transfered_list,
            'appeal'        =>$appeal_list,
        ];
        DataReturn::returnBase64Json(200,'ok',$res);
    }

    //预约记录
    public function reservation_log(){
        $data = db('pig_reservation')
                    ->alias('a')
                    ->join('pig_goods b','a.pig_id = b.id')
                    ->field('a.*,b.goods_name,b.small_price,b.large_price,b.contract_days,b.income_ratio')
                    ->order('reservation_time desc')
                    ->where(['user_id'=>$this->user_id])
                    ->select();
        $list = [];
        if ($data) {
            foreach ($data as $key => $value) {
                $value['reservation_time']   = date('Y-m-d H:i:s',$value['reservation_time']);
                $value['reservation_status'] = str_replace([1,0], ['已抢到','未抢到'], $value['reservation_status']);
                $list[] = $value;
            }
        }
        DataReturn::returnBase64Json(200,'ok',$list);
    }

    //申诉
    public function appeal(){
        if (request()->isPost()) {
            $post = I('post.');
            $user_id = $this->user_id;
            $order_id = $post['data']['order_id'];
            $list = db('pig_appeal')->where(['order_id'=>$order_id,'user_id'=>$user_id])->find();
            if ($list) {
                DataReturn::returnBase64Json(0, '你已提交申诉,无需重复提交', []);
            }
            $pig_order = db('pig_order')->where(['order_id'=>$order_id])->find();
            // halt($data);
            if ($pig_order['purchase_user_id'] == $user_id) {//购买人
                $complainant = 1;//买家申诉
            } else if($pig_order['sell_user_id'] == $user_id) {//出售人
                $complainant = 2;//卖家申诉
            }
            $data['order_id']    = $order_id;
            $data['user_id']     = $user_id;
            $data['img_url']     = $pig_order['img_url'];
            $data['add_time']    = $pig_order['establish_time'];
            $data['remark']      = $post['data']['remark'];
            $data['complainant'] = $complainant;

            // 启动事务
            Db::startTrans();
            try{
                db('pig_appeal')->insertGetId($data);//申诉表添加数据
                db('pig_order')->where(['order_id'=>$order_id])->update(['pay_status'=>0,'appeal_time'=>time()]);

                // 提交事务
                Db::commit();
                DataReturn::returnBase64Json(200,'操作成功',[]);
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                DataReturn::returnBase64Json(0, '操作失败1', []);
            }
        } else {
            DataReturn::returnBase64Json(0,'操作失败',[]);
        }
    }

    //转让福分
    public function transfer()
    {
        if(request()->isPost()){
            $post= input('post.');
            $mobile = $post['data']['mobile'];
            $number = $post['data']['number'];
            $paypwd = $post['data']['password'];
            $user = Db::name('users')->where('mobile',$mobile)->find();
            if(!$user){
                DataReturn::returnBase64Json(0, '暂无此用户,请检查后输入', []);
            }
            $list=Db::name('users')->where('user_id',$this->user_id)->find();
            if($list['pay_points'] < $number){
                DataReturn::returnBase64Json(0, '福分数量不足!', []);
            }
            if(empty($list['paypwd'])){
                DataReturn::returnBase64Json(304, '请先设置交易密码', '/dist/pages/set_paypwd.html');
            }
            if(encrypt($paypwd) != $list['paypwd'] ){
                DataReturn::returnBase64Json(0, '交易密码错误!', []);
            }
            // 启动事务
            Db::startTrans();
            try{
                Db::name('users')->where('user_id',$user['user_id'])->setInc('pay_points',$number);//增加
                Db::name('users')->where('user_id',$this->user_id)->setDec('pay_points',$number);//减少
                accountLog1($this->user_id,0,0,-$number,'转赠给用户id:'.$user['user_id'].',手机号'.$user['mobile'],2);//资金流日志
                accountLog1($user['user_id'],0,0,$number,'转赠收入  用户id:'.$this->user_id.',手机号'.$this->user_info['mobile'],2);//资金流日志
                Recommend::up_level($user['user_id']);
                // 提交事务
                Db::commit();
                DataReturn::returnBase64Json(200,'操作成功',[]);
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                DataReturn::returnBase64Json(0, '操作失败', []);
            }
        }
        DataReturn::returnBase64Json(0, '操作失败', []);
    }

    //福分记录
    public function blessings_log(){
        $_list = db('account_log')->where(['user_id'=>$this->user_id,'pay_points'=>['<>',0]])->order('change_time desc')->select();
        $pay_points = db('users')->where(['user_id'=>$this->user_id])->value('pay_points');
        if ($_list) {
            foreach ($_list as $key => $value) {
                $value['change_time'] = date('Y-m-d H:i:s',$value['change_time']);
                $list[] = $value;
            }
            $data = [
                'list'=>$list,
                'pay_points'=>$pay_points
            ];
            DataReturn::returnBase64Json(200, '请求成功', $data);
        }else{
            $data = [
                'pay_points'=>$pay_points
            ];
            DataReturn::returnBase64Json(0, '暂无数据', $data);
        }
    }

    //推广财分收益记录
    public function profit(){
        $_list = db('account_log')->where(['user_id'=>$this->user_id,'user_money'=>['<>',0]])->order('change_time desc')->select();
        $accumulated_income = db('account_log')->where(['user_id'=>$this->user_id])->sum('user_money');//累计收益
        if ($_list) {
            foreach ($_list as $key => $value) {
                $value['change_time'] = date('Y-m-d H:i:s',$value['change_time']);
                $list[] = $value;
            }
            $data = [
                'list'=>$list,
                'accumulated_income'=>$accumulated_income
            ];
            DataReturn::returnBase64Json(200, '请求成功', $data);
        }else{
            $data = [
                'accumulated_income'=>$accumulated_income
            ];
            DataReturn::returnBase64Json(0, '暂无数据', $data);
        }
    }

    //合约收益记录
    public function profit_log(){
        $list = db('pig_doge_money')->where(['user_id'=>$this->user_id,'type'=>3])->order('add_time desc')->select();
        if ($list) {
            foreach ($list as $key => $value) {
                $value['add_time'] = date('Y-m-d H:i:s',$value['add_time']);
                $data[] = $value;
            }
            DataReturn::returnBase64Json(200, '请求成功', $data);
        }else{
            DataReturn::returnBase64Json(0, '暂无数据', []);
        }
    }

    //推广财分出售
    public function sell(){
        if(request()->isPost()){
            $post= input('post.');
            $number = $post['data']['number'];
            $paypwd = $post['data']['paypwd'];
            $min_sell_usermoney = config('min_sell_usermoney');
            $list=Db::name('users')->where('user_id',$this->user_id)->find();
            
            
           // 2019_4_2---------
            $sale_low_money=config('sale_low_money');//最少交易的财分数量
            $sale_max_money=config('sale_max_money');//最大交易的财分数量
            $sale_day_maxnum=config('sale_day_maxnum');//每日最多可以出售多少次

            if($number<$sale_low_money){
               DataReturn::returnBase64Json(0, '出售最低不低于'.$sale_low_money.'财分', []);
            }
            
            if($number>$sale_max_money){
               DataReturn::returnBase64Json(0, '出售最高不超过'.$sale_max_money.'财分', []);
            }


            $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));    //今天开始时间
            $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;  //今天结束时间
            
            $mytodaysell = Db::name('user_exclusive_pig')      //我今天出售财分获得的鱼
                    ->where('buy_time', '>=', $beginToday)
                    ->where('buy_time', '<', $endToday)
                    ->where(['buy_type'=>'sell','user_id'=>$this->user_id])
                    ->count();

           if($sale_day_maxnum<=$mytodaysell){
              DataReturn::returnBase64Json(0, '您已超过每日限购次数', []);
           }
         
          // 2019_4_2---------

             $addsalt = new \app\api\controller\Addsalt();
             $usermoneysalt=$addsalt->checkuserMoneysalt($this->user_id);
            if(!$usermoneysalt){
                DataReturn::returnBase64Json(0, "您的账号异常，暂时不可出售推广财分", []);
             } 

            if(empty($list['paypwd'])){
                DataReturn::returnBase64Json(304, '请先设置交易密码', '/dist/pages/set_paypwd.html');
            }
            
          
         



            if($list['user_money'] < $number){
                DataReturn::returnBase64Json(0, '推广财分数量不足!', []);
            }
            if($number < $min_sell_usermoney){
                DataReturn::returnBase64Json(0, '最小出售推广财分数量为'.$min_sell_usermoney.'!', []);
            }
            if(encrypt($paypwd) != $list['paypwd'] ){
                DataReturn::returnBase64Json(0, '交易密码错误!', []);
            }

            $small_price = Db::name('pig_goods')->min('small_price');
            $large_price = Db::name('pig_goods')->max('large_price');
            $small_price_list = Db::name('pig_goods')->where(['small_price'=>$small_price])->find();
            $large_price_list = Db::name('pig_goods')->where(['large_price'=>$large_price])->find();

            if ($number < $small_price) {
                $pig_goods = $small_price_list;
            }elseif ($number > $large_price) {
                $pig_goods = $large_price_list;
            }else{
                $pig_goods = Db::name('pig_goods')
                    ->where('small_price', '<=', $number)
                    ->where('large_price', '>=', $number)
                    ->find();
            }
            $data = [];
            $data['user_id']    = $this->user_id;
            $data['pig_id']     = $pig_goods['id'];
            $data['price']      = $number;
            $data['buy_time']   = time();
            $data['end_time']   = time()+24*3600*$pig_goods['contract_days'];
            $data['is_able_sale']   = 1;
            // 启动事务
            Db::startTrans();
            try{
                accountLog($this->user_id,-$number,0,'出售推广财分',0,0,'',9);//资金流日志
                $pig_id = Db::name('user_exclusive_pig')->insertGetId($data);
                //生成系统订单
                $game = new Game();
                $pig_order_sn = $game->get_order_sn();
                $order = [];
                $order['establish_time']   = time();
                $order['pig_order_sn']     = $pig_order_sn;
                $order['pay_status']       = 2;
                $order['sell_user_id']     = 0;//系统
                $order['purchase_user_id'] = $this->user_id;
                $order['pig_level']        = $pig_goods['id'];
                $order['pig_price']        = $number;
                $order['pig_id']           = $pig_id; //鱼所属用户的ID
                $order['end_time']         = time();
                $order_id = Db::name('pig_order')->insertGetId($order);

                //pig加盐------------------
                $buytype='sell';
                $res=new \app\api\controller\Addsalt();
                $pigsalt=$res->pigaddsalt($this->user_id,$order_id,$data['buy_time'],$buytype);


                //更新目前的用户所属鱼的对应的Order_id    
                Db::name('user_exclusive_pig')->where(['id'=>$pig_id])->save(['order_id'=>$order_id,'pig_salt'=>$pigsalt,'buy_type'=>$buytype]);
                //加入到redis
                $pfb = new PigFlashBuy();
                $pfb->addPigQueue($pig_goods['id'],$pig_id);


                // 提交事务
                Db::commit();
                DataReturn::returnBase64Json(1,'操作成功',[]);
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                DataReturn::returnBase64Json(0, '操作失败1', []);
            }
        }
        DataReturn::returnBase64Json(0, '操作失败', []);
    }

    //pig币
    public function pig_money(){
        $_list = db('pig_doge_money')->where(['user_id'=>$this->user_id,'type'=>1])->order('add_time desc')->select();
        $user_info = $this->user_info;
        $data = [];
        $list = [];
        foreach ($_list as $key => $value) {
            $value['add_time']  = date('Y-m-d H:i:s',$value['add_time']);
            $list[] = $value;
        }
        $pig_currency = $user_info['pig_currency'];
        $data = [
            'pig_currency'=>$pig_currency,
            'list'        =>$list,
        ];
        DataReturn::returnBase64Json(200, '请求成功',$data);
    }

    //pig币提取
    public function pig_extract(){
        if(request()->isPost()){
            $post= input('post.');
            $number = $post['data']['number'];
            $wallet_address = $post['data']['wallet_address'];
            $users = db('users')->where(['user_id'=>$this->user_id])->find();
            if (!$number) {
                DataReturn::returnBase64Json(0, '请输入提取数量!',[]);
            }
            if (!$wallet_address) {
                DataReturn::returnBase64Json(0, '请输入FISH钱包地址!',[]);
            }
            //pig币最少提取数量
            $limit_pig_extract = config('limit_pig_extract');
            if ($number < $limit_pig_extract) {
                DataReturn::returnBase64Json(0, '每次最少提取pig币数量为'.$limit_pig_extract.'!',[]);
            }
            //pig币手续费
            $pig_fee = config('pig_fee');
            $data['taxfee']= $number*$pig_fee*0.01;
            $pig_currency = $number+$data['taxfee'];
            if ($users['pig_currency'] < $pig_currency) {
                DataReturn::returnBase64Json(0, 'pig币不足!',[]);
            }
            $data['user_id'] = $this->user_id;
            $data['money']   = $number;
            $data['wallet_address']   = $wallet_address;
            $data['create_time'] = time();
            $data['type']    = 1;
            $res = db('withdrawals')->insertGetId($data);
            if ($res) {
                DataReturn::returnBase64Json(1, '提交成功,请等待管理员审核!',[]);
            } else {
                DataReturn::returnBase64Json(0, '提交失败!',[]);
            }
        }
    }

    //虾虾币
    public function doge_money(){
        $_list = db('pig_doge_money')->where(['user_id'=>$this->user_id,'type'=>2])->order('add_time desc')->select();
        $user_info = $this->user_info;
        $data = [];
        $list = [];
        foreach ($_list as $key => $value) {
            $value['add_time']  = date('Y-m-d H:i:s',$value['add_time']);
            $list[] = $value;
        }
        $doge_money = $user_info['doge_money'];
        $data = [
            'doge_money'=>$doge_money,
            'list'      =>$list,
        ];
        DataReturn::returnBase64Json(200, '请求成功',$data);
    }

    //虾虾币提取
    public function doge_extract(){
        if(request()->isPost()){
            $post= input('post.');
            $number = $post['data']['number'];
            $wallet_address = $post['data']['wallet_address'];
            $users = db('users')->where(['user_id'=>$this->user_id])->find();
            if (!$number) {
                DataReturn::returnBase64Json(0, '请输入提取数量!',[]);
            }
            if (!$wallet_address) {
                DataReturn::returnBase64Json(0, '请输入SHRIMP钱包地址!',[]);
            }
            //pig币最少提取数量
            $limit_doge_extract = config('limit_doge_extract');
            if ($number < $limit_doge_extract) {
                DataReturn::returnBase64Json(0, '每次最少提取虾虾币数量为'.$limit_doge_extract.'!',[]);
            }
            //doge手续费
            $doge_fee = config('doge_fee');
            $data['taxfee'] = $number*$doge_fee*0.01;
            $doge_money = $number+$data['taxfee'];
            if ($users['doge_money'] < $doge_money) {
                DataReturn::returnBase64Json(0, '虾虾币不足!',[]);
            }
            $data['user_id'] = $this->user_id;
            $data['money']   = $number;
            $data['wallet_address']   = $wallet_address;
            $data['create_time'] = time();
            $data['type']    = 2;
            
            $res = db('withdrawals')->insertGetId($data);
            if ($res) {
                DataReturn::returnBase64Json(200, '提交成功,请等待管理员审核!',[]);
            } else {
                DataReturn::returnBase64Json(0, '提交失败!',[]);
            }
        }
    }

    //福币充值
    public function blessings_recharge(){
        if(request()->isPost()){
            $post = input('post.');
            $imgs = $post['data']['imgs'];
            $paypwd = $post['data']['paypwd'];

            $list=Db::name('users')->where('user_id',$this->user_id)->find();
            if(empty($list['paypwd'])){
                DataReturn::returnBase64Json(304, '请先设置交易密码', '/dist/pages/set_paypwd.html');
            }
            if(encrypt($paypwd) != $list['paypwd'] ){
                DataReturn::returnBase64Json(0, '交易密码错误!', []);
            }
            if (!$imgs) {
                DataReturn::returnBase64Json(0,'请上传支付凭证!',[]);
            }
            $data['account']  = $post['data']['number'];
            $data['img_url']  = $post['data']['imgs'];
            $data['user_id']  = $this->user_id;
            $data['nickname'] = $this->user_info['nickname'];
            $data['order_sn'] = 'recharge'.get_rand_str(10,0,1);
            $data['add_time'] = time();
            $res = db('recharge')->insertGetId($data);
            if($res){
                DataReturn::returnBase64Json(200,'提交成功',[]);
            }else{
                DataReturn::returnBase64Json(0,'提交失败',[]);
            }
        }
        DataReturn::returnBase64Json(0, '操作失败', []);
    }

    //订单详情
    public function order_detail(){
        $post= input('post.');
        $order_id = $post['data']['order_id'];
        $list = Db::name('pig_order')
                ->alias('a')
                ->join('users b','a.purchase_user_id = b.user_id')
                ->join('users c','a.sell_user_id = c.user_id')
                ->where("a.order_id = $order_id")
                ->field("a.*,b.nickname as buyer,b.user_id as buyer_id,b.mobile as buyer_mobile,c.user_id as seller_id,c.nickname as seller,c.mobile as seller_mobile")
                ->find();
        $payment = db('user_payment')->where(['user_id'=>$list['seller_id']])->select();
        $payment_list = [];
         //DataReturn::returnBase64Json(-1, "账号异常,请联系管理员");
        if ($payment) {
            foreach ($payment as $key => $value) {
             // 判断收款人是否账号异常      
             $Addsalt=new Addsalt();
             $check=$Addsalt->checkpaymentsalt($value["id"]);
             if(!$check){
                DataReturn::returnBase64Json(-1, "账号异常,请联系管理员");
             }
             // 判断收款人是否账号异常 
                $payment_list[] = $value;
                $payment_list[$key]['pay_name'] = str_replace(array(1,2,3), array('支付宝','微信','银行卡'),$value['type']);
            }
        }
        $list['establish_time']  = date('Y-m-d H:i:s',$list['establish_time']);
        $data = [];
        $data = [
            'order_detail'  =>$list,
            'payment_list'  =>$payment_list,
        ];
        DataReturn::returnBase64Json(200,'请求成功',$data);
    }

    //转账  上传打款凭证 买家
    public function payment_voucher(){
        if(request()->isPost()){
            $post = input('post.');
            $imgs = $post['data']['imgs'] ? $post['data']['imgs'] : 0;
            $paypwd = $post['data']['paypwd'];
            $order_id = $post['data']['order_id'];

            $list=Db::name('users')->where('user_id',$this->user_id)->find();
            if(empty($list['paypwd'])){
                DataReturn::returnBase64Json(304, '请先设置交易密码', '/dist/pages/set_paypwd.html');
            }
            if(encrypt($paypwd) != $list['paypwd'] ){
                DataReturn::returnBase64Json(0, '交易密码错误!', []);
            }
            if (!$imgs) {
                DataReturn::returnBase64Json(0,'请上传支付凭证!',[]);
            }
            $data['img_url']  = $post['data']['imgs'];
            $data['order_id'] = $order_id;
            $res = db('pig_order')->update($data);
            if($res){
                DataReturn::returnBase64Json(200,'提交成功',[]);
            }else{
                DataReturn::returnBase64Json(0,'提交失败',[]);
            }
        }
        DataReturn::returnBase64Json(0, '操作失败', []);
    }

    //确认收款  卖家
    public function confim_receipt(){
        if(request()->isPost()){
            $post = input('post.');
            $paypwd = $post['data']['paypwd'];
            $order_id  = $post['data']['order_id'];
            //$pig_id    = $post['data']['pig_id'];
            //$buyer_id  = $post['data']['buyer_id'];
            //$seller_id = $post['data']['seller_id'];

            $list=Db::name('users')->where('user_id',$this->user_id)->find();

            //找到订单是自己的
            $order = Db::name('pig_order')->where('order_id',$order_id)->where('sell_user_id',$this->user_id)->find();

            if(empty($order)){
                DataReturn::returnBase64Json(0, '操作有误!', []);
            }

            $pig_id = $order['pig_id'];
            $buyer_id  = $order['purchase_user_id'];
            $seller_id = $this->user_id;

            if(empty($list['paypwd'])){
                DataReturn::returnBase64Json(304, '请先设置交易密码', '/dist/pages/set_paypwd.html');
            }
            if(encrypt($paypwd) != $list['paypwd'] ){
                DataReturn::returnBase64Json(0, '交易密码错误!', []);
            }

            trace(json_encode(input('')),'jiaoyi');
            $order = Db::name('pig_order')->where('order_id',$order_id)->find();
            $pig_currency = Db::name('pig_goods')->where('id',$order['pig_level'])->value('pig_currency');
            $contract_days = Db::name('pig_goods')->where('id',$order['pig_level'])->value('contract_days');

            $res1['order_id']   = $order_id;
            $res1['pay_status'] = 2;

            $res2['user_id'] = $buyer_id;
            $res2['from_user_id'] = $seller_id;
            $res2['id'] = $pig_id;
            $res2['buy_time'] = time();
            $res2['end_time'] = time()+24*3600*$contract_days;
            $res2['order_id'] = $order_id;

            $res2['buy_type']='userconfim';
            $res=new \app\api\controller\Addsalt();
            $res2['pig_salt']=$res->pigaddsalt($res2['user_id'],$res2['order_id'],$res2['buy_time'],$res2['buy_type']);

            // $data['buy_time']   = time();
            // $data['end_time']   = time()+24*3600*$pig_goods['contract_days'];

            $res3['user_id'] = $buyer_id;
            $res3['order_sn'] = $order['pig_order_sn'];
            $res3['pig_currency'] = $pig_currency;
            $res3['add_time'] = time();
            $res3['desc'] = '领养成功,增加鱼鱼币!';
            $res3['type'] = 1;
            // 启动事务
            Db::startTrans();
            try{
                // accountLog($this->user_id,-$number,0,'出售推广财分',0,0,'',8);//资金流日志
                $r1 = db('pig_order')->update($res1);
                $r2 =db('user_exclusive_pig')->update($res2);
                $r3 =db('pig_doge_money')->insertGetId($res3);
                $r4 =db('users')->update(['user_id'=>$buyer_id,'pig_currency'=>['exp','pig_currency+'.$pig_currency]]);

                if($r1 && $r2 && $r3 && $r4){
                    // 提交事务
                    Db::commit();
                    DataReturn::returnBase64Json(200,'操作成功',[]);
                }else{
                    Db::rollback();
                    DataReturn::returnBase64Json(0, '数据库出现异常', []);
                }

            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                DataReturn::returnBase64Json(0, '操作失败', []);
            }
        }
        DataReturn::returnBase64Json(0, '操作失败', []);
    }

    //是否可以预约 是否添加了收款方式
    public function is_yuyue(){
        $user_info = db('users')->where(['user_id'=>$this->user_id])->find();
        if ($user_info['real_name'] && $user_info['identity']) {
            DataReturn::returnBase64Json(1, '已实名认证', []);
        } else {
            DataReturn::returnBase64Json(0, '请先实名认证', []);
        }
    }

}