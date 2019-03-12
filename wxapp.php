<?php

defined("IN_IA") or die("Access Denied");
class Zh_diancModuleWxapp extends WeModuleWxapp
{
	public function doPageSystem()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
		echo json_encode($res);
	}
	public function doPageOpenid()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
		$code = $_GPC["code"];
		$appid = $res["appid"];
		$secret = $res["appsecret"];
		$url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $appid . "&secret=" . $secret . "&js_code=" . $code . "&grant_type=authorization_code";
		function httpRequest($url, $data = null)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
			if (!empty($data)) {
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			}
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($curl);
			curl_close($curl);
			return $output;
		}
		$res = httpRequest($url);
		print_r($res);
	}
	public function doPageStoreLogin()
	{
		global $_W, $_GPC;
		$user = $_GPC["user"];
		$password = md5($_GPC["password"]);
		$res = pdo_get("wpdc_seller", array("account" => $user, "pwd" => $password, "uniacid" => $_W["uniacid"]));
		if ($res) {
			echo json_encode($res);
		} else {
			echo "2";
		}
	}
	public function doPageStore()
	{
		global $_W, $_GPC;
		if ($_GPC["user_id"]) {
			$data["user_id"] = $_GPC["user_id"];
			$data["store_id"] = $_GPC["id"];
			$data["time"] = date("Y-m-d");
			$list = pdo_get("wpdc_traffic", $data);
			if (!$list) {
				pdo_insert("wpdc_traffic", $data);
			}
		}
		$res = pdo_get("wpdc_store", array("uniacid" => $_W["uniacid"], "id" => $_GPC["id"]));
		if ($res["img"]) {
			if (strlen($res["img"]) > 51) {
				$res["img"] = explode(",", $res["img"]);
			} else {
				$res["img"] = array(0 => $res["img"]);
			}
		}
		if ($res["yyzz"]) {
			if (strlen($res["yyzz"]) > 51) {
				$res["yyzz"] = explode(",", $res["yyzz"]);
			} else {
				$res["yyzz"] = array(0 => $res["yyzz"]);
			}
		}
		if ($res["environment"]) {
			if (strlen($res["environment"]) > 51) {
				$res["environment"] = explode(",", $res["environment"]);
			} else {
				$res["environment"] = array(0 => $res["environment"]);
			}
		}
		echo json_encode($res);
	}
	public function doPageLogin()
	{
		global $_GPC, $_W;
		$openid = $_GPC["openid"];
		$res = pdo_get("wpdc_user", array("openid" => $openid, "uniacid" => $_W["uniacid"]));
		if ($res) {
			$user_id = $res["id"];
			$data["openid"] = $_GPC["openid"];
			$data["img"] = $_GPC["img"];
			$data["name"] = $_GPC["name"];
			$res = pdo_update("wpdc_user", $data, array("id" => $user_id));
			$user = pdo_get("wpdc_user", array("openid" => $openid, "uniacid" => $_W["uniacid"]));
			echo json_encode($user);
		} else {
			$data["openid"] = $_GPC["openid"];
			$data["img"] = $_GPC["img"];
			$data["name"] = $_GPC["name"];
			$data["uniacid"] = $_W["uniacid"];
			$data["join_time"] = time();
			$res2 = pdo_insert("wpdc_user", $data);
			$user = pdo_get("wpdc_user", array("openid" => $openid, "uniacid" => $_W["uniacid"]));
			echo json_encode($user);
		}
	}
	public function doPageDishes()
	{
		global $_W, $_GPC;
		$type = pdo_getall("wpdc_type", array("uniacid" => $_W["uniacid"], "store_id" => $_GPC["id"]), array(), '', "order_by ASC");
		$list = pdo_getall("wpdc_dishes", array("uniacid" => $_W["uniacid"], "is_shelves" => 1, "dishes_type !=" => $_GPC["dishes_type"], "store_id" => $_GPC["id"]), array(), '', "sorting ASC");
		$data2 = array();
		$i = 0;
		XJyHs:
		if (!($i < count($type))) {
			echo json_encode($data2);
		} else {
			$data = array();
			$k = 0;
			mBBQ9:
			if (!($k < count($list))) {
				$data2[] = array("id" => $type[$i]["id"], "type_name" => $type[$i]["type_name"], "goods" => $data);
				$i++;
				goto XJyHs;
			}
			if ($type[$i]["id"] == $list[$k]["type_id"]) {
				$data[] = array("id" => $list[$k]["id"], "name" => $list[$k]["name"], "img" => $list[$k]["img"], "num" => $list[$k]["num"], "money" => $list[$k]["money"], "one" => $list[$k]["one"], "signature" => $list[$k]["signature"], "dishes_type" => $list[$k]["dishes_type"], "xs_num" => $list[$k]["xs_num"], "sit_ys_num" => $list[$k]["sit_ys_num"], "wm_money" => $list[$k]["wm_money"], "details" => $list[$k]["details"], "box_fee" => $list[$k]["box_fee"]);
			}
			$k++;
			goto mBBQ9;
		}
	}
	public function doPageDishesGg()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_spec", array("goods_id" => $_GPC["dishes_id"]));
		echo json_encode($res);
	}
	public function doPageDishesInfo()
	{
		global $_GPC, $_W;
		$res = pdo_get("wpdc_dishes", array("id" => $_GPC["id"]));
		$res2 = pdo_getall("wpdc_spec", array("goods_id" => $_GPC["id"]));
		$data["dishes"] = $res;
		$data["spec"] = $res2;
		echo json_encode($data);
	}
	public function doPageUrl()
	{
		global $_GPC, $_W;
		echo $_W["attachurl"];
	}
	public function doPageUrl2()
	{
		global $_GPC, $_W;
		echo $_W["siteroot"];
	}
	public function doPageAddOrder()
	{
		global $_W, $_GPC;
		if ($_GPC["type"] == 1) {
			$data["user_id"] = $_GPC["user_id"];
			$data["order_num"] = date("YmdHis", time()) . rand(1111, 9999);
			$data["state"] = 1;
			$data["time"] = date("Y-m-d H:i:s", time());
			$data["time2"] = time();
			$data["money"] = $_GPC["money"];
			$data["preferential"] = $_GPC["preferential"];
			$data["tel"] = $_GPC["tel"];
			$data["name"] = $_GPC["name"];
			$data["note"] = $_GPC["note"];
			$data["address"] = $_GPC["address"];
			$data["type"] = $_GPC["type"];
			$data["area"] = $_GPC["area"];
			$data["lat"] = $_GPC["lat"];
			$data["lng"] = $_GPC["lng"];
			$data["uniacid"] = $_W["uniacid"];
			$data["freight"] = $_GPC["freight"];
			$data["box_fee"] = $_GPC["box_fee"];
			$data["coupons_id"] = $_GPC["coupons_id"];
			$data["voucher_id"] = $_GPC["voucher_id"];
			$data["seller_id"] = $_GPC["seller_id"];
			$data["delivery_time"] = $_GPC["delivery_time"];
			$data["is_take"] = $_GPC["is_take"];
			$data["is_yue"] = $_GPC["is_yue"];
			$res = pdo_insert("wpdc_order", $data);
			$order_id = pdo_insertid();
			$a = json_decode(html_entity_decode($_GPC["sz"]));
			$sz = json_decode(json_encode($a), true);
			$data3["state"] = 1;
			if ($_GPC["coupons_id"]) {
				pdo_update("wpdc_usercoupons", $data3, array("coupons_id" => $_GPC["coupons_id"], "user_id" => $_GPC["user_id"]));
			}
			if ($_GPC["voucher_id"]) {
				pdo_update("wpdc_uservoucher", $data3, array("vouchers_id" => $_GPC["voucher_id"], "user_id" => $_GPC["user_id"]));
			}
			if ($res) {
				if ($data["is_yue"] == 1) {
					pdo_update("wpdc_user", array("wallet -=" => $_GPC["money"]), array("id" => $_GPC["user_id"]));
					$data4["money"] = $_GPC["money"];
					$data4["user_id"] = $_GPC["user_id"];
					$data4["type"] = 2;
					$data4["note"] = "外卖订单";
					$data4["time"] = date("Y-m-d H:i:s");
					pdo_insert("wpdc_qbmx", $data4);
				}
				$i = 0;
				XBKt2:
				if (!($i < count($sz))) {
					echo $order_id;
					goto Label_27;
				}
				$data2["name"] = $sz[$i]["name"];
				$data2["number"] = $sz[$i]["num"];
				$data2["money"] = $sz[$i]["money"];
				$data2["img"] = $sz[$i]["img"];
				$data2["dishes_id"] = $sz[$i]["dishes_id"];
				$data2["uniacid"] = $_W["uniacid"];
				$data2["order_id"] = $order_id;
				$res2 = pdo_insert("wpdc_goods", $data2);
				$i++;
				goto XBKt2;
			}
			echo "下单失败";
			Label_27:
		} else {
			if ($_GPC["type"] == 2) {
				$data["preferential"] = $_GPC["preferential"];
				$data["user_id"] = $_GPC["user_id"];
				$data["order_num"] = date("YmdHis", time()) . rand(1111, 9999);
				$data["time"] = date("Y-m-d H:i:s", time());
				$data["time2"] = time();
				$data["money"] = $_GPC["money"];
				$data["type"] = $_GPC["type"];
				$data["dn_state"] = 1;
				$data["uniacid"] = $_W["uniacid"];
				$data["table_id"] = $_GPC["table_id"];
				$data["coupons_id"] = $_GPC["coupons_id"];
				$data["voucher_id"] = $_GPC["voucher_id"];
				$data["seller_id"] = $_GPC["seller_id"];
				$data["is_yue"] = $_GPC["is_yue"];
				$res = pdo_insert("wpdc_order", $data);
				$order_id = pdo_insertid();
				$a = json_decode(html_entity_decode($_GPC["sz"]));
				$sz = json_decode(json_encode($a), true);
				$data3["state"] = 1;
				if ($_GPC["coupons_id"]) {
					pdo_update("wpdc_usercoupons", $data3, array("coupons_id" => $_GPC["coupons_id"]));
				}
				if ($_GPC["voucher_id"]) {
					pdo_update("wpdc_uservoucher", $data3, array("vouchers_id" => $_GPC["voucher_id"]));
				}
				if ($res) {
					if ($data["is_yue"] == 1) {
						pdo_update("wpdc_user", array("wallet -=" => $_GPC["money"]), array("id" => $_GPC["user_id"]));
						$data4["money"] = $_GPC["money"];
						$data4["user_id"] = $_GPC["user_id"];
						$data4["type"] = 2;
						$data4["note"] = "店内订单";
						$data4["time"] = date("Y-m-d H:i:s");
						pdo_insert("wpdc_qbmx", $data4);
					}
					pdo_update("wpdc_table", array("status" => 2), array("id" => $_GPC["table_id"]));
					$i = 0;
					HzH9n:
					if (!($i < count($sz))) {
						echo $order_id;
						goto Label_35;
					}
					$data2["name"] = $sz[$i]["name"];
					$data2["number"] = $sz[$i]["num"];
					$data2["money"] = $sz[$i]["money"];
					$data2["img"] = $sz[$i]["img"];
					$data2["dishes_id"] = $sz[$i]["dishes_id"];
					$data2["uniacid"] = $_W["uniacid"];
					$data2["order_id"] = $order_id;
					$res2 = pdo_insert("wpdc_goods", $data2);
					$i++;
					goto HzH9n;
				}
				echo "下单失败";
				Label_35:
			}
		}
	}
	public function doPageDada()
	{
		global $_W, $_GPC;
		include IA_ROOT . "/addons/zh_dianc/DadaOpenapi.php";
		$res = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
		$order = pdo_get("wpdc_order", array("id" => $_GPC["order_id"]));
		$store = pdo_get("wpdc_store", array("id" => $order["seller_id"]));
		$config = array();
		$config["app_key"] = $res["dada_key"];
		$config["app_secret"] = $res["dada_secret"];
		$config["source_id"] = $store["source_id"];
		$config["url"] = "http://newopen.imdada.cn/api/cityCode/list";
		$obj = new DadaOpenapi($config);
		$name = $_GPC["area"];
		$data = array();
		$reqStatus = $obj->makeRequest($data);
		if (!$reqStatus) {
			if ($obj->getCode() == 0) {
				$arr = $obj->getResult();
				foreach ($arr as $v) {
					if ($name == $v["cityName"]) {
						$cityCode = $v["cityCode"];
					}
				}
				$data2 = array("shop_no" => $store["shop_no"], "origin_id" => $order["order_num"], "city_code" => $cityCode, "tips" => 0, "info" => $order["note"], "cargo_price" => $order["money"], "is_prepay" => 0, "expected_fetch_time" => time() + 600, "receiver_name" => $order["name"], "receiver_address" => $order["address"], "receiver_phone" => $order["tel"], "receiver_lat" => $_GPC["lat"], "receiver_lng" => $_GPC["lng"], "callback" => "http://newopen.imdada.cn/inner/api/order/status/notify");
				$config["url"] = "http://newopen.imdada.cn/api/order/addOrder";
				$obj2 = new DadaOpenapi($config);
				$reqStatus2 = $obj2->makeRequest($data2);
				if (!$reqStatus2) {
					if ($obj2->getCode() == 0) {
						echo "下单成功";
						print_r($obj2->getResult());
					}
				}
			} else {
				echo "失败";
			}
		} else {
			echo "except";
		}
	}
	public function doPageCoupons()
	{
		global $_W, $_GPC;
		$userid = $_GPC["user_id"];
		$sql = "select a.* ,b.name,b.start_time,b.end_time,b.conditions,b.preferential,b.coupons_type,b.instruction,c.name as store_name,c.id as store_id from " . tablename("wpdc_usercoupons") . " a" . " left join " . tablename("wpdc_coupons") . " b on b.id=a.coupons_id " . " left join " . tablename("wpdc_store") . " c on c.id=b.store_id  WHERE a.user_id=:user_id ";
		$list = pdo_fetchall($sql, array(":user_id" => $userid));
		$sql2 = "select a.* ,b.name as store_name from " . tablename("wpdc_coupons") . " a" . " left join " . tablename("wpdc_store") . " b on b.id=a.store_id   WHERE a.uniacid=:uniacid";
		$res = pdo_fetchall($sql2, array(":uniacid" => $_W["uniacid"]));
		$data["ok"] = $list;
		$data["all"] = $res;
		echo json_encode($data);
	}
	public function doPageAddCoupons()
	{
		global $_W, $_GPC;
		$data["user_id"] = $_GPC["user_id"];
		$data["coupons_id"] = $_GPC["coupons_id"];
		$data["uniacid"] = $_W["uniacid"];
		$data["state"] = 2;
		$res2 = pdo_get("wpdc_usercoupons", array("user_id" => $_GPC["user_id"], "coupons_id" => $_GPC["coupons_id"]));
		$res = pdo_insert("wpdc_usercoupons", $data);
		if ($res2) {
			echo "不能重复领取";
		} else {
			if ($res) {
				echo "1";
			} else {
				echo "2";
			}
		}
	}
	public function doPageVoucher()
	{
		global $_W, $_GPC;
		$userid = $_GPC["user_id"];
		$sql = "select a.* ,b.name,b.start_time,b.end_time,b.preferential,b.voucher_type,b.instruction,c.name as store_name ,c.id as store_id from " . tablename("wpdc_uservoucher") . " a" . " left join " . tablename("wpdc_voucher") . " b on b.id=a.vouchers_id " . " left join " . tablename("wpdc_store") . " c on c.id=b.store_id  WHERE a.user_id=:user_id";
		$list = pdo_fetchall($sql, array(":user_id" => $userid));
		$sql2 = "select a.* ,b.name as store_name from " . tablename("wpdc_voucher") . " a" . " left join " . tablename("wpdc_store") . " b on b.id=a.store_id   WHERE a.uniacid=:uniacid";
		$res = pdo_fetchall($sql2, array(":uniacid" => $_W["uniacid"]));
		$data["ok"] = $list;
		$data["all"] = $res;
		echo json_encode($data);
	}
	public function doPageAddVoucher()
	{
		global $_W, $_GPC;
		$data["user_id"] = $_GPC["user_id"];
		$data["vouchers_id"] = $_GPC["vouchers_id"];
		$data["uniacid"] = $_W["uniacid"];
		$data["state"] = 2;
		$res2 = pdo_get("wpdc_uservoucher", array("user_id" => $_GPC["user_id"], "vouchers_id" => $_GPC["vouchers_id"]));
		$res = pdo_insert("wpdc_uservoucher", $data);
		if ($res2) {
			echo "不能重复领取";
		} else {
			if ($res) {
				echo "1";
			} else {
				echo "2";
			}
		}
	}
	public function doPagePay()
	{
		global $_W, $_GPC;
		include IA_ROOT . "/addons/zh_dianc/wxpay.php";
		$res = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
		$appid = $res["appid"];
		$openid = $_GPC["openid"];
		$mch_id = $res["mchid"];
		$key = $res["wxkey"];
		$out_trade_no = $mch_id . time();
		pdo_update("wpdc_order", array("sh_ordernum" => $out_trade_no), array("id" => $_GPC["order_id"]));
		$total_fee = $_GPC["money"];
		if (empty($total_fee)) {
			$body = "订单付款";
			$total_fee = floatval(99 * 100);
		} else {
			$body = "订单付款";
			$total_fee = floatval($total_fee * 100);
		}
		$weixinpay = new WeixinPay($appid, $openid, $mch_id, $key, $out_trade_no, $body, $total_fee);
		$return = $weixinpay->pay();
		echo json_encode($return);
	}
	public function doPagePay2()
	{
		global $_W, $_GPC;
		include IA_ROOT . "/addons/zh_dianc/wxpay.php";
		$res = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
		$appid = $res["appid"];
		$openid = $_GPC["openid"];
		$mch_id = $res["mchid"];
		$key = $res["wxkey"];
		$out_trade_no = $mch_id . time();
		$total_fee = $_GPC["money"];
		if (empty($total_fee)) {
			$body = "订单付款";
			$total_fee = floatval(99 * 100);
		} else {
			$body = "订单付款";
			$total_fee = floatval($total_fee * 100);
		}
		$weixinpay = new WeixinPay($appid, $openid, $mch_id, $key, $out_trade_no, $body, $total_fee);
		$return["a"] = $weixinpay->pay();
		$return["b"] = $out_trade_no;
		echo json_encode($return);
	}
	public function doPageMap()
	{
		global $_GPC, $_W;
		$op = $_GPC["op"];
		$url = "https://apis.map.qq.com/ws/geocoder/v1/?location=" . $op . "&key=EOJBZ-HSBW6-G2VSM-EE3KV-4OAAK-RXFWT&get_poi=0&coord_type=1";
		$html = file_get_contents($url);
		echo $html;
	}
	public function doPageMap2()
	{
		global $_GPC, $_W;
		$op = $_GPC["op"];
		$url = "https://apis.map.qq.com/ws/geocoder/v1/?address=" . $op . "&key=EOJBZ-HSBW6-G2VSM-EE3KV-4OAAK-RXFWT";
		$html = file_get_contents($url);
		echo $html;
	}
	public function doPageJuLi()
	{
		global $_GPC, $_W;
		$from = $_GPC["start"];
		$to = $_GPC["end"];
		$url = "https://apis.map.qq.com/ws/distance/v1/?mode=driving&from=" . $from . "&to=" . $to . "&key=EOJBZ-HSBW6-G2VSM-EE3KV-4OAAK-RXFWT";
		$html = file_get_contents($url);
		echo $html;
	}
	public function doPageZhuanh()
	{
		global $_GPC, $_W;
		$op = $_GPC["op"];
		$url = "https://apis.map.qq.com/ws/coord/v1/translate?locations=" . $op . "&type=3&key=EOJBZ-HSBW6-G2VSM-EE3KV-4OAAK-RXFWT";
		$html = file_get_contents($url);
		echo $html;
	}
	public function doPagePayOrder()
	{
		global $_W, $_GPC;
		$system = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
		$order = pdo_get("wpdc_order", array("id" => $_GPC["order_id"]));
		if ($order["type"] == 1) {
			$data["state"] = 2;
			$data["pay_time"] = time();
			$res = pdo_update("wpdc_order", $data, array("id" => $_GPC["order_id"]));
			if ($res) {
				if ($system["integral2"] > 0 and $system["is_jf"] == 1) {
					$jifen = round($system["integral2"] / 100 * $order["money"]);
					pdo_update("wpdc_user", array("total_score +=" => $jifen), array("id" => $order["user_id"]));
					$data5["score"] = $jifen;
					$data5["user_id"] = $order["user_id"];
					$data5["note"] = "外卖消费";
					$data5["type"] = 1;
					$data5["cerated_time"] = date("Y-m-d H:i:s");
					$data5["uniacid"] = $_W["uniacid"];
					pdo_insert("wpdc_integral", $data5);
				}
				pdo_update("wpdc_store", array("sales +=" => 1), array("id" => $order["seller_id"]));
				$good = pdo_getall("wpdc_goods", array("order_id" => $_GPC["order_id"]));
				$i = 0;
				AjXWv:
				if (!($i < count($good))) {
					echo "1";
					goto Label_65;
				}
				pdo_update("wpdc_dishes", array("num -=" => $good[$i]["number"]), array("id" => $good[$i]["dishes_id"]));
				pdo_update("wpdc_dishes", array("xs_num +=" => $good[$i]["number"]), array("id" => $good[$i]["dishes_id"]));
				$i++;
				goto AjXWv;
			}
			echo "2";
			Label_65:
		} else {
			if ($order["type"] == 2) {
				$data["dn_state"] = 2;
				$data["pay_type"] = 1;
				$data["pay_time"] = time();
				$res = pdo_update("wpdc_order", $data, array("id" => $_GPC["order_id"]));
				if ($res) {
					$set = pdo_get("wpdc_fxset", array("uniacid" => $_W["uniacid"]));
					$order = pdo_get("wpdc_order", array("id" => $_GPC["order_id"]));
					if ($set["is_open"] == 1) {
						if ($set["is_ej"] == 2) {
							$user = pdo_get("wpdc_fxuser", array("fx_user" => $order["user_id"]));
							if ($user) {
								$userid = $user["user_id"];
								$money = $order["money"] * ($set["commission"] / 100);
								pdo_update("wpdc_user", array("commission +=" => $money), array("id" => $userid));
								$data6["user_id"] = $userid;
								$data6["son_id"] = $order["user_id"];
								$data6["money"] = $money;
								$data6["time"] = time();
								$data6["uniacid"] = $_W["uniacid"];
								pdo_insert("wpdc_earnings", $data6);
							}
						} else {
							$user = pdo_get("wpdc_fxuser", array("fx_user" => $order["user_id"]));
							$user2 = pdo_get("wpdc_fxuser", array("fx_user" => $user["user_id"]));
							if ($user) {
								$userid = $user["user_id"];
								$money = $order["money"] * ($set["commission"] / 100);
								pdo_update("wpdc_user", array("commission +=" => $money), array("id" => $userid));
								$data6["user_id"] = $userid;
								$data6["son_id"] = $order["user_id"];
								$data6["money"] = $money;
								$data6["time"] = time();
								$data6["uniacid"] = $_W["uniacid"];
								pdo_insert("wpdc_earnings", $data6);
							}
							if ($user2) {
								$userid2 = $user2["user_id"];
								$money = $order["money"] * ($set["commission2"] / 100);
								pdo_update("wpdc_user", array("commission +=" => $money), array("id" => $userid2));
								$data7["user_id"] = $userid2;
								$data7["son_id"] = $order["user_id"];
								$data7["money"] = $money;
								$data7["time"] = time();
								$data7["uniacid"] = $_W["uniacid"];
								pdo_insert("wpdc_earnings", $data7);
							}
						}
					}
					if ($system["integral2"] > 0 and $system["is_jf"] == 1) {
						$jifen = round($system["integral2"] / 100 * $order["money"]);
						pdo_update("wpdc_user", array("total_score +=" => $jifen), array("id" => $order["user_id"]));
						$data5["score"] = $jifen;
						$data5["user_id"] = $order["user_id"];
						$data5["note"] = "店内消费";
						$data5["type"] = 1;
						$data5["cerated_time"] = date("Y-m-d H:i:s");
						$data5["uniacid"] = $_W["uniacid"];
						pdo_insert("wpdc_integral", $data5);
					}
					$good = pdo_getall("wpdc_goods", array("order_id" => $_GPC["order_id"]));
					$i = 0;
					ka33c:
					if (!($i < count($good))) {
						echo "1";
						goto Label_69;
					}
					pdo_update("wpdc_dishes", array("num -=" => $good[$i]["number"]), array("id" => $good[$i]["dishes_id"]));
					pdo_update("wpdc_dishes", array("xs_num +=" => $good[$i]["number"]), array("id" => $good[$i]["dishes_id"]));
					$i++;
					goto ka33c;
				}
				echo "2";
				Label_69:
			}
		}
	}
	public function doPageMyorder()
	{
		global $_W, $_GPC;
		$user_id = $_GPC["user_id"];
		$sql = "select a.* ,b.logo,b.name as store_name,b.tel as store_tel from " . tablename("wpdc_order") . " a" . " left join " . tablename("wpdc_store") . " b on b.id=a.seller_id   WHERE a.user_id=:user_id  and a.del=:del ORDER BY id DESC ";
		$res = pdo_fetchall($sql, array(":user_id" => $user_id, "del" => 2));
		$res2 = pdo_getall("wpdc_goods");
		$data2 = array();
		$i = 0;
		gibCV:
		if (!($i < count($res))) {
			echo json_encode($data2);
		} else {
			$data = array();
			$k = 0;
			FGKCe:
			if (!($k < count($res2))) {
				$data2[] = array("id" => $res[$i]["id"], "order_num" => $res[$i]["order_num"], "time" => $res[$i]["time"], "state" => $res[$i]["state"], "dn_state" => $res[$i]["dn_state"], "money" => $res[$i]["money"], "type" => $res[$i]["type"], "tel" => $res[$i]["store_tel"], "goods" => $data, "img" => $res[$i]["logo"], "name" => $res[$i]["store_name"], "is_yue" => $res[$i]["is_yue"]);
				$i++;
				goto gibCV;
			}
			if ($res[$i]["id"] == $res2[$k]["order_id"]) {
				$data[] = array("name" => $res2[$k]["name"], "num" => $res2[$k]["number"], "img" => $res2[$k]["img"], "money" => $res2[$k]["money"], "dishes_id" => $res2[$k]["dishes_id"]);
			}
			$k++;
			goto FGKCe;
		}
	}
	public function doPageOrderInfo()
	{
		global $_W, $_GPC;
		$sql = "select a.* ,b.name as table_name ,c.name  as  table_name_type from " . tablename("wpdc_order") . " a" . " left join " . tablename("wpdc_table") . " b on b.id=a.table_id " . " left join " . tablename("wpdc_table_type") . " c on b.type_id=c.id WHERE a.id=:id";
		$res = pdo_fetch($sql, array(":id" => $_GPC["id"]));
		$res2 = pdo_getall("wpdc_goods", array("order_id" => $_GPC["id"]));
		$res3 = pdo_get("wpdc_store", array("id" => $res["seller_id"]));
		$data["order"] = $res;
		$data["good"] = $res2;
		$data["store"] = $res3;
		echo json_encode($data);
	}
	public function doPageCancelOrder()
	{
		global $_W, $_GPC;
		$order = pdo_get("wpdc_order", array("id" => $_GPC["order_id"]));
		if ($order["type"] == 1) {
			$data["state"] = 5;
		} else {
			if ($order["type"] == 2) {
				$data["dn_state"] = 3;
			}
		}
		$data["cancel_time"] = time();
		$res = pdo_update("wpdc_order", $data, array("id" => $_GPC["order_id"]));
		if ($res) {
			$data3["state"] = 2;
			if ($order["coupons_id"]) {
				pdo_update("wpdc_usercoupons", $data3, array("coupons_id" => $order["coupons_id"], "user_id" => $order["user_id"]));
			}
			if ($order["voucher_id"]) {
				pdo_update("wpdc_uservoucher", $data3, array("vouchers_id" => $order["voucher_id"], "user_id" => $order["user_id"]));
			}
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageDelOrder()
	{
		global $_W, $_GPC;
		$res = pdo_update("wpdc_order", array("del" => 1), array("id" => $_GPC["order_id"]));
		if ($res) {
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageUpdAdd()
	{
		global $_W, $_GPC;
		$data["user_name"] = $_GPC["user_name"];
		$data["user_tel"] = $_GPC["user_tel"];
		$data["user_address"] = $_GPC["user_address"];
		$res = pdo_update("wpdc_user", $data, array("id" => $_GPC["user_id"]));
		if ($res) {
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPagePl()
	{
		global $_W, $_GPC;
		$data["seller_id"] = $_GPC["seller_id"];
		$data["order_id"] = $_GPC["order_id"];
		$data["order_num"] = $_GPC["order_num"];
		$data["score"] = $_GPC["score"];
		$data["content"] = $_GPC["content"];
		$data["img"] = $_GPC["img"];
		$data["cerated_time"] = date("Y-m-d H:i:s", time());
		$data["user_id"] = $_GPC["user_id"];
		$data["uniacid"] = $_W["uniacid"];
		$data["status"] = 1;
		$res = pdo_insert("wpdc_assess", $data);
		$order = pdo_get("wpdc_order", array("id" => $_GPC["order_id"]));
		if ($res) {
			$total = pdo_get("wpdc_assess", array("uniacid" => $_W["uniacid"], "seller_id" => $_GPC["seller_id"]), array("sum(score) as total"));
			$count = pdo_get("wpdc_assess", array("uniacid" => $_W["uniacid"], "seller_id" => $_GPC["seller_id"]), array("count(id) as count"));
			if ($total["total"] > 0 and $count["count"] > 0) {
				$pf = $total["total"] / $count["count"];
				$pf = floor($pf * 0.95 * 10) / 10;
				$pf = sprintf("%.1f", (double) $pf);
			} else {
				$pf = 0;
			}
			pdo_update("wpdc_store", array("score" => $pf), array("id" => $_GPC["seller_id"]));
			if ($order["type"] == 1) {
				$data2["state"] = 6;
			} else {
				if ($order["type"] == 2) {
					$data2["dn_state"] = 4;
				}
			}
			$data3["score"] = $_GPC["total_score"];
			$data3["user_id"] = $_GPC["user_id"];
			$data3["type"] = 1;
			$data3["order_id"] = $_GPC["order_id"];
			$data3["note"] = "评价订单";
			$data3["cerated_time"] = date("Y-m-d H:i:s");
			$data3["uniacid"] = $_W["uniacid"];
			pdo_insert("wpdc_integral", $data3);
			pdo_update("wpdc_user", array("total_score +=" => $_GPC["total_score"]), array("id" => $_GPC["user_id"]));
			pdo_update("wpdc_order", $data2, array("id" => $_GPC["order_id"]));
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageJfmx()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_integral", array("user_id" => $_GPC["user_id"]), array(), '', "id DESC");
		echo json_encode($res);
	}
	public function doPageStorePl()
	{
		global $_W, $_GPC;
		$sql = "select a.* ,b.name as user_name,b.img  as  user_img from " . tablename("wpdc_assess") . " a" . " left join " . tablename("wpdc_user") . " b on b.id=a.user_id   WHERE a.seller_id=:seller_id ORDER BY id DESC";
		$list = pdo_fetchall($sql, array(":seller_id" => $_GPC["id"]));
		echo json_encode($list);
	}
	public function doPageAd()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_slide", array("uniacid" => $_W["uniacid"]));
		echo json_encode($res);
	}
	public function doPageAd2()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_ad", array("uniacid" => $_W["uniacid"], "status" => 1), array(), '', "orderby asc");
		echo json_encode($res);
	}
	public function doPageReservation()
	{
		global $_W, $_GPC;
		$system = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
		$data["store_id"] = $_GPC["store_id"];
		$data["user_id"] = $_GPC["user_id"];
		$data["xz_date"] = $_GPC["xz_date"];
		$data["yjdd_date"] = $_GPC["yjdd_date"];
		$data["table_type_id"] = $_GPC["table_type_id"];
		$data["link_name"] = $_GPC["link_name"];
		$data["link_tel"] = $_GPC["link_tel"];
		$data["jc_num"] = $_GPC["jc_num"];
		$data["remark"] = $_GPC["remark"];
		$data["pay_money"] = $_GPC["money"];
		$data["ydcode"] = $_GPC["ydcode"];
		$data["order_num"] = date("YmdHis", time()) . rand(1111, 9999);
		$data["table_type_name"] = $_GPC["table_type_name"];
		$data["zd_cost"] = $_GPC["zd_cost"];
		$data["state"] = 1;
		$data["del"] = 2;
		$data["uniacid"] = $_W["uniacid"];
		$data["created_time"] = date("Y-m-d H:i:s");
		$data["time2"] = time();
		$data["is_yue"] = $_GPC["is_yue"];
		$res = pdo_insert("wpdc_ydorder", $data);
		$order_id = pdo_insertid();
		if ($res) {
			if ($_GPC["is_yue"] == 1) {
				pdo_update("wpdc_user", array("wallet -=" => $_GPC["money"]), array("id" => $_GPC["user_id"]));
				$data4["money"] = $_GPC["money"];
				$data4["user_id"] = $_GPC["user_id"];
				$data4["type"] = 2;
				$data4["note"] = "预约订单";
				$data4["time"] = date("Y-m-d H:i:s");
				pdo_insert("wpdc_qbmx", $data4);
			}
			if ($_GPC["money"] and $system["integral2"] > 0 and $system["is_jf"] == 1) {
				$jifen = round($system["integral2"] / 100 * $_GPC["money"]);
				pdo_update("wpdc_user", array("total_score +=" => $jifen), array("id" => $_GPC["user_id"]));
				$data5["score"] = $jifen;
				$data5["user_id"] = $_GPC["user_id"];
				$data5["note"] = "预约消费";
				$data5["type"] = 1;
				$data5["cerated_time"] = date("Y-m-d H:i:s");
				$data5["uniacid"] = $_W["uniacid"];
				pdo_insert("wpdc_integral", $data5);
			}
			echo $order_id;
		} else {
			echo "预定失败";
		}
	}
	public function doPageDelYd()
	{
		global $_W, $_GPC;
		$data["del"] = 1;
		$res = pdo_update("wpdc_ydorder", $data, array("id" => $_GPC["id"]));
		if ($res) {
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageCancelReservation()
	{
		global $_W, $_GPC;
		$data["state"] = 4;
		$res = pdo_update("wpdc_ydorder", $data, array("id" => $_GPC["id"]));
		if ($res) {
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageYdRefund()
	{
		global $_W, $_GPC;
		$data["state"] = 5;
		$res = pdo_update("wpdc_ydorder", $data, array("id" => $_GPC["id"]));
		if ($res) {
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageMyReservation()
	{
		global $_W, $_GPC;
		$sql = "select a.* ,b.name,b.tel,b.logo from " . tablename("wpdc_ydorder") . " a" . " left join " . tablename("wpdc_store") . " b on b.id=a.store_id   WHERE a.user_id=:user_id and a.del=:del ORDER BY a.id DESC";
		$list = pdo_fetchall($sql, array(":user_id" => $_GPC["user_id"], "del" => 2));
		echo json_encode($list);
	}
	public function doPageReservationInfo()
	{
		global $_W, $_GPC;
		$sql = "select a.* ,b.name,b.tel,b.logo from " . tablename("wpdc_ydorder") . " a" . " left join " . tablename("wpdc_store") . " b on b.id=a.store_id   WHERE a.id=:id ";
		$list = pdo_fetch($sql, array(":id" => $_GPC["id"]));
		echo json_encode($list);
	}
	public function doPageTableType()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_table_type", array("uniacid" => $_W["uniacid"], "seller_id" => $_GPC["store_id"]));
		echo json_encode($res);
	}
	public function doPageTable()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_table", array("uniacid" => $_W["uniacid"], "type_id" => $_GPC["type_id"], "status" => 0));
		echo json_encode($res);
	}
	public function doPageSms()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_sms", array("uniacid" => $_W["uniacid"], "store_id" => $_GPC["store_id"]));
		$tpl_id = $res["tpl_id"];
		$tel = $res["tel"];
		$key = $res["appkey"];
		$url = "http://v.juhe.cn/sms/send?mobile=" . $tel . "&tpl_id=" . $tpl_id . "&tpl_value=%23code%23%3D654654&key=" . $key;
		$data = file_get_contents($url);
		print_r($data);
	}
	public function doPageSms2()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_sms", array("uniacid" => $_W["uniacid"], "store_id" => $_GPC["store_id"]));
		$tpl_id = $res["tpl2_id"];
		$tel = $res["tel"];
		$key = $res["appkey"];
		$url = "http://v.juhe.cn/sms/send?mobile=" . $tel . "&tpl_id=" . $tpl_id . "&tpl_value=%23code%23%3D654654&key=" . $key;
		$data = file_get_contents($url);
		print_r($data);
	}
	public function doPageNew()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_order", array("user_id" => $_GPC["user_id"], "type" => 1, "seller_id" => $_GPC["store_id"]));
		if ($res) {
			echo "2";
		} else {
			echo "1";
		}
	}
	public function doPageScore()
	{
		global $_W, $_GPC;
		$total = pdo_get("wpdc_assess", array("uniacid" => $_W["uniacid"], "seller_id" => $_GPC["seller_id"]), array("sum(score) as total"));
		$count = pdo_get("wpdc_assess", array("uniacid" => $_W["uniacid"], "seller_id" => $_GPC["seller_id"]), array("count(id) as count"));
		if ($total["total"] > 0 and $count["count"] > 0) {
			echo $total["total"] / $count["count"];
		} else {
			echo "0";
		}
	}
	public function doPageComplete()
	{
		global $_W, $_GPC;
		$data["state"] = 4;
		$res = pdo_update("wpdc_order", $data, array("id" => $_GPC["id"]));
		if ($res) {
			$set = pdo_get("wpdc_fxset", array("uniacid" => $_W["uniacid"]));
			$order = pdo_get("wpdc_order", array("id" => $_GPC["id"]));
			if ($set["is_open"] == 1) {
				if ($set["is_ej"] == 2) {
					$user = pdo_get("wpdc_fxuser", array("fx_user" => $order["user_id"]));
					if ($user) {
						$userid = $user["user_id"];
						$money = $order["money"] * ($set["commission"] / 100);
						pdo_update("wpdc_user", array("commission +=" => $money), array("id" => $userid));
						$data2["user_id"] = $userid;
						$data2["son_id"] = $order["user_id"];
						$data2["money"] = $money;
						$data2["time"] = time();
						$data2["uniacid"] = $_W["uniacid"];
						pdo_insert("wpdc_earnings", $data2);
					}
				} else {
					$user = pdo_get("wpdc_fxuser", array("fx_user" => $order["user_id"]));
					$user2 = pdo_get("wpdc_fxuser", array("fx_user" => $user["user_id"]));
					if ($user) {
						$userid = $user["user_id"];
						$money = $order["money"] * ($set["commission"] / 100);
						pdo_update("wpdc_user", array("commission +=" => $money), array("id" => $userid));
						$data2["user_id"] = $userid;
						$data2["son_id"] = $order["user_id"];
						$data2["money"] = $money;
						$data2["time"] = time();
						$data2["uniacid"] = $_W["uniacid"];
						pdo_insert("wpdc_earnings", $data2);
					}
					if ($user2) {
						$userid2 = $user2["user_id"];
						$money = $order["money"] * ($set["commission2"] / 100);
						pdo_update("wpdc_user", array("commission +=" => $money), array("id" => $userid2));
						$data3["user_id"] = $userid2;
						$data3["son_id"] = $order["user_id"];
						$data3["money"] = $money;
						$data3["time"] = time();
						$data3["uniacid"] = $_W["uniacid"];
						pdo_insert("wpdc_earnings", $data3);
					}
				}
			}
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageZhuohao()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_table", array("id" => $_GPC["id"]));
		$res2 = pdo_get("wpdc_table_type", array("id" => $res["type_id"]));
		$data["table_name"] = $res["name"];
		$data["type_name"] = $res2["name"];
		$data["status"] = $res["status"];
		echo json_encode($data);
	}
	public function doPageGetHelp()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_help", array("uniacid" => $_W["uniacid"]), array(), '', "sort ASC");
		echo json_encode($res);
	}
	public function doPageUpload()
	{
		$uptypes = array("image/jpg", "image/jpeg", "image/png", "image/pjpeg", "image/gif", "image/bmp", "image/x-png");
		$max_file_size = 2000000;
		$destination_folder = "../attachment/";
		$watermark = 2;
		$watertype = 1;
		$waterposition = 1;
		$waterstring = "666666";
		$imgpreview = 1;
		$imgpreviewsize = 1 / 2;
		if (!is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
			echo "图片不存在!";
			die;
		}
		$file = $_FILES["upfile"];
		if ($max_file_size < $file["size"]) {
			echo "文件太大!";
			die;
		}
		if (!in_array($file["type"], $uptypes)) {
			echo "文件类型不符!" . $file["type"];
			die;
		}
		if (!file_exists($destination_folder)) {
			mkdir($destination_folder);
		}
		$filename = $file["tmp_name"];
		$image_size = getimagesize($filename);
		$pinfo = pathinfo($file["name"]);
		$ftype = $pinfo["extension"];
		$destination = $destination_folder . time() . "." . $ftype;
		if (file_exists($destination) && $overwrite != true) {
			echo "同名文件已经存在了";
			die;
		}
		if (!move_uploaded_file($filename, $destination)) {
			echo "移动文件出错";
			die;
		}
		$pinfo = pathinfo($destination);
		$fname = $pinfo["basename"];
		if ($watermark == 1) {
			$iinfo = getimagesize($destination, $iinfo);
			$nimage = imagecreatetruecolor($image_size[0], $image_size[1]);
			$white = imagecolorallocate($nimage, 255, 255, 255);
			$black = imagecolorallocate($nimage, 0, 0, 0);
			$red = imagecolorallocate($nimage, 255, 0, 0);
			imagefill($nimage, 0, 0, $white);
			switch ($iinfo[2]) {
				case 1:
					$simage = imagecreatefromgif($destination);
					goto y5JL0;
				case 2:
					$simage = imagecreatefromjpeg($destination);
					goto y5JL0;
				case 3:
					$simage = imagecreatefrompng($destination);
					goto y5JL0;
				case 6:
					$simage = imagecreatefromwbmp($destination);
					goto y5JL0;
				default:
					die("不支持的文件类型");
					die;
			}
			y5JL0:
			imagecopy($nimage, $simage, 0, 0, 0, 0, $image_size[0], $image_size[1]);
			imagefilledrectangle($nimage, 1, $image_size[1] - 15, 80, $image_size[1], $white);
			switch ($watertype) {
				case 1:
					imagestring($nimage, 2, 3, $image_size[1] - 15, $waterstring, $black);
					goto i4D3R;
				case 2:
					$simage1 = imagecreatefromgif("xplore.gif");
					imagecopy($nimage, $simage1, 0, 0, 0, 0, 85, 15);
					imagedestroy($simage1);
					goto i4D3R;
			}
			i4D3R:
			switch ($iinfo[2]) {
				case 1:
					imagejpeg($nimage, $destination);
					goto segD9;
				case 2:
					imagejpeg($nimage, $destination);
					goto segD9;
				case 3:
					imagepng($nimage, $destination);
					goto segD9;
				case 6:
					imagewbmp($nimage, $destination);
					goto segD9;
			}
			segD9:
			imagedestroy($nimage);
			imagedestroy($simage);
		}
		echo $fname;
		@(require_once IA_ROOT . "/framework/function/file.func.php");
		@($filename = $fname);
		@file_remote_upload($filename);
	}
	public function doPageMessage()
	{
		global $_W, $_GPC;
		function getaccess_token($_W)
		{
			$res = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
			$appid = $res["appid"];
			$secret = $res["appsecret"];
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret . '';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$data = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($data, true);
			return $data["access_token"];
		}
		function set_msg($_W)
		{
			$access_token = getaccess_token($_W);
			$res = pdo_get("wpdc_sms", array("uniacid" => $_W["uniacid"]));
			$res2 = pdo_get("wpdc_order", array("id" => $_GET["id"]));
			$formwork = "{\n           \"touser\": \"" . $_GET["openid"] . "\",\n           \"template_id\": \"" . $res["tid"] . "\",\n           \"form_id\":\"" . $_GET["form_id"] . "\",\n           \"data\": {\n             \"keyword1\": {\n               \"value\": \"" . $res2["order_num"] . "\",\n               \"color\": \"#173177\"\n             },\n             \"keyword2\": {\n               \"value\":\"" . $res2["name"] . "\",\n               \"color\": \"#173177\"\n             },\n             \"keyword3\": {\n               \"value\": \"" . $res2["tel"] . "\",\n               \"color\": \"#173177\"\n             },\n             \"keyword4\": {\n               \"value\":  \"" . $res2["money"] . "\",\n               \"color\": \"#173177\"\n             },\n             \"keyword5\": {\n               \"value\": \"" . date("Y-m-d H:i:s", $res2["pay_time"]) . "\",\n               \"color\": \"#173177\"\n             }\n           }\n         }";
			$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $access_token . '';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $formwork);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		echo set_msg($_W);
	}
	public function doPageMessage2()
	{
		global $_W, $_GPC;
		function getaccess_token($_W)
		{
			$res = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
			$appid = $res["appid"];
			$secret = $res["appsecret"];
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret . '';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$data = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($data, true);
			return $data["access_token"];
		}
		function set_msg($_W)
		{
			$access_token = getaccess_token($_W);
			$res = pdo_get("wpdc_sms", array("uniacid" => $_W["uniacid"]));
			$formwork = "{\n         \"touser\": \"" . $_GET["openid"] . "\",\n         \"template_id\": \"" . $res["dm_tid"] . "\",\n         \"form_id\":\"" . $_GET["form_id"] . "\",\n         \"data\": {\n           \"keyword1\": {\n             \"value\": \"" . $_GET["name"] . "\",\n             \"color\": \"#173177\"\n           },\n           \"keyword2\": {\n             \"value\":\"" . $_GET["money"] . "\",\n             \"color\": \"#173177\"\n           },\n           \"keyword3\": {\n             \"value\": \"" . date("Y-m-d H:i:s") . "\",\n             \"color\": \"#173177\"\n           }\n         }  \n       }";
			$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $access_token . '';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $formwork);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		echo set_msg($_W);
	}
	public function doPageMessage3()
	{
		global $_W, $_GPC;
		function getaccess_token($_W)
		{
			$res = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
			$appid = $res["appid"];
			$secret = $res["appsecret"];
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret . '';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$data = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($data, true);
			return $data["access_token"];
		}
		function set_msg($_W)
		{
			$access_token = getaccess_token($_W);
			$res = pdo_get("wpdc_sms", array("uniacid" => $_W["uniacid"]));
			$formwork = "{\n         \"touser\": \"" . $_GET["openid"] . "\",\n         \"template_id\": \"" . $res["yy_tid"] . "\",\n         \"form_id\":\"" . $_GET["form_id"] . "\",\n         \"data\": {\n           \"keyword1\": {\n             \"value\": \"" . $_GET["store_name"] . "\",\n             \"color\": \"#173177\"\n           },\n           \"keyword2\": {\n             \"value\":\"" . $_GET["xz_date"] . "　" . $_GET["yjdd_date"] . "\",\n             \"color\": \"#173177\"\n           },\n           \"keyword3\": {\n             \"value\": \"" . $_GET["link_name"] . "\",\n             \"color\": \"#173177\"\n           },\n           \"keyword4\": {\n             \"value\": \"" . $_GET["link_tel"] . "\",\n             \"color\": \"#173177\"\n           },\n           \"keyword5\": {\n             \"value\": \"" . $_GET["jc_num"] . "\",\n             \"color\": \"#173177\"\n           },\n           \"keyword6\": {\n             \"value\": \"" . $_GET["remark"] . "\",\n             \"color\": \"#173177\"\n           }\n         }\n       }";
			$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $access_token . '';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $formwork);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		echo set_msg($_W);
	}
	public function doPagePrint()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_order", array("id" => $_GPC["order_id"]));
		$res3 = pdo_get("wpdc_dyj", array("uniacid" => $_W["uniacid"], "store_id" => $res["seller_id"], "state" => 1, "location" => 1));
		$res2 = pdo_getall("wpdc_goods", array("order_id" => $_GPC["order_id"]));
		$content = "\n\n\n\n\n";
		$content .= "         订单编号  #" . $res["id"] . " \n\n";
		$content .= "          " . $res3["dyj_title"] . "\n\n";
		$content .= "----------已在线支付------------\n";
		$content .= "--------------------------------";
		$content .= "下单时间：" . $res["time"] . "\n";
		$content .= "--------------------------------";
		$name = '';
		$i = 0;
		hd2M_:
		if (!($i < count($res2))) {
			$content .= "--------------------------------";
			$content .= "餐盒费：　　　　　　　　　 " . $res["box_fee"] . "\n";
			$content .= "--------------------------------";
			if (strlen($res["preferential"]) == 4) {
				$content .= "已优惠：　　　　　　　　-" . $res["preferential"] . "\n";
			}
			if (strlen($res["preferential"]) == 5) {
				$content .= "已优惠：　　　　　　　　 -" . $res["preferential"] . "\n";
			}
			$content .= "--------------------------------";
			$content .= "已付：　　　　　　　　　　" . $res["money"] . "\n";
			$content .= "--------------------------------";
			$content .= "送货地点：" . $res["address"] . "\n";
			$content .= "联系电话：" . $res["tel"] . "\n";
			$content .= "联系人：" . $res["name"] . "\n";
			if ($res["note"]) {
				$content .= "备注：" . $res["note"] . "\n\n\n\n\n";
			}
			if (!($res3["type"] == 1)) {
				IsYgW:
				if (!($res3["type"] == 2)) {
					goto nPrrl;
				}
				include "print.class.php";
				$print = new Yprint();
				$apiKey = $res3["api"];
				$msign = $res3["token"];
				$partner = $res3["yy_id"];
				$machine_code = $res3["mid"];
				$print->action_print($partner, $machine_code, $content, $apiKey, $msign);
				goto nPrrl;
			}
			$content = "\n\n\n\n\n";
			$content .= "          订单编号  #" . $res["id"] . " \n\n";
			$content .= "          " . $res3["dyj_title"] . "\n\n";
			$content .= "----------已在线支付------------\n";
			$content .= "--------------------------------";
			$content .= "下单时间：" . $res["time"] . "\n";
			$content .= "--------------------------------";
			$name = '';
			$i = 0;
			e6nDP:
			if (!($i < count($res2))) {
				$content .= "--------------------------------";
				$content .= "餐盒费：　　　　　　　　　　" . $res["box_fee"] . "\n";
				$content .= "--------------------------------";
				if (strlen($res["preferential"]) == 4) {
					$content .= "优惠：　　　　　　　　　　 -" . $res["preferential"] . "\n";
				}
				if (strlen($res["preferential"]) == 5) {
					$content .= "优惠：　　　　　　　　　　-" . $res["preferential"] . "\n";
				}
				$content .= "--------------------------------";
				$content .= "已付：　　　　　　　　　　　" . $res["money"] . "\n";
				$content .= "--------------------------------";
				$content .= "送货地点：" . $res["address"] . "\n";
				$content .= "联系电话：" . $res["tel"] . "\n";
				$content .= "联系人：" . $res["name"] . "\n";
				if ($res["note"]) {
					$content .= "备注：" . $res["note"] . "\n\n\n\n\n";
				}
				$selfMessage = array("deviceNo" => $res3["dyj_id"], "printContent" => $content, "key" => $res3["dyj_key"], "times" => "1");
				$url = "http://open.printcenter.cn:8080/addOrder";
				$options = array("http" => array("header" => "Content-type: application/x-www-form-urlencoded ", "method" => "POST", "content" => http_build_query($selfMessage)));
				$context = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
				return $result;
				goto IsYgW;
			}
			if (strlen($res2[$i]["name"]) > 18) {
				$name = substr($res2[$i]["name"], 0, 24) . "..";
				ArSRB:
				$content .= '' . $name . "    X" . $res2[$i]["number"] . "   " . number_format($res2[$i]["number"] * $res2[$i]["money"], 2) . "\n";
				$i++;
				goto e6nDP;
			}
			$name = $res2[$i]["name"];
			$k = 0;
			JViMF:
			if (!($k < 18 - ceil(strlen($res2[$i]["name"]) / 3 * 2))) {
				goto ArSRB;
			}
			$name .= " ";
			$k++;
			goto JViMF;
		}
		if (strlen($res2[$i]["name"]) > 18) {
			$name = substr($res2[$i]["name"], 0, 24) . "..";
			StaMb:
			$content .= '' . $name . "    X" . $res2[$i]["number"] . "   " . number_format($res2[$i]["number"] * $res2[$i]["money"], 2) . "\n";
			$i++;
			goto hd2M_;
		}
		$name = $res2[$i]["name"];
		$k = 0;
		VWzFW:
		if (!($k < 18 - ceil(strlen($res2[$i]["name"]) / 3 * 2))) {
			goto StaMb;
		}
		$name .= " ";
		$k++;
		goto VWzFW;
		nPrrl:
	}
	public function doPagePrint2()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_order", array("id" => $_GPC["order_id"]));
		$res3 = pdo_get("wpdc_dyj", array("uniacid" => $_W["uniacid"], "store_id" => $res["seller_id"], "state" => 1, "location" => 2));
		$res2 = pdo_getall("wpdc_goods", array("order_id" => $_GPC["order_id"]));
		$content = "\n\n";
		$content .= "         订单编号  #" . $res["id"] . " \n\n";
		$content .= "          " . $res3["dyj_title"] . "\n\n";
		$content .= "----------已在线支付------------\n";
		$content .= "--------------------------------";
		$content .= "下单时间：" . $res["time"] . "\n";
		$content .= "--------------------------------";
		$name = '';
		$i = 0;
		lWTOD:
		if (!($i < count($res2))) {
			$content .= "--------------------------------";
			$content .= "餐盒费：　　　　　　　　　 " . $res["box_fee"] . "\n";
			$content .= "--------------------------------";
			if (strlen($res["preferential"]) == 4) {
				$content .= "已优惠：　　　　　　　　　-" . $res["preferential"] . "\n";
			}
			if (strlen($res["preferential"]) == 5) {
				$content .= "已优惠：　　　　　　　　 -" . $res["preferential"] . "\n";
			}
			$content .= "--------------------------------";
			$content .= "已付：　　　　　　　　　　" . $res["money"] . "\n";
			$content .= "--------------------------------";
			$content .= "送货地点：" . $res["address"] . "\n";
			$content .= "联系电话：" . $res["tel"] . "\n";
			$content .= "联系人：" . $res["name"] . "\n";
			if ($res["note"]) {
				$content .= "备注：" . $res["note"] . "\n\n\n\n\n";
			}
			if (!($res3["type"] == 1)) {
				Az7Cl:
				if (!($res3["type"] == 2)) {
					goto Tm1Ng;
				}
				include "print.class.php";
				$print = new Yprint();
				$apiKey = $res3["api"];
				$msign = $res3["token"];
				$partner = $res3["yy_id"];
				$machine_code = $res3["mid"];
				$print->action_print($partner, $machine_code, $content, $apiKey, $msign);
				goto Tm1Ng;
			}
			$content = "\n\n\n\n\n";
			$content .= "          订单编号  #" . $res["id"] . " \n\n";
			$content .= "          " . $res3["dyj_title"] . "\n\n";
			$content .= "----------已在线支付------------\n";
			$content .= "--------------------------------";
			$content .= "下单时间：" . $res["time"] . "\n";
			$content .= "--------------------------------";
			$name = '';
			$i = 0;
			tnx1w:
			if (!($i < count($res2))) {
				$content .= "--------------------------------";
				$content .= "餐盒费：　　　　　　　　　　" . $res["box_fee"] . "\n";
				$content .= "--------------------------------";
				if (strlen($res["preferential"]) == 4) {
					$content .= "已优惠：　　　　　　　　　　-" . $res["preferential"] . "\n";
				}
				if (strlen($res["preferential"]) == 5) {
					$content .= "已优惠：　　　　　　　　　　-" . $res["preferential"] . "\n";
				}
				$content .= "--------------------------------";
				$content .= "已付：　　　　　　　　　　　" . $res["money"] . "\n";
				$content .= "--------------------------------";
				$content .= "送货地点：" . $res["address"] . "\n";
				$content .= "联系电话：" . $res["tel"] . "\n";
				$content .= "联系人：" . $res["name"] . "\n";
				if ($res["note"]) {
					$content .= "备注：" . $res["note"] . "\n\n\n\n\n";
				}
				$selfMessage = array("deviceNo" => $res3["dyj_id"], "printContent" => $content, "key" => $res3["dyj_key"], "times" => "1");
				$url = "http://open.printcenter.cn:8080/addOrder";
				$options = array("http" => array("header" => "Content-type: application/x-www-form-urlencoded ", "method" => "POST", "content" => http_build_query($selfMessage)));
				$context = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
				return $result;
				goto Az7Cl;
			}
			if (strlen($res2[$i]["name"]) > 18) {
				$name = substr($res2[$i]["name"], 0, 24) . "..";
				G5BXO:
				$content .= '' . $name . "    X" . $res2[$i]["number"] . "   " . number_format($res2[$i]["number"] * $res2[$i]["money"], 2) . "\n";
				$i++;
				goto tnx1w;
			}
			$name = $res2[$i]["name"];
			$k = 0;
			xw_DP:
			if (!($k < 18 - ceil(strlen($res2[$i]["name"]) / 3 * 2))) {
				goto G5BXO;
			}
			$name .= " ";
			$k++;
			goto xw_DP;
		}
		if (strlen($res2[$i]["name"]) > 18) {
			$name = substr($res2[$i]["name"], 0, 24) . "..";
			LKQgB:
			$content .= '' . $name . "    X" . $res2[$i]["number"] . "   " . number_format($res2[$i]["number"] * $res2[$i]["money"], 2) . "\n";
			$i++;
			goto lWTOD;
		}
		$name = $res2[$i]["name"];
		$k = 0;
		rtbsX:
		if (!($k < 18 - ceil(strlen($res2[$i]["name"]) / 3 * 2))) {
			goto LKQgB;
		}
		$name .= " ";
		$k++;
		goto rtbsX;
		Tm1Ng:
	}
	public function doPageDnPrint()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_order", array("id" => $_GPC["order_id"]));
		$res3 = pdo_get("wpdc_dyj", array("uniacid" => $_W["uniacid"], "store_id" => $res["seller_id"], "state" => 1, "location" => 1));
		$res2 = pdo_getall("wpdc_goods", array("order_id" => $_GPC["order_id"]));
		$table = pdo_get("wpdc_table", array("id" => $res["table_id"]));
		$content = "\n\n";
		$content .= "         订单编号  #" . $res["id"] . " \n\n";
		$content .= "          " . $res3["dyj_title"] . "\n\n";
		$content .= "----------" . $_GPC["pay_type"] . "--------------\n";
		$content .= "--------------------------------";
		$content .= "下单时间：" . $res["time"] . "\n";
		$content .= "--------------------------------";
		$name = '';
		$i = 0;
		xRqkK:
		if (!($i < count($res2))) {
			$content .= "--------------------------------";
			$content .= "合计：" . $res["money"] . "元\n";
			$content .= "订餐时间：" . $res["time"] . "\n";
			$content .= "桌号：" . $table["name"] . "\n";
			$content .= "支付方式：" . $_GPC["pay_type"] . "\n\n\n\n\n";
			if ($res3["type"] == 1) {
				$selfMessage = array("deviceNo" => $res3["dyj_id"], "printContent" => $content, "key" => $res3["dyj_key"], "times" => "1");
				$url = "http://open.printcenter.cn:8080/addOrder";
				$options = array("http" => array("header" => "Content-type: application/x-www-form-urlencoded", "method" => "POST", "content" => http_build_query($selfMessage)));
				$context = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
				return $result;
			}
			if ($res3["type"] == 2) {
				include "print.class.php";
				$print = new Yprint();
				$apiKey = $res3["api"];
				$msign = $res3["token"];
				$partner = $res3["yy_id"];
				$machine_code = $res3["mid"];
				$print->action_print($partner, $machine_code, $content, $apiKey, $msign);
			}
		} else {
			if (strlen($res2[$i]["name"]) > 18) {
				$name = substr($res2[$i]["name"], 0, 21) . "..";
				wGFD5:
				$content .= '' . $name . '' . $res2[$i]["money"] . "   " . $res2[$i]["number"] . "  " . $res2[$i]["number"] * $res2[$i]["money"] . "\n";
				$i++;
				goto xRqkK;
			}
			$name = $res2[$i]["name"];
			$k = 0;
			dqpHm:
			if (!($k < 15 - ceil(strlen($res2[$i]["name"]) / 3 * 2))) {
				goto wGFD5;
			}
			$name .= " ";
			$k++;
			goto dqpHm;
		}
	}
	public function doPageDnPrint2()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_order", array("id" => $_GPC["order_id"]));
		$res3 = pdo_get("wpdc_dyj", array("uniacid" => $_W["uniacid"], "store_id" => $res["seller_id"], "state" => 1, "location" => 2));
		$res2 = pdo_getall("wpdc_goods", array("order_id" => $_GPC["order_id"]));
		$table = pdo_get("wpdc_table", array("id" => $res["table_id"]));
		$content = "\n\n";
		$content .= "         订单编号  #" . $res["id"] . " \n\n";
		$content .= "        " . $res3["dyj_title"] . "\n\n";
		$content .= "----------" . $_GPC["pay_type"] . "--------------\n";
		$content .= "--------------------------------";
		$content .= "下单时间：" . $res["time"] . "\n";
		$content .= "--------------------------------";
		$name = '';
		$i = 0;
		bjrhN:
		if (!($i < count($res2))) {
			$content .= "--------------------------------";
			$content .= "合计：" . $res["money"] . "元\n";
			$content .= "订餐时间：" . $res["time"] . "\n";
			$content .= "桌号：" . $table["name"] . "\n";
			$content .= "支付方式：" . $_GPC["pay_type"] . "\n\n\n\n\n";
			if ($res3["type"] == 1) {
				$selfMessage = array("deviceNo" => $res3["dyj_id"], "printContent" => $content, "key" => $res3["dyj_key"], "times" => "1");
				$url = "http://open.printcenter.cn:8080/addOrder";
				$options = array("http" => array("header" => "Content-type: application/x-www-form-urlencoded", "method" => "POST", "content" => http_build_query($selfMessage)));
				$context = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
				return $result;
			}
			if ($res3["type"] == 2) {
				include "print.class.php";
				$print = new Yprint();
				$apiKey = $res3["api"];
				$msign = $res3["token"];
				$partner = $res3["yy_id"];
				$machine_code = $res3["mid"];
				$print->action_print($partner, $machine_code, $content, $apiKey, $msign);
			}
		} else {
			if (strlen($res2[$i]["name"]) > 18) {
				$name = substr($res2[$i]["name"], 0, 21) . "..";
				PW8yo:
				$content .= '' . $name . '' . $res2[$i]["money"] . "   " . $res2[$i]["number"] . "  " . $res2[$i]["number"] * $res2[$i]["money"] . "\n";
				$i++;
				goto bjrhN;
			}
			$name = $res2[$i]["name"];
			$k = 0;
			HYZ2x:
			if (!($k < 15 - ceil(strlen($res2[$i]["name"]) / 3 * 2))) {
				goto PW8yo;
			}
			$name .= " ";
			$k++;
			goto HYZ2x;
		}
	}
	public function doPageWxapp()
	{
		global $_W, $_GPC;
		function getaccess_token($_W)
		{
			$res = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
			$appid = "wxa78792229b3293cc";
			$secret = "fce19e77d6fb1fda0785003398d4ab57";
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret . '';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$data = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($data, true);
			return $data["access_token"];
		}
		function set_msg($_W)
		{
			$access_token = getaccess_token($_W);
			$cardid = "pH-cKwjHcGt1dsn9lxBTmNpVMvsU";
			$post = "{\n    \"card_id\":\"pH-cKwjHcGt1dsn9lxBTmNpVMvsU\"\n  }";
			$url = "https://api.weixin.qq.com/card/get?access_token=" . $access_token . '';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		echo set_msg($_W);
	}
	public function doPageStoreType()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_storetype", array("uniacid" => $_W["uniacid"]), array(), '', "num asc");
		echo json_encode($res);
	}
	public function doPageStoreList()
	{
		global $_W, $_GPC;
		$where = " WHERE uniacid=:uniacid";
		if ($_GPC["type_id"]) {
			$where .= " and md_type = :md_type";
			$data[":md_type"] = $_GPC["type_id"];
		}
		$data[":uniacid"] = $_W["uniacid"];
		$pageindex = max(1, intval($_GPC["page"]));
		$pagesize = 10;
		$sql = "select *  from " . tablename("wpdc_store") . '' . $where . " order by number asc";
		$select_sql = $sql . " LIMIT " . ($pageindex - 1) * $pagesize . "," . $pagesize;
		$list = pdo_fetchall($select_sql, $data);
		echo json_encode($list);
	}
	public function doPageSearchStore()
	{
		global $_W, $_GPC;
		$op = $_GPC["key"];
		$where = "%{$op}%";
		$sql = "select *  from " . tablename("wpdc_store") . " WHERE  name LIKE :name || address LIKE :address and uniacid=:uniacid";
		$list = pdo_fetchall($sql, array(":name" => $where, ":address" => $where, "uniacid" => $_W["uniacid"]));
		echo json_encode($list);
	}
	public function doPageStoreListPf()
	{
		global $_W, $_GPC;
		if ($_GPC["type_id"]) {
			$res = pdo_getall("wpdc_store", array("uniacid" => $_W["uniacid"], "md_type" => $_GPC["type_id"]), array(), '', "score DESC");
			echo json_encode($res);
		} else {
			$res = pdo_getall("wpdc_store", array("uniacid" => $_W["uniacid"]), array(), '', "score DESC");
			echo json_encode($res);
		}
	}
	public function doPageStoreListXl()
	{
		global $_W, $_GPC;
		if ($_GPC["type_id"]) {
			$res = pdo_getall("wpdc_store", array("uniacid" => $_W["uniacid"], "md_type" => $_GPC["type_id"]), array(), '', "sales DESC");
			echo json_encode($res);
		} else {
			$res = pdo_getall("wpdc_store", array("uniacid" => $_W["uniacid"]), array(), '', "sales DESC");
			echo json_encode($res);
		}
	}
	public function doPageStoreListJl()
	{
		global $_W, $_GPC;
		echo asort($_GPC["sz"]);
	}
	public function doPageReduction()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_reduction", array("store_id" => $_GPC["id"]), array(), '', "full DESC");
		echo json_encode($res);
	}
	public function doPageTuik()
	{
		global $_W, $_GPC;
		$res = pdo_update("wpdc_order", array("state" => 7), array("id" => $_GPC["order_id"]));
		if ($res) {
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageEmail()
	{
		global $_W, $_GPC;
		$row = pdo_get("uni_settings", array("notify !=" => ''), array("notify"));
		$row["notify"] = @iunserializer($row["notify"]);
		if (!empty($row["notify"]) && !empty($row["notify"]["mail"])) {
			$config = $row["notify"]["mail"];
		}
		function ihttp_email2($to, $subject, $body, $global = false)
		{
			global $_W, $_GPC;
			$system = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
			static $mailer;
			set_time_limit(0);
			if (empty($mailer)) {
				if (!class_exists("PHPMailer")) {
					load()->library("phpmailer");
				}
				$mailer = new PHPMailer();
				global $_W;
				$config = $GLOBALS["_W"]["setting"]["mail"];
				$config = array("username" => $system["username"], "password" => $system["password"], "smtp" => array("type" => $system["type"], "server" => '', "port" => '', "authmode" => 0), "sender" => $system["sender"], "signature" => $system["signature"]);
				$config["charset"] = "utf-8";
				if ($config["smtp"]["type"] == "163") {
					$config["smtp"]["server"] = "smtp.163.com";
					$config["smtp"]["port"] = 25;
				} else {
					if ($config["smtp"]["type"] == "qq") {
						$config["smtp"]["server"] = "ssl://smtp.qq.com";
						$config["smtp"]["port"] = 465;
					} else {
						if (!empty($config["smtp"]["authmode"])) {
							$config["smtp"]["server"] = "ssl://" . $config["smtp"]["server"];
						}
					}
				}
				if (!empty($config["smtp"]["authmode"])) {
					if (!extension_loaded("openssl")) {
						return error(1, "请开启 php_openssl 扩展！");
					}
				}
				$mailer->signature = $config["signature"];
				$mailer->isSMTP();
				$mailer->CharSet = $config["charset"];
				$mailer->Host = $config["smtp"]["server"];
				$mailer->Port = $config["smtp"]["port"];
				$mailer->SMTPAuth = true;
				$mailer->Username = $config["username"];
				$mailer->Password = $config["password"];
				!empty($config["smtp"]["authmode"]) && ($mailer->SMTPSecure = "ssl");
				$mailer->From = $config["username"];
				$mailer->FromName = $config["sender"];
				$mailer->isHTML(true);
			}
			if ($body) {
				if (is_array($body)) {
					$body = '';
					foreach ($body as $value) {
						if (substr($value, 0, 1) == "@") {
							if (!is_file($file = ltrim($value, "@"))) {
								return error(1, $file . " 附件不存在或非文件！");
							}
							$mailer->addAttachment($file);
						} else {
							$body .= $value . "\\n";
						}
					}
				} else {
					if (substr($body, 0, 1) == "@") {
						$mailer->addAttachment(ltrim($body, "@"));
						$body = '';
					}
				}
			}
			if (!empty($mailer->signature)) {
				$body .= htmlspecialchars_decode($mailer->signature);
			}
			$mailer->Subject = $subject;
			$mailer->Body = $body;
			$mailer->addAddress($to);
			if ($mailer->send()) {
				return true;
			} else {
				return error(1, $mailer->ErrorInfo);
			}
		}
		$store_id = $_GPC["store_id"];
		$store = pdo_get("wpdc_sms", array("store_id" => $store_id));
		$body = "您有新的" . $_GPC["type"] . "订单,请去后台处理!<br><br>";
		$result = ihttp_email2($store["email"], "订单通知", $body);
		print_r($result);
	}
	public function doPageDmpay()
	{
		global $_W, $_GPC;
		$system = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
		$data["money"] = $_GPC["money"];
		$data["store_id"] = $_GPC["store_id"];
		$data["uniacid"] = $_W["uniacid"];
		$data["time"] = date("Y-m-d H:i:s");
		$res = pdo_insert("wpdc_dmorder", $data);
		if ($res) {
			$set = pdo_get("wpdc_fxset", array("uniacid" => $_W["uniacid"]));
			$order = pdo_get("wpdc_dmorder", array("id" => $_GPC["id"]));
			if ($set["is_open"] == 1) {
				if ($set["is_ej"] == 2) {
					$user = pdo_get("wpdc_fxuser", array("fx_user" => $_GPC["user_id"]));
					if ($user) {
						$userid = $user["user_id"];
						$money = $order["money"] * ($set["commission"] / 100);
						pdo_update("wpdc_user", array("commission +=" => $money), array("id" => $userid));
						$data2["user_id"] = $userid;
						$data2["son_id"] = $_GPC["user_id"];
						$data2["money"] = $money;
						$data2["time"] = time();
						$data2["uniacid"] = $_W["uniacid"];
						pdo_insert("wpdc_earnings", $data2);
					}
				} else {
					$user = pdo_get("wpdc_fxuser", array("fx_user" => $_GPC["user_id"]));
					$user2 = pdo_get("wpdc_fxuser", array("fx_user" => $user["user_id"]));
					if ($user) {
						$userid = $user["user_id"];
						$money = $order["money"] * ($set["commission"] / 100);
						pdo_update("wpdc_user", array("commission +=" => $money), array("id" => $userid));
						$data2["user_id"] = $userid;
						$data2["son_id"] = $_GPC["user_id"];
						$data2["money"] = $money;
						$data2["time"] = time();
						$data2["uniacid"] = $_W["uniacid"];
						pdo_insert("wpdc_earnings", $data2);
					}
					if ($user2) {
						$userid2 = $user2["user_id"];
						$money = $order["money"] * ($set["commission2"] / 100);
						pdo_update("wpdc_user", array("commission +=" => $money), array("id" => $userid2));
						$data3["user_id"] = $userid2;
						$data3["son_id"] = $_GPC["user_id"];
						$data3["money"] = $money;
						$data3["time"] = time();
						$data3["uniacid"] = $_W["uniacid"];
						pdo_insert("wpdc_earnings", $data3);
					}
				}
			}
			if ($system["integral2"] > 0 and $system["is_jf"] == 1) {
				$jifen = round($system["integral2"] / 100 * $_GPC["money"]);
				pdo_update("wpdc_user", array("total_score +=" => $jifen), array("id" => $_GPC["user_id"]));
				$data5["score"] = $jifen;
				$data5["user_id"] = $_GPC["user_id"];
				$data5["note"] = "当面付消费";
				$data5["type"] = 1;
				$data5["cerated_time"] = date("Y-m-d H:i:s");
				$data5["uniacid"] = $_W["uniacid"];
				pdo_insert("wpdc_integral", $data5);
			}
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageStoreOrder()
	{
		global $_W, $_GPC;
		$store_id = $_GPC["store_id"];
		$res = pdo_getall("wpdc_order", array("seller_id" => $_GPC["store_id"], "type" => 1, "del" => 2), array(), '', "id DESC");
		$res2 = pdo_getall("wpdc_goods");
		$data2 = array();
		$i = 0;
		vZAt2:
		if (!($i < count($res))) {
			echo json_encode($data2);
		} else {
			$data = array();
			$k = 0;
			mzSMk:
			if (!($k < count($res2))) {
				$data2[] = array("order" => $res[$i], "goods" => $data);
				$i++;
				goto vZAt2;
			}
			if ($res[$i]["id"] == $res2[$k]["order_id"]) {
				$data[] = array("name" => $res2[$k]["name"], "num" => $res2[$k]["number"], "img" => $res2[$k]["img"], "money" => $res2[$k]["money"], "dishes_id" => $res2[$k]["dishes_id"]);
			}
			$k++;
			goto mzSMk;
		}
	}
	public function doPageWmSale()
	{
		global $_W, $_GPC;
		$time = date("Y-m-d");
		$time = "'%{$time}%'";
		$storeid = $_GPC["store_id"];
		$wm = "select sum(money) as total from " . tablename("wpdc_order") . " WHERE time LIKE " . $time . " and seller_id=" . $storeid . " and state not in (5,1,8) and type=1";
		$wm = pdo_fetch($wm);
		echo $wm["total"];
	}
	public function doPageWmSale2()
	{
		global $_W, $_GPC;
		$time = date("Y-m-d", time() - 24 * 60 * 60);
		$time = "'%{$time}%'";
		$storeid = $_GPC["store_id"];
		$wm = "select sum(money) as total from " . tablename("wpdc_order") . " WHERE time LIKE " . $time . " and seller_id=" . $storeid . " and state not in (5,1,8) and type=1";
		$wm = pdo_fetch($wm);
		echo $wm["total"];
	}
	public function doPageWmSale3()
	{
		global $_W, $_GPC;
		$storeid = $_GPC["store_id"];
		$wm = "select sum(money) as total from " . tablename("wpdc_order") . " WHERE seller_id=" . $storeid . " and state not in (5,1,8) and type=1";
		$wm = pdo_fetch($wm);
		echo $wm["total"];
	}
	public function doPageWmOrder()
	{
		global $_W, $_GPC;
		$time = date("Y-m-d");
		$time = "'%{$time}%'";
		$storeid = $_GPC["store_id"];
		$wm = "select * from " . tablename("wpdc_order") . " WHERE time LIKE " . $time . " and seller_id=" . $storeid . "  and type=1";
		$wm = pdo_fetchall($wm);
		echo count($wm);
	}
	public function doPageWmOrder2()
	{
		global $_W, $_GPC;
		$time = date("Y-m-d");
		$time = "'%{$time}%'";
		$storeid = $_GPC["store_id"];
		$wm = "select * from " . tablename("wpdc_order") . " WHERE time LIKE " . $time . " and seller_id=" . $storeid . "  and type=1 and state in (6,4)";
		$wm = pdo_fetchall($wm);
		echo count($wm);
	}
	public function doPageJieOrder()
	{
		global $_W, $_GPC;
		$data2["state"] = 3;
		$res = pdo_update("wpdc_order", $data2, array("id" => $_GPC["order_id"]));
		if ($res) {
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageTg()
	{
		global $_W, $_GPC;
		$id = $_GPC["order_id"];
		include_once IA_ROOT . "/addons/zh_dianc/cert/WxPay.Api.php";
		load()->model("account");
		load()->func("communication");
		$WxPayApi = new WxPayApi();
		$input = new WxPayRefund();
		$path_cert = IA_ROOT . "/addons/zh_dianc/cert/" . "apiclient_cert_" . $_W["uniacid"] . ".pem";
		$path_key = IA_ROOT . "/addons/zh_dianc/cert/" . "apiclient_key_" . $_W["uniacid"] . ".pem";
		$account_info = $_W["account"];
		$refund_order = pdo_get("wpdc_order", array("id" => $id));
		$res = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
		$appid = $res["appid"];
		$key = $res["wxkey"];
		$mchid = $res["mchid"];
		$out_trade_no = $refund_order["sh_ordernum"];
		$fee = $refund_order["money"] * 100;
		$input->SetAppid($appid);
		$input->SetMch_id($mchid);
		$input->SetOp_user_id($mchid);
		$input->SetRefund_fee($fee);
		$input->SetTotal_fee($fee);
		$input->SetOut_refund_no($id);
		$input->SetOut_trade_no($out_trade_no);
		$result = $WxPayApi->refund($input, 6, $path_cert, $path_key, $key);
		if ($result["result_code"] == "SUCCESS") {
			pdo_update("wpdc_order", array("state" => 8), array("id" => $id));
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageJj()
	{
		global $_W, $_GPC;
		$id = $_GPC["order_id"];
		$res = pdo_update("wpdc_order", array("state" => 9), array("id" => $_GPC["id"]));
		if ($res) {
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageDel()
	{
		global $_W, $_GPC;
		$res = pdo_update("wpdc_order", array("del" => 1), array("id" => $_GPC["order_id"]));
		if ($res) {
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageClose()
	{
		global $_W, $_GPC;
		$res = pdo_update("wpdc_store", array("is_rest" => 1), array("id" => $_GPC["store_id"]));
		if ($res) {
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageOpen()
	{
		global $_W, $_GPC;
		$res = pdo_update("wpdc_store", array("is_rest" => 2), array("id" => $_GPC["store_id"]));
		if ($res) {
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageNewOrder()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_order", array("state" => 2, "seller_id" => $_GPC["store_id"]));
		if ($res) {
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageTraffic()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_traffic", array("time" => date("Y-m-d"), "store_id" => $_GPC["store_id"]));
		echo count($res);
	}
	public function doPageStoreCode()
	{
		global $_W, $_GPC;
		function getCoade($storeid)
		{
			function getaccess_token()
			{
				global $_W, $_GPC;
				$res = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
				$appid = $res["appid"];
				$secret = $res["appsecret"];
				$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret . '';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$data = curl_exec($ch);
				curl_close($ch);
				$data = json_decode($data, true);
				return $data["access_token"];
			}
			function set_msg($storeid)
			{
				$access_token = getaccess_token();
				$data2 = array("scene" => $storeid, "page" => "zh_dianc/pages/info/info", "width" => 400);
				$data2 = json_encode($data2);
				$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $access_token . '';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data2);
				$data = curl_exec($ch);
				curl_close($ch);
				return $data;
			}
			$img = set_msg($storeid);
			$img = base64_encode($img);
			return $img;
		}
		$base64_image_content = "data:image/jpeg;base64," . getCoade($_GPC["store_id"]);
		if (preg_match("/^(data:\\s*image\\/(\\w+);base64,)/", $base64_image_content, $result)) {
			$type = $result[2];
			$new_file = IA_ROOT . "/addons/zh_dianc/img/";
			if (!file_exists($new_file)) {
				mkdir($new_file, 511);
			}
			$wname = "{$_GPC["store_id"]}" . ".{$type}";
			$new_file = $new_file . $wname;
			file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)));
		}
		$size = 60;
		$font = IA_ROOT . "/addons/zh_dianc/img/simhei.ttf";
		$res = pdo_get("wpdc_store", array("id" => $_GPC["store_id"]));
		if ($res["hb_img"]) {
			$img = $_W["attachurl"] . $res["hb_img"];
		} else {
			$img = IA_ROOT . "/addons/zh_dianc/tu.jpg";
		}
		$pathname = IA_ROOT . "/addons/zh_dianc/img/{$_GPC["store_id"]}.jpg";
		$store = pdo_get("wpdc_store", array("id" => $_GPC["store_id"]));
		$text = $store["name"];
		$len = mb_strlen($text, "UTF-8");
		$left_x = (640 - $len * 15) / 2;
		$img = imagecreatefromjpeg($img);
		$black = imagecolorallocate($img, 255, 255, 255);
		imagettftext($img, $size, 0, $left_x, 1380, $black, $font, $text);
		header("Content-Type: image/png");
		ImagePNG($img, $pathname);
		imagedestroy($img);
		$bg_image = $pathname;
		$sub_image = $new_file;
		$add_x = 240;
		$add_y = 459;
		$add_w = 600;
		$add_h = 600;
		$out_image = IA_ROOT . "/addons/zh_dianc/img2/{$_GPC["store_id"]}.jpg";
		if ($sub_image) {
			$bg_image_c = imagecreatefromstring(file_get_contents($bg_image));
			$sub_image_c = imagecreatefromstring(file_get_contents($sub_image));
			imagecopyresampled($bg_image_c, $sub_image_c, $add_x, $add_y, 0, 0, $add_w, $add_h, imagesx($sub_image_c), imagesy($sub_image_c));
			imagejpeg($bg_image_c, $out_image, 100);
			imagedestroy($sub_image_c);
			imagedestroy($bg_image_c);
		}
	}
	public function doPageJftype()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_jftype", array("uniacid" => $_W["uniacid"]), array(), '', "num asc");
		echo json_encode($res);
	}
	public function doPageJfGoods()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_jfgoods", array("uniacid" => $_W["uniacid"]), array(), '', "num asc");
		echo json_encode($res);
	}
	public function doPageJfGoodsInfo()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_jfgoods", array("id" => $_GPC["id"]));
		echo json_encode($res);
	}
	public function doPageJftypeGoods()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_jfgoods", array("type_id" => $_GPC["type_id"]), array(), '', "num asc");
		echo json_encode($res);
	}
	public function doPageAd3()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_ad", array("uniacid" => $_W["uniacid"], "status" => 1, "type" => 3), array(), '', "orderby asc");
		echo json_encode($res);
	}
	public function doPageExchange()
	{
		global $_W, $_GPC;
		$data["user_id"] = $_GPC["user_id"];
		$data["good_id"] = $_GPC["good_id"];
		$data["user_name"] = $_GPC["user_name"];
		$data["user_tel"] = $_GPC["user_tel"];
		$data["address"] = $_GPC["address"];
		$data["integral"] = $_GPC["integral"];
		$data["good_name"] = $_GPC["good_name"];
		$data["good_img"] = $_GPC["good_img"];
		$data["time"] = date("Y-m-d H:i:s");
		$res = pdo_insert("wpdc_jfrecord", $data);
		if ($res) {
			pdo_update("wpdc_jfgoods", array("number -=" => 1), array("id" => $_GPC["good_id"]));手机
			if ($_GPC["type"] == 1) {
				pdo_update("wpdc_user", array("wallet +=" => $_GPC["hb_money"]), array("id" => $_GPC["user_id"]));
				$data2["money"] = $_GPC["hb_money"];
				$data2["user_id"] = $_GPC["user_id"];
				$data2["type"] = 1;
				$data2["note"] = "积分兑换";
				$data2["time"] = date("Y-m-d H:i:s");
				pdo_insert("wpdc_qbmx", $data2);
			}
			$data3["score"] = $_GPC["integral"];
			$data3["user_id"] = $_GPC["user_id"];
			$data3["note"] = "兑换商品";
			$data3["type"] = 2;
			$data3["cerated_time"] = date("Y-m-d H:i:s");
			$data3["uniacid"] = $_W["uniacid"];
			pdo_insert("wpdc_integral", $data3);
			pdo_update("wpdc_user", array("total_score -=" => $_GPC["integral"]), array("id" => $_GPC["user_id"]));
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageDhmx()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_jfrecord", array("user_id" => $_GPC["user_id"]), array(), '', "id DESC");
		echo json_encode($res);
	}
	public function doPageQbmx()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_qbmx", array("user_id" => $_GPC["user_id"]), array(), '', "id DESC");
		echo json_encode($res);
	}
	public function doPageUserInfo()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_user", array("id" => $_GPC["user_id"]));
		echo json_encode($res);
	}
	public function doPageCzhd()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_czhd", array("uniacid" => $_W["uniacid"]), array(), '', "full DESC");
		echo json_encode($res);
	}
	public function doPageRecharge()
	{
		global $_W, $_GPC;
		$res = pdo_update("wpdc_user", array("wallet +=" => $_GPC["money"]), array("id" => $_GPC["user_id"]));
		if ($res) {
			$data["money"] = $_GPC["money"];
			$data["user_id"] = $_GPC["user_id"];
			$data["type"] = 1;
			$data["note"] = "钱包充值";
			$data["time"] = date("Y-m-d H:i:s");
			$res2 = pdo_insert("wpdc_qbmx", $data);
			if ($res2) {
				echo "1";
			} else {
				echo "2";
			}
		}
	}
	public function doPageRuZhu()
	{
		global $_W, $_GPC;
		$data["store_name"] = $_GPC["store_name"];
		$data["tel"] = $_GPC["tel"];
		$data["user_name"] = $_GPC["user_name"];
		$data["img"] = $_GPC["img"];
		$data["state"] = 1;
		$data["user_id"] = $_GPC["user_id"];
		$data["address"] = $_GPC["address"];
		$data["time"] = time();
		$data["uniacid"] = $_W["uniacid"];
		$res = pdo_insert("wpdc_ruzhu", $data);
		if ($res) {
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageMyRuZhu()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_ruzhu", array("user_id" => $_GPC["user_id"]));
		echo json_encode($res);
	}
	public function doPageDistribution()
	{
		global $_W, $_GPC;
		pdo_delete("wpdc_distribution", array("user_id" => $_GPC["user_id"]));
		$data["user_id"] = $_GPC["user_id"];
		$data["user_name"] = $_GPC["user_name"];
		$data["user_tel"] = $_GPC["user_tel"];
		$data["time"] = time();
		$data["state"] = 1;
		$data["uniacid"] = $_W["uniacid"];
		$res = pdo_insert("wpdc_distribution", $data);
		if ($res) {
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageMyDistribution()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_distribution", array("user_id" => $_GPC["user_id"]));
		echo json_encode($res);
	}
	public function doPageFxSet()
	{
		global $_W, $_GPC;
		$res = pdo_get("wpdc_fxset", array("uniacid" => $_W["uniacid"]));
		echo json_encode($res);
	}
	public function doPageMySx()
	{
		global $_W, $_GPC;
		$sql = "select a.* ,b.name from " . tablename("wpdc_fxuser") . " a" . " left join " . tablename("wpdc_user") . " b on b.id=a.user_id   WHERE a.fx_user=:fx_user ";
		$res = pdo_fetch($sql, array(":fx_user" => $_GPC["user_id"]));
		echo json_encode($res);
	}
	public function doPageEarnings()
	{
		global $_W, $_GPC;
		$sql = "select a.* ,b.name,b.img from " . tablename("wpdc_earnings") . " a" . " left join " . tablename("wpdc_user") . " b on b.id=a.son_id   WHERE a.user_id=:user_id order by id DESC";
		$res = pdo_fetchall($sql, array(":user_id" => $_GPC["user_id"]));
		echo json_encode($res);
	}
	public function doPageMyCode()
	{
		global $_W, $_GPC;
		function getCoade($storeid)
		{
			function getaccess_token()
			{
				global $_W, $_GPC;
				$res = pdo_get("wpdc_system", array("uniacid" => $_W["uniacid"]));
				$appid = $res["appid"];
				$secret = $res["appsecret"];
				$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret . '';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$data = curl_exec($ch);
				curl_close($ch);
				$data = json_decode($data, true);
				return $data["access_token"];
			}
			function set_msg($storeid)
			{
				$access_token = getaccess_token();
				$data2 = array("scene" => $storeid, "width" => 400);
				$data2 = json_encode($data2);
				$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $access_token . '';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data2);
				$data = curl_exec($ch);
				curl_close($ch);
				return $data;
			}
			$img = set_msg($storeid);
			$img = base64_encode($img);
			return $img;
		}
		echo getCoade($_GPC["user_id"]);
	}
	public function doPageYjtx()
	{
		global $_W, $_GPC;
		$data["user_id"] = $_GPC["user_id"];
		$data["type"] = $_GPC["type"];
		$data["user_name"] = $_GPC["user_name"];
		$data["account"] = $_GPC["account"];
		$data["tx_cost"] = $_GPC["tx_cost"];
		$data["sj_cost"] = $_GPC["sj_cost"];
		$data["state"] = 1;
		$data["time"] = time();
		$data["uniacid"] = $_W["uniacid"];
		$res = pdo_insert("wpdc_commission_withdrawal", $data);
		if ($res) {
			pdo_update("wpdc_user", array("commission -=" => $_GPC["tx_cost"]), array("id" => $_GPC["user_id"]));
			echo "1";
		} else {
			echo "2";
		}
	}
	public function doPageYjtxList()
	{
		global $_W, $_GPC;
		$res = pdo_getall("wpdc_commission_withdrawal", array("user_id" => $_GPC["user_id"]), array(), '', "id DESC");
		echo json_encode($res);
	}
	public function doPageBinding()
	{
		global $_W, $_GPC;
		$set = pdo_get("wpdc_fxset", array("uniacid" => $_W["uniacid"]));
		$res = pdo_get("wpdc_fxuser", array("fx_user" => $_GPC["fx_user"]));
		$res2 = pdo_get("wpdc_fxuser", array("user_id" => $_GPC["fx_user"], "fx_user" => $_GPC["user_id"]));
		if ($set["is_open"] == 1) {
			if ($_GPC["user_id"] == $_GPC["fx_user"]) {
				echo "自己不能绑定自己";
			} else {
				if ($res || $res2) {
					echo "不能重复绑定";
				} else {
					$res3 = pdo_insert("wpdc_fxuser", array("user_id" => $_GPC["user_id"], "fx_user" => $_GPC["fx_user"], "time" => time()));
					if ($res3) {
						echo "1";
					} else {
						echo "2";
					}
				}
			}
		}
	}
	public function doPageMyTeam()
	{
		global $_W, $_GPC;
		$sql = "select a.* ,b.name,b.img from " . tablename("wpdc_fxuser") . " a" . " left join " . tablename("wpdc_user") . " b on b.id=a.fx_user   WHERE a.user_id=:user_id order by id DESC";
		$res = pdo_fetchall($sql, array(":user_id" => $_GPC["user_id"]));
		$res2 = array();
		$i = 0;
		lcn96:
		if (!($i < count($res))) {
			$res4 = array();
			$k = 0;
			BROi_:
			if (!($k < count($res2))) {
				$data["one"] = $res;
				$data["two"] = $res4;
				echo json_encode($data);
				goto oGzme;
			}
			$j = 0;
			bRDzj:
			if (!($j < count($res2[$k]))) {
				$k++;
				goto BROi_;
			}
			$res4[] = $res2[$k][$j];
			$j++;
			goto bRDzj;
		}
		$sql2 = "select a.* ,b.name,b.img from " . tablename("wpdc_fxuser") . " a" . " left join " . tablename("wpdc_user") . " b on b.id=a.fx_user   WHERE a.user_id=:user_id order by id DESC";
		$res3 = pdo_fetchall($sql2, array(":user_id" => $res[$i]["fx_user"]));
		$res2[] = $res3;
		$i++;
		goto lcn96;
		oGzme:
	}
	public function doPageMyCommission()
	{
		global $_W, $_GPC;
		$system = pdo_get("wpdc_fxset", array("uniacid" => $_W["uniacid"]));
		$user = pdo_get("wpdc_user", array("id" => $_GPC["user_id"]));
		if ($user["commission"] < $system["tx_money"]) {
			$ke = 0.0;
		} else {
			$ke = $user["commission"];
		}
		$sq = "select sum(tx_cost) as tx_cost from " . tablename("wpdc_commission_withdrawal") . " WHERE  user_id=" . $_GPC["user_id"];
		$sq = pdo_fetch($sq);
		$sq = $sq["tx_cost"];
		$cg = "select sum(tx_cost) as tx_cost from " . tablename("wpdc_commission_withdrawal") . " WHERE  state=2 and user_id=" . $_GPC["user_id"];
		$cg = pdo_fetch($cg);
		$cg = $cg["tx_cost"];
		$lei = "select sum(money) as tx_cost from " . tablename("wpdc_earnings") . " WHERE  user_id=" . $_GPC["user_id"];
		$lei = pdo_fetch($lei);
		$lei = $lei["tx_cost"];
		$data["ke"] = $ke;
		$data["sq"] = $sq;
		$data["cg"] = $cg;
		$data["lei"] = $lei;
		echo json_encode($data);
	}
}