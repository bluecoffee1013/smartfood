<?php
global $_GPC, $_W;
$system=pdo_get('wpdc_system',array('uniacid'=>$_W['uniacid']));
$time=time()-($system['day']*24*60*60);
pdo_update('wpdc_order',array('state'=>4),array('state'=>3,'time <='=>$time));
$GLOBALS['frames'] = $this->getMainMenu();
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
if($type=='all'){
    if(checksubmit('submit2')){
        $sql="SELECT a.*,b.md_name FROM ".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_store") . " b on a.seller_id=b.id WHERE a.time2 >= :time2 and a.time2 <= :time22 and a.uniacid=:uniacid and a.type=1 ORDER BY a.time2 DESC";
        $data=array(':time2'=>$start,':time22'=>$end,':uniacid'=>$_W['uniacid']);
        $total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wpdc_order'). "  WHERE time2 >= :time2 and time2 <= :time22 and uniacid=:uniacid and type=1 ORDER BY time2 DESC",$data);
    }elseif(checksubmit('submit')){
        $sql="SELECT a.*,b.md_name FROM ".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_store") . " b on a.seller_id=b.id WHERE (a.name LIKE :name || a.
        order_num LIKE :order_num)  and a.uniacid=:uniacid and type=1 ORDER BY time2 DESC";
        $data=array(':name' => $where,':order_num' => $where,':uniacid'=>$_W['uniacid']);
        $total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wpdc_order')." WHERE (name LIKE :name || order_num LIKE :order_num)  and uniacid=:uniacid and type=1 ORDER BY time2 DESC",$data);
    }else{
        $sql="SELECT a.*,b.md_name FROM".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_store") . " b on a.seller_id=b.id where a.uniacid=:uniacid and a.type=1 ORDER BY a.time2 DESC";
        $data=array(':uniacid'=>$_W['uniacid']);
        $total=pdo_fetchcolumn("select count(*) from " .tablename('wpdc_order')."where uniacid=:uniacid and type=1 ORDER BY time2 DESC",$data);
    }
}elseif($type=='wait'){
    $sql="select a.*,b.md_name from".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_store") . " b on a.seller_id=b.id where a.state=1 and a.uniacid=:uniacid and a.type=1 ORDER BY a.time2 DESC";
    $data=array(':uniacid'=>$_W['uniacid']);
    $total=pdo_fetchcolumn("select count(*) from " .tablename('wpdc_order')."where state=1 and uniacid=:uniacid and type=1 ORDER BY time2 DESC",$data);
}elseif($type=='now'){
    $sql="select a.*,b.md_name from".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_store") . " b on a.seller_id=b.id where a.state=2 and a.uniacid=:uniacid and a.type=1 ORDER BY a.time2 DESC";
    $data=array(':uniacid'=>$_W['uniacid']);
    $total=pdo_fetchcolumn("select count(*) from " .tablename('wpdc_order')."where state=2 and uniacid=:uniacid and type=1 ORDER BY time2 DESC",$data);
}elseif($type=='cancel'){
    $sql="select a.*,b.md_name from".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_store") . " b on a.seller_id=b.id where a.state=5 and a.uniacid=:uniacid and a.type=1 ORDER BY a.time2 DESC";
    $data=array(':uniacid'=>$_W['uniacid']);
    $total=pdo_fetchcolumn("select count(*) from " .tablename('wpdc_order')."where state=5 and uniacid=:uniacid and type=1 ORDER BY time2 DESC",$data);
}elseif($type=='complete'){
    $sql="select a.*,b.md_name from".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_store") . " b on a.seller_id=b.id where a.state=4 and a.uniacid=:uniacid and a.type=1 ORDER BY a.time2 DESC";
    $data=array(':uniacid'=>$_W['uniacid']);
    $total=pdo_fetchcolumn("select count(*) from " .tablename('wpdc_order')."where state=4 and uniacid=:uniacid and type=1 ORDER BY time2 DESC",$data);
}elseif($type=='delivery'){
    $sql="select a.*,b.md_name from".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_store") . " b on a.seller_id=b.id where a.state=3 and a.uniacid=:uniacid and a.type=1 ORDER BY a.time2 DESC";
    $data=array(':uniacid'=>$_W['uniacid']);
    $total=pdo_fetchcolumn("select count(*) from " .tablename('wpdc_order')."where state=3 and uniacid=:uniacid and type=1 ORDER BY time2 DESC",$data);
}
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;

$list=pdo_fetchall($select_sql,$data);

$pager = pagination($total, $pageindex, $pagesize);
if($_GPC['op']=='jd'){
    $data2['state']=3;
    $res=pdo_update('wpdc_order',$data2,array('id'=>$_GPC['id']));
    if($res){
        message('接单成功！', $this->createWebUrl('order'), 'success');
    }else{
        message('接单失败！','','error');
    }

}



if(checksubmit('export_submit', true)) {
    $time=date("Y-m-d");
    $time="'%$time%'";
        $count = pdo_fetchcolumn("SELECT COUNT(*) FROM". tablename("wpdc_order")." WHERE type=1 and time LIKE ".$time);
        $pagesize = ceil($count/5000);
        //array_unshift( $names,  '活动名称'); 

        $header = array(
            'item'=>'序号',
            'md_name' => '门店名称',
           'order_num' => '订单号', 
           'name' => '联系人', 
           'tel' => '联系电话',
           'address' => '联系地址',
           'time' => '下单时间',
           'money' => '金额',
           'state' => '外卖状态',
           // 'dn_state' => '店内状态',
           'goods' => '商品'

        );

        
        
        $keys = array_keys($header);
        $html = "\xEF\xBB\xBF";
        foreach ($header as $li) {
            $html .= $li . "\t ,";
        }
        $html .= "\n";
        for ($j = 1; $j <= $pagesize; $j++) {
            $sql = "select a.*,b.md_name from " . tablename("wpdc_order")."  a"  . " inner join " . tablename("wpdc_store")." b on a.seller_id=b.id  WHERE a.type=1 and a.time LIKE ".$time."  limit " . ($j - 1) * 5000 . ",5000 ";
            $list = pdo_fetchall($sql);
   
            

        }
            if (!empty($list)) {
                $size = ceil(count($list) / 500);
                for ($i = 0; $i < $size; $i++) {
                    $buffer = array_slice($list, $i * 500, 500);
                    $user = array();
                    foreach ($buffer as $k =>$row) {
                        $row['item']= $k+1;
                        if($row['state']==0){
                            $row['state']='无状态';
                        }elseif($row['state']==1){
                            $row['state']='待付款';
                        }elseif($row['state']==2){
                            $row['state']='等待接单';
                        }elseif($row['state']==3){
                            $row['state']='等待送达';
                        }elseif($row['state']==4){
                            $row['state']='完成';
                        }elseif($row['state']==5){
                            $row['state']='取消订单';
                        }elseif($row['state']==6){
                            $row['state']='评论完成';
                        }elseif($row['state']==7){
                            $row['state']='待退款';
                        }elseif($row['state']==8){
                            $row['state']='退款成功';
                        }elseif($row['state']==9){
                            $row['state']='退款失败';
                        }

                        // if($row['dn_state']==0){
                        //     $row['dn_state']='无状态';
                        // }elseif($row['dn_state']==1){
                        //     $row['dn_state']='待付款';
                        // }elseif($row['dn_state']==2){
                        //     $row['dn_state']='已完成';
                        // }elseif($row['dn_state']==3){
                        //     $row['dn_state']='关闭订单';
                        // }elseif($row['dn_state']==4){
                        //     $row['dn_state']='已评论';
                        // }
                        $good=pdo_getall('wpdc_goods',array('order_id'=>$row['id']));
                        for($i=0;$i<count($good);$i++){
                            $date6='';
                            $date6 .=$good[$i]['name'].'*'.$good[$i]['number']."  ";
                        }
                        $row['goods']=$date6;
                        foreach ($keys as $key) {
                            $data5[] = $row[$key];
                        }
                        $user[] = implode("\t ,", $data5) . "\t ,";
                        unset($data5);
                    }
                    $html .= implode("\n", $user) . "\n";
                }
            }
        
        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=今日外卖订单数据.csv");
        echo $html;
        exit();
    }



    if($_GPC['op']=='delete'){
    $res=pdo_delete('wpdc_order',array('id'=>$_GPC['id']));
    if($res){
         message('删除成功！', $this->createWebUrl('order'), 'success');
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
        $refund_order =pdo_get('wpdc_order',array('id'=>$id));  
        $res=pdo_get('wpdc_system',array('uniacid'=>$_W['uniacid']));
        $appid=$res['appid'];
        $key=$res['wxkey'];
        $mchid=$res['mchid']; 
        //print_r( $refund_order );die;
        $out_trade_no=$refund_order['sh_ordernum'];//商户订单号
        $fee = $refund_order['money'] * 100;
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
           pdo_update('wpdc_order',array('state'=>8),array('id'=>$id));
           message('退款成功',$this->createWebUrl('order',array()),'success');
         
    }else{
        message($result['err_code_des'],'','error');
}
}

if($_GPC['op']=='jj'){
    $res=pdo_update('wpdc_order',array('state'=>9),array('id'=>$_GPC['id']));
    if($res){
        message('拒绝退款成功',$this->createWebUrl('order',array()),'success');
    }else{
 message('拒绝退款失败','','error');
    }
}
include $this->template('web/order');