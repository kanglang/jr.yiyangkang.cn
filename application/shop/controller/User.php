<?php

namespace app\shop\controller;
use app\shop\logic\OrderLogic;
use think\AjaxPage;
use think\Page;
use think\Verify;
use think\Db;
use think\Exception;
use app\shop\logic\UsersLogic;
use think\Loader;
use app\common\controller\Logic;
use app\common\controller\Recommend;


class User extends Base {


    public function _initialize(){
        parent::_initialize();

        //提现状态
        $tx_status_arr  = ['-2'=>'删除作废','-1'=>'审核失败','0'=>'申请中','1'=>'审核通过','2'=>'付款成功','3'=>'付款失败'];
        $this->assign('tx_status_arr',$tx_status_arr);
    }

    public function index(){
       
    /*   $str="abcdefghijkl";
       $ret_aes=new \app\common\lib\Aes();
       $enstr=$ret_aes->encrypt($str);
       $destr= $ret_aes->decrypt($enstr);
       echo $destr;
       exit;*/
       
      /*$res=new \app\api\controller\Addsalt();

      $pigsalt=$res->pigaddsalt(12,123,123456);*/
     
      return $this->fetch();
    }

    /**
     * 会员列表
     */
    public function ajaxindex(){
        // 搜索条件
        $condition = array();
        I('mobile') ? $condition['mobile'] = I('mobile') : false;
        I('user_id') ? $condition['user_id'] = I('user_id') : false;

        I('first_leader') && ($condition['first_leader'] = I('first_leader')); // 查看一级下线人有哪些
        I('second_leader') && ($condition['second_leader'] = I('second_leader')); // 查看二级下线人有哪些
        I('third_leader') && ($condition['third_leader'] = I('third_leader')); // 查看三级下线人有哪些
        $sort_order = I('order_by').' '.I('sort');

        $model = M('users');
        $count = $model->where($condition)->count();
        $Page  = new AjaxPage($count,10);
        //  搜索条件下 分页赋值
        foreach($condition as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }

        $userList = $model->where($condition)->order($sort_order)->limit($Page->firstRow.','.$Page->listRows)->select();

        //$users['price']    = db('user_exclusive_pig')->where('user_id', $user_id)->sum('price');//总资产
        //$users['contract_revenue']   = db('pig_doge_money')->where(['user_id'=>$user_id,'type'=>3])->sum('contract_revenue');//合约收益
         
        //增加总资产和合约收益
        foreach ($userList as  &$val) {
            $val['allprice']    = db('user_exclusive_pig')->where('user_id', $val["user_id"])->sum('price');//总资产
            $val['contract_revenue']   = db('pig_doge_money')->where(['user_id'=>$val["user_id"],'type'=>3])->sum('contract_revenue');//合约收益
         } 


        $user_id_arr = get_arr_column($userList, 'user_id');
        $first_leader = $second_leader = $third_leader = 0;
        if(!empty($user_id_arr))
        {
            $first_leader = DB::query("select first_leader,count(1) as count  from __PREFIX__users where first_leader in(".  implode(',', $user_id_arr).")  group by first_leader");
            $first_leader = convert_arr_key($first_leader,'first_leader');

            $second_leader = DB::query("select second_leader,count(1) as count  from __PREFIX__users where second_leader in(".  implode(',', $user_id_arr).")  group by second_leader");
            $second_leader = convert_arr_key($second_leader,'second_leader');

            $third_leader = DB::query("select third_leader,count(1) as count  from __PREFIX__users where third_leader in(".  implode(',', $user_id_arr).")  group by third_leader");
            $third_leader = convert_arr_key($third_leader,'third_leader');
        }
        $this->assign('first_leader',$first_leader);
        $this->assign('second_leader',$second_leader);
        $this->assign('third_leader',$third_leader);
        $show = $Page->show();
        $this->assign('userList',$userList);
        $this->assign('level',M('user_level')->getField('level_id,level_name'));
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('pager',$Page);
        return $this->fetch();
    }

    /**
     * 更改会员上级
     */
    public function change_leader(){
        if($this->request->isPost()){
            $userId = input('id');
            $first  = input('user_id');
            $info   = change_distribution($userId,$first);
            if($info['status'] == 1){
                $this->success($info['msg']);
            }else{
                $this->error($info['msg']);
            }
        }
        // 搜索条件
        $condition = array();
        $condition['id']       = I('get.id') ? I('get.id') : '';
        $condition['mobile']   = I('get.mobile') ? I('get.mobile') : '';
        $condition['user_id']  = I('get.user_id') ? I('get.user_id') : '';
        $condition['nickname'] = I('get.nickname') ? I('get.nickname') : '';
        $where = array();
        if(!empty($condition['mobile']))   $where['mobile']   = array('like',$condition['mobile'].'%');
        if(!empty($condition['nickname'])) $where['nickname'] = array('like','%'.$condition['nickname'].'%');
        if(!empty($condition['user_id']))  $where['user_id']  = $condition['user_id'];
        $model = M('users');
        $count = $model->where($where)->count();
        $Page  = new Page($count,10);
        //搜索条件下 分页赋值
        foreach($condition as $key=>$val) {
            $Page->parameter[$key] = urlencode($val);
        }

        $userList = $model->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        $show = $Page->show();
        return $this->fetch('',[
            'condition' => $condition,//搜索条件
            'userList'  => $userList,//用户列表
            'page'      => $show,//分页
        ]);
    }

    /**
     * 会员详细信息查看
     */
    public function detail(){
        $uid = I('get.id');
        $user = D('users')->where(array('user_id'=>$uid))->find();
        if(!$user)
            exit($this->error('会员不存在'));
        if(IS_POST){
            //  会员信息编辑
            $password = I('post.password');
            $password2 = I('post.password2');

            $paypwd = I('post.paypwd');
            $paypwd2 = I('post.paypwd2');
            if($password != '' && $password != $password2){
                exit($this->error('两次输入密码不同'));
            }

            if($paypwd != $paypwd2){
                exit($this->error('两次输入支付密码不同'));
            }
            if($paypwd == '' &&$paypwd2 == '' ){
                unset($_POST['paypwd']);
                unset($_POST['paypwd2']);
            }else{
                unset($_POST['paypwd2']);
                $_POST['paypwd'] = encrypt($_POST['paypwd']);
            }

            if($password == '' && $password2 == ''){
                unset($_POST['password']);
            }else{
                $_POST['password'] = encrypt($_POST['password']);
            }
            if(!empty($_POST['mobile']))
            {   $mobile = trim($_POST['mobile']);
                $c = M('users')->where("user_id != $uid and mobile = '$mobile'")->count();
                $c && exit($this->error('手机号不得和已有用户重复'));
            }
            if(isset($_POST['password2'])) unset($_POST['password2']);
            $row = M('users')->where(array('user_id'=>$uid))->save($_POST);
            if($row)
                exit($this->success('修改成功'));
            exit($this->error('未作内容修改或修改失败'));
        }

        $user['first_lower'] = M('users')->where("first_leader = {$user['user_id']}")->count();
        $user['second_lower'] = M('users')->where("second_leader = {$user['user_id']}")->count();
        $user['third_lower'] = M('users')->where("third_leader = {$user['user_id']}")->count();
        $level = M('user_level')->field('level_id,level_name')->select();

        $this->assign('level',$level);
        $this->assign('user',$user);
        return $this->fetch();
    }

    //银行卡信息查看 
    public function bankdetail(){  
        $uid = I('get.id');
        //user_payment
        $bank_card = D('user_payment')->where(array('user_id'=>$uid,'type'=>3))->find();
        $alipay = D('user_payment')->where(array('user_id'=>$uid,'type'=>1))->find();
        $weixin = D('user_payment')->where(array('user_id'=>$uid,'type'=>2))->find();
       
        if($bank_card){
           $bank_card= $bank_card;
        }else{
            $bank_card=array();
        }

        if($alipay){
           $alipay= $alipay;
        }else{
            $alipay=array();
        }

       if($weixin){
           $weixin= $weixin;
        }else{
           $weixin=array();
        }
     

        $this->assign('bank_card',$bank_card);
        $this->assign('alipay',$alipay);
        $this->assign('weixin',$weixin);
    
        return $this->fetch();
   
    }

    public function add_user(){
    	if(IS_POST){
    		$data = I('post.');
			$user_obj = new UsersLogic();
			$res = $user_obj->addUser($data);
			if($res['status'] == 1){
				$this->success('添加成功',U('User/index'));exit;
			}else{
				$this->error('添加失败,'.$res['msg'],U('User/index'));
			}
    	}
    	return $this->fetch();
    }

    public function export_user(){
    	$strTable ='<table width="500" border="1">';
    	$strTable .= '<tr>';
    	$strTable .= '<td style="text-align:center;font-size:12px;width:120px;">会员ID</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="100">会员昵称</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">会员等级</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">手机号</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">邮箱</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">注册时间</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">最后登陆</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">余额</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">积分</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">累计消费</td>';
    	$strTable .= '</tr>';
    	$count = M('users')->count();
    	$p = ceil($count/5000);
    	for($i=0;$i<$p;$i++){
    		$start = $i*5000;
    		$end = ($i+1)*5000;
    		$userList = M('users')->order('user_id')->limit($start.','.$end)->select();
    		if(is_array($userList)){
    			foreach($userList as $k=>$val){
    				$strTable .= '<tr>';
    				$strTable .= '<td style="text-align:center;font-size:12px;">'.$val['user_id'].'</td>';
    				$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['nickname'].' </td>';
    				$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['level'].'</td>';
    				$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['mobile'].'</td>';
    				$strTable .= '<td style="text-align:left;font-size:12px;">'.date('Y-m-d H:i',$val['reg_time']).'</td>';
    				$strTable .= '<td style="text-align:left;font-size:12px;">'.date('Y-m-d H:i',$val['last_login']).'</td>';
    				$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['user_money'].'</td>';
    				$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['pay_points'].' </td>';
    				$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['total_amount'].' </td>';
    				$strTable .= '</tr>';
    			}
    			unset($userList);
    		}
    	}
    	$strTable .='</table>';
    	downloadExcel($strTable,'users_'.$i);
    	exit();
    }

    /**
     * 用户收货地址查看
     */
    public function address(){
        $uid = I('get.id');
        $lists = D('user_address')->where(array('user_id'=>$uid))->select();
        $regionList = get_region_list();
        $this->assign('regionList',$regionList);
        $this->assign('lists',$lists);
        return $this->fetch();
    }

    /**
     * 删除会员
     */
    public function delete(){
        $uid = I('get.id');
        $row = M('users')->where(array('user_id'=>$uid))->delete();
        if($row){
            $this->success('成功删除会员');
        }else{
            $this->error('操作失败');
        }
    }
    /**
     * 删除会员
     */
    public function ajax_delete(){
        $uid = I('id');
        if($uid){
            $row = M('users')->where(array('user_id'=>$uid))->delete();
            if($row !== false){
                $this->ajaxReturn(array('status' => 1, 'msg' => '删除成功', 'data' => ''));
            }else{
                $this->ajaxReturn(array('status' => 0, 'msg' => '删除失败', 'data' => ''));
            }
        }else{
            $this->ajaxReturn(array('status' => 0, 'msg' => '参数错误', 'data' => ''));
        }
    }

    /**
     * 账户资金记录
     */
    public function account_log(){
        $timegap = urldecode(I('timegap'));
        $user_id = I('post.user_id');
        $type = I('type');
        $map = array();
        if ($timegap) {
            $gap = explode(' - ', $timegap);
            $begin = $gap[0];
            $end = $gap[1];
            $map['change_time'] = array('between', array(strtotime($begin), strtotime($end)));
        }
        if ($type) {
            $map['type'] = $type;
        }
        //获取类型
        $type = I('get.type');
        //获取记录总数
        if(!empty($user_id)) $map['a.user_id'] = $user_id;
        $count = M('account_log')
                  ->alias('a')
                  ->join('users b','a.user_id = b.user_id')
                  ->field('a.*,b.mobile')
                  ->where($map)
                  ->count();
        $page = new Page($count);
        $lists  = M('account_log')
                  ->alias('a')
                  ->join('users b','a.user_id = b.user_id')
                  ->field('a.*,b.mobile')
                  ->where($map)
                  ->order('change_time desc')
                  ->limit($page->firstRow.','.$page->listRows)
                  ->select();
        $this->assign('user_id',$user_id);
        $this->assign('count',$count);
        $this->assign('page',$page->show());
        $this->assign('lists',$lists);
        return $this->fetch();
    }

    /**
     * 账户资金调节
     */
    public function account_edit(){
        $user_id = I('user_id');

        if(!$user_id > 0) $this->ajaxReturn(['status'=>0,'msg'=>"参数有误"]);
        $user = M('users')->field('user_id,user_money,frozen_money,pay_points,is_lock,doge_money,pig_currency,usermoneysalt')->where('user_id',$user_id)->find();
        if(IS_POST){
            $desc = I('post.desc');
            if(!$desc)
                $this->ajaxReturn(['status'=>0,'msg'=>"请填写操作说明"]);
            //加减用户推广财分
            $p_op_type_1 = I('post.money_act_type_1');
            $user_money  = I('post.user_money/f');
            if($user_money!=0){
        
                $user_money_1=  $p_op_type_1 ? ($user['user_money'] + $user_money) : ($user['user_money']-$user_money);
                $user_money  =  $p_op_type_1 ? $user_money : 0-$user_money;
                
                //如果推广财分不为零，就加盐        
                   $salt_money=$user_money_1;
                   $addsalt = new \app\api\controller\Addsalt();
                   $usermoneysalt=$addsalt->userMoneyaddsalt($user_id,$salt_money);
               // 为推广财分做加盐处理-------------

                if($user_money_1 >=0){
                    $laststatus=0;
                    //查看上次是否盐异常------------------
                    $addsalt = new \app\api\controller\Addsalt();
                    $usermoneysaltcheck=$addsalt->checkuserMoneysalt($user_id);
                    if(!$usermoneysaltcheck){
                       $laststatus=-1;
                    } 

                    M('users')->where('user_id',$user_id)->update(['user_money' => $user_money_1,'usermoneysalt' => $usermoneysalt]);
                    $account_log=array('user_id'=>$user_id,'user_money'=>$user_money,'change_time'=>time(),'desc'=>$desc,'type'=>11,
                        'laststatus'=>$laststatus);
                    M('account_log')->add($account_log);
                    $status = 1;
                }else{

                    $this->ajaxReturn(['status'=>0,'msg'=>"用户剩余推广财分不足！！"]);
                }
            }

            //加减用户福分
            $p_op_type_2 = I('post.point_act_type_2');
            $pay_points  = I('post.pay_points/d');
            if($pay_points !=0){

                $pay_points_2=  $p_op_type_2 ? ($user['pay_points'] + $pay_points) : ($user['pay_points']-$pay_points);
                $pay_points  =  $p_op_type_2 ? $pay_points : 0-$pay_points;
                if($pay_points_2 >=0){
                    M('users')->where('user_id',$user_id)->update(['pay_points' => $pay_points_2]);
                    $account_log=array('user_id'=>$user_id,'pay_points'=>$pay_points,'change_time'=>time(),'desc'=>$desc,'type'=>11);
                    M('account_log')->add($account_log);
                    //判断是否满足福分 升级条件
                    Recommend::up_level($user_id);//用户升级
                    $status = 1;
                }else{

                    $this->ajaxReturn(['status'=>0,'msg'=>"用户剩余福分不足！！"]);
                }
            }

            //加减用户SHRIMP
            $p_op_type_3 = I('post.point_act_type_3');
            $doge_money  = I('post.doge_money/d');
            if($doge_money !=0){

                $doge_money_3=  $p_op_type_3 ? ($user['doge_money'] + $doge_money) : ($user['doge_money']-$doge_money);
                $doge_money  =  $p_op_type_3 ? $doge_money : 0-$doge_money;
                if($doge_money_3 >=0){
                    M('users')->where('user_id',$user_id)->update(['doge_money' => $doge_money_3]);
                    $account_log=array('user_id'=>$user_id,'doge_money'=>$doge_money,'change_time'=>time(),'desc'=>$desc,'type'=>11);
                    M('account_log')->add($account_log);
                    $status = 1;
                }else{

                    $this->ajaxReturn(['status'=>0,'msg'=>"用户剩余SHRIMP不足！！"]);
                }
            }

            //加减用户FISH
            $p_op_type_4  = I('post.point_act_type_4');
            $pig_currency = I('post.pig_currency/d');
            if($pig_currency !=0){

                $pig_currency_4= $p_op_type_4 ? ($user['pig_currency'] + $pig_currency) : ($user['pig_currency']-$pig_currency);
                $pig_currency =  $p_op_type_4 ? $pig_currency : 0-$pig_currency;
                if($pig_currency_4 >=0){
                    M('users')->where('user_id',$user_id)->update(['pig_currency' => $pig_currency_4]);
                    $account_log=array('user_id'=>$user_id,'pig_currency'=>$pig_currency,'change_time'=>time(),'desc'=>$desc,'type'=>11);
                    M('account_log')->add($account_log);
                    $status = 1;
                }else{

                    $this->ajaxReturn(['status'=>0,'msg'=>"用户剩余FISH不足！！"]);
                }

            }

            if($status == 1){

                $this->ajaxReturn(['status'=>1,'msg'=>"操作成功",'url'=>U("shop/User/account_log",array('id'=>$user_id))]);
            }else{

                $this->ajaxReturn(['status'=>-1,'msg'=>"充值未成功"]);
            }

            exit;


            // //加减冻结资金
            // $f_op_type = I('post.frozen_act_type');
            // $revision_frozen_money = I('post.frozen_money/f');
            // if( $revision_frozen_money != 0){    //有加减冻结资金的时候
            //     $frozen_money =  $f_op_type ? $revision_frozen_money : 0-$revision_frozen_money;
            //     $frozen_money = $user['frozen_money']+$frozen_money;    //计算用户被冻结的资金
            //     if($f_op_type==1 and $revision_frozen_money > $user['user_money'])
            //     {
            //         $this->ajaxReturn(['status'=>0,'msg'=>"用户剩余资金不足！！"]);
            //     }
            //     if($f_op_type==0 and $revision_frozen_money > $user['frozen_money'])
            //     {
            //         $this->ajaxReturn(['status'=>0,'msg'=>"冻结的资金不足！！"]);
            //     }
            //     $user_money = $f_op_type ? 0-$revision_frozen_money : $revision_frozen_money ;    //计算用户剩余资金
            //     M('users')->where('user_id',$user_id)->update(['frozen_money' => $frozen_money]);
            // }
            // if(accountLog($user_id,$user_money,$pay_points,$desc,0,0,'',9))
            // {
            //     $this->ajaxReturn(['status'=>1,'msg'=>"操作成功",'url'=>U("shop/User/account_log",array('id'=>$user_id))]);
            // }else{
            //     $this->ajaxReturn(['status'=>-1,'msg'=>"操作失败"]);
            // }
            // exit;
        }
        $this->assign('user_id',$user_id);
        $this->assign('user',$user);
        return $this->fetch();
    }

    public function recharge(){
    	$timegap = urldecode(I('timegap'));
        $nickname = I('nickname');
        $mobile = I('mobile');
        $map = array();
        if($timegap){
            $gap = explode(' - ', $timegap);
            $begin = $gap[0];
            $end = $gap[1];
            $map['add_time'] = array('between',array(strtotime($begin),strtotime($end)));
        }
        if($nickname){
            $map['nickname'] = array('like',"%$nickname%");
        }
        if($mobile){
            $map['mobile'] = $mobile;
        }
        // $count = M('recharge')->where($map)->count();
        // $page = new Page($count);
        // $lists  = M('recharge')->where($map)->order('add_time desc')->limit($page->firstRow.','.$page->listRows)->select();

        $count = Db::name('recharge')->alias('w')->join('__USERS__ u', 'u.user_id = w.user_id', 'INNER')->where($map)->count();

        $Page  = new Page($count,20);

        $lists = Db::name('recharge')->alias('w')->field('w.*,u.nickname,u.mobile')->join('__USERS__ u', 'u.user_id = w.user_id', 'INNER')->where($map)->order("w.add_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();

        $show  = $Page->show();

        $this->assign('page',$show);
        $this->assign('lists',$lists);
        return $this->fetch();
    }

    /**
     * 审核充值
     */
    public function editRecharge() {
        $order_id = input('id');
        if (request()->isPost()) {
            $post          = input('post.');

            $recharge = Db::name('recharge r')->field('r.user_id,r.order_id,r.nickname,r.account,r.img_url,r.pay_status,r.order_sn')
                          ->join('users u', 'r.user_id=u.user_id')->where('order_id', $post['order_id'])->find();
            $pay_status    = $post['pay_status'];
            $remark        = $post['remark'];
            $verifier_time = time();
            if ($pay_status == 1 && $recharge['pay_status'] != 1) {


                // 启动事务
                Db::startTrans();
                try {
                    Db::name('recharge')->update([
                        'order_id'      => $post['order_id'],
                        'pay_status'    => $pay_status,
                        'remark'        => $remark,
                        'verifier_time' => $verifier_time
                    ]);
                    accountLog($post['user_id'],0, $recharge['account'], '用户充值', 0, $post['order_id'], $post['order_sn'],10);//资金流日志

                    //判断是否满足福分 升级条件
                    Recommend::up_level($post['user_id']);//升级为会员


                    Db::commit();
                    return $this->success('操作成功', U('recharge'));
                } catch (Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return $this->error('操作失败');
                }
            } else {
                $res = Db::name('recharge ')->where('order_id' , $post['order_id'])->setField([
                    'pay_status'    => $pay_status,
                    'remark'        => $remark,
                    'verifier_time' => $verifier_time
                ]);
                if ($res) {
                    return $this->success('操作成功', 'User/recharge');
                } else {
                    return $this->success('操作失败');
                }
            }
        }
        $recharge = Db::name('recharge r')->field('r.user_id,r.order_id,r.nickname,r.account,r.img_url,r.pay_status,r.order_sn')
                      ->join('users u', 'r.user_id=u.user_id')->where('order_id', $order_id)->find();
        $this->assign('data', $recharge);
        return $this->fetch();
    }

    public function level(){
    	$act = I('get.act','add');
    	$this->assign('act',$act);
    	$level_id = I('get.level_id');
    	if($level_id){
    		$level_info = D('user_level')->where('level_id='.$level_id)->find();
    		$this->assign('info',$level_info);
    	}
    	return $this->fetch();
    }

    public function levelList(){
    	$Ad =  M('user_level');
        $p = $this->request->param('p');
    	$res = $Ad->order('level_id')->page($p.',10')->select();
    	if($res){
    		foreach ($res as $val){
    			$list[] = $val;
    		}
    	}
    	$this->assign('list',$list);
    	$count = $Ad->count();
    	$Page = new Page($count,10);
    	$show = $Page->show();
    	$this->assign('page',$show);
    	return $this->fetch();
    }

    //分红设置 添加/编辑 页面
    public function distSet(){

        $request = request()->param();//p($request);

        if( isset($request['id']) && $request['id'] ){

            $level_info = M('user_ratio')->where('id',$request['id'])->find();
            $this->assign('info',$level_info);

        }


        return $this->fetch();
    }

    //分红设置列表
    public function distSetList(){

        $M =  M('user_ratio');
        $user_level = input('user_level') ? input('user_level') : 3;

        $list = $M->where('user_level',$user_level)->order('lowermoney asc')->select();
        foreach($list as $k=>$v){
            switch($v['user_level']){
                case 3: $list[$k]['user_level']="钻卡"; break;
                case 4: $list[$k]['user_level']="公司"; break;
                default:$list[$k]['user_level']=" ";
            }
        }
        $this->assign('list',$list);
        $this->assign('user_level',$user_level);
        return $this->fetch();

    }

    //分红设置 新增/更新 操作
    public function distSetHandle(){

        $request = input('post.');//pe($request);
        $input = [
            ['proportion','require|integer','分成比例'],
            ['user_level','require|integer','用户等级'],
        ];
        $validate = ValidateAuto($input,$request);
        if ($validate) {
            $this->error($validate);
        }

        $UsersLogic  = new UsersLogic();

        try {

            if( isset($request['id']) && $request['id'] ){//编辑

                $input = [
                    ['id','require|integer','分红设置编号'],
                ];
                $validate = ValidateAuto($input,$request);
                if ($validate) {
                    $this->error($validate);
                }

                $res = $UsersLogic->distSetUpdate($request);

            }else{//添加

                if( isset($request['id']) ){
                    unset($request['id']);
                }

                $res = $UsersLogic->distSetInsert($request);
            }

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        $this->success('操作成功',url('user/distSetList'));


    }

    public  function distSetDel(){

        $request = request()->param();//pe($request);
        $input = [
            ['id','require|integer','分红设置编号'],
        ];
        $validate = ValidateAuto($input,$request);
        if ($validate) {
            $this->error($validate);
        }

        $res = db('user_ratio')->delete($request['id']);

        $this->success('操作成功',$res);
    }

     /**
     * 会员等级添加编辑删除
     */
    public function levelHandle()
    {
        $data = I('post.');
        $userLevelValidate = Loader::validate('UserLevel');

        $return = ['status' => 0, 'msg' => '参数错误', 'result' => ''];//初始化返回信息
        if (!empty($data['act']) && $data['act'] == 'add') {
            if (!$userLevelValidate->batch()->check($data)) {
                $return = ['status' => 0, 'msg' => '添加失败', 'result' => $userLevelValidate->getError()];
            } else {
                unset($data['act']);
                $r = D('user_level')->add($data);
                if ($r !== false) {
                    $return = ['status' => 1, 'msg' => '添加成功', 'result' => $userLevelValidate->getError()];
                } else {
                    $return = ['status' => 0, 'msg' => '添加失败，数据库未响应', 'result' => ''];
                }
            }
        }
        if (!empty($data['act']) && $data['act'] == 'edit') {
            // if (!$userLevelValidate->scene('edit')->batch()->check($data)) {
            //     $return = ['status' => 0, 'msg' => '编辑失败', 'result' => $userLevelValidate->getError()];
            // } else {
                unset($data['act']);
                $r = D('user_level')->where('level_id=' . $data['level_id'])->save($data);
                if ($r !== false) {
                    $return = ['status' => 1, 'msg' => '编辑成功', 'result' => $userLevelValidate->getError()];
                } else {
                    $return = ['status' => 0, 'msg' => '编辑失败，数据库未响应', 'result' => ''];
                }
            // }
        }
        if (!empty($data['act']) && $data['act'] == 'del') {
            $r = D('user_level')->where('level_id=' . $data['level_id'])->delete();
            if ($r !== false) {
                $return = ['status' => 1, 'msg' => '删除成功', 'result' => ''];
            } else {
                $return = ['status' => 0, 'msg' => '删除失败，数据库未响应', 'result' => ''];
            }
        }
        if($return['status']==1){
            $this->success($return['msg'],url('user/levelList'));
        }else{
            $this->error($return['msg']);
        }

    }

    /**
     * 搜索用户名
     */
    public function search_user()
    {
        $search_key = trim(I('search_key'));
        $list = M('users')->where(" mobile like '%$search_key%' ")->select();
        foreach($list as $key => $val)
        {
            echo "<option value='{$val['user_id']}'>{$val['mobile']}</option>";
        }
        exit;
    }

    /**
     * 分销树状关系
     */
    public function ajax_distribut_tree()
    {
          $list = M('users')->where("first_leader = 1")->select();
          return $this->fetch();
    }

    /**
     *
     * @time 2016/08/31
     * @author dyr
     * 发送站内信
     */
    public function sendMessage()
    {
        $user_id_array = I('get.user_id_array');
        $users = array();
        if (!empty($user_id_array)) {
            $users = M('users')->field('user_id,nickname')->where(array('user_id' => array('IN', $user_id_array)))->select();
        }
        $this->assign('users',$users);
        return $this->fetch();
    }

    /**
     * 发送系统消息
     * @author dyr
     * @time  2016/09/01
     */
    public function doSendMessage()
    {
        $call_back = I('call_back');//回调方法
        $text= I('post.text');//内容
        $type = I('post.type', 0);//个体or全体
        $admin_id = session('admin_id');
        $users = I('post.user/a');//个体id
        $message = array(
            'admin_id' => $admin_id,
            'message' => $text,
            'category' => 0,
            'send_time' => time()
        );

        if ($type == 1) {
            //全体用户系统消息
            $message['type'] = 1;
            M('send_message')->add($message);
        } else {
            //个体消息
            $message['type'] = 0;
            if (!empty($users)) {
                $create_message_id = M('send_message')->add($message);
                foreach ($users as $key) {
                    M('user_message')->add(array('user_id' => $key, 'message_id' => $create_message_id, 'status' => 0, 'category' => 0));
                }
            }
        }
        echo "<script>parent.{$call_back}(1);</script>";
        exit();
    }

    /**
     * 提现申请记录
     */
    public function withdrawals()
    {
    	$this->get_withdrawals_list();
        return $this->fetch();
    }

    public function get_withdrawals_list($status=''){
    	$user_id = I('user_id/d');
        $mobile  = I('mobile');
    	$realname = I('realname');
    	$bank_card = I('bank_card');
    	$create_time = I('create_time');
    	$create_time = str_replace("+"," ",$create_time);
    	$create_time2 = $create_time  ? $create_time  : date('Y-m-d',strtotime('-1 year')).' - '.date('Y-m-d',strtotime('+1 day'));
    	$create_time3 = explode(' - ',$create_time2);

    	$this->assign('start_time',$create_time3[0]);
    	$this->assign('end_time',$create_time3[1]);


    	$status = empty($status) ? I('status') : $status;

        $where = [];
        //
    	// if(empty($status) || $status === '0'){
    	// 	$where['w.status'] =  array('lt',1);
    	// }
    	// if($status === '0' || $status > 0) {
    	// 	$where['w.status'] = $status;
    	// }
        if($status != '') {
          $where['w.status'] = $status;
        }
        // var_dump($where['w.status']);die;
        // (I('status') !== '') && $where['status']  = I('status');
    	$user_id && $where['u.user_id'] = $user_id;
        $mobile && $where['u.mobile'] = $mobile;
    	$realname && $where['w.realname'] = array('like','%'.$realname.'%');
    	$bank_card && $where['w.bank_card'] = array('like','%'.$bank_card.'%');
        $where['w.create_time'] =  array(array('gt', strtotime($create_time3[0])), array('lt', strtotime($create_time3[1])));
    	$count = Db::name('withdrawals')->alias('w')->join('__USERS__ u', 'u.user_id = w.user_id', 'INNER')->where($where)->count();
    	$Page  = new Page($count,20);


    	$list = Db::name('withdrawals')->alias('w')->field('w.*,u.nickname,u.mobile')->join('__USERS__ u', 'u.user_id = w.user_id', 'INNER')->where($where)->order("w.id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        // echo "<pre>";
        // var_dump($list);die;
    	$this->assign('create_time',$create_time2);
    	$show  = $Page->show();
    	$this->assign('show',$show);
    	$this->assign('list',$list);
    	$this->assign('pager',$Page);
    }

    /**
     * 删除申请记录
     */
    public function delWithdrawals()
    {
        $model = M("withdrawals");
        $model->where('id ='.$_GET['id'])->delete();
        $return_arr = array('status' => 1,'msg' => '操作成功','data'  =>'',);   //$return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);
        $this->ajaxReturn($return_arr);
    }

    /**
     * 修改编辑 申请提现
     */
    public  function editWithdrawals(){
       $id = I('id');
       $model = M("withdrawals");
       $withdrawals = $model->find($id);
       $user = M('users')->where("user_id = {$withdrawals['user_id']}")->find();
       if($user['nickname'])
           $withdrawals['user_name'] = $user['nickname'];
       elseif($user['mobile'])
           $withdrawals['user_name'] = $user['mobile'];

       $this->assign('user',$user);
       $this->assign('data',$withdrawals);
       return $this->fetch();
    }

    /**
     *  处理会员提现申请
     */
    public function withdrawals_update(){
    	$id = I('id/a');
        $data['status']=$status = I('status');
    	$data['remark'] = I('remark');
        $type    = I('type');
        $user_id = I('user_id');
        $money = I('money');
        //用户本身的币种数量
        $users = M('users')->field("doge_money,pig_currency")->where("user_id",$user_id)->find();


        if($status == 1){

            //币种
            if($type == 1){        //pig币

                $summoney = $users['pig_currency'] -  $money;

                if($summoney < 0 ){

                    $this->ajaxReturn(array('status'=>0,'msg'=>"账户pig币不足"),'JSON');
                }
                $result = array('pig_currency'=>$summoney);
                M("users")->where('user_id',$user_id)->update($result);


            }else if($type == 2){  //虾虾币

                $summoney = $users['doge_money'] -  $money;

                if($summoney < 0 ){

                    $this->ajaxReturn(array('status'=>0,'msg'=>"账户虾虾币不足"),'JSON');
                }
                $result = array('doge_money'=>$summoney);
                M("users")->where('user_id',$user_id)->update($result);
            }

            if($status == 1) $data['check_time'] = time();
            // if($status != 1) $data['refuse_time'] = time();
            $r = M('withdrawals')->where('id in ('.implode(',', $id).')')->update($data);
            if($r){
                $this->ajaxReturn(array('status'=>1,'msg'=>"操作成功"),'JSON');
            }else{
                $this->ajaxReturn(array('status'=>0,'msg'=>"操作失败"),'JSON');
            }
        }else{

            if($status == 1) $data['check_time'] = time();
            // if($status != 1) $data['refuse_time'] = time();
            $r = M('withdrawals')->where('id in ('.implode(',', $id).')')->update($data);
            if($r){
                $this->ajaxReturn(array('status'=>1,'msg'=>"操作成功"),'JSON');
            }else{
                $this->ajaxReturn(array('status'=>0,'msg'=>"操作失败"),'JSON');
            }
        }

    }
    // 用户申请提现
    public function transfer(){
    	$id = I('selected/a');
    	if(empty($id))$this->error('请至少选择一条记录');
    	$atype = I('atype');
        var_dump($atype);die;
    	if(is_array($id)){
    		$withdrawals = M('withdrawals')->where('id in ('.implode(',', $id).')')->select();
    	}else{
    		$withdrawals = M('withdrawals')->where(array('id'=>$id))->select();
    	}
    	$alipay['batch_num'] = 0;
    	$alipay['batch_fee'] = 0;
    	foreach($withdrawals as $val){
    		$user = M('users')->where(array('user_id'=>$val['user_id']))->find();
    		if($user['user_money'] < $val['money'])
    		{
    			$data = array('status'=>-2,'remark'=>'账户余额不足');
    			M('withdrawals')->where(array('id'=>$val['id']))->save($data);
    			$this->error('账户余额不足');
    		}else{
    			$rdata = array('type'=>1,'money'=>$val['money'],'log_type_id'=>$val['id'],'user_id'=>$val['user_id']);
    			if($atype == 'online'){
			header("Content-type: text/html; charset=utf-8");
            exit("功能正在开发中。。。");
    			}else{
    				accountLog($val['user_id'], ($val['money'] * -1), 0,"管理员处理用户提现申请");//手动转账，默认视为已通过线下转方式处理了该笔提现申请
    				$r = M('withdrawals')->where(array('id'=>$val['id']))->save(array('status'=>2,'pay_time'=>time()));
    				expenseLog($rdata);//支出记录日志
    			}
    		}
    	}
    	if($alipay['batch_num']>0){
    		//支付宝在线批量付款
    		include_once  PLUGIN_PATH."payment/alipay/alipay.class.php";
    		$alipay_obj = new \alipay();
    		$alipay_obj->transfer($alipay);
    	}
    	$this->success("操作成功!",U('remittance'),3);
    }

    /**
     *  转账汇款记录
     */
    public function remittance(){
    	$status = I('status',1);
    	$this->assign('status',$status);
    	$this->get_withdrawals_list($status);
        return $this->fetch();
    }

        /**
     * 签到列表
     * @date 2017/09/28
     */
    public function signList() {
    header("Content-type: text/html; charset=utf-8");
exit("功能正在开发中。。。");
    }


    /**
     * 会员签到 ajax
     * @date 2017/09/28
     */
    public function ajaxsignList() {
    header("Content-type: text/html; charset=utf-8");
exit("功能正在开发中。。。");
    }

    /**
     * 签到规则设置
     * @date 2017/09/28
     */
    public function signRule() {
    header("Content-type: text/html; charset=utf-8");
exit("功能正在开发中。。。");
    }

    /**
     * 会员审核列表
     */
    public function CheckList()
    {
        $pagesize = config('paginate')['list_rows'];//每页数量
        $param=request()->param(); //获取url参数
        $list = M('user_apply_log')->where("level = 4")->order('create_time desc')->paginate($pagesize,false,array('query' => array_splice($param,1)));
        $this->assign('list',$list);
        $this->assign("page", $list->render());
        return $this->fetch();
    }

    /**
     * 会员审核
     */
    public function check()
    {
        $id = input('id');
        $data = M('user_apply_log')->where(['id'=>$id])->find();
        if (request()->isPost()) {
            $post = input('post.');
            $user_id = $post['user_id'];
            $post['update_time'] = time();
            $res = M('user_apply_log')->where(['id'=>$id,'status'=>1])->find();
            if ($res) {
                $this->error('非法操作','CheckList');
            }
            if ($post['status'] == 1) {
                $res = Logic::updCompany($user_id);
                if ($res) {
                    Db::name('user_apply_log')->where(['id'=>$id])->update($post);
                    Db::name('users')->where(['user_id'=>$user_id])->update(['level'=>4,'company_create_time'=>time()]);
                    $this->success('操作成功','CheckList');
                }else{
                    $this->error('暂不满足升级条件,无法升级为公司','CheckList');
                }
            }else{
                Db::name('user_apply_log')->where(['id'=>$id])->update($post);
                $this->success('操作成功','CheckList');
            }
        }
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 交易中心申诉列表
     */
    public function AppealList()
    {
        // $map = [];
        // $user_id = input("user_id");
        // if (!empty($user_id)) {
        //     $map['user_id'] = $user_id;
        // }
        // $pagesize = config('paginate')['list_rows'];//每页数量
        // $param=request()->param(); //获取url参数
        // $lists = db('pig_appeal')->where($map)->order('id desc')->paginate($pagesize,false,array('query' => array_splice($param,1)));
        // $this->assign("lists",$lists);
        // $this->assign("page", $lists->render());
        // return $this->fetch();

        $user_id = I('user_id/d');
        $mobile  = I('mobile');

        $where = [];
        $user_id && $where['u.user_id'] = $user_id;
        $mobile && $where['u.mobile'] = $mobile;

        $count = Db::name('pig_appeal')->alias('w')->join('__USERS__ u', 'u.user_id = w.user_id', 'INNER')->where($where)->count();

        $Page  = new Page($count,20);

        $list = Db::name('pig_appeal')->alias('w')->field('w.*,u.nickname,u.mobile')->join('__USERS__ u', 'u.user_id = w.user_id', 'INNER')->where($where)->order("w.id desc")->limit($Page->firstRow.','.$Page->listRows)->select();

        $show  = $Page->show();

        $this->assign("list",$list);
        $this->assign("show",$show);

        return $this->fetch();
    }
    /*
     * 申诉中心
     */
    public function CheckAppeal(){

        $id = input("id");
        $order_id= input("order_id");

        $list = db('pig_appeal')->where('id',$id)->find();

        $users = Db::name('users')->field('nickname')->where(['user_id'=>$list['user_id']])->find();

        $order = Db::name('pig_order')->field('pig_order_sn,order_id')->where(['order_id'=>$order_id])->find();

        if(empty($order)){

            $this->error('该申诉没有订单信息','AppealList');
        }

        // echo "<pre>";
        // var_dump($order);die;
        $this->assign('list',$list);
        $this->assign('users',$users);
        $this->assign('order',$order);
        return $this->fetch();
    }
    /*
     * 修改申诉
     */
    public function CheckAppeal_edit(){

        $post = input('post.');

         if(empty($post)){
            $this->error('非法操作','AppealList');
        }

        //申诉
        $data = db('pig_appeal')->where('id',$post['id'])->find();

        //订单
        $order = db('pig_order')->where('order_id',$data['order_id'])->find();

        //鱼的表
        $exclusive_pig = db('user_exclusive_pig')->where('id',$order['pig_id'])->find();

        //查询鱼的等级收益结束时间
        $pig_goods = db('pig_goods')->field('contract_days')->where('id',$order['pig_level'])->find();

        $goods_time = $pig_goods['contract_days'];
        $time = time();
        //算出下个合约期结束的时间
        $day = "+".$goods_time." day";
        $end_time = strtotime($day, time());

        //延长订单的结束时间
        $order_end_time = strtotime("+1 hour", date($order['end_time'],time()));

        if(empty($data)){

            $this->error('非法操作','CheckAppeal');
        }

        if(empty($order)){

            $this->error('订单信息错误','CheckAppeal');
        }
        if(empty($exclusive_pig)){

            $this->error('参数有误','CheckAppeal');
        }
        // echo "<pre>";
        // var_dump($order['pay_status']);
        // die;

        if($order['pay_status'] == 2){

            Db::name('pig_appeal')->where(['id'=>$post['id']])->update(['status'=>2,'update_time'=>time()]);
            $this->success('订单已完成，申诉失效','AppealList');
        }

        if ($data['complainant'] == 1 && $post['status'] ==1) {//买家申诉
            Db::startTrans();

                    $res1 = Db::name('pig_appeal')->where(['id'=>$post['id']])->update(['status'=>1,'update_time'=>time()]);
                    $res2 = Db::name('pig_order')->where(['order_id'=>$order['order_id']])->update(['pay_status'=>2]);
                    //加盐--------------
                    $nowtime=time();
                    $buy_type='buyAppeal';
                    $res=new \app\api\controller\Addsalt();
                    $pig_salt=$res->pigaddsalt($order['purchase_user_id'],$order['order_id'],$nowtime,$buy_type);


                    $res3 = Db::name('user_exclusive_pig')->where(['id'=>$exclusive_pig['id']])->update(['user_id'=>$order['purchase_user_id'],'from_user_id'=>$order['sell_user_id'],'buy_time'=>$nowtime,'end_time'=>$end_time,'order_id'=>$order['order_id']
                        ,'buy_type'=>$buy_type,'pig_salt'=>$pig_salt]);

                    if ($res1 && $res2 && $res3  ) {
                    // 提交事务
                        Db::commit();
                    $this->success('操作成功','AppealList');
                    }else{
                        Db::rollback();
                        $this->error('操作失败');
                    }

        }else if($data['complainant'] == 2 && $post['status'] ==1){//卖家申诉
            Db::startTrans();


                    $order_id = Db::name('pig_order')->where(['pig_id'=>$exclusive_pig['id'],'purchase_user_id'=>$order['sell_user_id']])->order('order_id desc')->value('order_id');
                    $res1 = Db::name('pig_appeal')->where(['id'=>$data['id']])->update(['status'=>1,'update_time'=>time()]);
                    $res2 = Db::name('pig_order')->where(['order_id'=>$order['order_id']])->update(['pay_status'=>2]);
                    //加盐--------------
                    $nowtime=time();
                    $buy_type='sellAppeal';
                    $res=new \app\api\controller\Addsalt();
                    $pig_salt=$res->pigaddsalt($order['sell_user_id'],$order_id,$nowtime,$buy_type);

                    $res3 = Db::name('user_exclusive_pig')->where(['id'=>$exclusive_pig['id']])->update(['user_id'=>$order['sell_user_id'],'from_user_id'=>$order['purchase_user_id'],'buy_time'=>$nowtime,'end_time'=>$end_time,'order_id'=>$order_id,'buy_type'=>$buy_type,'pig_salt'=>$pig_salt]);
                    //交易完成增加鱼鱼币
                    $pig_currency = db('pig_goods')->where(['id' => $exclusive_pig['pig_id']])->value('pig_currency'); //获取该鱼的鱼币

                    $pig['pig_currency'] = $pig_currency;
                    $pig['add_time']     = time();
                    $pig['desc']         = '增加鱼鱼币';
                    $pig['type']         = 1;
                    $pig['user_id']      = $exclusive_pig['user_id']; //增加鱼鱼币的人
                    $pig['order_sn']     = $order['pig_order_sn'];
                    db('pig_doge_money')->add($pig);
                    db('users')->update(['user_id'=>$exclusive_pig['user_id'],'pig_currency'=>['exp','pig_currency+'.$pig_currency]]);
                    if ($res1 && $res2 && $res3) {
                    // 提交事务
                        Db::commit();
                    $this->success('操作成功','AppealList');
                    }else{
                        Db::rollback();
                        $this->error('操作失败');
                    }
        }else if($post['status'] ==-1){  //申诉失败  再延长一个小时
             Db::startTrans();

                    $res1 = Db::name('pig_appeal')->where(['id'=>$data['id']])->update(['status'=>-1,'update_time'=>time()]);
                    $res2 = Db::name('pig_order')->where(['order_id'=>$order['order_id']])->update(['pay_status'=>1,'end_time'=>$order_end_time,'img_url'=>null]);
                    if ($res1 && $res2) {
                    // 提交事务
                        Db::commit();
                    $this->success('操作成功','AppealList');
                    }else{
                        Db::rollback();
                        $this->error('操作失败');
                    }
        }else{

            $this->error('请选择正确的申诉方式');
        }
    }

        /**
     * 审核身份列表
     */
/*    public function identity()
    {
        $map = [];
        $user_id = input("user_id");
        if (!empty($user_id)) {
            $map['user_id'] = $user_id;
        }
        $pagesize = config('paginate')['list_rows'];//每页数量
        $param=request()->param(); //获取url参数
        $lists = db('user_identity')->where($map)->order('id desc')->paginate($pagesize,false,array('query' => array_splice($param,1)));
        $this->assign("lists",$lists);
        $this->assign("page", $lists->render());
        return $this->fetch();
    }*/

    public function identity()
    {
        $map = [];
        $user_id = input("user_id");
        if (!empty($user_id)) {
            $map['t1.user_id'] = $user_id;
        }
        $mobile = input("mobile");
        if (!empty($mobile)) {
            $map['t2.mobile'] = $mobile;
        }
    


        $pagesize = config('paginate')['list_rows'];//每页数量
        $param=request()->param(); //获取url参数
        //$lists = db('user_identity')->where($map)->order('id desc')->paginate($pagesize,false,array('query' => array_splice($param,1)));
        $lists = Db('user_identity as t1')
                            ->field('t1.*')
                            ->join('users t2','t1.user_id = t2.user_id','LEFT')
                            ->where($map)->order('t1.id desc')->paginate($pagesize,false,array('query' => array_splice($param,1)));
        $this->assign("lists",$lists);
        $this->assign("page", $lists->render());
        return $this->fetch();
    }


    /*
     * 审核身份中心
     */
    public function Check_identity(){

        $id = input("id");

        $list = db('user_identity')->where('id',$id)->find();

        $users = Db::name('users')->field('nickname')->where(['user_id'=>$list['user_id']])->find();

        $send_mail = Db::name('send_mail')->where(['user_id'=>$list['user_id']])->value('content');

        // $order = Db::name('pig_order')->field('pig_order_sn,order_id')->where(['order_id'=>$order_id])->find();

        // if(empty($order)){

        //     $this->error('该申诉没有订单信息','AppealList');
        // }

        // echo "<pre>";
        // var_dump($order);die;
        $this->assign('list',$list);

        if($list['status']==-1){

        $this->assign('send_mail',$send_mail);

        }

        $this->assign('users',$users);
        return $this->fetch();
    }

    /*
     * 提交审核
     */
    public function Check_identity_edit()
    {
        $post = input('post.');


        $admin_id =$_SESSION['think']['admin_id'];


        $data = db('user_identity')->where('id',$post['id'])->find();


        if(empty($data)){

            $this->error('该审核信息有误','identity');
        }

        if($post['status'] == 1){

            Db::startTrans();

                    $res1 = Db::name('user_identity')->where(['id'=>$post['id']])->update(['status'=>1,'update_time'=>time()]);
                    $res2 = Db::name('users')->where(['user_id'=>$data['user_id']])->update(['identity'=>$data['identity'],'real_name'=>$data['real_name']]);
                    if ($res1 && $res2) {
                    // 提交事务
                        Db::commit();
                    $this->success('提交成功','identity');
                    }else{
                        Db::rollback();
                        $this->error('提交失败');
                    }
        }else if($post['status'] == -1){

            if(empty($post['reason'])){

               $this->error('请填写审核原因');
            }

            Db::startTrans();

                    $res1 = Db::name('user_identity')->where(['id'=>$post['id']])->update(['status'=>-1,'update_time'=>time()]);
                    $res2 = Db::name('send_mail')->insert(['user_id'=>$data['user_id'],'admin_id'=>$admin_id,'content'=>$post['reason'],'create_time'=>time()]);
                    if ( $res2) {
                    // 提交事务
                        Db::commit();
                    $this->success('提交成功','identity');
                    }else{
                        Db::rollback();
                        $this->error('提交失败');
                    }
        }else{

            $this->error('请选择正确的审核方式');
        }

    }
}
