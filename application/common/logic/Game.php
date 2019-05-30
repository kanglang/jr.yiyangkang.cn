<?php
/**
 * Created by PhpStorm.
 * User: Xgh
 * Date: 2018/12/10
 * Time: 9:18
 */

namespace app\common\logic;
use app\api\controller\JuHe;
use redis\Redis;
use think\Db;

class Game
{
    public $game_id ;
    protected $daojishi; //倒计时
    public $openaward ; //开奖
    protected $now_time ; //现在的时间
    protected $next_level_time ; //下一个阶段的时间
    public $gaming_model ; //现在的游戏
    protected $all_game_model ; //所有游戏模型
    protected $redis ;
    protected $game_name_status_pre ; //游戏场次状态前缀
    protected $game_name_status_expire_time ; //过期时间
    protected $game_award_list ; //

    public function __construct()
    {
        $this->config();
        $this->game_name_pre = 'game_name_pre';
        $this->game_name_expire_time =7200;
        $this->game_award_list = 'game_award_list_';

    }

    //返回某一个游戏的阶段，下个阶段的时间
    public function getRuningInfo(){
        $game = $this->runing();
        $info = [];
        if(!!$game){
            $level = $this->gameTimeArea($game['start_time']);
            $info['next_time'] = $this->next_level_time;
            $info['level'] = $level;
            $info['id'] = $game['id'];
        }
        return $info;
    }

    public function getCoolTime(){
        return $this->daojishi;
    }

    //获取进行中的游戏
    public function runing(){
        //当前时间
        $now_time = date('H:i:s',strtotime(date('H:i:s')) - $this->openaward);
        $where['start_time'] = ['gt',$now_time];
        $where['today_is_open'] = 0;
        $where['is_display'] = 1;
        $pig_list = Db::name('pig_goods')->where($where)->order('start_time')->find();
        return $pig_list ;
    }

    //游戏配置
    public function config(){
        $value = Db::name('config')->where('name','daojishi')->value('value');
        $value = $value ? $value : 120;
        $this->daojishi = $value;
        $this->openaward = 60 * 30;
        $this->now_time = time();
    }

    //开奖时间
    public function getGameOpenTime($game_id){
        $start_time = Db::name('pig_goods')->where('id',$game_id)->value('start_time');

        return strtotime($start_time) + $this->openaward ;
    }

    //下个游戏执行剩余时间
    public function excute_time(){
        $pig = $this->runing();
        if(!!$pig){

            $this->game_id = $pig['id'];

            $this->gaming_model = $pig;
            //改变游戏ID
            $start_time  =strtotime('Ymd')+ strtotime($pig['start_time']) + $this->openaward;
            return $start_time  - time();
        }else{
           
            $pig_list = Db::name('pig_goods')->order('start_time')->find();
            $this->game_id = $pig_list['id'];
            $this->gaming_model = $pig_list;
            $end_time = strtotime(date('Ymd')) + strtotime(date($pig_list['start_time'])) +86400 ;
            return $end_time - time() + $this->openaward;
        }
    }


    //判断是否到了开奖时间---旧版
    public function openGame(){
        $_now_time = time();
        //游戏封闭区 凌晨0~2点不执行开奖 -2019年2月16日16:32:53
        if($this->timeStopOpenGame($_now_time)){
                    $now_time = date('H:i:s',$_now_time - $this->openaward);
                    $where['start_time'] = ['elt',$now_time];
                    $where['today_is_open'] = 0;
                    $where['is_display'] = 1;
                    $where['is_lock'] = 0;
                    $game = Db::name('pig_goods')->where($where)->order('start_time')->find();
                    if(!empty($game)){
                        //直接锁表
                        Db::name('pig_goods')->where($where)->save(['is_lock'=>1]);

                        $this->game_id = $game['id'];
                        $this->gaming_model = $game;
                $this->flashBuy();
            }
        }
       
    }

    //新版邹泽彬
    public function ZopenGame(){
        $_now_time = time();
        //游戏封闭区 凌晨0~2点不执行开奖 -2019年2月16日16:32:53
        if($this->timeStopOpenGame($_now_time)){
            $now_time = date('H:i:s',$_now_time - $this->openaward);
            $where['start_time'] = ['elt',$now_time];
            $where['today_is_open'] = 0;
            $where['is_display'] = 1;
            $where['is_lock'] = 0;
            $game = Db::name('pig_goods')->where($where)->order('start_time')->find();
            if(!empty($game)){
               $this->doGameOver($game);
            }
        }
    }

    //前台触发,游戏是否结束
    public function fontGameOver($game_id){

    }

    //处理游戏结束
    public function doGameOver($game){
        Db::startTrans();
        //直接锁表
        $update_table = Db::name('pig_goods')->where('id',$game['id'])->save(['today_is_open'=>1,'is_lock'=>1]);
        $plus_pig_list = Db::name('user_exclusive_pig')->where('buy_time','lt',strtotime($game['start_time']))->where(['is_able_sale'=>1,'pig_id'=>$game['id']])->select();
        $all_admin_user_id = Db::name('users')->where('is_admin',1)->column('user_id');
        //获取系统管理员随机分配
        if($plus_pig_list && $all_admin_user_id){
            foreach($plus_pig_list as $key => $pig){
                $award_user_key = array_rand($all_admin_user_id,1);
                $award_user_id = $all_admin_user_id[$award_user_key];
                $rs = $this->createOrder($pig,$award_user_id);
                if($rs['status'] == 1){
                    Db::commit();
                }else{
                    Db::rollback();
                }
            }
        }

        //还有一些人没有抢到的,需要退回福分
        $query = Db::name('pig_reservation')->where(['pig_id'=>$game['id']])->whereTime('reservation_time','today')->where('reservation_status',0)->field('pay_points,user_id')->select();
        foreach($query as $k => $value){
            accountLog($value['user_id'],0,$value['pay_points'],'抢购失败,预约退回福分',0,0,0,4,$game['id']);
        }

        //处理该场次的redis
        $flash_buy = new PigFlashBuy();
        $pig_queue_name = $flash_buy->getPigQueueName($game['id']);
        $point_pig_list = $flash_buy->getPointPigGroupName($game['id']);
        Redis::del($pig_queue_name);
        Redis::del($point_pig_list);

    }

    function timeStopOpenGame($time){
        //获取当前0时0分
        $today_0 = strtotime(date('Ymd',$time));
        $sub_time = $time - $today_0;
        if($sub_time > 7200){
            return true;
        }else{
            return false;
        }
    }


    //每个游戏的状态区间
    function gameTimeArea($game_time){
        $stage_1 = $game_time -  $this->daojishi;
        $stage_3 = $game_time +  $this->openaward;
        $now_time = $this->now_time;
        $_stage = 1;
        //$this->connection->send('区间开始');
        if($now_time < $stage_1){
            //倒计时之前
            //$this->stage1($stage_1,$id);
            $_stage = 1;
            $this->next_level_time = $stage_1 - $now_time;
            // return 1;
        }elseif($now_time >= $stage_1 && $now_time < $game_time){
            //$this->stage2($game_time);
            $_stage = 2;
            //倒计时中
            $this->next_level_time = $game_time - $now_time;

            //  return 2;
        }elseif($now_time<$stage_3 && $now_time >= $game_time ){
            //开奖中
            //$this->stage3();
            $_stage = 3;
            $this->next_level_time = $game_time - $now_time;

            //  return 3;
        }elseif($now_time > $stage_3){
             return 4;
        }
        return $_stage;
    }


    //设置所有游戏的模型
    public function setGameModel($data){
        $this->all_game_model = $data;
    }

    //将模型附加游戏阶段
    public function addGameLevel(){
        $model = $this->all_game_model;
        foreach($model as $k => $v){
            $_time =strtotime($v['start_time']);
            $model[$k]['game_level'] = $this->gameTimeArea($_time);
        }
        return $model;
    }

    //当天的游戏时间
    public function exchage_time($time){
    }


    public function setGameId($id){
        $this->game_id = $id;
    }
    //抢购
    /*
     * 优先给指定的人的鱼
     *
     * */
    public function flashBuy(){
        //$log = 'the game_id is '.$this->game_id.',';
       // $log .= 'opengame_time is '.date('d H:i:s',time());
        $redis_name = $this->game_name_pre . $this->game_id;
        $this->getGameStatus();
        //echo Redis::expiretime($redis_name)."<br/>";
        //echo Redis::get($redis_name);
        $game_status = Redis::get($redis_name);
        if($game_status == 1){
            $this->updateGameStatus(2);

            $pigbuy = new PigFlashBuy();
            $redis_users =$pigbuy->getUsers($this->game_id);
            //获取当前游戏的模型
            $game_model = Db::name('pig_goods')->where('id',$this->game_id)->find();
            //$redis_users = ['1','2','3','16'];
            // $redis_users = $pigbuy->getUsers($this->game_id);
            //获取所有参与的用户
            $user_lists = $join_user_list = !$redis_users ? [] : array_column($redis_users,'user_id');
            //处理的鱼
            $send_user = [];
            //找到成熟的鱼
            $pig_lists = Db::name('user_exclusive_pig')->where('pig_id',$this->game_id)->where('is_able_sale',1)->order('appoint_user_id desc')->select();
            //抢到鱼的用户
            $buy_pig_user_list = [];

            if(!empty($pig_lists)){
                foreach($pig_lists as $k =>$pig){
                    if(count($user_lists) <= 0 || !$user_lists){
                        //供过于求， 继续繁殖
                        //$contract_days = db('pig_goods')->where(['id' => $this->game_id])->value('contract_days');
                        //$datas['user_id'] = $pig['user_id'];
                        //$datas['pig_id'] = $pig['id'];
                       // $datas['buy_time'] = time();
                       // $datas['end_time'] =time() + $contract_days * 86400;
                       // $datas['order_id'] = 0;
                        //$datas['is_able_sale'] = 0;
//                    dump($datas);
                       // db('user_exclusive_pig')->where(['id'=>$pig['id']])->update($datas);
                        //交易完成增加鱼鱼币
                      //  $pig_currency = db('pig_goods')->where(['id' => $pig['id']])->value('pig_currency');
                      //  $pig1['pig_currency'] = $pig_currency;
                      //  $pig1['add_time']     = time();
                      //  $pig1['desc']         = '增加鱼鱼币';
                    //    $pig1['type']         = 1;
                     //   $pig1['user_id']      = $pig['user_id'];
                        //db('pig_doge_money')->insert($pig1);
                        //供过于求， 继续走交易流程 --2019-1-14 16:50:48
                        //抢到的人生成订单
                        $this->createOrder($pig,$pig['user_id']);

                    }else{
                        //是否有人被指定的
                        if($pig['appoint_user_id'] && in_array($pig['appoint_user_id'],$user_lists)){
                            foreach($user_lists as $k => $v){
                                if($v == $pig['appoint_user_id']){
                                    unset($user_lists[$k]);
                                }
                            }
                            $award_user = $pig['appoint_user_id'];
                        }else{
                            $award_user = array_shift($user_lists);
                            //若果中奖人是自己，且中奖参与人数中还有人的话，那么将自己与下个人的位置调换
                            if($pig['user_id'] == $award_user && count($user_lists) > 1){
                                $position_user = $award_user;
                                $award_user = array_shift($user_lists);
                                array_unshift($user_lists,$position_user);
                            }
                        }

                        if(!empty($award_user)){
                            //计算结束时间
                            $day = Db::name('pig_goods')->where('id',$pig['pig_id'])->value('contract_days');
                            //判断是否已经预约了
                            $is_yuyue = $this->isYuyue($award_user);
                            if(!$is_yuyue){
                                $desc = sprintf('抢购%s消耗福分',$game_model['goods_name']);
                                $adoptive_energy = $game_model['adoptive_energy'];
                                //$desc = '抢购消耗福分';
                                //扣除福分
                                accountLog($award_user,0,-"{$adoptive_energy}",$desc,0,0,0,4,$this->game_id);
                            }
                            $buy_pig_user_list[] = $award_user;
                            //抢到的人生成订单
                            $this->createOrder($pig,$award_user);
                        }
                    }
                    $send_user[] = $pig['id'];
                }
            }

            //处理剩余的人 求过于供
            //预约了的没有抢的人需要退回福分--没抢到的 都退回
            //if(count($user_lists) > 0){
            //退预约积分-当天-场次
            $query = Db::name('pig_reservation')->where(['pig_id'=>$this->game_id]);
            if(count($buy_pig_user_list) > 0){
                $query->whereNotIn('user_id',$buy_pig_user_list);
            }
            $no_buy_pig = $query->whereTime('reservation_time','today')->select();
            if($no_buy_pig){
                foreach($no_buy_pig as $key => $value){
                    accountLog($value['user_id'],0,$value['pay_points'],'抢购失败,预约退回福分',0,0,0,4,$this->game_id);
                    //unset($user_lists[$value['user_id']]);
                }
            }
            //}


            $name = $pigbuy->getFlashName($this->game_id);

            //echo 'game:'.$this->game_id .'--award_list:' . json_encode($buy_pig_user_list);
        

            $redis = new Redis();
            $redis->del($name);
            db('pig_goods')->where(['id' =>$this->game_id])->update(['today_is_open' => 1]);
            //处理所有列表的鱼
            db('user_exclusive_pig')->where('id','in',$send_user)->update(['is_able_sale'=>0,'appoint_user_id'=>0]);
            //中奖人
            Redis::set($this->game_award_list . $this->game_id,json_encode($buy_pig_user_list),$this->game_name_expire_time);
            $this->updateGameStatus(3);

            //加入一些处理的记录-仅供查找处理
            $data_log['join_user_list'] = implode(',', !empty($join_user_list) ?$join_user_list:[] );
            $data_log['award_user_list'] = implode(',',!empty($buy_pig_user_list) ?$buy_pig_user_list:[] );
            $data_log['pig_list'] = implode(',',!empty($send_user) ?$send_user:[]);
            $data_log['pig_id'] = $this->game_id;
            $data_log['change_time'] = time();
            Db::name('pig_award_log')->insertGetId($data_log);


            
        }
    }

    public function updateOneData(){
        //$pig = 1;
        $pig = Db::name('user_exclusive_pig')->where('id',1)->find();
        $award_user_id = 24;

        $this->createOrder($pig,$award_user_id);
    }


    public function createOrder($pig,$award_user_id){
        try{
            //短信处理
            $sms = new JuHe();
            $data['sell_user_id'] = $pig['user_id'];
            $data['pig_level'] = $pig['pig_id'];
            $data['pig_price'] = $pig['price'];
            $data['pig_id'] = $pig['id'];
            //$data['user_id'] = $award_user;
            $data['establish_time'] = time();
            $data['end_time'] = time() + 3600 * 2;
            $data['pig_order_sn'] = $this->get_order_sn();
            $data['purchase_user_id'] = $award_user_id;
            $pig_order = Db::name('pig_order')->insertGetId($data);
            $pres = true;
            //将预约记录是否抢到改一下状态
             $p_res_id = Db::name('pig_reservation')->where('pig_id',$pig['pig_id'])->where('user_id',$award_user_id)->whereTime('reservation_time','today')->value('id');
            if(!empty($p_res_id)){
                $pres = Db::name('pig_reservation')->where('pig_id',$pig['pig_id'])->where('user_id',$award_user_id)->whereTime('reservation_time','today')->save(['reservation_status'=>1]);
            }

            //这里加下盐
            $buy_type = 'createOrder';
            $res = new \app\api\controller\Addsalt();
            $pig_salt=$res->pigaddsalt($pig['user_id'],$pig_order,$pig['buy_time'],$buy_type);
            //将指定的去掉
            $save_user = Db::name('user_exclusive_pig')->where('id',$pig['id'])->save(['order_id'=>$pig_order,'pig_salt'=>$pig_salt,'buy_type'=>$buy_type,'appoint_user_id'=>0,'is_able_sale'=>0]);

            //抢购后，扣除对应的福分
            $game_model = Db::name('pig_goods')->where('id',$pig['pig_id'])->find();
            //判断是否已经预约了
            $is_yuyue = $this->isYuyue($award_user_id);
            $fufen_do = true;
            if(!$is_yuyue){
                $desc = sprintf('抢购%s消耗福分',$game_model['goods_name']);
                $adoptive_energy = $game_model['adoptive_energy'];
                //$desc = '抢购消耗福分';
                //扣除福分
                $fufen_do = accountLog($award_user_id,0,-"{$adoptive_energy}",$desc,0,0,0,4,$pig['pig_id']);
            }
           trace(sprintf('%s,%s,%s,%s',$pig_order ,$pres , $save_user , $fufen_do),'game');
            if($pig_order && $pres && $save_user && $fufen_do){
                //发送短信，
                $purchase_mobile = Db::name('users')->where('user_id',$award_user_id)->value('mobile');
                $sell_mobile = Db::name('users')->where('user_id',$pig['user_id'])->value('mobile');
                $sms->sendJuHeSms(3,$purchase_mobile,1111);//抢购人
                $sms->sendJuHeSms(4,$sell_mobile,1111);//出售人
                return ['status'=>1,'msg'=>'成功'];
            }else{
                return ['status'=>0,'msg'=>'程序数据更新出错'];
            }
        }catch(\Exception $e){
            return ['status'=>0,'msg'=>'创建订单失败'];
        }

    }

    //判断是否已经预约了--依赖setGameId
    public function isYuyue($user_id){
        $rs = Db::name('pig_reservation')->whereTime('reservation_time','today')->where(['user_id' => $user_id])->where('pig_id',$this->game_id)->column('pig_id');
        return !empty($rs) ? true : false;
    }

    /**
     * 获取订单 order_sn
     * @return string
     */
    public function get_order_sn()
    {
        $order_sn = null;
        // 保证不会有重复订单号存在
        while(true){
            $order_sn = date('YmdHis').rand(1000,9999); // 订单编号
            $order_sn_count = Db::name('pig_order')->where("pig_order_sn = ".$order_sn)->count();
            if($order_sn_count == 0)
                break;
        }
        return $order_sn;
    }

    //写入开奖时间
    public static function writeTime($game_id = '',$time = ''){
        //$start_time = Db::name('pig_goods')->where('id',$game_id)->value('start_time');
        //$game = '-------------------------------------------------\n';
       // $game = sprintf('下一场游戏的ID:%s,开始时间是:%s',$game_id,$time).'\n';
        $game = 'youxi';
        //$file_name = 'open_game_'.date('d',time()).'.txt';
        //trace($game,'game');
        file_put_contents(ROOT_PATH .'/public/gamelog/opengame.txt',$game);
    }

    public function updateGameStatus($status){
        $game_name = $this->game_name_pre . $this->game_id;
        Redis::set($game_name,$status);
    }

    //redis 获取场次状态--依赖setGameId
    public function getGameStatus(){
        $game_name = $this->game_name_pre . $this->game_id;
        return Redis::setnx($game_name,1,$this->game_name_expire_time);
    /*    $game_name = $this->game_name_pre . $this->game_id;
        $game = Redis::get($game_name);
        if($game){
            $json_game = json_decode($game);
            return $json_game['status'];
        }else{
            $game = Db::name('pig_goods')->
            $data['status'] = 0;//设置默认值
            $data['game_time'] = ;//设置游戏时间
            Redis::set($game_name,$status, $this->game_name_expire_time);
            return $status;
        }*/
    }



}