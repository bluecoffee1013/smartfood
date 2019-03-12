<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu2();
if($_GPC['id']){
setcookie("storeid",$_GPC['id']);
$cur_store = $this->getStoreById($_GPC['id']);	
$storeid=$_GPC['id'];

}else{
$storeid=$_COOKIE["storeid"];
$cur_store = $this->getStoreById($storeid);	
}
$time=date("Y-m-d");
$time2=date("Y-m-d",strtotime("-1 day"));
$time3=strtotime(date("Y-m-d",strtotime("-6 day")));
$time="'%$time%'";
$time2="'%$time2%'";
$sql = "select * from " . tablename("wpdc_order")." WHERE time LIKE ".$time." and seller_id=".$storeid;
$res = pdo_fetchall($sql);
$sql2 = "select * from " . tablename("wpdc_ydorder")." WHERE created_time LIKE ".$time." and store_id=".$storeid;
$res2 = pdo_fetchall($sql2);
$ordernum=(count($res)+count($res2));//今日订单统计



$wm = "select sum(money) as total from " . tablename("wpdc_order")." WHERE time LIKE ".$time." and seller_id=".$storeid." and state not in (5,1,8) and type=1 and pay_time !=''";
$wm = pdo_fetch($wm);//今天的外卖销售额
$dn = "select sum(money) as total from " . tablename("wpdc_order")." WHERE time LIKE ".$time." and seller_id=".$storeid." and dn_state not in (3,1) and type=2 and pay_time !=''";
$dn = pdo_fetch($dn);//今天的店内销售额
$yd = "select sum(pay_money) as total from " . tablename("wpdc_ydorder")." WHERE created_time LIKE ".$time." and store_id=".$storeid." and state!=6";
$yd = pdo_fetch($yd);//今天的预定销售额
$dmf = "select sum(money) as total from " . tablename("wpdc_dmorder")." WHERE time LIKE ".$time." and store_id=".$storeid;
$dmf = pdo_fetch($dmf);//今天的当面付销售额
$total = $wm['total']+$dn['total']+$yd['total']+$dmf['total'];//今天的销售额



$ztwm = "select sum(money) as total from " . tablename("wpdc_order")." WHERE time LIKE ".$time2." and seller_id=".$storeid." and state not in (5,1,8) and type=1 and pay_time !=''";
$ztwm = pdo_fetch($ztwm);//昨天的外卖销售额
$ztdn = "select sum(money) as total from " . tablename("wpdc_order")." WHERE time LIKE ".$time2." and seller_id=".$storeid." and dn_state not in (3,1) and type=2 and pay_time !=''";
$ztdn = pdo_fetch($ztdn);//昨天的店内销售额
$ztyd = "select sum(pay_money) as total from " . tablename("wpdc_ydorder")." WHERE created_time LIKE ".$time2." and store_id=".$storeid." and state!=6";
$ztyd = pdo_fetch($ztyd);//昨天的预定销售额
$ztdmf = "select sum(money) as total from " . tablename("wpdc_dmorder")." WHERE time LIKE ".$time2." and store_id=".$storeid;
$ztdmf = pdo_fetch($ztdmf);//昨天的当面付销售额
$total2 = $ztwm['total']+$ztdn['total']+$ztyd['total']+$ztdmf['total'];//昨天的销售额



$qtwm = "select sum(money) as total from " . tablename("wpdc_order")." WHERE time2 >= ".$time3." and seller_id=".$storeid." and state not in (5,1,8) and type=1 and pay_time !=''";
$qtwm = pdo_fetch($qtwm);//七天的外卖销售额
$qtdn = "select sum(money) as total from " . tablename("wpdc_order")." WHERE time2 >= ".$time3." and seller_id=".$storeid." and dn_state not in (3,1) and type=2 and pay_time !=''";
$qtdn = pdo_fetch($qtdn);//七天的店内销售额
$qtyd = "select sum(pay_money) as total from " . tablename("wpdc_ydorder")." WHERE created_time >= ".$time3." and store_id=".$storeid." and state!=6";
$qtyd = pdo_fetch($qtyd);//七天的预定销售额
$qtdmf = "select sum(money) as total from " . tablename("wpdc_dmorder")." WHERE time2 >= ".$time3." and store_id=".$storeid;
$qtdmf = pdo_fetch($qtdmf);//七天的当面付销售额
$total3 = $qtwm['total']+$qtdn['total']+$qtyd['total']+$qtdmf['total'];//七天的销售额



$sql6=count(pdo_getall('wpdc_order',array('seller_id'=>$storeid,'state'=>2)));//待接单的外卖
$sql7=count(pdo_getall('wpdc_order',array('seller_id'=>$storeid,'state'=>3)));//待送达的外卖
$sql8=count(pdo_getall('wpdc_order',array('seller_id'=>$storeid,'dn_state'=>1)));//待付款的店内
$sql9=count(pdo_getall('wpdc_ydorder',array('store_id'=>$storeid,'state'=>1)));//待审核的预约订单
$sql10=count(pdo_getall('wpdc_order',array('seller_id'=>$storeid,'state'=>4)));//完成的外卖订单

$sql11=count(pdo_getall('wpdc_table',array('store_id'=>$storeid,'status !='=>0)));//已开台的桌子
$sql12=count(pdo_getall('wpdc_table',array('store_id'=>$storeid,'status'=>0)));//未开台的桌子

$sql13=count(pdo_getall('wpdc_dishes',array('store_id'=>$storeid,'dishes_type'=>array(1,3),'is_shelves'=>1 )));//外卖已上架的菜品
$sql14=count(pdo_getall('wpdc_dishes',array('store_id'=>$storeid,'dishes_type'=>array(1,3),'is_shelves'=>2 )));//外卖未上架的菜品


$sql15=count(pdo_getall('wpdc_dishes',array('store_id'=>$storeid,'dishes_type'=>array(2,3),'is_shelves'=>1 )));//店内已上架的菜品
$sql16=count(pdo_getall('wpdc_dishes',array('store_id'=>$storeid,'dishes_type'=>array(2,3),'is_shelves'=>2 )));//店内未上架的菜品
include $this->template('web/index');