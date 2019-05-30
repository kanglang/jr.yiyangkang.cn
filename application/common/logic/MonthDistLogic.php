<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: IT宇宙人
 * Date: 2015-09-09
 * 
 * TPshop 公共逻辑类  将放到Application\Common\Logic\   由于很多模块公用 将不在放到某个单独模下面
 */

namespace app\common\logic;

use think\Model;
use think\db;
//use think\Page;

/**
 * 分销逻辑层
 * Class CatsLogic
 * @package Home\Logic
 */
class MonthDistLogic //extends Model
{

     public $msg = '';
     public $code = 1;
     public $data = [];

     //错误
     public function error($msg,$code=0){

        $this->msg = $msg;
        $this->code = $code;

        $result['msg'] = $this->msg;
        $result['code'] = $this->code;

        return $result;
     }

     //成功
     public function success($msg,$data,$code=1){

        $this->msg = $msg;
        $this->code = $code;
        $this->data = $data;

        $result['msg'] = $this->msg;
        $result['code'] = $this->code;
        $result['data'] = $this->data;

        return $result;
     }
     
     /*
      *获取某指定时间错的条件时间
      */
    public function some_time($time,$where){

        $dt = date('Y-m-d',$time);
        
        return date('Y-m-d',strtotime("$dt.$where"));
     }


     /*
      * 获取左右时间区间
      */
     public function left_right_time($distribut){

        $debug = false;

        // $y = date('Y');
        // $m = date('m');

        //每月几号月分成
        // $begin_month = $debug ? $distribut['month'].' 23:59:59' : '01';
        //上个月这号
        // $bdate = $y.'-'.$m.'-'.$begin_month;//p($bdate);
        // $before_month = $this->some_time(strtotime($bdate),'-1month');//p($before_month);
  
        //本月最后一天 
        $before_month = $debug ? '2000-01-01' : date('Y-m-01', strtotime(date("Y-m-d")));
        $bdate = $debug ? '2018-12-31 23:59:59' : date('Y-m-d', strtotime("$before_month +1 month -1 day")).' 23:59:59';

        $ltime = strtotime($before_month.'00:00:00');
        $rtime = strtotime($bdate);

        $data['ltime'] = $ltime;
        $data['rtime'] = $rtime;

        return $data;
     }


     /*
      * 月分红
      */
    public function monthly_dist(){  

        //顶级大v
        $top_v = [];
        //
        $data = [];

        $distribut = tpCache('distribut');
        if( !$distribut['switch'] ){
            return $this->error('分销未开启');
        }

        $endDay = date("t");//p($endDay);
        if( date('d',time()) != $endDay ){
            //return $this->error('每月'.$endDay.'号结算，时间未到！');
        }

        // 获取左右时间区间
        $left_right_time = $this->left_right_time($distribut);//p($left_right_time);
        $ltime = $left_right_time['ltime'];
        $rtime = $left_right_time['rtime'];

        //检查是否已经写入过了
        $exist_dist = M('monthly_dist')
        ->field('id')
        ->where('lbetween_time','=',$ltime)
        ->where('rbetween_time','=',$rtime)
        ->order('id')
        ->find();

        if( $exist_dist ){
            //return $this->error('已经结算过了！');
        }

        $monthly_config = M('user_ratio')
        ->select();//p($monthly_config);

        if( !$monthly_config ){
            return $this->error('月分红金额累计区间未设置');
        }

        $field = 'user_id,first_leader,level';
         //查找顶级大v
        $top_v = M('users')
        // ->field('user_id,first_leader,level')
        ->where('level',4)
        ->order('user_id')
        ->getField($field);
        // ->select();
         //p($top_v);

        foreach ($top_v as $key => $value) {
            if( $value['first_leader'] ){
                $check = checktop_parents($value['first_leader']);
                if( $check ){
                    unset($top_v[$key]);
                }
            }
            
        }

        //p($top_v);

        if( $top_v ){
            
            //月计算开始
            foreach ($top_v as $key => $value) 
            {
                $data_item = $this->down_calcul($value,$distribut,$monthly_config,$value['first_leader'],$value['level']);

                $data = array_merge($data,$data_item);
            }

            $data = array_column($data,null,'user_id');

        }

        //pe($data);

        if( !empty($data) ){

            Db::startTrans();
            $result = true;
            try {
                $row = M('monthly_dist')->insertAll($data);
                foreach ($data as $key => $value) {

                    $desic = date('Y-m-d H:i:s',$value['lbetween_time']).'-'.date('Y-m-d H:i:s',$value['rbetween_time']).'之间的月分红分成';
                    $result = accountLog($value['user_id'], $value['my_amount'], 0, $desic, $value['my_amount']);

                }
                Db::commit();
            } catch (Exception $e) {
                $result = false;
                Db::rollback();
            }
            

            if($result == false){
                return $this->error('发生错误');
            }

            return $this->success('计算成功',$row);
        }else{
            return $this->error('计算结果为空');
        }
        
    }


    public  function down_calcul($user,$distribut_config,$monthly_config,$parent_id,$level,$type = 1 ,$result = []){

        $dav_cids       =    //下级集合
        $all_cids       = [];//旗下所有人

        $my_buy         =    //我的购买量
        $all_buy        =    //我这条线所有人购买量
        $other_buy      = 0; //我旗下所有人的购买量

        $other_percent  =    //直团百分比
        $all_percent    = 0; //总百分比

        $all_amount     =    //总月分红
        $other_amount   =    //直团月分红
        $my_amount      =0;  //我的月分红

        $dav            = [];//旗下大v用户信息

        $uid = $user['user_id'];//p($uid);

        //获取团所有用户
        $child_field = 'user_id,level,first_leader';
        $child_ids = childrens($uid,$child_field);//p($child_ids);
        foreach ($child_ids as $key => $value) {
            if( $value['level'] == 4 && $value['first_leader'] == $uid ){
                $dav_cids[] = $value['user_id'];//下级集合
                $dav[] = $value;
            }
            $all_cids[] = $value['user_id']; 
        }
        
        $d_cids = implode(',',$dav_cids);//di($d_cids);
        $a_cids = implode(',',$all_cids);//di($a_cids);

        // 获取左右时间区间
        $left_right_time = $this->left_right_time($distribut_config);
        $ltime = $left_right_time['ltime'];
        $rtime = $left_right_time['rtime'];

        $where = 'In';
        $pay_status = [1];
        $con_time = 'pay_time';

        //计算我的购买量
        $my_buy = M('order')
        ->where('user_id',$uid)
        ->where('pay_status',$where,$pay_status)
        ->where($con_time,'>=',$ltime)
        ->where($con_time,'<',$rtime)
        ->sum('total_amount');
        !$my_buy && $my_buy = 0; 
           // di($my_buy);

        //计算所有购买量
        $other_buy = M('order')
        ->where('user_id','In',$a_cids)
        ->where('pay_status',$where,$pay_status)
        ->where($con_time,'>=',$ltime)
        ->where($con_time,'<',$rtime)
        ->sum('total_amount');
        !$other_buy && $other_buy = 0; 
           // di($other_buy);

        $all_buy = ($my_buy + $other_buy) ;  
          // di($all_buy);

        //获取百分比
        foreach ($monthly_config as $key => $value) {

            if( $all_buy >= $value['lowermoney'] && $all_buy <= $value['topmoney'] ){
                $all_percent = $value['proportion']/100;
            }

        }
        // di($all_percent);
        
        $davs_amount = 0;//所有大v计算的分成总和
        foreach ($dav_cids as $key => $val) {
        
            $dav_ids = array_column( childrens($val,$child_field),'user_id' );
            $dav_ids[] = $val;
            $dav_aids = implode(',',$dav_ids);//包括大v自己的线上所有人
            //p($dav_aids);

            $dav_buy = M('order')
            ->where('user_id','In',$dav_aids)
            ->where('pay_status',$where,$pay_status)
            ->where($con_time,'>=',$ltime)
            ->where($con_time,'<',$rtime)
            ->sum('total_amount');
            //p($dav_buy);
            foreach ($monthly_config as $k => $value) {
                if( $dav_buy >= $value['lowermoney'] && $dav_buy <= $value['topmoney'] ){
                    $davs_amount += $dav_buy * $value['proportion']/100;
                }
            }

            
        }


        //总业绩
        $all_amount     = sprintf('%.2f',($all_buy * $all_percent))  ;
        //直团业绩
        $other_amount   = sprintf('%.2f',$davs_amount);
        //我的业绩
        $my_amount      = sprintf('%.2f',($all_amount - $other_amount));
        //我的业绩 = （我+除我外的所有人的业绩）* 对应比 - 我旗下大v的业绩*对应比

         //di($all_amount);
         //di($other_amount);
         //de($my_amount);
        $y = date('Y');
        $m = date('m');
        if( $all_amount > 0 ){
        
            $data = [];
            $data['user_id']            = $uid;
            $data['child_ids']          = $a_cids;
            $data['all_percent']        = $all_percent;
            $data['my_buy']             = $my_buy;
            $data['all_buy']            = $all_buy;
            $data['my_amount']          = $my_amount;
            $data['other_amount']       = $other_amount;
            $data['all_amount']         = $all_amount;
            $data['ayear']              = $y;
            $data['amonth']             = $m;
            $data['parent_id']          = $parent_id;
            $data['level']              = $level;
            $data['add_time']           = time();
            $data['lbetween_time']      = $ltime;
            $data['rbetween_time']      = $rtime;

            $result[] = $data;

        }else{
            return $result;
        }

        if( !empty($dav) ){
        
            foreach ($dav as $key => $value) {

                $item = $this->down_calcul($value,$distribut_config,$monthly_config,$value['first_leader'],$value['level'],1,$result);

                $result = array_merge($result,$item);

            }

            $result = array_column($result,null,'user_id');

            return $result;
           
        }else{
            return $result;
        }

        
    }


}