<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$info=pdo_get('wpdc_ad',array('id'=>$_GPC['id']));
if(checksubmit('submit')){
         $data['logo']=$_GPC['logo'];
        $data['src']=$_GPC['src'];
        $data['orderby']=$_GPC['orderby'];
        $data['status']=$_GPC['status'];
        $data['title']=$_GPC['title'];
        $data['xcx_name']=$_GPC['xcx_name'];
        $data['type']=$_GPC['type'];
        $data['appid']=trim($_GPC['appid']);
        $data['uniacid']=$_W['uniacid'];
        $data['created_time']=date('Y-m-d H:i:s');
     if($_GPC['id']==''){  
        $res=pdo_insert('wpdc_ad',$data);
        if($res){
             message('添加成功！', $this->createWebUrl('ad'), 'success');
        }else{
             message('添加失败！','','error');
        }
    }else{
        $res=pdo_update('wpdc_ad',$data,array('id'=>$_GPC['id']));
        if($res){
             message('编辑成功！', $this->createWebUrl('ad'), 'success');
        }else{
             message('编辑失败！','','error');
        }
    }
}
include $this->template('web/addad');