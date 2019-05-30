<?php
namespace app\push\controller;
use app\common\logic\Game;
use GatewayWorker\Lib\Gateway;
use think\Db;
use think\helper\Time;
use Workerman\Lib\Timer;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Worker{
    /**
     * 当客户端发来消息时触发
     * @param int $client_id 连接id
     * @param mixed $data 具体消息
     */
    public static function onMessage($client_id, $data){
        $message = json_decode($data, true);
        $message_type = $message['type'];

        switch($message_type) {
            case 'init':
                // uid
                $uid = $message['id'];
                // 设置session
                $_SESSION = [
                    'username' => $message['username'],
                    'avatar'   => isset($message['avatar'])?$message['avatar']:'',
                    'id'       => $uid,
                    'sign'     => $message['sign'],
                    'type'      => $type,
                ];
                // 将当前链接与uid绑定
                Gateway::bindUid($client_id, $uid);
                // 通知当前客户端初始化
                $init_message = array(
                    'message_type' => 'init',
                    'id'           => $uid,
                );
                Gateway::sendToClient($client_id, json_encode($init_message));
                return;
                break;
            case 'chatMessage':
                // 聊天消息
                $type = $message['data']['to']['type'];
                $to_id = $message['data']['to']['id'];
                $uid = $_SESSION['id'];

                $chat_message = [
                    'message_type' => 'chatMessage',
                    'data' => [
                        'username' => $_SESSION['username'],
                        'avatar'   => $_SESSION['avatar'],
                        'id'       => $type === 'friend' ? $uid : $to_id,
                        'type'     => $type,
                        'content'  => htmlspecialchars($message['data']['mine']['content']),
                        'timestamp'=> time()*1000,
                    ]
                ];

                return Gateway::sendToUid($to_id, json_encode($chat_message));
                break;
            case 'hide':
            case 'online':
                $status_message = [
                    'message_type' => $message_type,
                    'id'           => $_SESSION['id'],
                ];
                $_SESSION['online'] = $message_type;
                Gateway::sendToAll(json_encode($status_message));
                return;
                break;
            case 'ping':
                return;
            default:
                echo "unknown message $data" . PHP_EOL;
        }
    }
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     *
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        Gateway::sendToUid($client_id,'吃屎啊你');
    }
    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public static function onClose($client_id){
        $logout_message = [
            'message_type' => 'logout',
            'id'           => $_SESSION['id']
        ];
        Gateway::sendToAll(json_encode($logout_message));
    }
    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public static function onError($client_id, $code, $msg)
    {
        echo "error $code $msg\n";
    }
    /**
     * 每个进程启动
     * @param $worker
     */
    public static function onWorkerStart($worker)
    {
        //$__game = new Game();
        //$game_time = $__game->excute_time();
        //self::writeTime( $__game->game_id,$game_time);
        //self::Time1();
        Timer::add(1,function(){
            $game = new Game();
             $game->openGame();
            //if($is_open_game){
            //    $time2 = Timer::add($game->getCoolTime(),function() use(&$game,&$time2){
           //         trace('进入开奖期','game');
          //          $game->flashBuy();
          //          Timer::del($time2);
          //      });
          //  }
            //unset($game);
        });
    }

    //定时1
    public static function Time1(){
        //获取下次启动的游戏
        $__game = new Game();
        $game_time = $__game->excute_time();
        self::writeTime($__game->game_id,$game_time);
        $game_id = $__game->game_id;
        $timer1 = Timer::add($game_time,function() use(&$game_id,&$__game,&$timer1){
            $__game->flashBuy();
            unset($__game);
            self::Time2();
            Timer::del($timer1);
        },false);
    }
    public static function Time2(){
        //获取下次启动的游戏
        $__game2 = new Game();
        $game_time = $__game2->excute_time();
        $game_id = $__game2->game_id;
        self::writeTime($__game2->game_id,$game_time);

        $timer2 = Timer::add($game_time,function() use(&$game_id,&$__game2,&$timer2){
            $__game2->flashBuy();
            unset($__game2);
            self::Time1();
            Timer::del($timer2);
        },false);
    }

    //写入开奖时间
    public static function writeTime($game_id,$time){
        //$start_time = Db::name('pig_goods')->where('id',$game_id)->value('start_time');
        //$game = '-------------------------------------------------\n';
        $game = sprintf('下一场游戏的ID:%s,开始时间是:%s',$game_id,$time).'\n';
        $file_name = 'open_game_'.date('d',time()).'.txt';
        trace($game,'game');
        file_put_contents(ROOT_PATH .'/public/gamelog/'.$file_name,$game,'FILE_APPEND');
    }


}