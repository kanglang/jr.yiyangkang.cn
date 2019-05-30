<?php

// 调用方式 \think\Loader::model('AccountComm','logic');//用户逻辑层
// 公共用户逻辑层（默认找当前模块的，不存在则查找公共的）
namespace app\common\logic;

use think\Loader;
use think\Db;

class AccountComm
{

    /**
     * 注册用户
     * @param $user
     * @return array
     */
	public function register($user,$invite=0,$first_leader=0){
        $openid = $user['openid'];
        $unionid = $user['unionid'];
        $UserCommLogic = Loader::model('UserComm','logic');
        $res = $UserCommLogic->addUser($user,$invite,$first_leader);
        
        return $this->login($openid,$unionid);
	}

    /**
     * 登录
     * @param $unionid
     * @return array
     */
    public function login($openid,$unionid=''){
        $result = array();
        if($unionid){
            $user = db('users')->field('*,user_id as uid')->where('unionid',$unionid)->find();
        }else{
            $user = db('users')->field('*,user_id as uid')->where('openid',$openid)->find();
        }
        
        return $user;
    }

}

