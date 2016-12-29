/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : fz22

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-11-03 09:11:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tingfeng_attachment
-- ----------------------------
DROP TABLE IF EXISTS `tingfeng_attachment`;
CREATE TABLE `tingfeng_attachment` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL COMMENT '文件名称',
  `save_name` varchar(255) NOT NULL COMMENT '保存的文件名称',
  `file_dir` varchar(255) NOT NULL COMMENT '文件路径',
  `file_md5` char(32) NOT NULL COMMENT '文件md5值',
  `type` varchar(255) NOT NULL COMMENT '文件类型',
  `size` double(10,2) NOT NULL COMMENT '文件大小',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tingfeng_game
-- ----------------------------
DROP TABLE IF EXISTS `tingfeng_game`;
CREATE TABLE `tingfeng_game` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '游戏名称',
  `type_id` tinyint(3) NOT NULL DEFAULT '1' COMMENT '游戏类型',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tpl` varchar(255) NOT NULL DEFAULT '' COMMENT '游戏模板路径',
  `sort` int(5) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '1',
  `default_member_id` int(8) NOT NULL DEFAULT '0',
  `hit_number` int(8) NOT NULL DEFAULT '0',
  `buy_number` int(8) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '数据写入时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Table structure for tingfeng_game_field
-- ----------------------------
DROP TABLE IF EXISTS `tingfeng_game_field`;
CREATE TABLE `tingfeng_game_field` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `game_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `field` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `content` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `style` varchar(255) DEFAULT NULL,
  `tips` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tingfeng_game_member
-- ----------------------------
DROP TABLE IF EXISTS `tingfeng_game_member`;
CREATE TABLE `tingfeng_game_member` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `member_id` int(8) NOT NULL,
  `game_id` int(8) NOT NULL,
  `name` char(30) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `font` varchar(255) DEFAULT NULL,
  `grade` int(5) DEFAULT NULL,
  `color` text,
  `reward` int(8) DEFAULT NULL,
  `reward_url` varchar(255) DEFAULT NULL,
  `share` varchar(255) DEFAULT NULL,
  `hit_number` int(8) NOT NULL DEFAULT '0',
  `qq_number` int(8) NOT NULL DEFAULT '0',
  `weixin_number` int(8) NOT NULL DEFAULT '0',
  `other_number` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tingfeng_game_type
-- ----------------------------
DROP TABLE IF EXISTS `tingfeng_game_type`;
CREATE TABLE `tingfeng_game_type` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '类型名称',
  `sort` int(5) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Table structure for tingfeng_models
-- ----------------------------
DROP TABLE IF EXISTS `tingfeng_models`;
CREATE TABLE `tingfeng_models` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `name` char(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `table_name` char(30) NOT NULL COMMENT '数据库表名（不含前缀）',
  `description` char(30) DEFAULT '' COMMENT '描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `sort` tinyint(3) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Table structure for tingfeng_model_field
-- ----------------------------
DROP TABLE IF EXISTS `tingfeng_model_field`;
CREATE TABLE `tingfeng_model_field` (
  `id` mediumint(10) NOT NULL AUTO_INCREMENT,
  `model_id` mediumint(5) NOT NULL,
  `field` varchar(255) NOT NULL COMMENT '字段',
  `name` varchar(255) NOT NULL COMMENT '字段名称',
  `tips` varchar(255) DEFAULT '' COMMENT '字段提示',
  `form_type` varchar(255) NOT NULL COMMENT '表格类型',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tingfeng_sys_node
-- ----------------------------
DROP TABLE IF EXISTS `tingfeng_sys_node`;
CREATE TABLE `tingfeng_sys_node` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL DEFAULT '' COMMENT '名称',
  `pid` int(8) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `m` char(20) DEFAULT '' COMMENT '应用识标',
  `c` char(20) NOT NULL DEFAULT '' COMMENT '控制器',
  `a` char(20) NOT NULL DEFAULT '' COMMENT '操作',
  `is_dispaly` tinyint(1) DEFAULT '1' COMMENT '是否在后台菜单中显示：0不显示，1显示',
  `sort` int(5) DEFAULT '0',
  `icon` varchar(255) DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示，0不显示1显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tingfeng_sys_role
-- ----------------------------
DROP TABLE IF EXISTS `tingfeng_sys_role`;
CREATE TABLE `tingfeng_sys_role` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `node_ids` varchar(255) DEFAULT '' COMMENT '-1为超级管理员',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tingfeng_sys_user
-- ----------------------------
DROP TABLE IF EXISTS `tingfeng_sys_user`;
CREATE TABLE `tingfeng_sys_user` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `username` char(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `role_id` int(5) NOT NULL DEFAULT '0' COMMENT '权限ID',
  `super` int(5) NOT NULL DEFAULT '0' COMMENT '是否是超级管理员',
  `content` text NOT NULL COMMENT '联系方式等信息',
  `status` int(5) NOT NULL DEFAULT '0' COMMENT '状态',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(255) NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
