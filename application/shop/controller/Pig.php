<?php

namespace app\shop\controller;
use app\common\logic\PigFlashBuy;
use app\shop\logic\OrderLogic;
use think\AjaxPage;
use think\Page;
use think\Verify;
use think\Db;
use think\Exception;
use app\shop\logic\UsersLogic;
use think\Loader;
use app\common\controller\Logic;
use redis\Redis;

class Pig extends Base {


    public function pigList(){
        $Ad =  M('pig_goods');
        $p = $this->request->param('p');

        $res = $Ad->order('id')->page($p.',10')->select();
        if($res){
            foreach ($res as $val){
                // $val['start_time'] = date("H:i",$val['start_time']);
                // $val['end_time'] = date("H:i",$val['end_time']);
                // var_dump($val['start_time'],$val['end_time']);

                $game_reset_time = strtotime("+24 hour", $val['game_reset_time']);

                if(!empty($val['game_reset_time'])){

                    if(time() >= $game_reset_time){

                        $val['is_restart'] = 2;

                    }else{

                        $val['is_restart'] = 1;
                    }
                }else{

                    $val['is_restart'] = 2;
                }


                $list[] = $val;

            }

            $this->assign('list',$list);
        }
        
        $count = $Ad->count();
        $Page = new Page($count,10);
        $show = $Page->show();
        $this->assign('page',$show);
        return $this->fetch();
    }

    public function pigadd(){
        $act = I('get.act','add');
        $this->assign('act',$act);
        $id = I('get.id');
        if($id){
            $level_info = D('pig_goods')->where('id='.$id)->find();
            // $level_info['start_time'] = date("H:i",$level_info['start_time']);
            // $level_info['end_time'] = date("H:i",$level_info['end_time']);
            $this->assign('info',$level_info);
        }

        return $this->fetch();
    }

    public function pigHandle(){

        $data = I('post.');
      
        if (!empty($data['act']) && $data['act'] == 'add') {  //添加

            $start_time = $data['start_time'];
            $end_time   = $data['end_time'];

            // var_dump($start_time);die;
            // $start=strtotime($start_time);
            // $end  = strtotime($end_time);

            // $large_price = Db::name('pig_goods')->field('small_price,large_price')->select();
           
            // foreach ($large_price as $key => $value) {
                
            //     if(intval($data['small_price']) == intval($value['small_price']) || intval($data['small_price']) == intval($value['large_price'])){
                
            //         $return = ['status' => 0, 'msg' => '金额重复，重新填写'];
            //         return $this->error($return['msg']);
                
            //     }
            //     if(intval($data['large_price']) == intval($value['small_price']) || intval($data['large_price']) == intval($value['large_price'])){

            //         $return = ['status' => 0, 'msg' => '金额重复，重新填写'];
            //         return $this->error($return['msg']);
            //     }
            // }   
 
            $pig['id'] = $data['id'];
            $pig['goods_name'] = $data['goods_name'];
            $pig['small_price']= $data['small_price'];
            $pig['large_price']= $data['large_price'];
            $pig['start_time'] = $start_time;
            $pig['end_time']   = $end_time;
            $pig['reservation']= $data['reservation'];
            $pig['adoptive_energy'] = $data['adoptive_energy'];
            $pig['contract_days']= $data['contract_days'];
            $pig['income_ratio'] = $data['income_ratio'];
            $pig['pig_currency'] = $data['pig_currency'];
            $pig['doge_money'] = $data['doge_money'];
            $pig['images'] = $data['images'];
            $r = Db::name('pig_goods')->insert($pig);
            if ($r !== false) {
                $return = ['status' => 1, 'msg' => '添加成功'];
            } else {
                $return = ['status' => 0, 'msg' => '添加失败，数据库未响应', 'result' => ''];
            }
        }

        if (!empty($data['act']) && $data['act'] == 'edit') { //编辑

            // $large_price = Db::name('pig_goods')->field('small_price,large_price')->select();
           
            // foreach ($large_price as $key => $value) {
                
            //     if(intval($data['small_price']) == intval($value['small_price']) || intval($data['small_price']) == intval($value['large_price'])){
                
            //         $return = ['status' => 0, 'msg' => '金额重复，重新填写'];
            //         return $this->error($return['msg']);
                
            //     }
            //     if(intval($data['large_price']) == intval($value['small_price']) || intval($data['large_price']) == intval($value['large_price'])){

            //         $return = ['status' => 0, 'msg' => '金额重复，重新填写'];
            //         return $this->error($return['msg']);
            //     }
            // }

            $start_time = $data['start_time'];
            $end_time   = $data['end_time'];

            // var_dump($start_time);die;
            // $start=strtotime($start_time);
            // $end  = strtotime($end_time);


            $pig['id'] = $data['id'];
            $pig['goods_name'] = $data['goods_name'];
            $pig['small_price']= $data['small_price'];
            $pig['large_price']= $data['large_price'];
            $pig['start_time'] = $start_time;
            $pig['end_time']   = $end_time;
            $pig['reservation']= $data['reservation'];
            $pig['adoptive_energy'] = $data['adoptive_energy'];
            $pig['contract_days']= $data['contract_days'];
            $pig['income_ratio'] = $data['income_ratio'];
            $pig['pig_currency'] = $data['pig_currency'];
            $pig['doge_money'] = $data['doge_money'];
            $pig['images'] = $data['images'];
            $r = D('pig_goods')->where('id=' . $data['id'])->save($pig);
            if ($r !== false) {
                $return = ['status' => 1, 'msg' => '编辑成功'];
            } else {
                $return = ['status' => 0, 'msg' => '编辑失败，数据库未响应', 'result' => ''];
            }
        }

        if (!empty($data['act']) && $data['act'] == 'del') {  //删除

            $r = D('pig_goods')->where('id=' . $data['id'])->delete();
            if ($r !== false) {
                $return = ['status' => 1, 'msg' => '删除成功', 'result' => ''];
            } else {
                $return = ['status' => 0, 'msg' => '删除失败，数据库未响应', 'result' => ''];
            }
        }

        if($return['status']==1){
            $this->success($return['msg'],url('pig/pigList'));
        }else{
            $this->error($return['msg']);
        }
        
    }



     public function pigLog(){
        $pig = Db::name('pig_goods')->field('goods_name,id')->select();

        $this->assign('pig',$pig);
        return $this->fetch();
    }
    public function pigindex(){
        // 搜索条件
        
        $condition = array();

        I('mobile') ? $condition['t2.mobile']  =   I('mobile') : false;  //出售人
        I('id') ? $condition['t2.user_id']  =   I('id') : false;  //出售人
        I('pig_level') ? $condition['t3.id']  =   I('pig_level') : false;  //鱼等级

        // I('email') ? $condition['email'] = I('email') : false;
        // I('user_id') ? $condition['user_id'] = I('user_id') : false;
        $condition['is_able_sale'] =1;
        // $count = Db('user_exclusive_pig')->where($condition)->count();
        // $Page  = new AjaxPage($count,10);
        //  搜索条件下 分页赋值
        // foreach($condition as $key=>$val) {
        //     $Page->parameter[$key]   =   urlencode($val);
        // }
        $count = M('user_exclusive_pig t1')->join('users t2','t1.user_id = t2.user_id','LEFT')->join('pig_goods t3','t1.pig_id = t3.id','LEFT')->where($condition)->count();
        $Page  = new AjaxPage($count,20);

        $show = $Page->show();
        // var_dump(I('mobile'));die;
         //获取订单列表
        if(empty($condition)){

            $userList = Db('user_exclusive_pig')->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            
        }else{

            $userList = Db('user_exclusive_pig as t1')
                            ->field('t1.*')
                            ->join('users t2','t1.user_id = t2.user_id','LEFT')
                            ->join('pig_goods t3','t1.pig_id = t3.id','LEFT')
                            ->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();  
                          
        }
    
        // $userList =  Db('user_exclusive_pig')->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    
        foreach ($userList as $key => $value) {
         /* $userList[$key]['checkstauts']='正常';
          if($value["pig_salt"]==''){
             $userList[$key]['checkstauts']='异常';
          } */
          $user_mobile  = Db::name('users')->field('mobile')->where(['user_id'=>$value['user_id']])->find();
          $first_mobile = Db::name('users')->field('mobile')->where(['user_id'=>$value['from_user_id']])->find();
          $pig_level = Db::name('pig_goods')->field('goods_name')->where(['id'=>$value['pig_id']])->find();
          $userList[$key]['name'] = $user_mobile['mobile']; 
          $userList[$key]['first_name'] = $first_mobile['mobile']; 
          $userList[$key]['pig_level'] = $pig_level['goods_name']; 
        }
      
        $show = $Page->show();
        $this->assign('userList',$userList);
        $this->assign('count',$count);
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('pager',$Page);
        return $this->fetch();
    }

    public function change_pig(){
            
            $data = I('post.');

            if(!empty($data)){

                $user_s = Db::name('users')->where(['user_id'=>$data['appoint_user_id']])->find();
                
                if(empty($user_s)){
                    $return = ['status' => -1, 'msg' => '该用户不存在'];
                    $this->error($return['msg']);
                }
                if($user_s['rule_sort'] == 0){

                    $return = ['status' => -1, 'msg' => '该用户禁止排单'];
                    $this->error($return['msg']);
                }

                if($user_s){

                    M('user_exclusive_pig')->where('id',$data['id'])->update(['appoint_user_id' => $data['appoint_user_id']]);

                    $return = ['status' => 1, 'msg' => '提交成功'];
                    $this->success($return['msg']);
                }else{
                    $return = ['status' => -1, 'msg' => '操作失败'];
                    $this->error($return['msg']);
                }

            }else{

                $id = input('id');

                $exclusive_pig = Db::name('user_exclusive_pig')->where(['id'=>$id])->find();

                $this->assign('exclusive_pig',$exclusive_pig);
            }

            return $this->fetch();
    }

    public function pigOrder(){

        $pig = Db::name('pig_goods')->field('goods_name,id')->select();

        $this->assign('pig',$pig);

        return $this->fetch();
    }


     /*
     *交易订单
     */
    public function ajaxindex(){


        $orderLogic = new OrderLogic();
     

        // 搜索条件
        $condition = array();

        I('mobile') ? $condition['t2.mobile']  =   I('mobile') : false;                   //出售人
        I('id') ? $condition['t2.user_id']  =   I('id') : false;                   //出售人
        I('pig_order_sn') ? $condition['t1.pig_order_sn'] = I('pig_order_sn') : false;    //订单编号
        I('pig_level') ? $condition['t3.id']  =   I('pig_level') : false;          //鱼等级
         (I('pay_status') !== '') && $condition['t1.pay_status']  =   I('pay_status') ;   //订单状态
        // I('purchase_user_id') ? $condition['t3.mobile'] = I('purchase_user_id') : false; //购买人
        // I('pay_status') ? $condition['t1.pay_status'] = intval(I('pay_status')) : false;        //订单状态
        // var_dump($condition['t1.pay_status']);die;
   
        $sort_order = I('order_by','order_id DESC').' '.I('sort');

        $count = M('pig_order t1')->join('users t2','t1.sell_user_id = t2.user_id','LEFT')->join('pig_goods t3','t1.pig_level = t3.id','LEFT')->where($condition)->count();
        $Page  = new AjaxPage($count,20);

        $show = $Page->show();
        
        //获取订单列表
        if(empty($condition)){

            $orderList = Db('pig_order')->where($condition)->order('order_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
           
        }else{
             $orderList = Db('pig_order as t1')
                            ->field('t1.*')
                            ->join('users t2','t1.sell_user_id = t2.user_id','LEFT')
                            ->join('pig_goods t3','t1.pig_level = t3.id','LEFT')
                            ->where($condition)->order('order_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();  
                          
        }
    
        // $orderList = Db('pig_order')->where($condition)->order('order_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        foreach ($orderList as $key => $value) {
            
          $user_mobile  = Db::name('users')->field('mobile')->where(['user_id'=>$value['sell_user_id']])->find();
          $first_mobile = Db::name('users')->field('mobile')->where(['user_id'=>$value['purchase_user_id']])->find();
          $pig_level = Db::name('pig_goods')->field('goods_name')->where(['id'=>$value['pig_level']])->find();
          $orderList[$key]['user_name'] = $user_mobile['mobile']; 
          $orderList[$key]['first_name'] = $first_mobile['mobile']; 
          $orderList[$key]['pig_level'] = $pig_level['goods_name']; 
        }
       
        $this->assign('orderList',$orderList);
        $this->assign('count',$count);
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('pager',$Page);
        return $this->fetch();
    }

    /*
     *修改时间
     */
    public function order_pig(){

            $data = I('post.');

            if(!empty($data)){
                
                $order = Db::name('pig_order')->where(['order_id'=>$data['order_id']])->find();
                
                $establish_time = $data['establish_time'];

                $time  = strtotime($establish_time);

                if($order){
                    M('pig_order')->where('order_id',$data['order_id'])->update(['establish_time' => $time]);
                    $return = ['status' => 1, 'msg' => '修改成功'];
                    $this->success($return['msg']);
                }else{
                    $return = ['status' => -1, 'msg' => '操作失败'];
                    $this->error($return['msg']);
                }

            }else{

                $order_id = input('id');
                
                $exclusive_pig = Db::name('pig_order')->where(['order_id'=>$order_id])->find();

                $this->assign('exclusive_pig',$exclusive_pig);
            }

            return $this->fetch();
    }
    /*
     *  修改指定id的详情页
     */
    public function change(){

        $id = input("id");

        $exclusive_pig = Db::name('user_exclusive_pig')->where(['id'=>$id])->find();

        $this->assign('exclusive_pig',$exclusive_pig);

        return $this->fetch();
    }

    /*
     *  修改指定id的详情页
     */
    public function change_edit(){

            $data = I('post.');


            if(!empty($data)){

                $user_s = Db::name('users')->where(['user_id'=>$data['appoint_user_id']])->find();
                
                if(empty($user_s)){
              
                    $this->error('该用户不存在');
                }
                if($user_s['rule_sort'] == 0){

                    $this->error('该用户禁止排单');
                }

                if($user_s){

                    M('user_exclusive_pig')->where('id',$data['id'])->update(['appoint_user_id' => $data['appoint_user_id']]);
                    //找到对应的级别
                    $game_id = M('user_exclusive_pig')->where('id',$data['id'])->value('pig_id');
                    //加入指定
                    $pig_flash_buy = new PigFlashBuy();
                    $pig_flash_buy->addPointPigRedis($game_id,$data['appoint_user_id']);

                    $this->success('操作成功','pigLog');

                }else{

                    $this->error('操作失败');
                }

            }

            return $this->fetch();

    }

     /*
     *  会员的鱼
     */
     public function pigUser(){

        $pig = Db::name('pig_goods')->field('goods_name,id')->select();

        $this->assign('pig',$pig);
        return $this->fetch();
     }

     /*
     *  会员的鱼
     */
     public function piginuser(){

        $condition = array();

        I('mobile') ? $condition['t2.mobile']  =   I('mobile') : false;  //出售人
         I('id') ? $condition['t2.user_id']  =   I('id') : false;  //出售人id
        I('pig_level') ? $condition['t3.id']  =   I('pig_level') : false;  //鱼等级
        // I('is_able_sale') ? $condition['t1.is_able_sale']  =   I('is_able_sale') : false;  //鱼等级

        // (I('is_able_sale') !== '') && $condition['t1.is_able_sale']  =   I('is_able_sale') ;
        // var_dump($condition);die;


        $count = M('user_exclusive_pig t1')->join('users t2','t1.user_id = t2.user_id','LEFT')->join('pig_goods t3','t1.pig_id = t3.id','LEFT')->where($condition)->count();
        $Page  = new AjaxPage($count,20);

        $show = $Page->show();
    
         //获取会员所有的鱼
        if(empty($condition)){

            $userList = Db('user_exclusive_pig')->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            
        }else{

            $userList = Db('user_exclusive_pig as t1')
                            ->field('t1.*')
                            ->join('users t2','t1.user_id = t2.user_id','LEFT')
                            ->join('pig_goods t3','t1.pig_id = t3.id','LEFT')
                            ->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();  
                          
        }
    
        // $userList =  Db('user_exclusive_pig')->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    
        foreach ($userList as $key => $value) {
          //查看鱼是否异常
          $userList[$key]['checkstauts']=1;
          if($value["pig_salt"]==''){
             $userList[$key]['checkstauts']=-1;
          }else{

            $res= new \app\api\controller\Addsalt();
            $stautss=$res->checkpigsalt($value["id"]);
            if(!$stautss){
                $userList[$key]['checkstauts']=-1;
            }
          } 
          //查看鱼是否异常
          $user_mobile  = Db::name('users')->field('mobile')->where(['user_id'=>$value['user_id']])->find();
          $first_mobile = Db::name('users')->field('mobile')->where(['user_id'=>$value['from_user_id']])->find();
          $pig_level = Db::name('pig_goods')->field('goods_name')->where(['id'=>$value['pig_id']])->find();
          $userList[$key]['name'] = $user_mobile['mobile']; 
          $userList[$key]['first_name'] = $first_mobile['mobile']; 
          $userList[$key]['pig_level'] = $pig_level['goods_name']; 
        }
      
        $show = $Page->show();
        $this->assign('userList',$userList);
        $this->assign('count',$count);
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('pager',$Page);
        return $this->fetch();
     }

     /***
     *已销毁的鱼
     **/
     public function pigUserDestroy (){


        $pig = Db::name('pig_goods')->field('goods_name,id')->select();

        $this->assign('pig',$pig);
        return $this->fetch();
     }

     /***
     *已销毁的鱼ajax
     **/

     public function piginuserDestroy(){

        $condition = array();

        I('mobile') ? $condition['t2.mobile']  =   I('mobile') : false;  //出售人
        I('pig_level') ? $condition['t3.id']  =   I('pig_level') : false;  //鱼等级

        $condition['t1.type']='del';
        // I('is_able_sale') ? $condition['t1.is_able_sale']  =   I('is_able_sale') : false;  //鱼等级

        // (I('is_able_sale') !== '') && $condition['t1.is_able_sale']  =   I('is_able_sale') ;
        // var_dump($condition);die;


        $count = M('user_exclusive_pig_del t1')->join('users t2','t1.user_id = t2.user_id','LEFT')->join('pig_goods t3','t1.pig_id = t3.id','LEFT')->where($condition)->count();
        $Page  = new AjaxPage($count,20);

        $show = $Page->show();
    
         //获取会员所有的鱼
        if(empty($condition)){

            $userList = Db('user_exclusive_pig_del')->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            
        }else{

            $userList = Db('user_exclusive_pig_del as t1')
                            ->field('t1.*')
                            ->join('users t2','t1.user_id = t2.user_id','LEFT')
                            ->join('pig_goods t3','t1.pig_id = t3.id','LEFT')
                            ->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();  
                          
        }
    
        // $userList =  Db('user_exclusive_pig')->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    
        foreach ($userList as $key => $value) {
         /* $userList[$key]['checkstauts']='正常';
          if($value["pig_salt"]==''){
             $userList[$key]['checkstauts']='异常';
          } */
          $user_mobile  = Db::name('users')->field('mobile')->where(['user_id'=>$value['user_id']])->find();
          $first_mobile = Db::name('users')->field('mobile')->where(['user_id'=>$value['from_user_id']])->find();
          $pig_level = Db::name('pig_goods')->field('goods_name')->where(['id'=>$value['pig_id']])->find();
          $userList[$key]['name'] = $user_mobile['mobile']; 
          $userList[$key]['first_name'] = $first_mobile['mobile']; 
          $userList[$key]['pig_level'] = $pig_level['goods_name']; 
        }
      
        $show = $Page->show();
        $this->assign('userList',$userList);
        $this->assign('count',$count);
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('pager',$Page);
        return $this->fetch();
     }






    /*
     *  预约
     */   
     public function pigReserve()
     {   
        $pig = Db::name('pig_goods')->field('goods_name,id')->select();

        $this->assign('pig',$pig);
        return $this->fetch();
     }

     public function pigsert()
     {
        $condition = array();

        I('mobile') ? $condition['t2.mobile']  =   I('mobile') : false;  //出售人
        I('pig_level') ? $condition['t3.id']  =   I('pig_level') : false;  //鱼等级

        $start_time = I("start_time") ? I("start_time")."00:00:00" : false;
        $end_time = I("end_time") ? I("end_time")."23:59:59" : false;


        // var_dump($start_time,$end_time);
         if (!empty($start_time) && empty($end_time)) {
           $condition['t1.reservation_time'] = ['>=',strtotime($start_time)];
        }else if (!empty($end_time) && empty($start_time)) {
           $condition['t1.reservation_time'] = ['<=',strtotime($end_time)];
        }else if(!empty($start_time) && !empty($end_time)){

            if($start_time == $end_time){

                $condition['FROM_UNIXTIME(t1.reservation_time,"%Y-%m-%d")'] = $start_time;

            }else{

                // $condition["t1.reservation_time"] = ['>=',strtotime($start_time)];
                // $condition["t1.reservation_time"] = ['<=',strtotime($end_time)];
                $condition["t1.reservation_time"] = ['between',[strtotime($start_time),strtotime($end_time)]];
            }

        }

        // var_dump($condition);die;

        // var_dump(I('start_time'));die;
        // I('is_able_sale') ? $condition['t1.is_able_sale']  =   I('is_able_sale') : false;  //鱼等级

        // (I('is_able_sale') !== '') && $condition['t1.is_able_sale']  =   I('is_able_sale') ;
        // var_dump($condition);die;


        // $count = M('pig_reservation t1')->join('users t2','t1.user_id = t2.user_id','LEFT')->join('pig_goods t3','t1.pig_id = t3.id','LEFT')->where($condition)->count();

        $count = M('pig_reservation t1')->join('pig_goods t3','t1.pig_id = t3.id','LEFT')->where($condition)->count();

        $Page  = new AjaxPage($count,20);

        $show = $Page->show();
    
         //获取会员所有的鱼
        if(empty($condition)){

            $userList = Db('pig_reservation')->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            
        }else{

            $userList = Db('pig_reservation as t1')
                            ->field('t1.*')
                            // ->join('users t2','t1.user_id = t2.user_id','LEFT')
                            ->join('pig_goods t3','t1.pig_id = t3.id','LEFT')
                            ->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();  
                          
        }
    
        // $userList =  Db('user_exclusive_pig')->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        
        foreach ($userList as $key => $value) {
            
          $user_mobile  = Db::name('users')->field('mobile')->where(['user_id'=>$value['user_id']])->find();
          $pig_level = Db::name('pig_goods')->field('goods_name')->where(['id'=>$value['pig_id']])->find();
          $userList[$key]['name'] = $user_mobile['mobile']; 
          $userList[$key]['pig_level'] = $pig_level['goods_name']; 
        }
      
        $show = $Page->show();
        $this->assign('userList',$userList);
        $this->assign('count',$count);
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('pager',$Page);
        return $this->fetch();
     }

    public function resetGame(){
         file_put_contents(ROOT_PATH . 'data.txt',1);
        $this->success('重置游戏服务时间大约需1分钟,此过程中尽量不要再次重启');
    }
    public function restart(){

        $goods_id = I('goods_id');

        if(empty($goods_id)){
            $this->ajaxReturn(['status' => -1,'message' => '参数有误']);
        }

        $pig_goods = Db::name('pig_goods')->where('id',$goods_id)->find();

        if($pig_goods){

            db('pig_goods')->where('id',$pig_goods['id'])->update(['today_is_open'=> 0,'is_lock'=>0,'game_reset_time'=>time()]);
           
            Redis::del('game_award_list_'.$pig_goods['id']); //中奖人
            Redis::del('game_name_pre'.$pig_goods['id']); //游戏状态
            Redis::del('flash_buy_'.date('Ymd',time()).'_'.$pig_goods['id']);
            $this->ajaxReturn(['status' => 1,'message' => '重启成功']);

        }else{

            $this->ajaxReturn(['status' => -1,'message' => '重启失败']);
        }

    }

    /**
     * [pigDel 删除鱼]
     * @return [type] [description]
     */
    public function pigDel()
    {
        $id = intval(input('param.id'));

        $pigres = Db::name('user_exclusive_pig')->where(['id'=>$id])->find(); //查看这条购买记录

        if(!$pigres){
            return json(['code' => 0, 'data' => '', 'msg' => "找不到此记录"]);
        }

        $indate=[];
        $indate["user_id"]= $pigres["user_id"];
        $indate["order_id"]= $pigres["order_id"];
        $indate["pig_id"]= $pigres["pig_id"];
        $indate["is_able_sale"]= $pigres["is_able_sale"];
        $indate["price"]= $pigres["price"];
        $indate["from_user_id"]= $pigres["from_user_id"];
        $indate["appoint_user_id"]= $pigres["appoint_user_id"];
        $indate["buy_time"]= $pigres["buy_time"];
        $indate["end_time"]= $pigres["end_time"];
        $indate["del_id"]= $pigres["id"];
        $indate["deltime"]=date('Y-m-d H:i:s');
        Db::startTrans();
        try{

            $delres = Db::name('user_exclusive_pig_delete')->insertGetId($indate);

            $res = Db::name('user_exclusive_pig')->delete($id);

            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['code' => 0, 'data' => '', 'msg' => "操作异常"]);

        }

        return json(['code' => 1, 'data' => $id, 'msg' => "删除鱼成功"]);
    }





}
