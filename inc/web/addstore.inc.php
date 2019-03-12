<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$area=pdo_getall('wpdc_area',array('uniacid'=>$_W['uniacid']),array(),'','num asc');
$type=pdo_getall('wpdc_storetype',array('uniacid'=>$_W['uniacid']),array(),'','num asc');
$info=pdo_get('wpdc_store',array('id'=>$_GPC['id']));
if(checksubmit('submit')){
  $data['is_rest']=$_GPC['is_rest'];
  $data['is_yy']=$_GPC['is_yy'];
  $data['is_wm']=$_GPC['is_wm'];
  $data['is_dn']=$_GPC['is_dn'];
  $data['is_sy']=$_GPC['is_sy'];
  $data['is_pd']=$_GPC['is_pd'];
  $data['pd_name']=$_GPC['pd_name'];
  $data['pd_img']=$_GPC['pd_img'];
  $data['yy_name']=$_GPC['yy_name'];
  $data['yy_img']=$_GPC['yy_img'];
  $data['wm_name']=$_GPC['wm_name'];
  $data['wm_img']=$_GPC['wm_img'];
  $data['dn_name']=$_GPC['dn_name'];
  $data['dn_img']=$_GPC['dn_img'];
  $data['sy_name']=$_GPC['sy_name'];
  $data['sy_img']=$_GPC['sy_img'];
  // $data['time']=$_GPC['time'];
  // $data['time2']=$_GPC['time2'];
  // $data['time3']=$_GPC['time3'];
  // $data['time4']=$_GPC['time4'];
  $data['md_name']=$_GPC['md_name'];
  $data['md_type']=$_GPC['md_type'];
  $data['md_area']=$_GPC['md_area'];
  $data['md_logo']=$_GPC['md_logo'];
    $data['poundage']=$_GPC['poundage'];
  
  $data['number']=$_GPC['number'];
  $data['md_content']=$_GPC['md_content'];
  $data['uniacid']=$_W['uniacid'];
  if(!$_GPC['md_area']){
    message('请选择门店区域!','','error'); 
}
if(!$_GPC['md_type']){
    message('请选择门店类型!','','error'); 
}
if(!$_GPC['md_name']){
    message('请填写门店名称!','','error'); 
}
if(!$_GPC['md_logo']){
    message('请选择门店LOGO!','','error'); 
}
if($_GPC['id']==''){  
    $data['color']="#34AAFF";
    $res=pdo_insert('wpdc_store',$data);
    if($res){
       message('添加成功！', $this->createWebUrl('store'), 'success');
   }else{
       message('添加失败！','','error');
   }
}else{
    $res=pdo_update('wpdc_store',$data,array('id'=>$_GPC['id']));
    if($res){
       message('编辑成功！', $this->createWebUrl('store'), 'success');
   }else{
       message('编辑失败！','','error');
   }
}
}
include $this->template('web/addstore');