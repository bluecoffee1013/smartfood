<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$sql="select a.* ,b.name,b.img as  user_img from " . tablename("wpdc_jfrecord") . " a"  . " left join " . tablename("wpdc_user") . " b on b.id=a.user_id WHERE a.good_id =".$_GPC['good_id']." order by id DESC";
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list = pdo_fetchall($select_sql);	   
$total=pdo_fetchcolumn("select count(*) from " . tablename("wpdc_jfrecord") . " a"  . " left join " . tablename("wpdc_user") . " b on b.id=a.user_id WHERE a.good_id =".$_GPC['good_id']."");
$pager = pagination($total, $pageindex, $pagesize);

include $this->template('web/receivelist');