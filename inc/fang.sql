--
-- 空数据库fang
--


CREATE DATABASE fang DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE fang;
-- --------------------------------------------------------

--
-- 表的结构 `f_chengjiao`
--

CREATE TABLE f_chengjiao (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  city_name varchar(20) NOT NULL,
  wuye_name varchar(10) NOT NULL,
  area decimal(10,2) unsigned NOT NULL COMMENT '//面积',
  taoshu int(10) NOT NULL COMMENT '//套数',
  fang_time date NOT NULL COMMENT '//房地产信息网入库时间',
  os_time datetime NOT NULL COMMENT '//入库电脑时间',
  PRIMARY KEY (id)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `f_city`
--

CREATE TABLE f_city (
  city_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  city_name varchar(20) NOT NULL,
  PRIMARY KEY (city_id)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `f_info`
--

CREATE TABLE f_info (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  info_name varchar(30) NOT NULL,
  city_name varchar(10) NOT NULL,
  fa_time date NOT NULL,
  info text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `f_tudi`
--

CREATE TABLE f_tudi (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  tudi_id varchar(20) NOT NULL COMMENT '//地块编号',
  city_name varchar(10) NOT NULL COMMENT '//区域',
  weizhi varchar(255) NOT NULL COMMENT '//地块位置',
  yongtu varchar(80) NOT NULL COMMENT '//土地用途',
  rongjilv varchar(40) NOT NULL COMMENT '//容积率',
  midu varchar(40) NOT NULL COMMENT '//建筑密度',
  lvdi varchar(40) NOT NULL COMMENT '//绿地率',
  fa_time date NOT NULL COMMENT '//发布日期',
  chengjiao_time datetime NOT NULL COMMENT '//成交日期',
  jiezhi_time datetime NOT NULL COMMENT '//保证金截止时间',
  baozhengjin decimal(10,2) unsigned NOT NULL COMMENT '//竞买保证金',
  mianji_m decimal(10,4) unsigned NOT NULL COMMENT '//占地面积（亩）',
  mianji_p decimal(10,4) unsigned NOT NULL COMMENT '//占地面积（㎡）',
  guihua_p decimal(10,4) unsigned NOT NULL COMMENT '//规划建筑面积（㎡）',
  qipaijia decimal(10,2) unsigned NOT NULL COMMENT '//起拍价（万/亩）',
  chengjiaodanjia decimal(10,2) unsigned NOT NULL COMMENT '//成交单价（万/亩）',
  chengjiazongjia decimal(10,2) unsigned NOT NULL COMMENT '//成交总地价（万元）',
  loumiandijia decimal(10,2) unsigned NOT NULL COMMENT '//楼面地价',
  yijialv varchar(4) NOT NULL COMMENT '//溢价率',
  jingderen varchar(255) NOT NULL COMMENT '//竞得人',
  qishijia decimal(10,2) unsigned NOT NULL COMMENT '//起始价（万元）',
  dituweizhi varchar(255) NOT NULL COMMENT '//地图位置',
  xuzhi text NOT NULL COMMENT '//出让须知',
  is_cheng varchar(20) NOT NULL COMMENT '//交易状态',
  PRIMARY KEY (id)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `f_wuye`
--

CREATE TABLE f_wuye (
  wuye_id mediumint(8) NOT NULL AUTO_INCREMENT,
  wuye_name varchar(10) NOT NULL,
  PRIMARY KEY (`wuye_id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `f_yushou`
--

CREATE TABLE f_yushou (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  city_name varchar(10) NOT NULL COMMENT '//区域',
  pro_name varchar(20) NOT NULL COMMENT '//项目名称',
  yushou_id varchar(24) NOT NULL COMMENT '//预售证号',
  fa_time date NOT NULL COMMENT '//发证日期',
  yushou_lou varchar(20) NOT NULL COMMENT '//预售楼栋',
  yushou_tao int(10) NOT NULL COMMENT '//预售套数',
  yushou_mianji decimal(10,2) NOT NULL COMMENT '//预售面积',
  qiye_name varchar(20) NOT NULL COMMENT '//开发企业名称',
  yushou_yongtu varchar(20) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM;
