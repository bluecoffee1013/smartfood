<?php

defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
     $certfile = IA_ROOT . "/addons/zh_dianc/cert/" . 'apiclient_cert_' . $_W['uniacid'] . '.pem';
    $keyfile = IA_ROOT . "/addons//zh_dianc/cert/" . 'apiclient_key_' . $_W['uniacid'] . '.pem';
    $item=pdo_get('wpdc_system',array('uniacid'=>$_W['uniacid']));
    if(checksubmit('submit')){
        file_put_contents($certfile, trim($_GPC['apiclient_cert']));
             file_put_contents($keyfile, trim($_GPC['apiclient_key']));
            $data['mchid']=trim($_GPC['mchid']);
            $data['wxkey']=trim($_GPC['wxkey']);
            $data['uniacid']=trim($_W['uniacid']);
            $data['apiclient_cert']=$_GPC['apiclient_cert'];
            $data['apiclient_key']=$_GPC['apiclient_key']; 
            $data['is_yue']=$_GPC['is_yue']; 
            if($_GPC['id']==''){                
                $res=pdo_insert('wpdc_system',$data);
                if($res){
                    message('添加成功',$this->createWebUrl('pay',array()),'success');
                }else{
                    message('添加失败','','error');
                }
            }else{
                $res = pdo_update('wpdc_system', $data, array('id' => $_GPC['id']));
                if($res){
                    message('编辑成功',$this->createWebUrl('pay',array()),'success');
                }else{
                    message('编辑失败','','error');
                }
            }
        }
    include $this->template('web/pay');