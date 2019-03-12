<?php

$installSql = <<<sql

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_wpdc_account`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_account`;
CREATE TABLE `ims_wpdc_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `storeid` varchar(1000) NOT NULL,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `from_user` varchar(100) NOT NULL DEFAULT '',
  `accountname` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(200) NOT NULL DEFAULT '',
  `salt` varchar(10) NOT NULL DEFAULT '',
  `pwd` varchar(50) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pay_account` varchar(200) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '状态',
  `role` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1:店长,2:店员',
  `lastvisit` int(10) unsigned NOT NULL DEFAULT '0',
  `lastip` varchar(15) NOT NULL,
  `areaid` int(10) NOT NULL DEFAULT '0' COMMENT '区域id',
  `is_admin_order` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_notice_order` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_notice_queue` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_notice_service` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_notice_boss` tinyint(1) NOT NULL DEFAULT '0',
  `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_account
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_ad`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_ad`;
CREATE TABLE `ims_wpdc_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logo` varchar(300) NOT NULL COMMENT '图片',
  `src` varchar(300) NOT NULL COMMENT '链接地址',
  `uniacid` varchar(50) NOT NULL,
  `created_time` datetime NOT NULL COMMENT '创建时间',
  `orderby` int(4) NOT NULL COMMENT '排序',
  `status` int(4) NOT NULL COMMENT '状态1.启用，2禁用',
  `type` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `appid` varchar(30) NOT NULL,
  `xcx_name` varchar(30) NOT NULL,
  `title` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广告表';

-- ----------------------------
-- Records of ims_wpdc_ad
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_area`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_area`;
CREATE TABLE `ims_wpdc_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_name` varchar(20) NOT NULL COMMENT '区域名称',
  `num` int(11) NOT NULL COMMENT '排序',
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='门店区域';

-- ----------------------------
-- Records of ims_wpdc_area
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_assess`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_assess`;
CREATE TABLE `ims_wpdc_assess` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL COMMENT '商家ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `order_num` varchar(30) NOT NULL COMMENT '订单号',
  `score` int(11) NOT NULL COMMENT '分数',
  `content` text NOT NULL COMMENT '评价内容',
  `img` varchar(1000) NOT NULL COMMENT '图片',
  `cerated_time` datetime NOT NULL COMMENT '创建时间',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `uniacid` varchar(50) NOT NULL,
  `reply` varchar(1000) NOT NULL COMMENT '商家回复',
  `status` int(4) NOT NULL COMMENT '评价状态1，未回复，2已回复',
  `reply_time` datetime NOT NULL COMMENT '回复时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_assess
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_commission_withdrawal`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_commission_withdrawal`;
CREATE TABLE `ims_wpdc_commission_withdrawal` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1.支付宝2.银行卡',
  `state` int(11) NOT NULL COMMENT '1.审核中2.通过3.拒绝',
  `time` int(11) NOT NULL COMMENT '申请时间',
  `sh_time` int(11) NOT NULL COMMENT '审核时间',
  `uniacid` int(11) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `account` varchar(100) NOT NULL,
  `tx_cost` decimal(10,2) NOT NULL COMMENT '提现金额',
  `sj_cost` decimal(10,2) NOT NULL COMMENT '实际到账金额',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_commission_withdrawal
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_continuous`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_continuous`;
CREATE TABLE `ims_wpdc_continuous` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `day` int(11) NOT NULL COMMENT '天数',
  `integral` int(11) NOT NULL COMMENT '积分',
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_continuous
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_coupons`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_coupons`;
CREATE TABLE `ims_wpdc_coupons` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '名称',
  `start_time` varchar(20) NOT NULL COMMENT '开始时间',
  `end_time` varchar(20) NOT NULL COMMENT '结束时间',
  `conditions` varchar(10) NOT NULL COMMENT '条件',
  `preferential` varchar(10) NOT NULL COMMENT '优惠',
  `uniacid` varchar(50) NOT NULL,
  `coupons_type` int(4) NOT NULL COMMENT '使用类型1:外卖，2店内,3都可使用',
  `instruction` varchar(300) NOT NULL COMMENT '使用说明',
  `store_id` int(11) NOT NULL COMMENT '商家id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_coupons
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_czhd`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_czhd`;
CREATE TABLE `ims_wpdc_czhd` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `full` int(11) NOT NULL,
  `reduction` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_czhd
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_dishes`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_dishes`;
CREATE TABLE `ims_wpdc_dishes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `img` varchar(500) NOT NULL COMMENT '菜品图片',
  `num` int(11) NOT NULL COMMENT '数量',
  `money` decimal(10,2) NOT NULL,
  `type_id` int(11) NOT NULL COMMENT '分类id',
  `signature` int(11) NOT NULL COMMENT '1是  2否 招牌',
  `one` int(11) NOT NULL,
  `uniacid` varchar(50) NOT NULL,
  `xs_num` int(11) NOT NULL COMMENT '销售数量',
  `sit_ys_num` int(11) NOT NULL COMMENT '设置月销售数量',
  `is_shelves` int(4) NOT NULL COMMENT '是否上架1是,2否',
  `dishes_type` int(4) NOT NULL COMMENT '菜品类型，2为店内，1为外卖',
  `box_fee` decimal(10,2) NOT NULL,
  `wm_money` decimal(10,2) NOT NULL,
  `details` text NOT NULL COMMENT '描述',
  `sorting` int(11) NOT NULL COMMENT '排序',
  `store_id` int(11) NOT NULL COMMENT '商家id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_dishes
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_distribution`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_distribution`;
CREATE TABLE `ims_wpdc_distribution` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `user_tel` varchar(20) NOT NULL,
  `state` int(11) NOT NULL COMMENT '1.审核中2.通过3.拒绝',
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_distribution
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_dmorder`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_dmorder`;
CREATE TABLE `ims_wpdc_dmorder` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `money` decimal(10,2) NOT NULL,
  `store_id` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `time` varchar(20) NOT NULL,
  `time2` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_yue` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_dmorder
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_dyj`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_dyj`;
CREATE TABLE `ims_wpdc_dyj` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dyj_title` varchar(50) NOT NULL COMMENT '打印机标题',
  `dyj_id` varchar(50) NOT NULL COMMENT '打印机编号',
  `dyj_key` varchar(50) NOT NULL COMMENT '打印机key',
  `uniacid` varchar(50) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1.365  2.易联云',
  `name` varchar(20) NOT NULL COMMENT '打印机名称',
  `mid` varchar(100) NOT NULL COMMENT '打印机终端号',
  `api` varchar(100) NOT NULL COMMENT 'API密钥',
  `store_id` int(11) NOT NULL,
  `state` int(11) NOT NULL COMMENT '1开启2关闭',
  `location` int(11) NOT NULL COMMENT '1..前台 2后厨',
  `yy_id` varchar(20) NOT NULL COMMENT '用户id',
  `token` varchar(50) NOT NULL COMMENT '打印机终端密钥',
  `dyj_title2` varchar(50) NOT NULL,
  `dyj_id2` varchar(50) NOT NULL,
  `dyj_key2` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_dyj
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_earnings`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_earnings`;
CREATE TABLE `ims_wpdc_earnings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `son_id` int(11) NOT NULL COMMENT '下线',
  `money` decimal(10,2) NOT NULL,
  `time` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_earnings
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_fxset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_fxset`;
CREATE TABLE `ims_wpdc_fxset` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fx_details` text NOT NULL COMMENT '分销商申请协议',
  `tx_details` text NOT NULL COMMENT '佣金提现协议',
  `is_fx` int(11) NOT NULL COMMENT '1.开启分销审核2.不开启',
  `is_ej` int(11) NOT NULL COMMENT '是否开启二级分销1.是2.否',
  `tx_rate` int(11) NOT NULL COMMENT '提现手续费',
  `commission` varchar(10) NOT NULL COMMENT '一级佣金',
  `commission2` varchar(10) NOT NULL COMMENT '二级佣金',
  `tx_money` int(11) NOT NULL COMMENT '提现门槛',
  `img` varchar(100) NOT NULL,
  `img2` varchar(100) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `is_open` int(11) NOT NULL DEFAULT '1' COMMENT '1.开启2关闭',
  `instructions` text NOT NULL COMMENT '分销商说明',
  `is_type` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_fxset
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_fxuser`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_fxuser`;
CREATE TABLE `ims_wpdc_fxuser` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '一级分销',
  `fx_user` int(11) NOT NULL COMMENT '二级分销',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_fxuser
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_goods`;
CREATE TABLE `ims_wpdc_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `img` varchar(300) NOT NULL COMMENT '商品图片',
  `number` int(11) NOT NULL COMMENT '数量',
  `order_id` int(11) NOT NULL COMMENT '订单id',
  `name` varchar(50) NOT NULL COMMENT '商品名称',
  `money` decimal(10,2) NOT NULL,
  `uniacid` varchar(50) NOT NULL,
  `dishes_id` int(11) NOT NULL COMMENT '菜品id',
  `spec` varchar(20) NOT NULL COMMENT '规格',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_help`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_help`;
CREATE TABLE `ims_wpdc_help` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(200) NOT NULL COMMENT '标题',
  `answer` text NOT NULL COMMENT '回答',
  `sort` int(4) NOT NULL COMMENT '排序',
  `uniacid` varchar(50) NOT NULL,
  `created_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_help
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_integral`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_integral`;
CREATE TABLE `ims_wpdc_integral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `score` int(11) NOT NULL COMMENT '分数',
  `type` int(4) NOT NULL COMMENT '1加,2减',
  `order_id` int(11) NOT NULL COMMENT '订单id',
  `cerated_time` datetime NOT NULL COMMENT '创建时间',
  `uniacid` varchar(50) NOT NULL,
  `note` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_integral
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_jfgoods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_jfgoods`;
CREATE TABLE `ims_wpdc_jfgoods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `img` varchar(100) NOT NULL,
  `money` int(11) NOT NULL COMMENT '价格',
  `type_id` int(11) NOT NULL COMMENT '分类id',
  `goods_details` text NOT NULL,
  `process_details` text NOT NULL,
  `attention_details` text NOT NULL,
  `number` int(11) NOT NULL COMMENT '数量',
  `time` varchar(50) NOT NULL COMMENT '期限',
  `is_open` int(11) NOT NULL COMMENT '1.开启2关闭',
  `type` int(11) NOT NULL COMMENT '1.余额2.实物',
  `num` int(11) NOT NULL COMMENT '排序',
  `uniacid` int(11) NOT NULL,
  `hb_moeny` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_jfgoods
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_jfrecord`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_jfrecord`;
CREATE TABLE `ims_wpdc_jfrecord` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `good_id` int(11) NOT NULL COMMENT '商品id',
  `time` varchar(20) NOT NULL COMMENT '兑换时间',
  `user_name` varchar(20) NOT NULL COMMENT '用户地址',
  `user_tel` varchar(20) NOT NULL COMMENT '用户电话',
  `address` varchar(200) NOT NULL COMMENT '地址',
  `note` varchar(20) NOT NULL,
  `integral` int(11) NOT NULL COMMENT '积分',
  `good_name` varchar(50) NOT NULL COMMENT '商品名称',
  `good_img` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_jfrecord
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_jftype`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_jftype`;
CREATE TABLE `ims_wpdc_jftype` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `img` varchar(100) NOT NULL,
  `num` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_jftype
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_order`;
CREATE TABLE `ims_wpdc_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `order_num` varchar(20) NOT NULL COMMENT '订单号',
  `state` int(11) NOT NULL COMMENT '状态 1.待付款 2.等待接单 3.等待送达  4.完成',
  `time` varchar(20) NOT NULL COMMENT '下单时间',
  `pay_time` int(11) NOT NULL COMMENT '付款时间',
  `money` decimal(10,2) NOT NULL,
  `preferential` varchar(10) NOT NULL COMMENT '优惠',
  `tel` varchar(20) NOT NULL COMMENT '客户电话',
  `name` varchar(20) NOT NULL COMMENT '客户姓名',
  `address` varchar(200) NOT NULL,
  `delivery_time` varchar(20) NOT NULL COMMENT '送达时间',
  `time2` int(11) NOT NULL,
  `cancel_time` int(11) NOT NULL COMMENT '取消时间',
  `uniacid` varchar(50) NOT NULL,
  `type` int(4) NOT NULL COMMENT '订单类型1外卖，2店内',
  `dn_state` int(4) NOT NULL COMMENT '店内订单状态1,待支付，2已完成,3关闭订单',
  `table_id` int(11) NOT NULL COMMENT '桌台ID',
  `freight` decimal(10,2) NOT NULL COMMENT '配送费',
  `box_fee` decimal(10,2) NOT NULL COMMENT '餐盒费',
  `coupons_id` int(11) NOT NULL COMMENT '优惠劵ID',
  `voucher_id` int(11) NOT NULL COMMENT '代金劵ID',
  `seller_id` int(11) NOT NULL COMMENT '商家ID',
  `note` varchar(200) NOT NULL COMMENT '备注',
  `area` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `lng` varchar(20) NOT NULL,
  `del` int(11) NOT NULL DEFAULT '2' COMMENT '1.删除  2.未删除',
  `sh_ordernum` varchar(50) NOT NULL,
  `pay_type` int(11) NOT NULL COMMENT '1.线上2.线下',
  `del2` int(11) NOT NULL,
  `is_take` int(11) NOT NULL COMMENT '1.自取 2.不自取',
  `is_yue` int(11) NOT NULL COMMENT '1.余额 2.',
  `completion_time` int(11) NOT NULL COMMENT '完成时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_order
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_qbmx`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_qbmx`;
CREATE TABLE `ims_wpdc_qbmx` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `money` decimal(10,2) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1.加2减',
  `note` varchar(20) NOT NULL COMMENT '备注',
  `time` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_qbmx
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_reduction`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_reduction`;
CREATE TABLE `ims_wpdc_reduction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '活动名称',
  `full` int(11) NOT NULL COMMENT '满',
  `reduction` int(11) NOT NULL COMMENT '减',
  `type` int(11) NOT NULL COMMENT '1.外卖 2.店内 3.外卖+店内',
  `store_id` int(11) NOT NULL COMMENT '商家id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_reduction
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_ruzhu`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_ruzhu`;
CREATE TABLE `ims_wpdc_ruzhu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_name` varchar(20) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `img` varchar(100) NOT NULL,
  `state` int(11) NOT NULL COMMENT '1.待审核 2.通过 3.拒绝',
  `user_id` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `address` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_ruzhu
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_seller`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_seller`;
CREATE TABLE `ims_wpdc_seller` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(30) NOT NULL,
  `pwd` varchar(50) NOT NULL,
  `cerated_time` datetime NOT NULL,
  `uniacid` varchar(50) NOT NULL,
  `store_id` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_seller
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_signlist`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_signlist`;
CREATE TABLE `ims_wpdc_signlist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `time` varchar(20) NOT NULL COMMENT '签到时间',
  `integral` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_signlist
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_signset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_signset`;
CREATE TABLE `ims_wpdc_signset` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `one` int(11) NOT NULL COMMENT '首次奖励积分',
  `integral` int(11) NOT NULL COMMENT '每天签到积分',
  `is_open` int(11) NOT NULL COMMENT '1.开启2.关闭  签到',
  `is_bq` int(11) NOT NULL COMMENT '1.开启2.关闭  补签',
  `bq_integral` int(11) NOT NULL COMMENT '补签扣除积分',
  `details` text NOT NULL COMMENT '签到说明',
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_signset
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_sms`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_sms`;
CREATE TABLE `ims_wpdc_sms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `model` varchar(30) NOT NULL COMMENT '支付订单通知模板',
  `model2` varchar(30) NOT NULL COMMENT '预约通知模板',
  `tel` varchar(20) NOT NULL,
  `tid` varchar(100) NOT NULL,
  `signature` varchar(200) NOT NULL,
  `uniacid` varchar(50) NOT NULL,
  `yy_tid` varchar(50) NOT NULL COMMENT '预约模板ID',
  `dm_tid` varchar(50) NOT NULL COMMENT '当面付模板ID',
  `store_id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `appkey` varchar(100) NOT NULL COMMENT '应用key(聚合)',
  `tpl_id` int(11) NOT NULL COMMENT '模板id(聚合)',
  `tpl2_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_sms
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_spec`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_spec`;
CREATE TABLE `ims_wpdc_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `name` varchar(50) NOT NULL COMMENT '规格名称',
  `cost` decimal(10,2) NOT NULL COMMENT '价格',
  `num` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_spec
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_special`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_special`;
CREATE TABLE `ims_wpdc_special` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `day` varchar(20) NOT NULL COMMENT '日期',
  `integral` int(11) NOT NULL COMMENT '积分',
  `title` varchar(20) NOT NULL COMMENT '标题说明',
  `color` varchar(20) NOT NULL COMMENT '颜色',
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_special
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_store`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_store`;
CREATE TABLE `ims_wpdc_store` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '商家名称',
  `address` varchar(500) NOT NULL COMMENT '商家地址',
  `time` varchar(100) NOT NULL COMMENT '营业时间',
  `tel` varchar(20) NOT NULL COMMENT '电话',
  `announcement` varchar(500) NOT NULL COMMENT '公告',
  `conditions` varchar(10) NOT NULL COMMENT '条件',
  `preferential` varchar(10) NOT NULL COMMENT '优惠',
  `support` varchar(500) NOT NULL COMMENT '支持',
  `is_rest` int(11) NOT NULL COMMENT '是否休息(1 是  2否)',
  `img` text NOT NULL COMMENT '商家图片',
  `start_at` varchar(20) NOT NULL COMMENT '起送价',
  `freight` varchar(20) NOT NULL COMMENT '配送费',
  `logo` varchar(200) NOT NULL COMMENT '店铺logo',
  `details` text NOT NULL,
  `bgimg` text NOT NULL COMMENT '商家背景图片',
  `time2` varchar(20) NOT NULL,
  `color` varchar(20) NOT NULL,
  `uniacid` varchar(50) NOT NULL,
  `xyh_money` decimal(10,2) NOT NULL COMMENT '新用户立减金额',
  `xyh_open` int(4) NOT NULL COMMENT '是否开启新用户立减1是2否',
  `integral` int(11) NOT NULL COMMENT ' 积分 (设置评价获取积分)',
  `coordinates` varchar(50) NOT NULL COMMENT '经纬度',
  `distance` varchar(100) NOT NULL COMMENT '配送距离单位米',
  `is_yy` int(4) NOT NULL COMMENT '是否预约1是',
  `is_wm` int(4) NOT NULL COMMENT '是否外卖1是',
  `is_dn` int(4) NOT NULL COMMENT '是否店内1是',
  `is_sy` int(4) NOT NULL COMMENT '是否收银1是',
  `is_pd` int(4) NOT NULL COMMENT '是否排队1是',
  `ps_mode` int(4) NOT NULL COMMENT '配送方式 1.蜂鸟，2商家',
  `bq_logo` varchar(100) NOT NULL,
  `is_display` int(4) NOT NULL COMMENT '幻灯片是否显示,1是，2否',
  `yyzz` text NOT NULL COMMENT '营业资质',
  `environment` text NOT NULL COMMENT '商家环境',
  `sd_time` varchar(20) NOT NULL COMMENT '配送时间',
  `md_logo` varchar(200) NOT NULL COMMENT '门店logo',
  `md_name` varchar(50) NOT NULL COMMENT '门店名称',
  `md_area` int(11) NOT NULL COMMENT '门店区域id',
  `md_type` int(11) NOT NULL COMMENT '门店类型id',
  `md_content` varchar(300) NOT NULL COMMENT '门店简介',
  `number` int(11) NOT NULL,
  `score` float NOT NULL COMMENT '评分',
  `sales` int(11) NOT NULL COMMENT '销量',
  `is_jd` int(11) NOT NULL COMMENT '1自动接单  2.手动接单',
  `jd_time` int(11) NOT NULL COMMENT '自动接单时间',
  `source_id` int(11) NOT NULL COMMENT '商家',
  `shop_no` varchar(20) NOT NULL COMMENT '门店编号',
  `is_mp3` int(11) NOT NULL COMMENT '1.开启 2.关闭',
  `is_video` int(11) NOT NULL COMMENT '1.开启 2.关闭',
  `store_mp3` text NOT NULL,
  `store_video` text NOT NULL,
  `is_yypay` int(11) NOT NULL COMMENT '是否开启预约付款1.是2.否',
  `yy_name` varchar(20) NOT NULL COMMENT '预约文本',
  `yy_img` varchar(100) NOT NULL COMMENT '预约图片',
  `wm_name` varchar(20) NOT NULL COMMENT '外卖文本',
  `wm_img` varchar(100) NOT NULL,
  `dn_name` varchar(20) NOT NULL COMMENT '店内文本',
  `dn_img` varchar(100) NOT NULL,
  `sy_name` varchar(20) NOT NULL COMMENT '收银文本',
  `sy_img` varchar(100) NOT NULL,
  `pd_name` varchar(20) NOT NULL COMMENT '优惠券文本',
  `pd_img` varchar(100) NOT NULL,
  `box_name` varchar(20) NOT NULL,
  `storecode` varchar(200) NOT NULL,
  `is_czztpd` int(11) NOT NULL DEFAULT '1' COMMENT '是否开启餐桌状态判断',
  `is_chzf` int(11) DEFAULT '1' COMMENT '是否开启餐后支付',
  `time3` varchar(20) NOT NULL,
  `time4` varchar(20) NOT NULL,
  `hb_img` varchar(100) NOT NULL,
  `is_open` int(11) NOT NULL DEFAULT '1',
  `is_jf` int(11) NOT NULL DEFAULT '1',
  `is_wmjf` int(11) NOT NULL DEFAULT '1',
  `is_yyjf` int(11) NOT NULL DEFAULT '1',
  `is_dnjf` int(11) NOT NULL DEFAULT '1',
  `is_dmjf` int(11) NOT NULL DEFAULT '1',
  `poundage` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_store
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_storetype`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_storetype`;
CREATE TABLE `ims_wpdc_storetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL COMMENT '类型名称',
  `num` int(11) NOT NULL,
  `img` varchar(200) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `commission` int(11) NOT NULL,
  `commission2` int(11) NOT NULL,
  `poundage` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_storetype
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_system`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_system`;
CREATE TABLE `ims_wpdc_system` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `appid` varchar(100) NOT NULL COMMENT 'appid',
  `appsecret` varchar(200) NOT NULL COMMENT 'appsecret',
  `link` varchar(200) NOT NULL COMMENT '网址',
  `mchid` varchar(20) NOT NULL COMMENT '商户号',
  `wxkey` varchar(100) NOT NULL COMMENT '商户秘钥',
  `uniacid` varchar(50) NOT NULL,
  `url_name` varchar(20) NOT NULL COMMENT '网址名称',
  `details` text NOT NULL COMMENT '关于我们',
  `url_logo` varchar(100) NOT NULL COMMENT '网址logo',
  `bq_name` varchar(50) NOT NULL COMMENT '版权名称',
  `link_name` varchar(30) NOT NULL COMMENT '网站名称',
  `link_logo` varchar(100) NOT NULL COMMENT '网站logo',
  `more` int(11) NOT NULL DEFAULT '1',
  `default_store` int(11) NOT NULL COMMENT '默认门店id',
  `support` varchar(20) NOT NULL COMMENT '技术支持',
  `bq_logo` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL,
  `map_key` varchar(100) NOT NULL COMMENT '腾讯地图key',
  `tz_appid` varchar(30) NOT NULL,
  `tz_name` varchar(30) NOT NULL,
  `pt_name` varchar(30) NOT NULL COMMENT '平台名称',
  `dada_key` varchar(50) NOT NULL COMMENT '达达key',
  `dada_secret` varchar(50) NOT NULL COMMENT '达达secret',
  `apiclient_cert` text NOT NULL,
  `apiclient_key` text NOT NULL,
  `day` int(11) NOT NULL COMMENT '天数',
  `username` varchar(20) NOT NULL COMMENT '邮箱用户名',
  `password` varchar(50) NOT NULL COMMENT '邮箱密码',
  `type` varchar(10) NOT NULL COMMENT '邮箱类型',
  `sender` varchar(50) NOT NULL COMMENT '发件人名称',
  `signature` varchar(200) NOT NULL COMMENT '邮件签名',
  `is_email` int(11) NOT NULL COMMENT '1开启  2关闭',
  `tx_money` decimal(10,2) NOT NULL COMMENT '最低提现金额',
  `tx_rate` int(11) NOT NULL COMMENT '手续费',
  `tx_details` text NOT NULL COMMENT '提现详情',
  `tel` varchar(20) NOT NULL COMMENT '平台电话',
  `dc_name` varchar(20) NOT NULL COMMENT '点餐文本',
  `wm_name` varchar(20) NOT NULL COMMENT '外卖文本',
  `yd_name` varchar(20) NOT NULL COMMENT '预定文本',
  `typeset` int(11) NOT NULL COMMENT '1开启  2.关闭(分类)',
  `integral` int(11) NOT NULL COMMENT '评论得积分',
  `cjwt` text NOT NULL,
  `rzxy` text NOT NULL,
  `is_ruzhu` int(11) NOT NULL COMMENT '1.开启2关闭',
  `is_yue` int(11) NOT NULL DEFAULT '1',
  `integral2` int(11) NOT NULL COMMENT '消费得积分',
  `is_jf` int(11) NOT NULL DEFAULT '1' COMMENT '1开启2关闭',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_system
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_table`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_table`;
CREATE TABLE `ims_wpdc_table` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '桌台号',
  `num` int(4) NOT NULL COMMENT '就餐人数',
  `type_id` varchar(50) NOT NULL COMMENT '桌台类型ID',
  `tag` varchar(50) NOT NULL COMMENT '标签',
  `orderby` int(11) NOT NULL COMMENT '排序',
  `status` int(4) NOT NULL COMMENT '状态，0 空闲，1开台，2已下单，3已支付',
  `uniacid` varchar(50) NOT NULL COMMENT '公众号ID',
  `store_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_table
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_table_type`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_table_type`;
CREATE TABLE `ims_wpdc_table_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '名字',
  `fw_cost` decimal(10,2) NOT NULL COMMENT '服务费',
  `zd_cost` decimal(10,2) NOT NULL COMMENT '最低消费',
  `yd_cost` decimal(10,2) NOT NULL COMMENT '预付款',
  `num` int(11) NOT NULL COMMENT '数量',
  `orderby` int(11) NOT NULL,
  `ss_seller` varchar(50) NOT NULL COMMENT '所属商家',
  `seller_id` int(11) NOT NULL COMMENT '商家id',
  `uniacid` varchar(50) NOT NULL COMMENT '公众号ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_table_type
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_traffic`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_traffic`;
CREATE TABLE `ims_wpdc_traffic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `time` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_traffic
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_type`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_type`;
CREATE TABLE `ims_wpdc_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL COMMENT '分类名称',
  `uniacid` varchar(50) NOT NULL,
  `order_by` int(4) NOT NULL,
  `store_id` int(11) NOT NULL COMMENT '商家id',
  `is_open` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_type
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_user`;
CREATE TABLE `ims_wpdc_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `join_time` int(11) NOT NULL,
  `img` varchar(500) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `uniacid` varchar(50) NOT NULL,
  `user_name` varchar(50) NOT NULL COMMENT '收货人姓名',
  `user_tel` varchar(50) NOT NULL COMMENT '收货人电话',
  `user_address` varchar(100) NOT NULL COMMENT '收货人地址',
  `total_score` int(11) NOT NULL,
  `wallet` decimal(10,2) NOT NULL COMMENT '钱包',
  `commission` decimal(10,2) NOT NULL,
  `day` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_user
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_usercoupons`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_usercoupons`;
CREATE TABLE `ims_wpdc_usercoupons` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `coupons_id` int(11) NOT NULL COMMENT '优惠券id',
  `state` int(11) NOT NULL COMMENT '1使用  2未使用',
  `uniacid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_usercoupons
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_uservoucher`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_uservoucher`;
CREATE TABLE `ims_wpdc_uservoucher` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `vouchers_id` int(11) NOT NULL COMMENT '代金券id',
  `state` int(11) NOT NULL COMMENT '1使用  2未使用',
  `uniacid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_uservoucher
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_uuset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_uuset`;
CREATE TABLE `ims_wpdc_uuset` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL COMMENT '门店id',
  `appid` varchar(50) NOT NULL,
  `appkey` varchar(50) NOT NULL,
  `account` varchar(30) NOT NULL,
  `OpenId` varchar(50) NOT NULL,
  `uniacid` varchar(50) NOT NULL,
  `is_open` int(4) NOT NULL DEFAULT '2' COMMENT '1开启,2关闭(uu跑腿)',
  `is_check` int(4) NOT NULL COMMENT '1自动,2手动确认订单价格',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_uuset
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_voucher`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_voucher`;
CREATE TABLE `ims_wpdc_voucher` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '名称',
  `start_time` varchar(20) NOT NULL COMMENT '开始时间',
  `end_time` varchar(20) NOT NULL COMMENT '结束时间',
  `preferential` varchar(10) NOT NULL COMMENT '优惠',
  `uniacid` varchar(50) NOT NULL,
  `voucher_type` int(4) NOT NULL COMMENT '使用类型1:外卖，2店内,3都可使用',
  `instruction` varchar(300) NOT NULL COMMENT '使用说明',
  `store_id` int(11) NOT NULL COMMENT '商家id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_voucher
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_withdrawal`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_withdrawal`;
CREATE TABLE `ims_wpdc_withdrawal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL COMMENT '真实姓名',
  `username` varchar(100) NOT NULL COMMENT '账号',
  `type` int(11) NOT NULL COMMENT '1支付宝 2.微信 3.银行',
  `time` varchar(20) NOT NULL COMMENT '申请时间',
  `sh_time` varchar(20) NOT NULL COMMENT '审核时间',
  `state` int(11) NOT NULL COMMENT '1.待审核 2.通过  3.拒绝',
  `tx_cost` decimal(10,2) NOT NULL COMMENT '提现金额',
  `sj_cost` decimal(10,2) NOT NULL COMMENT '实际金额',
  `store_id` int(11) NOT NULL COMMENT '商家id',
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_withdrawal
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_wpdc_ydorder`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wpdc_ydorder`;
CREATE TABLE `ims_wpdc_ydorder` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `xz_date` varchar(30) NOT NULL COMMENT '选择日期',
  `yjdd_date` varchar(30) NOT NULL COMMENT '预计到店时间',
  `table_type_id` int(11) NOT NULL COMMENT '桌位类型ID',
  `link_name` varchar(50) NOT NULL COMMENT '联系人',
  `link_tel` varchar(50) NOT NULL COMMENT '联系电话',
  `jc_num` int(4) NOT NULL COMMENT '就餐人数',
  `remark` varchar(500) NOT NULL COMMENT '备注',
  `state` int(4) NOT NULL COMMENT '状态 1,待审核，2已审核,3已拒绝,4取消',
  `uniacid` varchar(50) NOT NULL,
  `created_time` varchar(30) NOT NULL COMMENT '创建时间',
  `time2` int(11) NOT NULL COMMENT '时间撮',
  `order_num` varchar(30) NOT NULL COMMENT '订单号',
  `table_type_name` varchar(50) NOT NULL COMMENT '桌台类型名称',
  `store_id` int(11) NOT NULL COMMENT '商家id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `zd_cost` decimal(10,2) NOT NULL COMMENT '最低消费',
  `pay_money` decimal(10,2) NOT NULL COMMENT '付款金额',
  `ydcode` varchar(100) NOT NULL COMMENT '商户订单号',
  `del` int(11) NOT NULL COMMENT '1.删除 2.未删除',
  `is_yue` int(11) NOT NULL DEFAULT '2',
  `completion_time` int(11) NOT NULL COMMENT '完成时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_wpdc_ydorder
-- ----------------------------


sql;
$row = pdo_run($installSql);


