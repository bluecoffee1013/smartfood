<?php
global $_GPC, $_W;
load()->func('tpl');

$GLOBALS['frames'] = $this->getMainMenu($storeid,$action);
if(checksubmit('submit')){
            $op=$_GPC['keywords'];
            $where="%$op%";
           }else{
            $where='%%';
           }    
     if(checksubmit('submit2')){
        $start=strtotime($_GPC['time']['start']);
        $end=strtotime($_GPC['time']['end']);
    }
    $pageindex = max(1, intval($_GPC['page']));
    $pagesize=10;
    $type=isset($_GPC['type'])?$_GPC['type']:'all';
    $sql="select a.*,b.md_name from ".tablename("wpdc_ydorder"). " a"  . " left join " . tablename("wpdc_store") . " b on a.store_id=b.id where a.uniacid=:uniacid";
if($type=='all'){
    if(checksubmit('submit2')){
        $sql.=" and a.time2 >= :time2 and a.time2 <= :time22";
$data=array(':time2'=>$start,':time22'=>$end,':uniacid'=>$_W['uniacid']);
$total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wpdc_ydorder')." WHERE time2 >= :time2 and time2 <= :time22 and uniacid=:uniacid  ORDER BY time2 DESC",$data);
}elseif(checksubmit('submit')){
    $sql.="  and (a.link_name LIKE :name|| a.order_num LIKE :order_num)   ";
    $data=array(':name' => $where,':order_num' => $where,':uniacid'=>$_W['uniacid']);
    $total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wpdc_ydorder')." WHERE (link_name LIKE :name || order_num LIKE :order_num)  and uniacid=:uniacid  ORDER BY time2 DESC",$data);
}else{
    $sql=$sql;
    $data=array(':uniacid'=>$_W['uniacid']);
    $total=pdo_fetchcolumn("select count(*) from " .tablename('wpdc_ydorder')."where uniacid=:uniacid  ORDER BY time2 DESC",$data);
    //$total=pdo_fetchcolumn($sql,$data);
}       
}elseif($type=='wait'){
        $sql.=" and a.state=1";
        $data=array(':uniacid'=>$_W['uniacid']);
        $total=pdo_fetchcolumn("select count(*) from " .tablename('wpdc_ydorder')."where uniacid=:uniacid  and state=1 ORDER BY time2 DESC",$data);
}elseif($type=='complete'){
    $sql.=" and a.state=2";
    $data=array(':uniacid'=>$_W['uniacid']);
    $total=pdo_fetchcolumn("select count(*) from " .tablename('wpdc_ydorder')."where  uniacid=:uniacid and state=2 ",$data);
}elseif($type=='reject'){
    $sql.=" and a.state=3";
    $data=array(':uniacid'=>$_W['uniacid']);
    $total=pdo_fetchcolumn("select count(*) from " .tablename('wpdc_ydorder')."where uniacid=:uniacid  and state=3",$data);
}elseif($type=='cancel'){
    $sql.=" and a.state=4";
    $data=array(':uniacid'=>$_W['uniacid']);
    $total=pdo_fetchcolumn("select count(*) from " .tablename('wpdc_ydorder')."where uniacid=:uniacid  and state=4",$data);
}
        $select_sql =$sql." order by a.time2  desc LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
        $list=pdo_fetchall($select_sql,$data);
        $pager = pagination($total, $pageindex, $pagesize);
        if($_GPC['op']=='ok'){
            $data2['state']=2;
            // $data2['completion_time']=time();
        $rst=pdo_update('wpdc_ydorder',$data2,array('id'=>$_GPC['id']));
        if($rst){


            

$system=pdo_get('wpdc_system',array('uniacid'=>$_W['uniacid']));


/////////////////分销/////////////////

        $set=pdo_get('wpdc_fxset',array('uniacid'=>$_W['uniacid']));
        $order=pdo_get('wpdc_ydorder',array('id'=>$_GPC['id']));
        $store=pdo_get('wpdc_store',array('id'=>$order['seller']));
        $type=pdo_get('wpdc_storetype',array('id'=>$store['md_type']));
        if($set['is_open']==1){//开启分销
          if($set['is_type']==1){//开启分类分销
              if($set['is_ej']==2){//不开启二级分销
               $user=pdo_get('wpdc_fxuser',array('fx_user'=>$order['user_id']));
               if($user){
                    $userid=$user['user_id'];//上线id
                    $money=$order['money']*($type['commission']/100);//一级佣金
                    pdo_update('wpdc_user',array('commission +='=>$money),array('id'=>$userid));
                    $data6['user_id']=$userid;//上线id
                    $data6['son_id']=$order['user_id'];//下线id
                    $data6['money']=$money;//金额
                    $data6['time']=time();//时间
                    $data6['uniacid']=$_W['uniacid'];
                    pdo_insert('wpdc_earnings',$data6);
                  }
      }else{//开启二级
          $user=pdo_get('wpdc_fxuser',array('fx_user'=>$order['user_id']));
          $user2=pdo_get('wpdc_fxuser',array('fx_user'=>$user['user_id']));//上线的上线
          if($user){
            $userid=$user['user_id'];//上线id
            $money=$order['money']*($type['commission']/100);//一级佣金
            pdo_update('wpdc_user',array('commission +='=>$money),array('id'=>$userid));
            $data6['user_id']=$userid;//上线id
            $data6['son_id']=$order['user_id'];//下线id
            $data6['money']=$money;//金额
            $data6['time']=time();//时间
            $data6['uniacid']=$_W['uniacid'];
            pdo_insert('wpdc_earnings',$data6);
          }
          if($user2){
            $userid2=$user2['user_id'];//上线的上线id
            $money=$order['money']*($type['commission2']/100);//二级佣金
            pdo_update('wpdc_user',array('commission +='=>$money),array('id'=>$userid2));
            $data7['user_id']=$userid2;//上线id
            $data7['son_id']=$order['user_id'];//下线id
            $data7['money']=$money;//金额
            $data7['time']=time();//时间
            $data7['uniacid']=$_W['uniacid'];
            pdo_insert('wpdc_earnings',$data7);
          }
        }
          }else{
            if($set['is_ej']==2){//不开启二级分销
           $user=pdo_get('wpdc_fxuser',array('fx_user'=>$order['user_id']));
           if($user){
            $userid=$user['user_id'];//上线id
            $money=$order['money']*($set['commission']/100);//一级佣金
            pdo_update('wpdc_user',array('commission +='=>$money),array('id'=>$userid));
            $data6['user_id']=$userid;//上线id
            $data6['son_id']=$order['user_id'];//下线id
            $data6['money']=$money;//金额
            $data6['time']=time();//时间
            $data6['uniacid']=$_W['uniacid'];
            pdo_insert('wpdc_earnings',$data6);
          }
      }else{//开启二级
       $user=pdo_get('wpdc_fxuser',array('fx_user'=>$order['user_id']));
          $user2=pdo_get('wpdc_fxuser',array('fx_user'=>$user['user_id']));//上线的上线
          if($user){
            $userid=$user['user_id'];//上线id
            $money=$order['money']*($set['commission']/100);//一级佣金
            pdo_update('wpdc_user',array('commission +='=>$money),array('id'=>$userid));
            $data6['user_id']=$userid;//上线id
            $data6['son_id']=$order['user_id'];//下线id
            $data6['money']=$money;//金额
            $data6['time']=time();//时间
            $data6['uniacid']=$_W['uniacid'];
            pdo_insert('wpdc_earnings',$data6);
          }
          if($user2){
            $userid2=$user2['user_id'];//上线的上线id
            $money=$order['money']*($set['commission2']/100);//二级佣金
            pdo_update('wpdc_user',array('commission +='=>$money),array('id'=>$userid2));
            $data7['user_id']=$userid2;//上线id
            $data7['son_id']=$order['user_id'];//下线id
            $data7['money']=$money;//金额
            $data7['time']=time();//时间
            $data7['uniacid']=$_W['uniacid'];
            pdo_insert('wpdc_earnings',$data7);
          }
        }
          }
        }
      
/////////////////分销/////////////////

//////////积分/////////
        
 if($order['money'] and $system['integral2']>0 and $system['is_jf']==1){
    if($store['is_yyjf']==1){
       $jifen=round(($system['integral2']/100)*$order['money']);
          pdo_update('wpdc_user',array('total_score +='=>$jifen),array('id'=>$order['user_id']));
          $data5['score']=$jifen;
          $data5['user_id']=$order['user_id'];
          $data5['note']='预约消费';
          $data5['type']=1;
          $data5['cerated_time']=date('Y-m-d H:i:s');
          $data5['uniacid']=$_W['uniacid'];//小程序id
          pdo_insert('wpdc_integral',$data5);  
    }
          
        }

////////////积分//////////

            message('确认成功',$this->createWebUrl('ydorder',array()),'success');
        }else{
            message('确认失败','','error');
        }
        }
if($_GPC['op']=='delete'){
    $res=pdo_delete('wpdc_ydorder',array('id'=>$_GPC['id']));
    if($res){
         message('删除成功！', $this->createWebUrl('ydorder'), 'success');
        }else{
              message('删除失败！','','error');
        }
}

if($_GPC['op']=='tg'){
        $id=$_GPC['id'];
        include_once IA_ROOT . '/addons/zh_dianc/cert/WxPay.Api.php';
        load()->model('account');
        load()->func('communication');
        $WxPayApi = new WxPayApi();
        $input = new WxPayRefund();
       //$path_cert = IA_ROOT . '/addons/zh_dianc/cert/apiclient_cert.pem';
       // $path_key = IA_ROOT . '/addons/zh_dianc/cert/apiclient_key.pem';
        $path_cert = IA_ROOT . "/addons/zh_dianc/cert/".'apiclient_cert_' . $_W['uniacid'] . '.pem';
        $path_key = IA_ROOT . "/addons/zh_dianc/cert/".'apiclient_key_' . $_W['uniacid'] . '.pem';
        $account_info = $_W['account'];
        $refund_order =pdo_get('wpdc_ydorder',array('id'=>$id));  
        $res=pdo_get('wpdc_system',array('uniacid'=>$_W['uniacid']));
        $appid=$res['appid'];
        $key=$res['wxkey'];
        $mchid=$res['mchid']; 
        $out_trade_no=$refund_order['ydcode'];//商户订单号
        $fee = $refund_order['pay_money'] * 100;
            //$refundid = $refund_order['transid'];
            //$refundid='4200000022201710178579320894';
            $input->SetAppid($appid);
            $input->SetMch_id($mchid);
            $input->SetOp_user_id($mchid);
            $input->SetRefund_fee($fee);
            $input->SetTotal_fee($fee);
           // $input->SetTransaction_id($refundid);
            $input->SetOut_refund_no($id);
         
           $input->SetOut_trade_no($out_trade_no);
       
            $result = $WxPayApi->refund($input, 6, $path_cert, $path_key, $key);
           
           //var_dump($result);die;
            if ($result['result_code'] == 'SUCCESS') {//退款成功
           pdo_update('wpdc_ydorder',array('state'=>6),array('id'=>$id));
           message('退款成功',$this->createWebUrl('ydorder',array()),'success');
         
    }else{
        message('退款失败','','error');
}
}

if($_GPC['op']=='jj'){
    $res=pdo_update('wpdc_ydorder',array('state'=>7),array('id'=>$_GPC['id']));
    if($res){
        message('拒绝退款成功',$this->createWebUrl('ydorder',array()),'success');
    }else{
 message('拒绝退款失败','','error');
    }
}
include $this->template('web/ydorder');