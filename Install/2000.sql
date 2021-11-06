INSERT INTO `pay_config` VALUES ('version', '2000');

ALTER TABLE `pay_qrlist`
ADD COLUMN  `wx_name` varchar(50) DEFAULT NULL COMMENT '绑定的微信店员';

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

