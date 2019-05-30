<?php
namespace app\reg\controller;
use think\Controller;
class User extends Controller
{

   

    //注册用户,返回信息
    public function reg(){
        $first_leader = I('get.first_leader');
        $this->assign('first_leader',$first_leader);
        return $this->fetch();
    }

    
}