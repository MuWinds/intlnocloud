
ALTER TABLE `pay_user`
ADD COLUMN  `pay_template` varchar(32) DEFAULT 'default' COMMENT '支付页模板',
ADD COLUMN  `outtime` int(10) DEFAULT NULL COMMENT '支付超时时间',
ADD COLUMN  `alipay_pay_open` int(1) DEFAULT '0' COMMENT '商户支付宝支付模式',
ADD COLUMN  `qqpay_pay_open` int(1) DEFAULT '0' COMMENT '商户QQ钱包支付模式',
ADD COLUMN  `alipay_api_url` varchar(32) DEFAULT NULL COMMENT '支付宝对接URL',
ADD COLUMN  `alipay_api_pid` varchar(32) DEFAULT NULL COMMENT '支付宝对接PID',
ADD COLUMN  `alipay_api_key` varchar(32) DEFAULT NULL COMMENT '支付宝对接KEY',
ADD COLUMN  `qqpay_api_url` varchar(32) DEFAULT NULL COMMENT 'QQ钱包对接URL',
ADD COLUMN  `qqpay_api_pid` varchar(32) DEFAULT NULL COMMENT 'QQ钱包对接PID',
ADD COLUMN  `qqpay_api_key` varchar(32) DEFAULT NULL COMMENT 'QQ钱包对接KEY';

ALTER TABLE pay_log modify column type varchar(188);