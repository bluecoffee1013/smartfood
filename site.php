<?php

defined('IN_IA') or die('Access Denied');
require IA_ROOT . '/addons/zh_dianc/inc/func/core.php';
class zh_diancModuleSite extends Core
{
	public function doMobileNewOrder()
	{
		global $_W, $_GPC;
		$time = time();
		$time2 = $time - 10;
		$seller_id = $_GPC['store'];
		$res = pdo_get('wpdc_order', array('state' => 2, 'seller_id' => $seller_id));
		$res2 = pdo_get('wpdc_order', array('time2 >=' => $time2, 'type' => 2, 'seller_id' => $seller_id));
		$res3 = pdo_get('wpdc_ydorder', array('state' => 1, 'store_id' => $seller_id));
		if ($res) {
			echo 1;
		} else {
			if ($res2) {
				echo 2;
			} else {
				if ($res3) {
					echo 3;
				} else {
					echo '暂无新订单!';
				}
			}
		}
	}
	public function doMobileJdOrder()
	{
		global $_W, $_GPC;
		$seller_id = $_GPC['id'];
		$store = pdo_get('wpdc_store', array('id' => $seller_id));
		if ($store['is_jd'] == 1) {
			$time = time() - $store['jd_time'];
			$data['state'] = 3;
			$res = pdo_update('wpdc_order', $data, array('seller_id' => $seller_id, 'time2 <=' => $time, 'state' => 2));
			if ($res) {
				echo '1';
			} else {
				echo '2';
			}
		}
	}
	public function doMobileUpdate()
	{
		global $_W, $_GPC;
		if ($_GPC['name']) {
			$data['name'] = $_GPC['name'];
		}
		if ($_GPC['money']) {
			$data['money'] = $_GPC['money'];
		}
		if ($_GPC['wm_money']) {
			$data['wm_money'] = $_GPC['wm_money'];
		}
		if ($_GPC['box_fee']) {
			$data['box_fee'] = $_GPC['box_fee'];
		}
		if ($_GPC['num']) {
			$data['num'] = $_GPC['num'];
		}
		if ($_GPC['xs_num']) {
			$data['xs_num'] = $_GPC['xs_num'];
		}
		$res = pdo_update('wpdc_dishes', $data, array('id' => $_GPC['id']));
		if ($res) {
			echo '1';
		} else {
			echo '2';
		}
	}
	public function doMobileUpdUser()
	{
		global $_W, $_GPC;
		$res = pdo_delete('wpdc_user', array('id' => $_GPC['id']));
		pdo_delete('wpdc_order', array('user_id' => $_GPC['id']));
		pdo_delete('wpdc_ydorder', array('user_id' => $_GPC['id']));
		if ($res) {
			echo '1';
		} else {
			echo '2';
		}
	}
	public function doMobileUpdCai()
	{
		global $_W, $_GPC;
		$res = pdo_delete('wpdc_dishes', array('id' => $_GPC['id']));
		$res = pdo_delete('wpdc_spec', array('goods_id' => $_GPC['id']));
		if ($res) {
			echo '1';
		} else {
			echo '2';
		}
	}
	public function doMobileDelCz()
	{
		global $_W, $_GPC;
		$res = pdo_delete('wpdc_czhd', array('id' => $_GPC['id']));
		if ($res) {
			echo '1';
		} else {
			echo '2';
		}
	}
	public function doMobileAddCz()
	{
		global $_W, $_GPC;
		$i = 0;
		Lt2EC:
		if ($i < count($_GPC['list'])) {
			$data['full'] = $_GPC['list'][$i]['full'];
			$data['reduction'] = $_GPC['list'][$i]['reduction'];
			$data['uniacid'] = $_W['uniacid'];
			pdo_insert('wpdc_czhd', $data);
			$i++;
			goto Lt2EC;
		}
	}
}