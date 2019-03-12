<?php
global $_GPC, $_W;
$system=pdo_get('wpdc_system',array('uniacid'=>$_W['uniacid']));

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
    if(checksubmit('submit2')){
        $sql="SELECT a.*,b.md_name,c.name FROM ".tablename('wpdc_dmorder'). " a"  . " left join " . tablename("wpdc_store") . " b on a.store_id=b.id " . " left join " . tablename("wpdc_user") . " c on c.id=a.user_id WHERE a.time2 >= :time2 and a.time2 <= :time22 and a.uniacid=:uniacid  ORDER BY a.time2 DESC";
        $data=array(':time2'=>$start,':time22'=>$end,':uniacid'=>$_W['uniacid']);
        $total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wpdc_dmorder'). "  WHERE time2 >= :time2 and time2 <= :time22 and uniacid=:uniacid  ORDER BY time2 DESC",$data);
    }elseif(checksubmit('submit')){
        $sql="SELECT a.*,b.md_name,c.name FROM ".tablename('wpdc_dmorder'). " a"  . " left join " . tablename("wpdc_store") . " b on a.store_id=b.id " . " left join " . tablename("wpdc_user") . " c on c.id=a.user_id WHERE b.md_name LIKE :md_name || c.name LIKE :name  and a.uniacid=:uniacid  ORDER BY a.time2 DESC";
        $data=array(':name' => $where,':md_name' => $where,':uniacid'=>$_W['uniacid']);
        $total=pdo_fetchcolumn("SELECT a.*,b.md_name,c.name FROM ".tablename('wpdc_dmorder'). " a"  . " left join " . tablename("wpdc_store") . " b on a.store_id=b.id " . " left join " . tablename("wpdc_user") . " c on c.id=a.user_id WHERE b.md_name LIKE :md_name || c.name LIKE :name  and a.uniacid=:uniacid  ORDER BY a.time2 DESC",$data);
    }else{
        $sql="SELECT a.*,b.md_name,c.name FROM".tablename('wpdc_dmorder'). " a"  . " left join " . tablename("wpdc_store") . " b on a.store_id=b.id " . " left join " . tablename("wpdc_user") . " c on c.id=a.user_id where a.uniacid=:uniacid  ORDER BY a.time2 DESC";
        $data=array(':uniacid'=>$_W['uniacid']);
        $total=pdo_fetchcolumn("select count(*) from " .tablename('wpdc_dmorder')."where uniacid=:uniacid  ORDER BY time2 DESC",$data);
    }

$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list=pdo_fetchall($select_sql,$data);
$pager = pagination($total, $pageindex, $pagesize);





include $this->template('web/dmorder');