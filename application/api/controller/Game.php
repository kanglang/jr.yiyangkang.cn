<?php
/**
 * Created by PhpStorm.
 * User: Xgh
 * Date: 2018/12/11
 * Time: 10:16
 */

namespace app\api\controller;


use app\common\logic\PigFlashBuy;
use app\common\model\Users;
use My\DataReturn;
use redis\Redis;
use think\Db;
use app\common\logic\Game as GameLogic;

use app\common\controller\Recommend;

class Game extends Base
{
    public function __construct()
    {
        parent::__construct();

    }

    //暂时为测试
    public function  text(){

       $re= new Recommend();
       $re->checkDestroyPig(8);

    }



    //检查正在的游戏
    public function checkGame(){
        $game = new \app\common\logic\Game();
        $time = $game->excute_time();
        $now_game_time = strtotime($game->gaming_model['start_time']);
        //前端显示开奖剩余时间
        $plus_time = $game->excute_time() - $game->openaward;
        //id 游戏ID  time 游戏时间  openaward 开奖冷却时间
        DataReturn::returnJson(200,'',['id'=>$game->game_id,'time'=> $plus_time,'openaward'=>$game->openaward,'cool_time'=>$game->getCoolTime() + 1,'now_game_time'=>$now_game_time,'stage'=>$game->gameTimeArea($now_game_time)]);
    }

    //开奖查询
    public function checkOpen(){
        $input = input('');
        $data = $input['data'];
        $game_id = $data['id'];
        $user_id = $this->user_id;
        $status = Redis::get('game_name_pre'.$game_id); //游戏状态
        if($status == 3){
            $list = Redis::get('game_award_list_'.$game_id);
            $award_list = empty($list) ? [] : json_decode($list);
            if(in_array($user_id,$award_list)){
                DataReturn::returnJson(200,'恭喜中奖');
            }else{
                DataReturn::returnJson(100,'很遗憾,没中奖');
            }

        }else{
            DataReturn::returnJson(201,'还没有开奖');
        }


        /*$rs = Db::name('pig_goods')->where('id',$game_id)->where('today_is_open',1)->find();
        if($rs){
            $order = Db::name('pig_order')->where('pig_level',$game_id)->whereTime('establish_time','today')->where('purchase_user_id',$user_id)->find();
            if($order){
                DataReturn::returnJson(200,'恭喜中奖');
            }else{
                DataReturn::returnJson(100,'很遗憾,没中奖');
            }

            DataReturn::returnJson(1,'开奖成功');
        }else{
            DataReturn::returnJson(201,'还没有开奖');
        }

        DataReturn::returnJson(1,'');*/
    }

    //获取已经预约了的数据
    public function isYuyueData(){
        $user_id = $this->user_id;
        $rs = Db::name('account_log')->whereTime('change_time','today')->where(['user_id' => $user_id])->where('type',4)->column('pig_id');
//        dump($rs);exit;
        $data = !empty($rs) ? $rs : [];
        DataReturn::returnBase64Json(200,'成功',$data);

    }

    //预约
    public function yuyue(){
        //定点flash_buy_point
        $config = config('flash_buy_point');
        $game_id = input('data.id');
        //判断余额是否足够
        $user = Users::get($this->user_id);
//        dump($config);exit;
        if($user['pay_points'] < $config){
            DataReturn::returnBase64Json(0,'福分不足,预约失败');
        }

        $game = new GameLogic();
        $game->setGameId($game_id);
        $is_yy = $game->isYuyue($this->user_id);
        if($is_yy){
            DataReturn::returnBase64Json(0,'今天已经预约了！');
        }

        $value = Db::name('pig_goods')->where('id',$game_id)->value('reservation');
        //流水记录
        $rs = accountLog($this->user_id,0,"-{$value}",'预约消费福分',0,0,0,4,$game_id);
        //预约记录
        Db::name('pig_reservation')->insert(['reservation_time'=>time(),'pig_id'=>$game_id,'user_id'=>$this->user_id,'pay_points'=>$value]);

        if($rs){
            DataReturn::returnBase64Json(1,'预约成功');
        }else{
            DataReturn::returnBase64Json(0,'预约失败了');
        }
    }

    //开抢触发
    public function flash_buy(){
        $input = input('');
        $data = $input['data'];

        //DataReturn::returnBase64Json(0,'福分不足,抢购失败啊啊啊');
        $check = $this->validate($data,'PigGoods.redis_id');
        if($check !== true){
            DataReturn::returnBase64Json(0,$check);
        }
        $user = session('user');

        if (!$this->user_info['real_name'] ||  !$this->user_info['identity']) {
            DataReturn::returnBase64Json(0, '还没实名认证', []);
        }

        $game_id = $data['id'];
        $n_game_a = new \app\common\logic\Game();
        //判断是否已经参与过了
        $pigflashbuy = new PigFlashBuy();
        $name = $pigflashbuy->getJoinGamerName($game_id);
        $join_merbe = empty(Redis::smembers($name)) ? [] :Redis::smembers($name) ;

        if(in_array($user['user_id'],$join_merbe)){
                DataReturn::returnBase64Json(500,'您已经参与过该场次,不能继续参与');
        }

        $now_time = time();
        //必须要控制开间时间段
        $start_time = Db::name('pig_goods')->where('id',$game_id)->value('start_time');
        //$a = $n_game_a->getGameOpenTime($game_id) - $now_time;
        //echo sprintf('%s - %s =%s ',$now_time,strtotime($start_time),$a);
        //抢过了的，不能继续抢

        if($now_time >= strtotime($start_time) && $now_time < $n_game_a->getGameOpenTime($game_id)){
            //加入抢购参与
            Redis::sadd($name,$user['user_id']);

            //预约不需要福分
            $game = new \app\common\logic\Game();
            $game->setGameId($data['id']);
            $is_yuyue = $game->isYuyue($this->user_id);
            if(!$is_yuyue){
                //$config = config('flash_buy_point');
                $config = Db::name('pig_goods')->where('id',$data['id'])->find();
                $adoptive_energy = $config['adoptive_energy'];
                //判断余额是否足够
                $user = Users::get($this->user_id);
                if($user['pay_points'] < $adoptive_energy){
                    DataReturn::returnBase64Json(0,'福分不足,抢购失败');
                }
            }
            $rs = $pigflashbuy->popPigQueue($game_id,$user['user_id']);
            if($rs['status'] == 1){
                DataReturn::returnBase64Json(1,'成功');
            }else{
                DataReturn::returnBase64Json(0,$rs['msg']);
            }
        }

        DataReturn::returnBase64Json(0,'已经开奖了');

    }
    //$data = ['id'=>sort]
    public function test(){
        $level = 8;

        $redis_flash_name =  'flash_buy_'.date('Ymd',time()).'_'.$level;
        $redis = new Redis();
        $add_lists[$this->user_info['user_id']] = $this->user_info['rule_sort'];

        $redis->set($redis_flash_name,json_encode($add_lists));
        echo $redis_flash_name;
    }

}