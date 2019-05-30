<?php
namespace app\api\controller;


use My\DataReturn;
use think\Db;

class Addsalt  {  

    public function __construct(){
        //parent::__construct();
    }

   //批量加盐
    public  function paymentaddsalt(){
       
       $salt=config('salt'); 
       $user_payment = db('user_payment')->field('id,account,name,qrcode_url')->select();
       $data=array();
       foreach($user_payment as $val){

          $data["paysalt"]=md5($val['account'].$val['name'].$val['qrcode_url'].config('salt'));

          $res=db('user_payment')->where(array('id'=>$val["id"]))->update($data);
 
       }

    }

    //查看盐是否正确
    public  function  checkpaymentsalt($id){
        
        $id=intval($id);
        
        $user_payment = db('user_payment')->where(array('id'=>$id))->field('id,account,name,qrcode_url,paysalt,user_id')->find();
   
        if(!$user_payment){
           return false;
        }
        $lastsalt=md5($user_payment['account'].$user_payment['name'].$user_payment['qrcode_url'].config('salt'));
        
        if($lastsalt!=$user_payment["paysalt"]){
            $data=[];
            $data["paymentid"]=$user_payment["id"];
            $data["user_id"]=$user_payment["user_id"];
            $data["day_time"]=date("Y-m-d h:i:s");
            M('user_payment_err')->add($data);
           return false;
        }
        return true;


    }

    //给鱼加盐
     public function  pigaddsalt($userid,$orderid,$buytime,$buytype){
       $str=$userid."_".$orderid."_".$buytime."_".$buytype;
       $ret_aes=new \app\common\lib\Aes();
       $enstr=$ret_aes->encrypt($str);
       return $enstr;
        
     }

     //查看鱼的盐是否正常
     public  function  checkpigsalt($id){
        $id=intval($id);
        $checkpig = db('user_exclusive_pig')->where(array('id'=>$id))->field('user_id,order_id,buy_time,pig_salt,buy_type')->find();
        if(!$checkpig or $checkpig["pig_salt"]==""){
           return false;
        } 

       $ret_aes=new \app\common\lib\Aes();
       $destr=$ret_aes->decrypt($checkpig["pig_salt"]);

       $str=$checkpig["user_id"]."_".$checkpig["order_id"]."_".$checkpig["buy_time"]."_".$checkpig["buy_type"];
       if($destr==$str){
          return $str;
       }else{
          return false;
       }


     }
      

      public  function  textpigsalt(){
            
            $ret_aes=new \app\common\lib\Aes();
            $str='V54bTcseDVjMjztvvha57Icggac2HHlbLi9QO4CzUnw';
            $destr=$ret_aes->decrypt($str);
            echo $destr;
      }

     


     //给推广财分加盐----------
     public function  userMoneyaddsalt($userid,$usermoney){
         $str=$userid."_".$usermoney;
         $ret_aes=new \app\common\lib\Aes();
         $enstr=$ret_aes->encrypt($str);
         return $enstr;
     }

     //查看推广财分与当前盐是否匹配
     public  function  checkuserMoneysalt($userid){
         $userid=intval($userid);
         $userdate = db('users')->where(array('user_id'=>$userid))->field('user_money,usermoneysalt')->find();
         if(!$userdate or $userdate["usermoneysalt"]==""){
            return false;
         }
         
         $ret_aes=new \app\common\lib\Aes();
         $destr=$ret_aes->decrypt($userdate["usermoneysalt"]);
         $destrarr= explode('_',$destr);
         if(count($destrarr)<2){
             return false;
         }
         
         $num=$destrarr[1]-$userdate["user_money"];
         $num=abs($num);

         if($num<0.5){
            return $userdate["user_money"];
         }else{
            return false;
         }

     }





  
}