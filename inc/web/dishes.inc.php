<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu2();
$storeid=$_COOKIE["storeid"];
$cur_store = $this->getStoreById($storeid);
$where=" WHERE a.uniacid=:uniacid and a.store_id=:store_id";
$data[':uniacid']=$_W['uniacid'];
$data[':store_id']=$storeid;
if(checksubmit('submit')){
	//echo $_GPC['area'];die;
    if($_GPC['keywords']){
    	$where .=" and a.name LIKE :name ";
    	 $op=$_GPC['keywords'];
          $data[':name']="%$op%";
    	
    }
    if($_GPC['dishes_type']){
    	$where .=" and a.dishes_type=:bid";
    	$data[':bid']=$_GPC['dishes_type'];
    }
    if($_GPC['is_shelves']){
    	$where .=" and a.is_shelves=:cid";
    	$data[':cid']=$_GPC['is_shelves'];
    }
}
$pageindex = max(1, intval($_GPC['page']));
$pagesize=15;
$sql="select a.* ,b.type_name from " . tablename("wpdc_dishes") . " a"  . " left join " . tablename("wpdc_type") . " b on b.id=a.type_id".$where;
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list = pdo_fetchall($select_sql,$data);	   
$total=pdo_fetchcolumn("select count(*) from " . tablename("wpdc_dishes") . " a"  . " left join " . tablename("wpdc_type") . " b on b.id=a.type_id".$where,$data);
$pager = pagination($total, $pageindex, $pagesize);
if($_GPC['id']){
	$data2['is_shelves']=$_GPC['is_shelves'];
	$res=pdo_update('wpdc_dishes',$data2,array('id'=>$_GPC['id']));
	if($res){
		message('设置成功',$this->createWebUrl('dishes',array()),'success');
	}else{
		message('设置失败','','error');
	}
}
if($_GPC['op']=='delete'){
	$result = pdo_delete('wpdc_dishes', array('id'=>$_GPC['delid']));
		if($result){
			message('删除成功',$this->createWebUrl('dishes',array()),'success');
		}else{
			message('删除失败','','error');
		}
}
include $this->template('web/dishes');