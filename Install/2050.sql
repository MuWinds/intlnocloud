INSERT INTO `pay_config` VALUES ('version', '2050');

/*删除字段*/
ALTER TABLE pay_user DROP COLUMN alipay_free_vip_time;
ALTER TABLE pay_user DROP COLUMN qqpay_free_vip_time;
ALTER TABLE pay_user DROP COLUMN wxpay_free_vip_time;

/*新增字段*/
ALTER TABLE `pay_user`
ADD COLUMN  `alipay_free_vip_time` datetime DEFAULT NULL COMMENT '支付宝免挂机会员到期时间',
ADD COLUMN  `qqpay_free_vip_time` datetime DEFAULT NULL COMMENT 'QQ钱包免挂机会员到期时间',
ADD COLUMN  `wxpay_free_vip_time` datetime DEFAULT NULL COMMENT '微信免挂机会员到期时间';


ALTER TABLE `pay_qrlist`
ADD COLUMN  `qr_id` varchar(200) COMMENT '请求更新返回的ID',
ADD COLUMN  `pay_user` varchar(88) DEFAULT '0' COMMENT '登陆账号',
ADD COLUMN  `pay_pass` varchar(88) DEFAULT '0' COMMENT '登陆密码',
ADD COLUMN  `data_data` varchar(88) DEFAULT '0' COMMENT '返回信息';

