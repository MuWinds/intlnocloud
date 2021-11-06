DROP TABLE IF EXISTS `pay_config`;
create table `pay_config` (
`k` varchar(32) NOT NULL,
`v` text NULL,
PRIMARY KEY  (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `pay_config` VALUES ('version', '2068');
INSERT INTO `pay_config` VALUES ('Instant_url', 'http://106.55.171.103/');
INSERT INTO `pay_config` VALUES ('admin_user', 'admin');
INSERT INTO `pay_config` VALUES ('admin_pass', '123456');
INSERT INTO `pay_config` VALUES ('gonggao', '欢迎使用本支付,有问题请咨询客服处理');
INSERT INTO `pay_config` VALUES ('sitename', 'INTL二维码Pay-码支付');
INSERT INTO `pay_config` VALUES ('title', '支付宝免签约_微信免签_QQ钱包免签约接口_优云宝_秒冲宝_码支付');
INSERT INTO `pay_config` VALUES ('keywords', 'INTL二维码Pay系统-支付免签约支付,个人支付宝即时到账接口,支付宝免签约接口,支付宝即时到账接口,微信免签接口,微信免签,支付宝辅助收款,API支付对接,码支付,Mpay支付,优云宝_秒冲宝');
INSERT INTO `pay_config` VALUES ('description', 'INTL二维码Pay系统-支付免签支付专为个人、企业收款而生的支付工具。为支付宝、微信支付的个人账户、企业账号，提供即时到账收款API。安全可靠，费率低。');
INSERT INTO `pay_config` VALUES ('mail_type', '本地发件二');
INSERT INTO `pay_config` VALUES ('mail_smtp', 'smtp.exmail.qq.com');
INSERT INTO `pay_config` VALUES ('mail_port', '25');
INSERT INTO `pay_config` VALUES ('mail_name', '51pay@51yunzf.com');
INSERT INTO `pay_config` VALUES ('mail_pwd', '');
INSERT INTO `pay_config` VALUES ('qr_nums', '6');
INSERT INTO `pay_config` VALUES ('ed_money', '100');
INSERT INTO `pay_config` VALUES ('webwh', '关闭维护');
INSERT INTO `pay_config` VALUES ('outtime', '240');
INSERT INTO `pay_config` VALUES ('Instant_pid', '云端账号');
INSERT INTO `pay_config` VALUES ('Instant_key', '云端密码');
INSERT INTO `pay_config` VALUES ('blockname', '百度云|摆渡|云盘|点券|芸盘|萝莉|罗莉|网盘|黑号|q币|Q币|扣币|qq货币|QQ货币|花呗|baidu云|bd云|吃鸡|透视|自瞄|后座|穿墙|脚本|外挂|模拟|辅助|检测|武器|套装');
INSERT INTO `pay_config` VALUES ('blockalert', '温馨提醒该商品禁止出售，如有疑问请联系网站客服！');
INSERT INTO `pay_config` VALUES ('captcha_id', 'b31335edde91b2f98dacd393f6ae6de8');
INSERT INTO `pay_config` VALUES ('captcha_key', '170d2349acef92b7396c7157eb9d8f47');
INSERT INTO `pay_config` VALUES ('pay_work_name', '<option value=\"支付问题\">支付问题</option>\n<option value=\"网站问题\">网站问题</option>\n<option value=\"二维码登录问题\">二维码登录问题</option>\n<option value=\"功能问题\">功能问题</option>\n<option value=\"发现BUG\">发现BUG</option>\n<option value=\"只想和你唠唠嗑\">只想和你唠唠嗑</option>');
INSERT INTO `pay_config` VALUES ('reg_open', '1');
INSERT INTO `pay_config` VALUES ('reg_pay', '0');
INSERT INTO `pay_config` VALUES ('test_open', '0');
INSERT INTO `pay_config` VALUES ('cronkey', 'intl');
INSERT INTO `pay_config` VALUES ('footer', 'INTL码支付');
INSERT INTO `pay_config` VALUES ('captcha_open_login', '1');
INSERT INTO `pay_config` VALUES ('login_qq', '4');
INSERT INTO `pay_config` VALUES ('template', 'default');

DROP TABLE IF EXISTS `pay_wechat_trumpet`;
CREATE TABLE `pay_wechat_trumpet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wx_user` varchar(50) NOT NULL COMMENT '微信账号',
  `wx_name` varchar(50) NOT NULL COMMENT '微信昵称',
  `beizhu` varchar(488) NOT NULL COMMENT '微信备注',
  `login_time` bigint(20) NOT NULL COMMENT '在线刷新时间',
  `sort` int(5) NOT NULL COMMENT '排序',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `addtime` datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `pay_log`;
CREATE TABLE `pay_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `type` varchar(188) NULL,
  `date` datetime NOT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `data` text NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `pay_risk`;
CREATE TABLE `pay_risk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(15) NOT NULL DEFAULT '0',
  `type` int(1) NOT NULL DEFAULT '0',
  `url` varchar(64) DEFAULT NULL,
  `content` varchar(64) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `pay_notice`;
CREATE TABLE `pay_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '公告标题',
  `datatxt` varchar(488) NOT NULL COMMENT '公告内容',
  `color` varchar(20) NOT NULL COMMENT '公告颜色',
  `sort` int(5) NOT NULL COMMENT '公告排序',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `addtime` datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `pay_notice` (`id`, `title`, `datatxt`, `status`, `addtime`) VALUES
(1, 'INTL公告', 'INTL码支付欢迎您的使用,有问题请联系QQ:2361203712', 1, '2021-4-12');

DROP TABLE IF EXISTS `pay_work`;
CREATE TABLE `pay_work` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(8) DEFAULT NULL,
  `num` varchar(16) NOT NULL,
  `types` varchar(16) NOT NULL,
  `biaoti` text,
  `text` text,
  `qq` varchar(16) NOT NULL,
  `edata` varchar(16) NOT NULL,
  `huifu` text,
  `wdata` varchar(16) NOT NULL,
  `active` varchar(4) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `pay_regcode`;
CREATE TABLE `pay_regcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(11) DEFAULT NULL COMMENT '类型',
  `code` varchar(32) DEFAULT NULL COMMENT '验证码',
  `to` varchar(20) DEFAULT NULL COMMENT '邮箱地址',
  `time` int(11) DEFAULT NULL COMMENT '间隔时间',
  `ip` varchar(20) DEFAULT NULL COMMENT '发送者IP',
  `data` varchar(88) DEFAULT NULL COMMENT '注册订单数据',
  `trade_no` varchar(32) DEFAULT NULL COMMENT '订单号',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `pay_user`;
CREATE TABLE `pay_user` (
  `pid` varchar(32) NOT NULL COMMENT '商户账号',
  `key` varchar(32) NOT NULL COMMENT '商户秘钥',
  `user` varchar(32) DEFAULT NULL COMMENT '商户登录账号',
  `pass` varchar(32) DEFAULT NULL COMMENT '商户登录密码',
  `phone` varchar(11) DEFAULT NULL COMMENT '商户绑定手机号',
  `email` varchar(32) DEFAULT NULL COMMENT '商户登录密码',
  `qq` varchar(10) NOT NULL COMMENT '商户QQ号',
  `social_uid` varchar(32) DEFAULT NULL COMMENT 'QQ快捷登陆绑定',
  `nickname` varchar(32) DEFAULT NULL COMMENT 'QQ快捷绑定昵称',
  `money` decimal(10,2) DEFAULT NULL COMMENT '商户额度',
  `pay_template` varchar(32) DEFAULT 'default' COMMENT '支付页模板',
  `outtime` int(10) DEFAULT NULL COMMENT '支付超时时间',
  `alipay_pay_open` int(1) DEFAULT '0' COMMENT '商户支付宝支付模式',
  `qqpay_pay_open` int(1) DEFAULT '0' COMMENT '商户QQ钱包支付模式',
  `alipay_api_url` varchar(32) DEFAULT NULL COMMENT '支付宝对接URL',
  `alipay_api_pid` varchar(32) DEFAULT NULL COMMENT '支付宝对接PID',
  `alipay_api_key` varchar(32) DEFAULT NULL COMMENT '支付宝对接KEY',
  `qqpay_api_url` varchar(32) DEFAULT NULL COMMENT 'QQ钱包对接URL',
  `qqpay_api_pid` varchar(32) DEFAULT NULL COMMENT 'QQ钱包对接PID',
  `qqpay_api_key` varchar(32) DEFAULT NULL COMMENT 'QQ钱包对接KEY',
  `alipay_free_vip_time` datetime DEFAULT NULL COMMENT '支付宝免挂机会员到期时间',
  `qqpay_free_vip_time` datetime DEFAULT NULL COMMENT 'QQ钱包免挂机会员到期时间',
  `wxpay_free_vip_time` datetime DEFAULT NULL COMMENT '微信免挂机会员到期时间',
  `addtime` datetime DEFAULT NULL COMMENT '用户添加时间',
   PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `pay_qrlist`;
CREATE TABLE `pay_qrlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` varchar(32) NOT NULL COMMENT '商户PID',
  `qr_id` varchar(200) COMMENT '请求更新返回的ID',
  `qr_url` varchar(300) NOT NULL COMMENT '二维码url地址',
  `type` varchar(32) NOT NULL COMMENT '二维码类型',
  `beizhu` varchar(32) DEFAULT NULL COMMENT '二维码备注',
  `wx_name` varchar(50) DEFAULT NULL COMMENT '绑定的微信店员',
  `pay_user` varchar(88) DEFAULT '0' COMMENT '登陆账号',
  `pay_pass` varchar(88) DEFAULT '0' COMMENT '登陆密码',
  `data_data` varchar(88) DEFAULT '0' COMMENT '返回信息',
  `cookie` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '登录COOKIE',
  `money` decimal(10,2) NOT NULL COMMENT '上次余额记录',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '二维码状态',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '出码排序',
  `crontime` varchar(32) DEFAULT '0' COMMENT '监控时间',
  `addtime` datetime DEFAULT NULL COMMENT '二维码添加时间',
  `endtime` datetime DEFAULT NULL COMMENT '二维码失效时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/* 回调信息列表 */
DROP TABLE IF EXISTS `pay_notify`;
CREATE TABLE `pay_notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` varchar(32) NOT NULL COMMENT '商户PID',
  `qr_id` varchar(32) NOT NULL DEFAULT '1' COMMENT '二维码ID',
  `qr_beizhu` varchar(32) DEFAULT '' COMMENT '二维码备注',
  `api_type` varchar(32) NOT NULL COMMENT '提交接口的支付方式',
  `money` decimal(10,2) NOT NULL COMMENT '到账金额',
  `pay_msg` varchar(300) DEFAULT '' COMMENT '回调返回数据',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '回调状态',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '出码排序',
  `crontime` varchar(32) DEFAULT '0' COMMENT '监控时间',
  `addtime` datetime DEFAULT NULL COMMENT '到账时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `pay_order`;
CREATE TABLE `pay_order` (
  `trade_no` varchar(64) NOT NULL COMMENT '支付系统订单号',
  `out_trade_no` varchar(64) NOT NULL COMMENT '商户订单号',
  `notify_url` varchar(288) DEFAULT NULL COMMENT '异步通知地址',
  `return_url` varchar(288) DEFAULT NULL COMMENT '同步通知地址',
  `type` varchar(20) NOT NULL COMMENT '支付方式',
  `pid` varchar(14) NOT NULL COMMENT '商户PID',
  `name` varchar(64) NOT NULL COMMENT '商品名称',
  `money` decimal(10,2) NOT NULL COMMENT '商品金额',
  `price` decimal(10,2) NOT NULL COMMENT '商品实付金额',
  `qr_id` varchar(255) NOT NULL COMMENT '二维码ID',
  `pay_id` varchar(32) NOT NULL COMMENT '支付者的IP',
  `api_type` varchar(32) NOT NULL COMMENT '提交接口的支付方式',
  `apitime` varchar(32) NOT NULL COMMENT '订单成功提交时间',
  `outtime` varchar(32) NOT NULL COMMENT '订单超时时间',
  `addtime` datetime DEFAULT NULL COMMENT '订单创建时间',
  `endtime` datetime DEFAULT NULL COMMENT '订单支付成功时间',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '订单支付状态',
  PRIMARY KEY (`trade_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;