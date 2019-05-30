<?php


namespace app\api\controller;
use think\Controller;
use app\common\logic\Game;
use My\DataReturn;



class Nologin extends Controller{

    public function __construct()
    {
        //处理数据
        init_config();//初始配置表数据
    }

    //配置表数据初始
    public function init_config(){
        //获取配置
        $config = Cache::get('db_config_data');
        if(!$config){
            $config = api('Config/lists');
            Cache::set('db_config_data',$config);
        }//var_dump($config);exit;
        \think\Config::set($config);
    }

	public function pig_goods(){
        $game = new Game();
        $_data = db('pig_goods')->where(['is_display'=>1])->select();
        $game->setGameModel($_data);
        $data = $game->addGameLevel();
        $list = [];
        foreach ($data as $key => $value) {
        	$value['start_time'] = substr($value['start_time'], 0,5);
            $value['end_time']   = substr($value['end_time'], 0,5);
            $value['small_price']   = substr($value['small_price'],0,strpos($value['small_price'], '.'));
        	$value['large_price']   = substr($value['large_price'],0,strpos($value['large_price'], '.'));
        	$list[] = $value;
        }
        DataReturn::returnJson(200, '数据返回成功',$list);
    }

    //配置  网站logo...提币页面的简介...
    public function config(){
        $logo = config('store_logo');
        $recharge_desc = config('recharge_desc');//提币页面的简介
        $pig_fee       = config('pig_fee');//pig币手续费
        $doge_fee      = config('doge_fee');//虾虾币手续费
        $data= [
            'logo'           => $logo,
            'recharge_desc'  => $recharge_desc,
            'pig_fee'        => $pig_fee,
            'doge_fee'       => $doge_fee,
        ];
        DataReturn::returnJson(200, '数据返回成功',$data);
    }

    public function update(){
        //$game = new \app\common\logic\Game();
        //$game->updateOneData();
        $this->timeStopOpenGame(time());
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

} 