<?php
/**
 * Created by PhpStorm.
 * User: Xgh
 * Date: 2018/11/14
 * Time: 17:14
 */
namespace app\api\Controller;
use app\common\model\Users;
use app\common\logic\PigFlashBuy;
use app\common\logic\Game;
use GatewayWorker\Gateway;
use My\DataReturn;
use redis\Redis;
use think\Controller;
use app\common\Controller\Recommend;
use app\common\controller\BaseComm;
use think\Db;

class Test extends Controller{

    public function resetGamge(){


        /*exec('/usr/local/php/bin/php /home/www/newteam/qukuailiangou/server.php restart -d 2>&1',$stop);

        dump($stop);*/
        `sudo php /home/www/newteam/qukuailiangou/server.php restart -d >>d.txt`;

    }
    //页面测试
    public function index(){
        Db::name('user_exclusive_pig')->where('id','in',[1,2])->update(['is_able_sale'=>0]);
        echo Db::name('user_exclusive_pig')->getlastsql();
        return $this->fetch();
    }



    public function user_add_flash(){
        if(request()->isPost()){
            $user_id = input('user_id');
            $game_id = input('id');
            //获取用户的信息
            $user = Users::get($user_id);

            $pig = new PigFlashBuy();
            $pig->setFlashName($game_id);
            $pig->addFlashRedis(['user_id'=>$user_id,'sort'=>$user['rule_sort']]);
            $join_lists = $pig->getUsers($game_id);
            dump($join_lists);

        }
        return $this->fetch();
    }

    public function openGame(){
        $id = input('id');
        $game = new Game();
        $game->setGameId($id);
        $game->flashBuy();
    }

    //模拟登陆
    public function login_demo()
    {
        //$data = DataReturn::baseFormat(input('data'));

        //返回处理的session_id
        $session['user_id'] = 1;
        $session['expire_time'] = time() + C('session.expire');
        session('user',$session);
        DataReturn::returnBase64Json(200,'登录成功');
    }
    //redis 队列入
    public function flash_buy(){

        $user = session('user');
        $user_info = Users::get($user['user_id']);
       // $data = input('data');
        //dump(input(''));exit;
        $input = input('');
        $data = $input['data'];
        $check = $this->validate($data,'PigGoods.redis_id');
        if($check !== true){
            DataReturn::returnBase64Json(0,$check);
        }

        //判断种子够不够
        //if($user_info[''])
        $this->addFlashRedis();
        //是否可以加入
        //判断场次是否存在
        DataReturn::returnBase64Json(1,'成功');

    }
    function addFlashRedis($data){
        $level = input('id');
        $redis_flash_name =  'flash_buy_'.date('Ymd',time()).'_'.$level;
        $redis = new Redis();
        //trace('redis:'.$redis_flash_name,'game');
        //$redis_flash_value = $redis->get($redis_flash_name);
        $num = $redis->llen($redis_flash_name);
        //trace('num:'.$num,'game');
        if(!$num){
            $add_data = $data; //新建数组
        }else{
            $flash_redis = json_decode($redis->get($redis_flash_name));
            $add_data = array_merge($flash_redis,$data);
        }

        //trace($add_data,'game');

        //做加入抢购的redis里面
        return $redis->set($redis_flash_name,json_encode($add_data));
    }


    public function testOpen(){
        $input = input('');
        //$data = $input['data'];
       // $game_id = $data['id'];
        $game_id = 11;
        $user_id = 28;
        $rs = Db::name('pig_goods')->where('id',$game_id)->where('today_is_open',1)->find();
        if($rs){
            $order = Db::name('pig_order')->where('pig_level',$game_id)->whereTime('establish_time','today')->where('purchase_user_id',$user_id)->find();
            if(!$order){
                DataReturn::returnJson(200,'恭喜中奖');
            }else{
                //退回预约分数
                //$value = Db::name('pig_goods')->where('id',$game_id)->value('reservation');
                $value = $rs['reservation'];
                $desc  = sprintf('预约%s福分回退',$rs['goods_name']);
                accountLog($user_id,0,"{$value}",$desc,0,0,0,4,$game_id);
                DataReturn::returnJson(100,'很遗憾,没中奖');
            }

            DataReturn::returnJson(1,'开奖成功');
        }else{
            DataReturn::returnJson(201,'还没有开奖');
        }

        DataReturn::returnJson(1,'');
    }
    //抢购
    /*
     * 优先给指定的人的鱼
     *
     * */
//    public function flashBuy(){
//        $pigbuy = new PigFlashBuy();
//        $redis_users =arsort($pigbuy->getUsers($this->game_id));
//        //获取所有参与的用户
//        $user_lists = array_keys($redis_users);
//        //处理的鱼
//        $send_user = [];
//        //找到成熟的鱼
//        $pig_lists = Db::name('user_exclusive_pig')->where('level',$this->game_id)->where('is_able_sale',1)->order('appoint_user_id desc')->select();
//
//        if(!empty($pig_lists)){
//            foreach($pig_lists as $k =>$pig){
//                if(count($user_lists) <= 0 ){
//                    //供过于求， 继续繁殖
//                }else{
//                    //是否有人被指定的
//                    if($pig['appoint_user_id'] && in_array($pig['appoint_user_id'],$user_lists)){
//                        $award_user = $pig['appoint_user_id'];
//                        unset($user_lists[$pig['appoint_user_id']]);
//                    }else{
//                        $award_user = array_shift($user_lsits);
//                    }
//
//                    //生成一条订单
//                    $data['sell_user_id'] = $pig['user_id'];
//                    $data['pig_level'] = $pig['level'];
//                    $data['pig_price'] = $pig['price'];
//                    $data['pig_id'] = $pig['id'];
//                    $data['user_id'] = $award_user;
//                    Db::name('pig_order')->add($data);
//                    // 保存处理的鱼
//                    $send_user[] = $pig['id'];
//                }
//            }
//        }
//
//        //处理剩余的人 求过于供
//        //if(count($user_lsits) > 0){
//        //直接通知他中不了
//        //}
//
//
//
//
//    }

    public function iii(){
        $pig_goods = db('pig_goods')->field('contract_days')->where('id',1)->find();

        $goods_time = $pig_goods['contract_days'];
        $time = time();
        //算出下个合约期结束的时间
        $day = "+".$goods_time." day";
        $end_time = strtotime($day, time());
        echo $end_time;
    }

    public function crontab(){
        $gamer = new Game();
        $gamer->openGame();
    }

    public function array_s(){
        $order_id  =143;


        //找到订单是自己的
        $order = Db::name('pig_order')->where('order_id',$order_id)->find();

        if(empty($order)){
            DataReturn::returnBase64Json(0, '操作有误!', []);
        }

        $pig_id = $order['pig_id'];
        $buyer_id  = $order['purchase_user_id'];
        $seller_id = $order['sell_user_id'];

        trace(json_encode(input('')),'jiaoyi');
        $order = Db::name('pig_order')->where('order_id',$order_id)->find();
        $pig_currency = Db::name('pig_goods')->where('id',$order['pig_level'])->value('pig_currency');
        $contract_days = Db::name('pig_goods')->where('id',$order['pig_level'])->value('contract_days');

        //$res1['order_id']   = $order_id;
       // $res1['pay_status'] = 2;

        $res2['user_id'] = $buyer_id;
        $res2['from_user_id'] = $seller_id;
        $res2['id'] = $pig_id;
        $res2['buy_time'] = time();
        $res2['end_time'] = time()+24*3600*$contract_days;
        $res2['order_id'] = $order_id;

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
           /* $r1 = true;
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
            }*/

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            DataReturn::returnBase64Json(0, '操作失败', []);
        }
    }
}