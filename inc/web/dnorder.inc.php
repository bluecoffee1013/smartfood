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
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
if(checksubmit('submit2')){
    $start=strtotime($_GPC['time']['start']);
    $end=strtotime($_GPC['time']['end']);
}
$type=isset($_GPC['type'])?$_GPC['type']:'all';
if($type=='all'){
    if(checksubmit('submit2')){
        $sql="SELECT a.*,b.name,b.status as t_status,c.name as tablename,d.md_name  FROM ".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_table") . " b on a.table_id=b.id  left join " . tablename("wpdc_table_type") ." c on b.type_id=c.id left join " . tablename("wpdc_store") ." d on a.seller_id=d.id WHERE a.time2 >= :time2 and a.time2 <= :time22 and a.uniacid=:uniacid and a.type=2 ORDER BY a.time2 DESC";
        $data=array(':time2'=>$start,':time22'=>$end,':uniacid'=>$_W['uniacid']);
        $total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_table") . " b on a.table_id=b.id  WHERE a.time2 >= :time2 and a.time2 <= :time22 and a.uniacid=:uniacid and a.type=2 ORDER BY a.time2 DESC",$data);
    }elseif(checksubmit('submit')){
        $sql="SELECT a.*,b.name,b.status as t_status,c.name as tablename,d.md_name  FROM ".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_table") . " b on a.table_id=b.id  left join " . tablename("wpdc_table_type") ." c on b.type_id=c.id left join " . tablename("wpdc_store") ." d on a.seller_id=d.id WHERE a. order_num LIKE :order_num  and a.uniacid=:uniacid and a.type=2 ORDER BY a.time2 DESC";
        $data=array(':order_num' => $where,':uniacid'=>$_W['uniacid']);
        $total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_table") . " b on a.table_id=b.id  WHERE a. order_num LIKE :order_num  and a.uniacid=:uniacid and a.type=2 ORDER BY a.time2 DESC",$data);
    }else{
        $sql="SELECT a.*,b.name ,b.status as t_status,c.name as tablename,d.md_name FROM ".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_table") . " b on a.table_id=b.id left join " . tablename("wpdc_table_type") ." c on b.type_id=c.id left join " . tablename("wpdc_store") ." d on a.seller_id=d.id  WHERE  a.uniacid=:uniacid and a.type=2 ORDER BY a.time2 DESC";
        $data=array(':uniacid'=>$_W['uniacid']);
        $total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_table") . " b on a.table_id=b.id  WHERE  a.uniacid=:uniacid and a.type=2 ORDER BY a.time2 DESC",$data);
    }
}elseif($type=='wait'){
    $sql="SELECT a.*,b.name,b.status as t_status,c.name as tablename,d.md_name FROM ".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_table") . " b on a.table_id=b.id left join " . tablename("wpdc_table_type") ." c on b.type_id=c.id  left join " . tablename("wpdc_store") ." d on a.seller_id=d.id WHERE  a.uniacid=:uniacid and a.type=2  and dn_state=1 ORDER BY a.time2 DESC";
    $data=array(':uniacid'=>$_W['uniacid']);
    $total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_table") . " b on a.table_id=b.id  WHERE  a.uniacid=:uniacid and a.type=2  and dn_state=1 ORDER BY a.time2 DESC",$data);
}elseif($type=='complete'){
    $sql="SELECT a.*,b.name,b.status as t_status,c.name as tablename,d.md_name FROM ".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_table") . " b on a.table_id=b.id  left join " . tablename("wpdc_table_type") ." c on b.type_id=c.id left join " . tablename("wpdc_store") ." d on a.seller_id=d.id  WHERE  a.uniacid=:uniacid and a.type=2  and dn_state=2 ORDER BY a.time2 DESC";
    $data=array(':uniacid'=>$_W['uniacid']);
    $total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_table") . " b on a.table_id=b.id  WHERE  a.uniacid=:uniacid and a.type=2  and dn_state=2 ORDER BY a.time2 DESC",$data);
}elseif($type=='close'){
    $sql="SELECT a.*,b.name,b.status as t_status,c.name as tablename,d.md_name FROM ".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_table") . " b on a.table_id=b.id  left join " . tablename("wpdc_table_type") ." c on b.type_id=c.id left join " . tablename("wpdc_store") ." d on a.seller_id=d.id  WHERE  a.uniacid=:uniacid and a.type=2  and dn_state=3 ORDER BY a.time2 DESC";
    $data=array(':uniacid'=>$_W['uniacid']);
    $total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wpdc_order'). " a"  . " left join " . tablename("wpdc_table") . " b on a.table_id=b.id  WHERE  a.uniacid=:uniacid and a.type=2  and dn_state=3 ORDER BY a.time2 DESC",$data);
}
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list=pdo_fetchall($select_sql,$data);
//var_dump($list);die;
$pager = pagination($total, $pageindex, $pagesize);
if($_GPC['op']=='receivables'){
    $id=$_GPC['id'];
    $data2['dn_state']=2;
    $result = pdo_update('wpdc_order',$data2, array('id'=>$id));
    if($result){
        message('确认成功',$this->createWebUrl('dnorder',array()),'success');
    }else{
        message('确认失败','','error');

    }
}elseif($_GPC['op']=='close'){
    $id=$_GPC['id'];
    $table_id=$_GPC['table_id'];
    $data2['dn_state']=3;
    $result = pdo_update('wpdc_order',$data2, array('id'=>$id));
    pdo_update('wpdc_table',array('status'=>0), array('id'=>$table_id));
    if($result){
        message('关闭成功',$this->createWebUrl('dnorder',array()),'success');
    }else{
        message('关闭失败','','error');

    }
}elseif($_GPC['op']=='open'){
    $table_id=$_GPC['id'];
    $data2['status']=0;
    $result = pdo_update('wpdc_table',$data2, array('id'=>$table_id));
    if($result){
        message('重新开台成功',$this->createWebUrl('dnorder',array()),'success');
    }else{
        message('重新开台失败','','error');
    }
}

if($_GPC['op']=='delete'){
    $res=pdo_delete('wpdc_order',array('id'=>$_GPC['id']));
 if($res){
 message('删除成功！', $this->createWebUrl('dnorder'), 'success');
}else{
      message('删除失败！','','error');
}
}

if(checksubmit('export_submit', true)) {
    $time=date("Y-m-d");
    $time="'%$time%'";
        $count = pdo_fetchcolumn("SELECT COUNT(*) FROM". tablename("wpdc_order")." WHERE time LIKE ".$time."and type=2");
        $pagesize = ceil($count/5000);
        //array_unshift( $names,  '活动名称'); 
        $header = array(
            'item'=>'序号',
            'md_name' => '门店名称',
           'order_num' => '订单号', 
           // 'name' => '联系人', 
           // 'tel' => '联系电话',
           // 'address' => '联系地址',
           'time' => '下单时间',
           'money' => '金额',
           'table_name' => '桌号',
           // 'state' => '外卖状态',
         'dn_state' => '店内状态',
           'goods' => '商品'

        );

        $keys = array_keys($header);
        $html = "\xEF\xBB\xBF";
        foreach ($header as $li) {
            $html .= $li . "\t ,";
        }
        $html .= "\n";
        for ($j = 1; $j <= $pagesize; $j++) {
            $sql = "select a.*,b.md_name,c.name as table_name from " . tablename("wpdc_order")."  a"  . " left join " . tablename("wpdc_store")." b on a.seller_id=b.id " . " left join " . tablename("wpdc_table") . " c on c.id=a.table_id  WHERE a.time LIKE ".$time." and a.type=2  limit " . ($j - 1) * 5000 . ",5000 ";
            $list = pdo_fetchall($sql);
   
            

        }
            if (!empty($list)) {
                $size = ceil(count($list) / 500);
                for ($i = 0; $i < $size; $i++) {
                    $buffer = array_slice($list, $i * 500, 500);
                    $user = array();
                    foreach ($buffer as $k =>$row) {
                        $row['item']= $k+1;
                        // if($row['state']==0){
                        //     $row['state']='无状态';
                        // }elseif($row['state']==1){
                        //     $row['state']='待付款';
                        // }elseif($row['state']==2){
                        //     $row['state']='等待接单';
                        // }elseif($row['state']==3){
                        //     $row['state']='等待送达';
                        // }elseif($row['state']==4){
                        //     $row['state']='完成';
                        // }elseif($row['state']==5){
                        //     $row['state']='取消订单';
                        // }elseif($row['state']==6){
                        //     $row['state']='评论完成';
                        // }
                        if($row['dn_state']==0){
                            $row['dn_state']='无状态';
                        }elseif($row['dn_state']==1){
                            $row['dn_state']='待付款';
                        }elseif($row['dn_state']==2){
                            $row['dn_state']='已完成';
                        }elseif($row['dn_state']==3){
                            $row['dn_state']='关闭订单';
                        }elseif($row['dn_state']==4){
                            $row['dn_state']='已评论';
                        }
                        $good=pdo_getall('wpdc_goods',array('order_id'=>$row['id']));
                        $date6='';
                        for($i=0;$i<count($good);$i++){
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
        header("Content-Disposition:attachment; filename=今日店内订单数据.csv");
        echo $html;
        exit();
    }
include $this->template('web/dnorder');