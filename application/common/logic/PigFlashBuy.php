<?php
/**
 * Created by PhpStorm.
 * User: Xgh
 * Date: 2018/11/26
 * Time: 13:43
 */

namespace app\common\logic;


use redis\Redis;
use think\Db;

class PigFlashBuy
{
    //加入redis抢购名
    public $redis_flash_name ;
    //鱼鱼队列名
    public $redis_pig_queue_name;
    //protected $redis ;
    function __construct()
    {
        //$this->redis = new Redis();
    }


    //抢购算法
    public function open(){

    }

    //设置当前的抢购场次
    public function setFlashName($id){
        $redis_flash_name =  'flash_buy_'.date('Ymd',time()).'_'.$id;
        $this->redis_flash_name = $redis_flash_name;
    }
    //设置当前的抢购场次
    public function getFlashName($id){
        $redis_flash_name =  'flash_buy_'.date('Ymd',time()).'_'.$id;
        return $redis_flash_name;
    }
    //获取某场次的队列
    public function getUsers($id){
        $redis_flash_name =  'flash_buy_'.date('Ymd',time()).'_'.$id;
        $user_lists = json_decode(Redis::get($redis_flash_name),true);
        //过滤重复的
        $arr = [];
        if(!empty($user_lists)){
            foreach($user_lists as $key => $value){
                if(in_array($value['user_id'],$arr)){
                    unset($user_lists[$key]);
                }else{
                    $arr[] = $value['user_id'];
                }
            }

            $reload_user_lists = [];
            //重装顺序
            foreach ($user_lists as $key => $value) {
                if(!in_array($value['sort'],array_keys($reload_user_lists))){
                    $reload_user_lists[$value['sort']] = [$value];
                }else{
                    array_push($reload_user_lists[$value['sort']],$value);
                }
            }

            //重新根据key排序
            krsort($reload_user_lists);

            //重新合并二位数组
            return $this->sort_array($reload_user_lists);
            //排序
        }
        return $user_lists;
    }

    function sort_array($array){
        return array_reduce($array, function ($result, $value) {
            return array_merge($result, array_values($value));
        }, array());
    }

    //加入抢购队列..依赖setFlashName
    public function addFlashRedis($data){
       // $redis = $this->redis;
        $num = Redis::get($this->redis_flash_name);
        //dump($num);
        //dump($this->redis_flash_name);

        if(!$num){
            $add_data[] = $data; //新建数组
        }else{
            $flash_redis = json_decode(Redis::get($this->redis_flash_name),true);
            array_push($flash_redis,$data);
            $add_data = $flash_redis;
        }
        //做加入抢购的redis里面
        return Redis::set($this->redis_flash_name,json_encode($add_data));
    }

    /*------------新抢购逻辑-----------------*/
    //设置当前日期鱼的队列名
    public function setPigQueueName($id){
        $redis_flash_name =  'pig_queue_'.date('Ymd',time()).'_'.$id;
        $this->redis_pig_queue_name = $redis_flash_name;
    }

    //获得当前日期鱼的队列名
    public function getPigQueueName($id){
        $redis_flash_name =  'pig_queue_'.date('Ymd',time()).'_'.$id;
        return $redis_flash_name;
    }

    //指定鱼的队列名
    public function getPointPigGroupName($id){
        $redis_flash_name =  'pig_point_queue_'.date('Ymd',time()).'_'.$id;
        return $redis_flash_name;
    }

    //获取指定鱼的队列
    public function getPointPigList($id){
        $name = $this->getPointPigGroupName($id);
        $arr = empty(Redis::smembers($name)) ? [] : Redis::smembers($name);
        return $arr;
    }

    //获取当前日期的参与者
    public function getJoinGamerName($id){
        $redis_flash_name =  'join_gamer_'.date('Ymd',time()).'_'.$id;
        return $redis_flash_name;
    }

    //加入指定的
    public function addPointPigRedis($id){
        $name = $this->getPointPigGroupName($id);
        $pig_queue_list = $this->getPigQueueName($id);

        //重置所有指定的
        $data = Db::name('user_exclusive_pig')->where('pig_id',$id)->where('appoint_user_id','>',0)->field('user_id,id')->select();
        Redis::del($name);
        //去掉队列里面的
        foreach($data as $k => $value){
            Redis::lrem($pig_queue_list,$value['id'],0);
            Redis::sadd($name,$value['user_id']);
        }
        return true;
    }



    //鱼入队---正常对
    public function addPigQueue($game_id,$pig_id){
        //入队时间必须要是当天开奖前的鱼
        $start_time = Db::name('pig_goods')->where(['id'=>$game_id])->value('start_time');
        if(time() < strtotime($start_time)){

            $pig_queue_name = $this->getPigQueueName($game_id);
            //加入之前,首先判断队列是否存在这只鱼,避免出现重复加入
            $queue_num = Redis::llen($pig_queue_name);
            $redis_pig_queue_list = Redis::lrange($pig_queue_name,$queue_num);
            $arr_pig_list = empty($redis_pig_queue_list) ? [] :$redis_pig_queue_list;

            $insert_contr = array_search($pig_id,$arr_pig_list);
            if($insert_contr === false){
                Redis::lpush($pig_queue_name,$pig_id,'last',86400);
            }
        }
    }

    //定时入队
    public function timerAddPigQueue(){
        //获取已经成熟的鱼

        $pig_list = Db::name('user_exclusive_pig')->where(['is_able_sale'=>1])->select();
        foreach($pig_list as $key =>$value){
            //参数1:等级,2:id
            $this->addPigQueue($value['pig_id'],$value['id']);
        }
    }

    //鱼出队--抢购
    public function popPigQueue($game_id,$user_id){
        //pop
        $pig_queue_name = $this->getPigQueueName($game_id);
        $point_pig_list = $this->getPointPigList($game_id);
        //update
        $game = new Game();
        $is_pointer_pig = in_array($user_id,$point_pig_list);
        if($is_pointer_pig){
            $pig_id = Db::name('user_exclusive_pig')->where('pig_id',$game_id)->where('appoint_user_id',$user_id)->value('id');
        }else{
            $pig_id = Redis::lpop($pig_queue_name);
        }


        if($pig_id){
            Db::startTrans();//开启事务
            //lock
            //首先找一下有没有指定给自己的
            $pig_info = Db::name('user_exclusive_pig')->lock(true)->where(['id'=>$pig_id])->find();
            $rs = $game->createOrder($pig_info,$user_id);
            if($rs['status'] == 1){
                Db::commit();
                //如果是指定,需要重置
                if($is_pointer_pig){
                    $key = array_search($user_id,$point_pig_list);
                    unset($point_pig_list[$key]);
                    $name = $this->getPointPigGroupName($game_id);
                    Redis::srem($name,$user_id);
                }

                $pigQueueNum = empty(Redis::llen($pig_queue_name)) ? 0 : Redis::llen($pig_queue_name);
                $pigPointNum = count($point_pig_list);
                $count_pig = $pigQueueNum + $pigPointNum ;
                if($count_pig == 0){
                    //处理项目结束
                    $pig_game = Db::name('pig_goods')->where('id',$game_id)->find();
                    $game->doGameOver($pig_game);
                }

                return ['status'=>1,'msg'=>'成功'];
            }else{
                Db::rollback();
                return ['status'=>0,'msg'=>$rs['msg']];
            }

            //找到对应的开奖时间
           // $game_start_time = Db::name('pig_goods')->where('id',$game_id)->value('start_time');
            //$pig_info = Db::name('user_exclusive_pig')->lock(true)->where(['appoint_user_id'=>$user_id,'pig_id'=>$game_id,'is_able_sale'=>1])->where('buy_time','lt',$game_start_time)->find();
            //防止剩余指定的鱼得到的为空.
           // if(!$pig_info){
           //     $pig_info = Db::name('user_exclusive_pig')->lock(true)->where(['appoint_user_id'=>0,'pig_id'=>$game_id,'is_able_sale'=>1])->where('buy_time','lt',$game_start_time)->find();
          //  }

            }


        $pigQueueNum = empty(Redis::llen($pig_queue_name)) ? 0 : Redis::llen($pig_queue_name);
        $pigPointNum = count($point_pig_list);
        $count_pig = $pigQueueNum + $pigPointNum ;
        if($count_pig == 0){
            //处理项目结束
            $pig_game = Db::name('pig_goods')->where('id',$game_id)->find();
            $game->doGameOver($pig_game);
        }

        return ['status'=>0,'msg'=>'手速慢了!'];
    }









}