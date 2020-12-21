

CREATE TABLE `jy_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `username` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT '管理员名',
  `password` varchar(50) COLLATE utf8_bin DEFAULT '' COMMENT '密码',
  `portrait` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '头像',
  `loginnum` int(11) DEFAULT '0' COMMENT '登陆次数',
  `last_login_ip` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(11) DEFAULT NULL COMMENT '最后登录时间',
  `real_name` varchar(20) COLLATE utf8_bin DEFAULT '' COMMENT '真实姓名',
  `phone` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT '手机号',
  `status` int(1) DEFAULT NULL COMMENT '状态 1：开启  2:禁用',
  `groupid` int(11) DEFAULT '1' COMMENT '用户角色id',
  `create_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='后台管理员表';

-- ----------------------------
-- Table structure for jy_ads
-- ----------------------------

CREATE TABLE `jy_ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `gzh_src` varchar(255) DEFAULT NULL,
  `xcx_src` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` smallint(1) DEFAULT NULL,
  `maincolor` varchar(20) DEFAULT NULL COMMENT '幻灯片主色',
  `shoptype` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for jy_area
-- ----------------------------

CREATE TABLE `jy_area` (
  `district_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '地区id',
  `district` varchar(100) DEFAULT NULL COMMENT '地区名称',
  `pid` int(11) DEFAULT NULL COMMENT '父id',
  `level` tinyint(1) DEFAULT NULL COMMENT '1:省  2:市  3:区',
  PRIMARY KEY (`district_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='地区表';

-- ----------------------------
-- Table structure for jy_article
-- ----------------------------

CREATE TABLE `jy_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章逻辑ID',
  `title` varchar(255) NOT NULL COMMENT '文章标题',
  `cate_id` int(11) NOT NULL DEFAULT '1' COMMENT '文章类别',
  `photo` text COMMENT '文章图片',
  `remark` varchar(256) DEFAULT '' COMMENT '文章描述',
  `keyword` varchar(32) DEFAULT '' COMMENT '文章关键字',
  `content` text NOT NULL COMMENT '文章内容',
  `views` int(11) NOT NULL DEFAULT '1' COMMENT '浏览量',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '文章类型',
  `is_tui` int(1) DEFAULT '0' COMMENT '是否推荐',
  `from` varchar(16) NOT NULL DEFAULT '' COMMENT '来源',
  `writer` varchar(64) NOT NULL COMMENT '作者',
  `ip` varchar(16) NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态 1：开启   2：关闭',
  `music` varchar(255) DEFAULT NULL COMMENT '音频',
  PRIMARY KEY (`id`),
  KEY `a_title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='文章表';

-- ----------------------------
-- Table structure for jy_article_cate
-- ----------------------------

CREATE TABLE `jy_article_cate` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL COMMENT '分类名称',
  `orderby` varchar(10) DEFAULT '100' COMMENT '排序',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态  1：开启  2：禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='文章分类表';

-- ----------------------------
-- Table structure for jy_auth_group
-- ----------------------------

CREATE TABLE `jy_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `title` char(100) NOT NULL DEFAULT '' COMMENT '角色名称',
  `status` tinyint(1) NOT NULL COMMENT '角色状态 1：开启   2：禁用',
  `rules` text NOT NULL COMMENT '角色权限   SUPERAUTH：超级权限',
  `describe` text COMMENT '角色描述',
  `create_time` int(11) DEFAULT NULL COMMENT '生成时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户组数据表';

-- ----------------------------
-- Table structure for jy_auth_group_access
-- ----------------------------

CREATE TABLE `jy_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '角色权限id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户-用户组关系表';

-- ----------------------------
-- Table structure for jy_auth_rule
-- ----------------------------

CREATE TABLE `jy_auth_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限id',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '模块/控制器/方法',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '权限规则名称',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1：菜单  2：按钮',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态  1：开启  2：禁用',
  `css` varchar(100) NOT NULL COMMENT '图标样式',
  `condition` varchar(100) NOT NULL DEFAULT '',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父栏目ID',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='权限规则表';

-- ----------------------------
-- Table structure for jy_classify
-- ----------------------------

CREATE TABLE `jy_classify` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限id',
  `acid` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '权限规则名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态  1：开启  2：禁用',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父栏目ID',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改',
  `css` varchar(10) DEFAULT '0',
  `name` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='分类表';

-- ----------------------------
-- Table structure for jy_comment
-- ----------------------------

CREATE TABLE `jy_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) DEFAULT NULL,
  `menuid` int(11) DEFAULT NULL,
  `media` varchar(100) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `addtime` int(10) DEFAULT NULL,
  `is_verify` smallint(1) DEFAULT '0',
  `uid` int(11) DEFAULT NULL,
  `goodstype` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for jy_config
-- ----------------------------

CREATE TABLE `jy_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置标题',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL COMMENT '状态   1：开启   2：禁用',
  `value` text COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='配置表';

-- ----------------------------
-- Table structure for jy_course
-- ----------------------------

CREATE TABLE `jy_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuid` int(11) NOT NULL,
  `acid` int(11) NOT NULL,
  `src` varchar(255) DEFAULT NULL,
  `coursename` varchar(200) NOT NULL,
  `freesecond` int(11) DEFAULT NULL,
  `viewnum` int(11) DEFAULT NULL,
  `addtime` varchar(200) NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `xh` int(10) DEFAULT NULL,
  `is_sk` smallint(1) DEFAULT '0' COMMENT '是否支持试看 0为不支持 1为支持',
  `media` varchar(20) DEFAULT NULL,
  `introduce` text,
  `status` smallint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for jy_coursemenu
-- ----------------------------

CREATE TABLE `jy_coursemenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL COMMENT '讲师ID',
  `menuname` varchar(255) NOT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `introduce` text NOT NULL,
  `acid` int(11) NOT NULL,
  `jianjie` varchar(255) DEFAULT NULL,
  `viewnum` int(11) DEFAULT '0',
  `addtime` varchar(200) NOT NULL,
  `fenleiid` varchar(100) DEFAULT NULL,
  `status` smallint(1) DEFAULT '0',
  `is_fx` smallint(1) DEFAULT '0',
  `ztmoney` decimal(10,2) DEFAULT NULL,
  `jtmoney` decimal(10,2) DEFAULT NULL,
  `is_zl` smallint(1) DEFAULT '0',
  `is_jfdh` smallint(1) DEFAULT '0',
  `score` int(11) DEFAULT NULL,
  `score_money` decimal(10,2) DEFAULT NULL,
  `one_lock` smallint(1) DEFAULT '0' COMMENT '单课程是否需要解锁1为需要0不需要',
  `xh` int(10) DEFAULT '0',
  `zsscore` int(11) DEFAULT '0',
  `media` varchar(10) DEFAULT NULL COMMENT '媒体类型 all为所有 video,audio,tuwen',
  `is_tj` smallint(1) DEFAULT '0' COMMENT '推荐',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for jy_credit
-- ----------------------------

CREATE TABLE `jy_credit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) DEFAULT NULL,
  `credit` int(11) DEFAULT NULL,
  `goodsid` int(11) DEFAULT NULL,
  `goodsname` varchar(255) DEFAULT NULL,
  `goodstype` varchar(50) DEFAULT NULL,
  `status` smallint(1) DEFAULT '1',
  `addtime` int(10) DEFAULT NULL,
  `stock` int(11) DEFAULT '0' COMMENT '库存',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for jy_dingyue
-- ----------------------------

CREATE TABLE `jy_dingyue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuid` int(11) NOT NULL,
  `media` varchar(100) NOT NULL,
  `acid` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `is_dingyue` smallint(1) DEFAULT '0',
  `openid` varchar(100) DEFAULT NULL,
  `goodstype` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for jy_gonggao
-- ----------------------------

CREATE TABLE `jy_gonggao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `src` varchar(255) DEFAULT NULL,
  `gzh_src` varchar(255) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for jy_log
-- ----------------------------

CREATE TABLE `jy_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `admin_name` varchar(50) DEFAULT NULL COMMENT '用户姓名',
  `description` varchar(300) DEFAULT NULL COMMENT '描述',
  `ip` char(60) DEFAULT NULL COMMENT 'IP地址',
  `status` int(3) DEFAULT NULL COMMENT '200 成功 100 失败',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `ipaddr` varchar(255) DEFAULT NULL COMMENT 'ip地区信息',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='日志表';

-- ----------------------------
-- Table structure for jy_mediaorder
-- ----------------------------

CREATE TABLE `jy_mediaorder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `orderid` varchar(100) NOT NULL,
  `menuid` varchar(100) NOT NULL,
  `ordertime` varchar(100) NOT NULL,
  `is_pay` smallint(1) DEFAULT '0',
  `pay_type` varchar(100) DEFAULT NULL,
  `goodstype` varchar(100) DEFAULT NULL,
  `media` varchar(100) DEFAULT NULL,
  `num` int(11) DEFAULT '1' COMMENT '购买数量',
  `price` decimal(10,2) DEFAULT NULL,
  `paytime` int(10) DEFAULT NULL,
  `score` int(10) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `sonid` int(11) DEFAULT '0',
  `yhm` varchar(200) DEFAULT NULL COMMENT '优惠码',
  `shr_name` varchar(200) DEFAULT NULL,
  `shr_phone` varchar(200) DEFAULT NULL,
  `shr_address` varchar(255) DEFAULT NULL,
  `wuliu` varchar(255) DEFAULT NULL,
  `kdnum` varchar(255) DEFAULT NULL,
  `fh_state` smallint(1) DEFAULT '0' COMMENT '0未发货 1发货中 2已收货 3已完成 4退款',
  `addressid` int(11) DEFAULT NULL COMMENT '收货人地址id',
  `remarks` varchar(255) DEFAULT NULL COMMENT '备注',
  `postage` decimal(10,2) DEFAULT NULL COMMENT '邮费',
  `apptype` varchar(100) DEFAULT NULL COMMENT 'APP类型',
  `paydetail` text COMMENT '支付详情',
  `credit` int(11) DEFAULT '0' COMMENT '积分兑换所需积分',
  `order_type` varchar(100) DEFAULT NULL COMMENT '订单类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for jy_qiandao
-- ----------------------------

CREATE TABLE `jy_qiandao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `qdtime` int(11) DEFAULT NULL,
  `openid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



-- ----------------------------
-- Table structure for jy_set
-- ----------------------------

CREATE TABLE `jy_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `tid` varchar(255) DEFAULT NULL,
  `is_baoming` smallint(1) DEFAULT NULL,
  `is_getnum` smallint(1) DEFAULT NULL,
  `is_free` smallint(1) DEFAULT NULL,
  `vipprice` decimal(10,2) DEFAULT NULL,
  `ypname` varchar(100) DEFAULT NULL,
  `twname` varchar(100) DEFAULT NULL,
  `spname` varchar(100) DEFAULT NULL,
  `zbname` varchar(100) DEFAULT NULL,
  `zlname` varchar(100) DEFAULT NULL,
  `jfdhname` varchar(100) DEFAULT NULL,
  `ypnums` int(11) DEFAULT NULL,
  `spnums` int(11) DEFAULT NULL,
  `twnums` int(11) DEFAULT NULL,
  `zbnums` int(11) DEFAULT NULL,
  `zlnums` int(11) DEFAULT NULL,
  `jfdhnums` int(11) DEFAULT NULL,
  `hashchar` varchar(255) DEFAULT NULL,
  `sqimg` varchar(255) DEFAULT NULL,
  `fxs2price` decimal(10,2) DEFAULT NULL,
  `pay_type` varchar(20) DEFAULT NULL,
  `ztyj` decimal(3,2) DEFAULT NULL,
  `jtyj` decimal(3,2) DEFAULT NULL,
  `jsyj` decimal(3,2) DEFAULT NULL COMMENT '讲师佣金比例',
  `gwjfdhbl` int(10) DEFAULT NULL,
  `cz_man` decimal(10,2) DEFAULT NULL,
  `cz_song` decimal(10,2) DEFAULT NULL,
  `is_videoshow` smallint(1) DEFAULT '0',
  `is_audioshow` smallint(1) DEFAULT '0',
  `is_fenlanshow` smallint(1) DEFAULT '0',
  `is_tuwenshow` smallint(1) DEFAULT '0',
  `is_jfdhshow` smallint(1) DEFAULT '0',
  `is_iconshow` smallint(1) DEFAULT '0',
  `is_ggshow` smallint(1) DEFAULT '0',
  `is_zhiboshow` smallint(1) DEFAULT '0',
  `qd_man` int(11) DEFAULT NULL,
  `qd_fan` decimal(10,2) DEFAULT '0.00',
  `qdjf_bl` int(11) DEFAULT '1' COMMENT '签到积分比例',
  `qd_man1` int(11) DEFAULT NULL,
  `qd_fan1` decimal(10,2) DEFAULT '0.00',
  `zsvip_days` int(11) DEFAULT '0' COMMENT '连续签到赠送VIP天数',
  `fx_type` smallint(1) DEFAULT '0',
  `tx_min` decimal(10,2) DEFAULT NULL COMMENT '最低提现金额',
  `tlurl` varchar(255) DEFAULT NULL,
  `blurl` varchar(255) DEFAULT NULL,
  `zbkey` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `kfurl` text,
  `vipqy1` varchar(255) DEFAULT NULL,
  `vipqy2` varchar(255) DEFAULT NULL,
  `vipqy3` varchar(255) DEFAULT NULL,
  `share_icon` varchar(255) DEFAULT NULL,
  `share_title` varchar(255) DEFAULT NULL,
  `share_desc` varchar(255) DEFAULT NULL,
  `share_link` varchar(255) DEFAULT NULL,
  `is_gz` smallint(1) DEFAULT NULL,
  `qrcode` varchar(255) DEFAULT NULL,
  `gzinfo` varchar(100) DEFAULT NULL,
  `is_icon_mr` smallint(1) DEFAULT '0' COMMENT '图标是否使用默认',
  `fxhb1` varchar(255) DEFAULT NULL,
  `fxhb2` varchar(255) DEFAULT NULL,
  `fxhb3` varchar(255) DEFAULT NULL,
  `share_hbtext` varchar(255) DEFAULT NULL COMMENT '分享海报提示语',
  `hbtext` varchar(255) DEFAULT NULL COMMENT '海报生成文字',
  `xhs` varchar(255) DEFAULT NULL COMMENT '排序',
  `msg_kcbuyok` varchar(255) DEFAULT NULL,
  `msg_kcstart` varchar(255) DEFAULT NULL,
  `msg_newyj` varchar(255) DEFAULT NULL,
  `msg_txok` varchar(255) DEFAULT NULL,
  `msg_newuser` varchar(255) DEFAULT NULL,
  `diy_flnum` int(10) DEFAULT NULL,
  `goodshb1` varchar(255) DEFAULT NULL,
  `goodshb2` varchar(255) DEFAULT NULL,
  `goodshb3` varchar(255) DEFAULT NULL,
  `txtext` varchar(255) DEFAULT NULL COMMENT '提现提示语',
  `fx_auto` smallint(1) DEFAULT NULL COMMENT '是否自动成为分销商',
  `dlsprice` decimal(10,2) DEFAULT NULL,
  `dl_level1` decimal(10,2) DEFAULT NULL,
  `dl_level2` decimal(10,2) DEFAULT NULL,
  `dl_level3` decimal(10,2) DEFAULT NULL,
  `dl_level4` decimal(10,2) DEFAULT NULL,
  `is_jy` smallint(1) DEFAULT NULL COMMENT '禁言功能',
  `jfshow` smallint(1) DEFAULT NULL,
  `moneyshow` smallint(1) DEFAULT NULL,
  `sjvipshow` smallint(1) DEFAULT NULL,
  `dlsshow` smallint(1) DEFAULT NULL,
  `fxsshow` smallint(1) DEFAULT NULL,
  `mykcshow` smallint(1) DEFAULT NULL,
  `mydyshow` smallint(1) DEFAULT NULL,
  `jsshow` smallint(1) DEFAULT NULL,
  `lxwmshow` smallint(1) DEFAULT NULL,
  `kfshow` smallint(1) DEFAULT NULL,
  `is_iconft_mr` smallint(1) DEFAULT NULL,
  `dl_pay` smallint(1) DEFAULT NULL COMMENT '成为代理是否必须付费',
  `kf_type` smallint(1) DEFAULT NULL,
  `qq` varchar(100) DEFAULT NULL,
  `wechat` varchar(100) DEFAULT NULL,
  `sms_key` varchar(255) DEFAULT NULL,
  `sms_secret` varchar(255) DEFAULT NULL,
  `sms_code` varchar(255) DEFAULT NULL,
  `sms_template_id` varchar(255) DEFAULT NULL,
  `is_fh` smallint(1) DEFAULT NULL,
  `msg_fahuo` varchar(255) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL COMMENT '知识店铺联系电话',
  `study_minute` int(11) DEFAULT '0' COMMENT '每学习几分钟可兑换积分',
  `dh_score` int(11) DEFAULT '0' COMMENT '每学习几分钟可以兑换多少积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for jy_set_app
-- ----------------------------

CREATE TABLE `jy_set_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `appid` varchar(200) DEFAULT NULL,
  `appsecret` varchar(255) DEFAULT NULL,
  `mchid` varchar(100) DEFAULT NULL,
  `paysecret` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for jy_study_time
-- ----------------------------

CREATE TABLE `jy_study_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `videostudytime` int(11) DEFAULT NULL,
  `audiostudytime` int(11) DEFAULT NULL,
  `tuwenstudytime` int(11) DEFAULT NULL,
  `date` varchar(20) DEFAULT NULL COMMENT '日期 具体到天',
  `dh_score` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for jy_teacher
-- ----------------------------

CREATE TABLE `jy_teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL,
  `imgname` varchar(200) NOT NULL,
  `img` varchar(200) NOT NULL,
  `introduce` text,
  `tphone` varchar(100) DEFAULT NULL,
  `tpassword` varchar(255) DEFAULT NULL,
  `fenlei` varchar(255) DEFAULT NULL,
  `is_verify` smallint(1) DEFAULT '1',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  `flid` int(11) DEFAULT NULL COMMENT '分类ID',
  `openid` varchar(200) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for jy_user
-- ----------------------------

CREATE TABLE `jy_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL,
  `app_openid` varchar(100) DEFAULT NULL,
  `gzh_openid` varchar(255) DEFAULT NULL COMMENT '公众号openid',
  `openid` varchar(255) DEFAULT NULL COMMENT '小程序openid',
  `nickname` varchar(200) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `gender` smallint(1) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `realname` varchar(30) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `createtime` varchar(100) DEFAULT NULL,
  `is_vip` smallint(1) DEFAULT '0',
  `vip_state` varchar(10) DEFAULT NULL,
  `viptypeid` int(11) DEFAULT NULL,
  `vipsj_fangshi` varchar(100) DEFAULT NULL COMMENT '会员升级方式',
  `vipsj_orderid` varchar(255) DEFAULT NULL,
  `vipsj_time` int(10) DEFAULT NULL,
  `credit` int(11) DEFAULT '0',
  `is_fxs` smallint(1) DEFAULT '0' COMMENT '是否分销商',
  `pid` int(11) DEFAULT '0',
  `path` varchar(255) DEFAULT '0',
  `plv` int(11) DEFAULT '1',
  `money` decimal(10,2) DEFAULT '0.00',
  `khh` varchar(200) DEFAULT NULL,
  `hzname` varchar(100) DEFAULT NULL,
  `cardnum` varchar(100) DEFAULT NULL,
  `txphone` varchar(20) DEFAULT NULL,
  `yongjin` decimal(10,3) DEFAULT '0.000',
  `jj` varchar(255) DEFAULT NULL,
  `dls_name` varchar(100) DEFAULT NULL,
  `dls_phone` varchar(100) DEFAULT NULL,
  `is_dls` smallint(1) DEFAULT '0' COMMENT '是否代理商',
  `alipay` varchar(255) DEFAULT NULL,
  `fenhong` decimal(10,2) DEFAULT NULL COMMENT '代理商分红',
  `dls_sh` smallint(1) DEFAULT '0' COMMENT '代理商审核，0为未提交，1为申请中，2为通过审核',
  `unionid` varchar(255) DEFAULT NULL,
  `status` smallint(1) DEFAULT '0',
  `apptype` varchar(20) DEFAULT NULL COMMENT '用户注册来源',
  `is_lock` smallint(1) DEFAULT '0' COMMENT '分销关系锁定 0为未发展下线 1为有下线',
  `lasttime` int(11) DEFAULT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for jy_viptime
-- ----------------------------

CREATE TABLE `jy_viptime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `days` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `bfprice` decimal(10,2) DEFAULT NULL,
  `zsscore` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for jy_ziyuan
-- ----------------------------

CREATE TABLE `jy_ziyuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `time` varchar(100) DEFAULT NULL,
  `imgname` varchar(255) DEFAULT NULL,
  `imgthumb` varchar(255) DEFAULT NULL,
  `imgcontent` text,
  `xlsname` varchar(255) DEFAULT NULL,
  `xlsthumb` varchar(255) DEFAULT NULL,
  `xlspath` varchar(255) DEFAULT NULL,
  `xlscontent` text,
  `pdfname` varchar(255) DEFAULT NULL,
  `pdfthumb` varchar(255) DEFAULT NULL,
  `pdfpath` varchar(255) DEFAULT NULL,
  `pdfcontent` text,
  `docname` varchar(255) DEFAULT NULL,
  `docthumb` varchar(255) DEFAULT NULL,
  `docpath` varchar(255) DEFAULT NULL,
  `doccontent` text,
  `pptname` varchar(255) DEFAULT NULL,
  `pptthumb` varchar(255) DEFAULT NULL,
  `pptpath` varchar(255) DEFAULT NULL,
  `pptcontent` text,
  `media` varchar(100) DEFAULT NULL,
  `mediaid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `jy_admin` (`id`, `username`, `password`,  `status`, `groupid`, `create_time`) VALUES ('1', 'admin', 'fa92c55df7df9f44d1d6efa2f74ab955', '1', '1', '1606806893');
INSERT INTO `jy_auth_group` (`id`, `title`, `status`, `rules`, `describe`, `create_time`, `update_time`) VALUES ('1', '超级管理员', '1', 'SUPERAUTH', '至高无上的权利', '1606806893', '1606806893');
INSERT INTO `jy_auth_group_access` (`uid`, `group_id`) VALUES ('1', '1');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('1', '#', '系统管理', '1', '1', 'fa fa-cog', '', '0', '9', '1446535750', '1541121645');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('2', 'admin/user/index', '管理员管理', '1', '1', 'fa fa-ban', '', '1', '10', '1446535750', '1540544544');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('3', 'admin/role/index', '角色管理', '1', '1', 'fa fa-ban', '', '1', '0', '1446535750', '1540434868');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('4', 'admin/menu/index', '菜单管理', '1', '1', 'fa fa-ban', '', '1', '30', '1446535750', '1540434958');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('5', '#', '数据库管理', '1', '1', 'fa fa-database', '', '0', '10', '1446535750', '1477312169');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('6', 'admin/data/index', '数据库备份', '1', '1', 'fa fa-ban', '', '5', '10', '1446535750', '1540435124');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('7', 'admin/data/optimize', '优化表', '1', '1', 'fa fa-ban', '', '6', '50', '1477312169', '1540435130');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('8', 'admin/data/repair', '修复表', '1', '1', 'fa fa-ban', '', '6', '50', '1477312169', '1540435138');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('9', 'admin/user/useradd', '添加管理员', '1', '1', 'fa fa-ban', '', '2', '50', '1477312169', '1540434790');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('10', 'admin/user/useredit', '编辑管理员', '1', '1', 'fa fa-ban', '', '2', '50', '1477312169', '1540434808');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('11', 'admin/user/userdel', '删除管理员', '1', '1', 'fa fa-ban', '', '2', '50', '1477312169', '1540434818');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('12', 'admin/user/user_state', '管理员状态', '1', '1', 'fa fa-ban', '', '2', '50', '1477312169', '1540434834');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('13', '#', '日志管理', '1', '1', 'fa fa-tasks', '', '0', '12', '1477312169', '1477312169');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('14', 'admin/log/operate_log', '行为日志', '1', '1', 'fa fa-ban', '', '13', '10', '1477312169', '1540435216');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('22', 'admin/log/del_log', '删除日志', '1', '1', 'fa fa-ban', '', '14', '50', '1477312169', '1540435224');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('24', '#', '文章管理', '1', '2', 'fa fa-paste', '', '0', '11', '1477312169', '1477312169');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('26', 'admin/article/index', '文章列表', '1', '2', '', '', '24', '20', '1477312333', '1477312333');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('27', 'admin/data/import', '数据库还原', '1', '1', 'fa fa-ban', '', '5', '20', '1477639870', '1540435152');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('28', 'admin/data/revert', '还原备份', '1', '1', 'fa fa-ban', '', '27', '50', '1477639972', '1540435166');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('29', 'admin/data/deldata', '删除备份', '1', '1', 'fa fa-ban', '', '27', '50', '1477640011', '1540435179');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('30', 'admin/role/roleAdd', '添加角色', '1', '1', 'fa fa-ban', '', '3', '50', '1477640011', '1540434875');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('31', 'admin/role/roleEdit', '编辑角色', '1', '1', 'fa fa-ban', '', '3', '50', '1477640011', '1540434889');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('32', 'admin/role/roleDel', '删除角色', '1', '1', 'fa fa-ban', '', '3', '50', '1477640011', '1540434901');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('33', 'admin/role/role_state', '角色状态', '1', '1', 'fa fa-ban', '', '3', '50', '1477640011', '1540434918');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('34', 'admin/role/giveAccess', '权限分配', '1', '1', 'fa fa-ban', '', '3', '50', '1477640011', '1540434950');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('35', 'admin/menu/add_rule', '添加菜单', '1', '1', 'fa fa-ban', '', '4', '50', '1477640011', '1540434966');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('36', 'admin/menu/edit_rule', '编辑菜单', '1', '1', 'fa fa-ban', '', '4', '50', '1477640011', '1540434982');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('37', 'admin/menu/del_rule', '删除菜单', '1', '1', 'fa fa-ban', '', '4', '50', '1477640011', '1540434991');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('38', 'admin/menu/rule_state', '菜单状态', '1', '2', 'fa fa-ban', '', '4', '50', '1477640011', '1540435007');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('39', 'admin/menu/ruleorder', '菜单排序', '1', '1', 'fa fa-ban', '', '4', '50', '1477640011', '1540435014');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('44', 'admin/article/add_article', '添加文章', '1', '2', '', '', '26', '50', '1477640011', '1477640011');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('45', 'admin/article/edit_article', '编辑文章', '1', '2', '', '', '26', '50', '1477640011', '1477640011');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('46', 'admin/article/del_article', '删除文章', '1', '2', '', '', '26', '50', '1477640011', '1477640011');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('47', 'admin/article/article_state', '文章状态', '1', '2', '', '', '26', '50', '1477640011', '1477640011');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('61', 'admin/config/index', '配置管理', '1', '1', 'fa fa-ban', '', '1', '40', '1479908607', '1540435030');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('62', 'admin/config/add_config', '添加配置', '1', '1', 'fa fa-ban', '', '61', '50', '1479908607', '1540435036');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('63', 'admin/config/edit_config', '编辑配置', '1', '1', 'fa fa-ban', '', '61', '50', '1479908607', '1540435042');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('64', 'admin/config/del_config', '删除配置', '1', '1', 'fa fa-ban', '', '61', '50', '1479908607', '1540435049');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('65', 'admin/config/status_config', '配置状态', '1', '1', 'fa fa-ban', '', '61', '50', '1479908607', '1540435066');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('66', 'admin/config/group', '网站配置', '1', '1', 'fa fa-ban', '', '1', '50', '1480316438', '1540435096');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('85', 'admin/index/clear', '清除缓存', '1', '1', 'fa fa-ban', '', '66', '50', '1522485859', '1540435103');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('91', 'admin/config/save', '保存配置', '1', '1', 'fa fa-ban', '', '66', '50', '1522824567', '1540435110');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('92', 'admin/data/export', '备份表', '1', '1', 'fa fa-ban', '', '6', '50', '1523161011', '1540435145');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('102', 'admin/user/batchdeluser', '批量删除', '1', '1', 'fa fa-ban', '', '11', '50', '1524645295', '1540434827');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('103', 'admin/role/batchdelrole', '批量删除', '1', '1', 'fa fa-ban', '', '32', '50', '1524648181', '1540434911');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('104', 'admin/menu/batchdelmenu', '批量删除', '1', '1', 'fa fa-ban', '', '37', '50', '1524653771', '1540434997');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('105', 'admin/config/batchdelconfig', '批量删除', '1', '1', 'fa fa-ban', '', '64', '50', '1524653826', '1540435059');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('109', 'admin/article/batchdelarticle', '批量删除', '1', '2', '', '', '46', '50', '1524654090', '1530680741');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('112', 'admin/log/batchdellog', '批量删除', '1', '1', 'fa fa-ban', '', '14', '50', '1524654233', '1540435231');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('116', 'admin/data/batchdeldata', '批量删除', '1', '1', 'fa fa-ban', '', '27', '50', '1524805218', '1540435185');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('117', '#', '一级菜单', '1', '2', 'fa fa-bars', '', '0', '8', '1524876437', '1542268663');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('118', '##', '二级菜单', '1', '2', 'fa fa-cubes', '', '117', '20', '1524879234', '1542268679');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('119', 'admin/user/index', '三级菜单（页面）', '1', '2', '', '', '118', '50', '1524879401', '1525252090');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('120', 'admin/user/addu', '页面操作（增删改）', '1', '2', '', '', '119', '50', '1524883447', '1524887249');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('121', 'admin/user/addu', '页面操作（增删改）', '1', '2', '', '', '119', '50', '1524883471', '1524887260');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('122', 'admin/user/index', '三级菜单（页面）', '1', '2', '', '', '118', '50', '1524883489', '1524887285');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('123', 'admin/user/index', '二级菜单（页面）', '1', '2', '', '', '117', '10', '1524886031', '1524887304');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('126', 'admin/menu/editfield', '快捷编辑', '1', '1', 'fa fa-ban', '', '4', '50', '1529631518', '1540519615');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('127', 'admin/user/forbiddenadmin', '批量禁用', '1', '1', 'fa fa-ban', '', '12', '50', '1530238799', '1540434840');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('128', 'admin/user/usingadmin', '批量启用', '1', '1', 'fa fa-ban', '', '12', '50', '1530238799', '1540434847');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('130', 'admin/role/forbiddenrole', '批量禁用', '1', '1', 'fa fa-ban', '', '33', '50', '1530248275', '1540434928');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('131', 'admin/role/usingrole', '批量启用', '1', '1', 'fa fa-ban', '', '33', '50', '1530248275', '1540434940');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('132', 'admin/config/forbiddenconfig', '批量禁用', '1', '1', 'fa fa-ban', '', '65', '50', '1530262327', '1540435073');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('133', 'admin/config/usingconfig', '批量启用', '1', '1', 'fa fa-ban', '', '65', '50', '1530262327', '1540435089');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('140', 'admin/article/forbiddenarticle', '批量禁用', '1', '2', '#', '', '47', '50', '1530681605', '1530681605');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('141', 'admin/article/usingarticle', '批量启用', '1', '2', '#', '', '47', '50', '1530681605', '1530681605');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('146', 'admin/user/exceladmin', '导出excel', '1', '1', 'fa fa-ban', '', '2', '50', '1531280281', '1540434858');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('165', 'admin/email/index', '第三方服务', '1', '2', 'fa fa-envelope-o', '', '0', '50', '1542181775', '1542181775');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('166', 'admin/email/sendEmail', '邮件', '1', '2', 'fa fa-envelope-open-o', '', '165', '50', '1542266986', '1542266986');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('167', 'admin/email/sendYzxCodegf', '云之讯短信', '1', '2', 'fa fa-paper-plane', '', '165', '50', '1542266986', '1542266986');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('168', 'admin/email/sendAliCode', '阿里短信', '1', '2', 'fa fa-paper-plane', '', '165', '50', '1542266986', '1542266986');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('169', '#', '店铺管理', '1', '1', 'fa fa-bank', '', '0', '0', '1598411016', '1598411016');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('170', 'admin/shop/ads', '幻灯片管理', '1', '1', 'fa fa-ban', '', '169', '1', '1598411080', '1598411080');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('171', '#', '课程管理', '1', '1', 'fa fa-graduation-cap', '', '0', '1', '1598415747', '1598942530');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('172', '##', '课程管理', '1', '1', 'fa fa-ban', '', '171', '1', '1598415810', '1598942715');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('173', 'admin/course/index?media=audio', '音频课程', '1', '1', 'fa fa-ban', '', '172', '2', '1598415851', '1598942554');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('174', 'admin/course/index?media=tuwen', '图文课程', '1', '1', 'fa fa-ban', '', '172', '3', '1598415889', '1598942567');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('175', 'admin/course/index?media=all', '不限', '1', '1', 'fa fa-ban', '', '172', '4', '1598415921', '1598947677');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('176', 'admin/classify/index', '课程分类', '1', '1', 'fa fa-ban', '', '171', '0', '1598415997', '1598493897');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('177', 'admin/course/add_menu', '添加目录', '1', '1', 'fa fa-ban', '', '171', '2', '1598521576', '1598942759');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('178', 'admin/course/index?media=video', '视频课程', '1', '1', 'fa fa-ban', '', '172', '1', '1598522261', '1598946554');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('180', 'admin/shop/comment', '评论管理', '1', '1', 'fa fa-ban', '', '169', '3', '1598579044', '1598579044');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('181', '#', '讲师管理', '1', '1', 'fa fa-user-circle-o', '', '0', '0', '1598579604', '1598579604');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('182', 'admin/teacher/index', '讲师列表', '1', '1', 'fa fa-ban', '', '181', '0', '1598579655', '1598579655');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('183', 'admin/teacher/add_teacher', '添加讲师', '1', '1', 'fa fa-ban', '', '181', '1', '1598579684', '1598579684');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('184', 'admin/teacher/dsh', '讲师审核', '1', '1', 'fa fa-ban', '', '181', '2', '1598579728', '1598579728');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('185', 'admin/course/index', '全部', '1', '1', 'fa fa-ban', '', '172', '0', '1598942670', '1598942908');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('186', '#', '订单管理', '1', '1', 'fa fa-navicon', '', '0', '3', '1599013532', '1599013532');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('187', '#', '用户管理', '1', '1', 'fa fa-user', '', '0', '4', '1599014579', '1599014579');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('188', 'admin/order/course', '课程订单', '1', '1', 'fa fa-ban', '', '186', '0', '1600350144', '1600350144');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('189', 'admin/order/mall', '商城订单', '1', '2', 'fa fa-ban', '', '186', '1', '1600350179', '1600350179');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('190', 'admin/order/pxcourse', '培训班订单', '1', '2', 'fa fa-ban', '', '186', '2', '1600350211', '1600350211');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('191', '#', '教辅商城', '1', '2', 'fa fa-cart-plus', '', '0', '2', '1600352184', '1600397581');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('192', '##', '商品管理', '1', '2', 'fa fa-ban', '', '191', '0', '1600352216', '1600352216');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('193', '##', '商品分类', '1', '2', 'fa fa-ban', '', '191', '1', '1600352247', '1600664049');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('194', '#', '培训机构', '1', '2', 'fa fa-group', '', '0', '8', '1600352653', '1600397615');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('195', 'admin/peixunban/index', '培训机构管理', '1', '2', 'fa fa-ban', '', '194', '0', '1600352797', '1600352797');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('196', 'admin/pxbclassify/index', '培训班分类', '1', '2', 'fa fa-ban', '', '194', '1', '1600352853', '1600352853');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('197', '#', '直播管理', '1', '2', 'fa fa-video-camera', '', '0', '1', '1600353099', '1600397565');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('198', 'admin/live/course', '课程直播', '1', '2', 'fa fa-ban', '', '197', '0', '1600353127', '1600353127');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('199', 'admin/live/goods', '直播带货', '1', '2', 'fa fa-ban', '', '197', '1', '1600353153', '1600353153');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('200', '#', '在线考试', '1', '2', 'fa fa-pencil-square-o', '', '0', '1', '1600353302', '1600397573');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('201', 'admin/exam/questions', '试题管理', '1', '2', 'fa fa-ban', '', '200', '0', '1600353434', '1600353434');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('202', 'admin/exam/classify', '试题分类', '1', '2', 'fa fa-ban', '', '200', '1', '1600353457', '1600353457');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('203', 'admin/exam/score', '成绩管理', '1', '2', 'fa fa-ban', '', '200', '2', '1600353503', '1600353503');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('204', '#', 'AI辅导', '1', '2', 'fa fa-android', '', '0', '7', '1600353739', '1600397597');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('205', 'admin/ai/index', '智能AI伴读', '1', '2', 'fa fa-ban', '', '204', '0', '1600353909', '1600353909');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('206', '#', '营销插件', '1', '1', 'fa fa-gift', '', '0', '5', '1600354549', '1600397590');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('207', 'admin/plugin/seckill', '秒杀', '1', '2', 'fa fa-ban', '', '206', '0', '1600658143', '1600658143');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('208', 'admin/plugin/pintuan', '拼团', '1', '2', 'fa fa-ban', '', '206', '1', '1600658482', '1600658482');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('209', 'admin/plugin/yhm', '商品优惠码', '1', '2', 'fa fa-ban', '', '206', '2', '1600658526', '1600658526');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('210', 'admin/plugin/jhm', '会员激活码', '1', '2', 'fa fa-ban', '', '206', '3', '1600658635', '1600658635');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('211', 'admin/plugin/credit', '积分商城', '1', '1', 'fa fa-ban', '', '206', '4', '1600700760', '1600700760');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('212', 'admin/plugin/payqa', '付费问答', '1', '2', 'fa fa-ban', '', '206', '5', '1600748117', '1600748117');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('213', '##', '设置', '1', '1', 'fa fa-ban', '', '169', '4', '1602114636', '1602114636');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('214', 'admin/set/fenxiao', '分销设置', '1', '2', 'fa fa-ban', '', '213', '1', '1602114715', '1602114715');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('215', 'admin/set/h5', 'H5设置', '1', '1', 'fa fa-ban', '', '213', '2', '1602222228', '1602222228');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('216', 'admin/set/smallapp', '小程序设置', '1', '1', 'fa fa-ban', '', '213', '3', '1602222583', '1602222583');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('217', 'admin/set/apps', 'APP设置', '1', '1', 'fa fa-ban', '', '213', '4', '1602222622', '1602222622');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('218', 'admin/set/pc', 'PC端设置', '1', '2', 'fa fa-ban', '', '213', '5', '1602222659', '1602222659');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('219', 'admin/vip/index', '用户管理', '1', '1', 'fa fa-ban', '', '187', '0', '1602298222', '1602298222');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('220', 'admin/live/set', '直播设置', '1', '2', 'fa fa-ban', '', '197', '2', '1602299223', '1602299223');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('221', 'admin/mall/set', '商城设置', '1', '2', 'fa fa-ban', '', '191', '2', '1602473297', '1602473297');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('222', 'admin/vip/timepart', '会员周期', '1', '1', 'fa fa-ban', '', '187', '1', '1602477463', '1602477463');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('223', 'admin/set/basic', '基本设置', '1', '1', 'fa fa-ban', '', '213', '0', '1602668285', '1602668285');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('224', 'admin/set/tplmsg', '模板消息', '1', '1', 'fa fa-ban', '', '213', '6', '1603793342', '1603793342');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('225', 'admin/set/message', '短信配置', '1', '1', 'fa fa-ban', '', '213', '7', '1603794085', '1603794085');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('226', 'admin/shop/notice', '消息通知', '1', '1', 'fa fa-ban', '', '169', '2', '1604047641', '1604047641');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('227', 'admin/set/seckill', '秒杀设置', '1', '2', 'fa fa-ban', '', '213', '8', '1604890489', '1604890489');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('228', 'admin/set/pintuan', '拼团设置', '1', '2', 'fa fa-ban', '', '213', '9', '1605172635', '1605172635');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('229', 'admin/course/add_course', '添加课程', '1', '1', 'fa fa-ban', '', '171', '3', '1606553248', '1606553248');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('230', 'admin/live/add_live', '添加直播', '1', '2', 'fa fa-ban', '', '197', '3', '1606553348', '1606553348');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('231', 'admin/peixunban/add_pxb', '添加培训班', '1', '2', 'fa fa-ban', '', '194', '2', '1606553442', '1606553442');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('232', 'admin/peixunban/add_pxcourse', '添加培训课程', '1', '2', 'fa fa-ban', '', '194', '3', '1606553486', '1606553486');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('233', 'admin/mall/add_goods', '添加商品', '1', '2', 'fa fa-ban', '', '192', '1', '1606553628', '1606553628');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('234', 'admin/mall/goods', '商品管理', '1', '2', 'fa fa-ban', '', '192', '0', '1606553701', '1606553701');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('235', 'admin/goodsclassify/index', '商品分类', '1', '2', 'fa fa-ban', '', '193', '0', '1606553736', '1606553736');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('236', 'admin/goodsclassify/add_classify', '添加分类', '1', '2', 'fa fa-ban', '', '193', '1', '1606553801', '1606553801');
INSERT INTO `jy_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES ('237', 'admin/plugin/creditadd', '添加积分商品', '1', '1', 'fa fa-ban', '', '206', '4', '1606890682', '1606890682');

INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('1', 'web_site_title', '1', '网站标题', '1', '', '网站标题前台显示标题', '1378898976', '1480575456', '1', '知识付费在线课程', '0');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('2', 'web_site_description', '2', '网站描述', '1', '', '网站搜索引擎描述', '1378898976', '1379235841', '1', 'copyright © 2018-2020 知识付费在线课程 山西匠言网络 all rights reserved.', '1');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('3', 'web_site_keyword', '2', '网站关键字', '1', '', '网站搜索引擎关键字', '1378898976', '1381390100', '1', '知识付费在线课程,匠言学院', '8');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('4', 'web_site_close', '4', '站点状态', '1', '0:关闭,1:开启', '站点关闭后其他管理员不能访问，超级管理员可以正常访问', '1378898976', '1529630265', '1', '1', '0');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('9', 'config_type_list', '3', '配置类型列表', '4', '', '主要用于数据解析和页面表单的生成', '1378898976', '1379235348', '1', '0:数字\n1:字符\n2:文本\n3:数组\n4:枚举', '2');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('10', 'web_site_icp', '1', '网站备案号', '1', '', '设置在网站底部显示的备案号，如“ 陇ICP备15002349号-1', '1378900335', '1480643159', '1', ' 京ICP备15002349号', '0');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('20', 'config_group_list', '3', '配置分组', '4', '', '配置分组', '1379228036', '1384418383', '1', '1:基本\n2:内容\n3:用户\n4:系统', '4');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('25', 'pages', '0', '每页记录数', '2', '', '后台数据每页显示记录数', '1379503896', '1533521664', '1', '10', '0');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('26', 'user_allow_register', '4', '开放注册', '3', '0:关闭注册\n1:允许注册', '是否开放用户注册', '1379504487', '1533521585', '1', '1', '3');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('28', 'data_backup_path', '1', '备份根路径', '4', '', '数据库备份根路径，路径必须以 / 结尾', '1381482411', '1533521561', '1', './data/', '5');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('29', 'data_backup_part_size', '0', '备份卷大小', '4', '', '数据库备份卷大小，该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', '1381482488', '1533521547', '1', '20971520', '7');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('30', 'data_backup_compress', '4', '是否启用压缩', '4', '0:不压缩\n1:启用压缩', '数据库压缩备份文件需要PHP环境支持gzopen,gzwrite函数', '1381713345', '1533521364', '1', '0', '9');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('31', 'data_backup_compress_level', '4', '备份压缩级别', '4', '1:普通\n4:一般\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', '1381713408', '1533521328', '1', '9', '10');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('36', 'admin_allow_ip', '2', '禁止访问IP', '4', '', '后台禁止访问IP，多个用逗号分隔，如果不配置表示不限制IP访问', '1387165454', '1533521226', '1', '0.0.0.0', '0');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('37', 'app_trace', '4', 'Trace', '4', '0:关闭\n1:开启', '是否显示页面Trace信息', '1387165685', '1537846673', '1', '0', '0');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('49', 'log_std', '4', '本地日志', '1', '0:关闭,1:开启', '是否开启记录日志文件', '1540200530', '1540264970', '1', '0', '50');
INSERT INTO `jy_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES ('50', 'web_site_version', '1', '版本号', '1', '', '系统版本号', '1598589978', '1598589978', '1', '2.0.0', '1');




