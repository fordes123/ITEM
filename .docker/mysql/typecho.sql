SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for item_comments
-- ----------------------------
DROP TABLE IF EXISTS `item_comments`;
CREATE TABLE `item_comments`  (
  `coid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `cid` int UNSIGNED NULL DEFAULT 0,
  `created` int UNSIGNED NULL DEFAULT 0,
  `author` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `authorId` int UNSIGNED NULL DEFAULT 0,
  `ownerId` int UNSIGNED NULL DEFAULT 0,
  `mail` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `agent` varchar(511) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `type` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'comment',
  `status` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'approved',
  `parent` int UNSIGNED NULL DEFAULT 0,
  PRIMARY KEY (`coid`) USING BTREE,
  INDEX `cid`(`cid` ASC) USING BTREE,
  INDEX `created`(`created` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of item_comments
-- ----------------------------

-- ----------------------------
-- Table structure for item_contents
-- ----------------------------
DROP TABLE IF EXISTS `item_contents`;
CREATE TABLE `item_contents`  (
  `cid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `slug` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created` int UNSIGNED NULL DEFAULT 0,
  `modified` int UNSIGNED NULL DEFAULT 0,
  `text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `order` int UNSIGNED NULL DEFAULT 0,
  `authorId` int UNSIGNED NULL DEFAULT 0,
  `template` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `type` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'post',
  `status` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'publish',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `commentsNum` int UNSIGNED NULL DEFAULT 0,
  `allowComment` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '0',
  `allowPing` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '0',
  `allowFeed` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '0',
  `parent` int UNSIGNED NULL DEFAULT 0,
  `views` int NOT NULL DEFAULT 0,
  `agree` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`cid`) USING BTREE,
  UNIQUE INDEX `slug`(`slug` ASC) USING BTREE,
  INDEX `created`(`created` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of item_contents
-- ----------------------------
INSERT INTO `item_contents` VALUES (2, '关于', 'start-page', 1725515390, 1725515390, '<!--markdown-->本页面由 Typecho 创建, 这只是个测试页面.', 0, 1, NULL, 'page', 'publish', NULL, 0, '1', '1', '1', 0, 1, 0);
INSERT INTO `item_contents` VALUES (3, 'JavaScript', '3', 1725517440, 1769527216, '<!--markdown-->', 0, 1, NULL, 'post', 'publish', NULL, 0, '1', '1', '1', 0, 12, 0);
INSERT INTO `item_contents` VALUES (4, 'PHP', '4', 1725517500, 1769527211, '<!--markdown-->一种流行的通用脚本语言，特别适合网页开发。\r\nPHP 快速、灵活且实用，支持从您的博客到全球最受欢迎的网站。', 0, 1, NULL, 'post', 'publish', NULL, 0, '1', '1', '1', 0, 11, 0);
INSERT INTO `item_contents` VALUES (5, 'MySQL', '5', 1725517620, 1769526542, '<!--markdown-->', 0, 1, NULL, 'post', 'publish', NULL, 0, '1', '1', '1', 0, 11, 1);
INSERT INTO `item_contents` VALUES (6, 'Typecho', '6', 1725517740, 1769526465, '<!--markdown-->', 0, 1, NULL, 'post', 'publish', NULL, 0, '1', '1', '1', 0, 13, 2);
INSERT INTO `item_contents` VALUES (7, 'README', '7', 1725518340, 1769700625, '<!--markdown--><p>\r\n  <a href=\"https://github.com/fordes123/ITEM/releases\"><img src=\"https://img.shields.io/github/v/release/fordes123/ITEM\" alt=\"last releases\" /></a>\r\n  <a href=\"https://github.com/fordes123/ITEM/actions/workflows/build.yaml\"><img src=\"https://img.shields.io/github/actions/workflow/status/fordes123/ITEM/build.yaml\" alt=\"build status\" /></a>\r\n  <a href=\"https://github.com/fordes123/ITEM/blob/main/LICENSE.txt\"><img src=\"https://img.shields.io/github/license/fordes123/ITEM\" alt=\"license\" /></a>\r\n</p>\r\n\r\n> ✨ Hugo 版现已推出：[hugo-theme-item](https://github.com/fordes123/hugo-theme-item)\r\n\r\n在编程语言中，\"item\" 这个单词常用来代表一个元素、一个选项  \r\n所以我们以此来命名这个网址导航主题，希望它能够承载更多的 \"item\"，链接每一个选项~\r\n\r\n[文档](https://github.com/fordes123/ITEM/wiki) | [示例站点](https://www.item.ink)\r\n![screenshot](https://github.com/user-attachments/assets/e136be3a-b9fe-48b2-803b-8023edf25309)', 0, 1, NULL, 'post', 'publish', '123456', 0, '1', '1', '1', 0, 15, 1);
INSERT INTO `item_contents` VALUES (8, 'Bootstrap', '8', 1769526900, 1769527189, '<!--markdown-->', 0, 1, NULL, 'post', 'publish', NULL, 0, '1', '1', '1', 0, 9, 1);
INSERT INTO `item_contents` VALUES (9, '演示站点', '9', 1769527020, 1770193647, '<!--markdown-->', 0, 1, NULL, 'post', 'publish', NULL, 0, '1', '1', '1', 0, 2, 0);

-- ----------------------------
-- Table structure for item_fields
-- ----------------------------
DROP TABLE IF EXISTS `item_fields`;
CREATE TABLE `item_fields`  (
  `cid` int UNSIGNED NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'str',
  `str_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `int_value` int NULL DEFAULT 0,
  `float_value` float NULL DEFAULT 0,
  PRIMARY KEY (`cid`, `name`) USING BTREE,
  INDEX `int_value`(`int_value` ASC) USING BTREE,
  INDEX `float_value`(`float_value` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of item_fields
-- ----------------------------
INSERT INTO `item_fields` VALUES (3, 'logo', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (3, 'navigation', 'str', '1', 0, 0);
INSERT INTO `item_fields` VALUES (3, 'score', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (3, 'text', 'str', '你和Java什么关系？', 0, 0);
INSERT INTO `item_fields` VALUES (3, 'url', 'str', 'https://www.javascript.com/', 0, 0);
INSERT INTO `item_fields` VALUES (4, 'logo', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (4, 'navigation', 'str', '1', 0, 0);
INSERT INTO `item_fields` VALUES (4, 'score', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (4, 'text', 'str', '谁赞成，谁反对？', 0, 0);
INSERT INTO `item_fields` VALUES (4, 'url', 'str', 'https://www.php.net/', 0, 0);
INSERT INTO `item_fields` VALUES (5, 'logo', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (5, 'navigation', 'str', '1', 0, 0);
INSERT INTO `item_fields` VALUES (5, 'score', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (5, 'text', 'str', '全球最受欢迎的开源数据库', 0, 0);
INSERT INTO `item_fields` VALUES (5, 'url', 'str', 'https://www.mysql.com/', 0, 0);
INSERT INTO `item_fields` VALUES (6, 'logo', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (6, 'navigation', 'str', '1', 0, 0);
INSERT INTO `item_fields` VALUES (6, 'score', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (6, 'text', 'str', '念念不忘，必有回响', 0, 0);
INSERT INTO `item_fields` VALUES (6, 'url', 'str', 'https://typecho.org/', 0, 0);
INSERT INTO `item_fields` VALUES (7, 'description', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (7, 'encryptTip', 'str', '密码 123456', 0, 0);
INSERT INTO `item_fields` VALUES (7, 'keywords', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (7, 'logo', 'str', 'https://favicon.im/github.com', 0, 0);
INSERT INTO `item_fields` VALUES (7, 'navigation', 'str', '0', 0, 0);
INSERT INTO `item_fields` VALUES (7, 'score', 'str', '5.0', 0, 0);
INSERT INTO `item_fields` VALUES (7, 'text', 'str', '这是站内文章', 0, 0);
INSERT INTO `item_fields` VALUES (7, 'url', 'str', 'https://github.com/fordes123/ITEM/', 0, 0);
INSERT INTO `item_fields` VALUES (8, 'logo', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (8, 'navigation', 'str', '1', 0, 0);
INSERT INTO `item_fields` VALUES (8, 'score', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (8, 'text', 'str', '构建快速响应式网站', 0, 0);
INSERT INTO `item_fields` VALUES (8, 'url', 'str', 'https://getbootstrap.com/', 0, 0);
INSERT INTO `item_fields` VALUES (9, 'description', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (9, 'encryptTip', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (9, 'keywords', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (9, 'logo', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (9, 'navigation', 'str', '1', 0, 0);
INSERT INTO `item_fields` VALUES (9, 'score', 'str', '', 0, 0);
INSERT INTO `item_fields` VALUES (9, 'text', 'str', '这是一个希望能成为你的主页的导航站', 0, 0);
INSERT INTO `item_fields` VALUES (9, 'url', 'str', 'https://www.item.ink/', 0, 0);

-- ----------------------------
-- Table structure for item_metas
-- ----------------------------
DROP TABLE IF EXISTS `item_metas`;
CREATE TABLE `item_metas`  (
  `mid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `slug` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `count` int UNSIGNED NULL DEFAULT 0,
  `order` int UNSIGNED NULL DEFAULT 0,
  `parent` int UNSIGNED NULL DEFAULT 0,
  PRIMARY KEY (`mid`) USING BTREE,
  INDEX `slug`(`slug` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of item_metas
-- ----------------------------
INSERT INTO `item_metas` VALUES (2, '开发组件', 'hashtag', 'category', '', 5, 1, 0);
INSERT INTO `item_metas` VALUES (6, '项目主页', 'code-branch', 'category', '', 3, 2, 0);

-- ----------------------------
-- Table structure for item_options
-- ----------------------------
DROP TABLE IF EXISTS `item_options`;
CREATE TABLE `item_options`  (
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user` int UNSIGNED NOT NULL DEFAULT 0,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`name`, `user`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of item_options
-- ----------------------------
INSERT INTO `item_options` VALUES ('actionTable', 0, '[]');
INSERT INTO `item_options` VALUES ('allowRegister', 0, '0');
INSERT INTO `item_options` VALUES ('allowXmlRpc', 0, '2');
INSERT INTO `item_options` VALUES ('attachmentTypes', 0, '@image@');
INSERT INTO `item_options` VALUES ('autoSave', 0, '0');
INSERT INTO `item_options` VALUES ('charset', 0, 'UTF-8');
INSERT INTO `item_options` VALUES ('commentDateFormat', 0, 'F jS, Y \\a\\t h:i a');
INSERT INTO `item_options` VALUES ('commentsAntiSpam', 0, '1');
INSERT INTO `item_options` VALUES ('commentsAutoClose', 0, '0');
INSERT INTO `item_options` VALUES ('commentsAvatar', 0, '1');
INSERT INTO `item_options` VALUES ('commentsAvatarRating', 0, 'G');
INSERT INTO `item_options` VALUES ('commentsCheckReferer', 0, '1');
INSERT INTO `item_options` VALUES ('commentsHTMLTagAllowed', 0, NULL);
INSERT INTO `item_options` VALUES ('commentsListSize', 0, '10');
INSERT INTO `item_options` VALUES ('commentsMarkdown', 0, '0');
INSERT INTO `item_options` VALUES ('commentsMaxNestingLevels', 0, '5');
INSERT INTO `item_options` VALUES ('commentsOrder', 0, 'ASC');
INSERT INTO `item_options` VALUES ('commentsPageBreak', 0, '0');
INSERT INTO `item_options` VALUES ('commentsPageDisplay', 0, 'last');
INSERT INTO `item_options` VALUES ('commentsPageSize', 0, '20');
INSERT INTO `item_options` VALUES ('commentsPostInterval', 0, '60');
INSERT INTO `item_options` VALUES ('commentsPostIntervalEnable', 0, '1');
INSERT INTO `item_options` VALUES ('commentsPostTimeout', 0, '2592000');
INSERT INTO `item_options` VALUES ('commentsRequireMail', 0, '1');
INSERT INTO `item_options` VALUES ('commentsRequireModeration', 0, '0');
INSERT INTO `item_options` VALUES ('commentsRequireUrl', 0, '0');
INSERT INTO `item_options` VALUES ('commentsShowCommentOnly', 0, '0');
INSERT INTO `item_options` VALUES ('commentsShowUrl', 0, '1');
INSERT INTO `item_options` VALUES ('commentsThreaded', 0, '1');
INSERT INTO `item_options` VALUES ('commentsUrlNofollow', 0, '1');
INSERT INTO `item_options` VALUES ('commentsWhitelist', 0, '0');
INSERT INTO `item_options` VALUES ('contentType', 0, 'text/html');
INSERT INTO `item_options` VALUES ('defaultAllowComment', 0, '1');
INSERT INTO `item_options` VALUES ('defaultAllowFeed', 0, '1');
INSERT INTO `item_options` VALUES ('defaultAllowPing', 0, '1');
INSERT INTO `item_options` VALUES ('defaultCategory', 0, '1');
INSERT INTO `item_options` VALUES ('description', 0, 'Your description here.');
INSERT INTO `item_options` VALUES ('editorSize', 0, '350');
INSERT INTO `item_options` VALUES ('feedFullText', 0, '1');
INSERT INTO `item_options` VALUES ('frontArchive', 0, '0');
INSERT INTO `item_options` VALUES ('frontPage', 0, 'recent');
INSERT INTO `item_options` VALUES ('generator', 0, 'Typecho 1.3.0');
INSERT INTO `item_options` VALUES ('gzip', 0, '0');
INSERT INTO `item_options` VALUES ('installed', 0, '1');
INSERT INTO `item_options` VALUES ('keywords', 0, 'typecho,php,blog');
INSERT INTO `item_options` VALUES ('lang', 0, 'zh_CN');
INSERT INTO `item_options` VALUES ('markdown', 0, '1');
INSERT INTO `item_options` VALUES ('pageSize', 0, '5');
INSERT INTO `item_options` VALUES ('panelTable', 0, '[]');
INSERT INTO `item_options` VALUES ('plugins', 0, '[]');
INSERT INTO `item_options` VALUES ('postDateFormat', 0, 'Y-m-d');
INSERT INTO `item_options` VALUES ('postsListSize', 0, '10');
INSERT INTO `item_options` VALUES ('rewrite', 0, '0');
INSERT INTO `item_options` VALUES ('routingTable', 0, '{\"0\":{\"index\":{\"url\":\"\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^[\\/]?$|\",\"format\":\"\\/\",\"params\":[]},\"archive\":{\"url\":\"\\/blog\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/blog[\\/]?$|\",\"format\":\"\\/blog\\/\",\"params\":[]},\"do\":{\"url\":\"\\/action\\/[action:alpha]\",\"widget\":\"\\\\Widget\\\\Action\",\"action\":\"action\",\"regx\":\"|^\\/action\\/([_0-9a-zA-Z-]+)[\\/]?$|\",\"format\":\"\\/action\\/%s\",\"params\":[\"action\"]},\"post\":{\"url\":\"\\/archives\\/[cid:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/archives\\/([0-9]+)[\\/]?$|\",\"format\":\"\\/archives\\/%s\\/\",\"params\":[\"cid\"]},\"attachment\":{\"url\":\"\\/attachment\\/[cid:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/attachment\\/([0-9]+)[\\/]?$|\",\"format\":\"\\/attachment\\/%s\\/\",\"params\":[\"cid\"]},\"category\":{\"url\":\"\\/category\\/[slug]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/category\\/([^\\/]+)[\\/]?$|\",\"format\":\"\\/category\\/%s\\/\",\"params\":[\"slug\"]},\"tag\":{\"url\":\"\\/tag\\/[slug]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/tag\\/([^\\/]+)[\\/]?$|\",\"format\":\"\\/tag\\/%s\\/\",\"params\":[\"slug\"]},\"author\":{\"url\":\"\\/author\\/[uid:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/author\\/([0-9]+)[\\/]?$|\",\"format\":\"\\/author\\/%s\\/\",\"params\":[\"uid\"]},\"search\":{\"url\":\"\\/search\\/[keywords]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/search\\/([^\\/]+)[\\/]?$|\",\"format\":\"\\/search\\/%s\\/\",\"params\":[\"keywords\"]},\"index_page\":{\"url\":\"\\/page\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/page\\/([0-9]+)[\\/]?$|\",\"format\":\"\\/page\\/%s\\/\",\"params\":[\"page\"]},\"archive_page\":{\"url\":\"\\/blog\\/page\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/blog\\/page\\/([0-9]+)[\\/]?$|\",\"format\":\"\\/blog\\/page\\/%s\\/\",\"params\":[\"page\"]},\"category_page\":{\"url\":\"\\/category\\/[slug]\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/category\\/([^\\/]+)\\/([0-9]+)[\\/]?$|\",\"format\":\"\\/category\\/%s\\/%s\\/\",\"params\":[\"slug\",\"page\"]},\"tag_page\":{\"url\":\"\\/tag\\/[slug]\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/tag\\/([^\\/]+)\\/([0-9]+)[\\/]?$|\",\"format\":\"\\/tag\\/%s\\/%s\\/\",\"params\":[\"slug\",\"page\"]},\"author_page\":{\"url\":\"\\/author\\/[uid:digital]\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/author\\/([0-9]+)\\/([0-9]+)[\\/]?$|\",\"format\":\"\\/author\\/%s\\/%s\\/\",\"params\":[\"uid\",\"page\"]},\"search_page\":{\"url\":\"\\/search\\/[keywords]\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/search\\/([^\\/]+)\\/([0-9]+)[\\/]?$|\",\"format\":\"\\/search\\/%s\\/%s\\/\",\"params\":[\"keywords\",\"page\"]},\"archive_year\":{\"url\":\"\\/[year:digital:4]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/([0-9]{4})[\\/]?$|\",\"format\":\"\\/%s\\/\",\"params\":[\"year\"]},\"archive_month\":{\"url\":\"\\/[year:digital:4]\\/[month:digital:2]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/([0-9]{4})\\/([0-9]{2})[\\/]?$|\",\"format\":\"\\/%s\\/%s\\/\",\"params\":[\"year\",\"month\"]},\"archive_day\":{\"url\":\"\\/[year:digital:4]\\/[month:digital:2]\\/[day:digital:2]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/([0-9]{4})\\/([0-9]{2})\\/([0-9]{2})[\\/]?$|\",\"format\":\"\\/%s\\/%s\\/%s\\/\",\"params\":[\"year\",\"month\",\"day\"]},\"archive_year_page\":{\"url\":\"\\/[year:digital:4]\\/page\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/([0-9]{4})\\/page\\/([0-9]+)[\\/]?$|\",\"format\":\"\\/%s\\/page\\/%s\\/\",\"params\":[\"year\",\"page\"]},\"archive_month_page\":{\"url\":\"\\/[year:digital:4]\\/[month:digital:2]\\/page\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/([0-9]{4})\\/([0-9]{2})\\/page\\/([0-9]+)[\\/]?$|\",\"format\":\"\\/%s\\/%s\\/page\\/%s\\/\",\"params\":[\"year\",\"month\",\"page\"]},\"archive_day_page\":{\"url\":\"\\/[year:digital:4]\\/[month:digital:2]\\/[day:digital:2]\\/page\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/([0-9]{4})\\/([0-9]{2})\\/([0-9]{2})\\/page\\/([0-9]+)[\\/]?$|\",\"format\":\"\\/%s\\/%s\\/%s\\/page\\/%s\\/\",\"params\":[\"year\",\"month\",\"day\",\"page\"]},\"comment_page\":{\"url\":\"[permalink:string]\\/comment-page-[commentPage:digital]\",\"widget\":\"\\\\Widget\\\\CommentPage\",\"action\":\"action\",\"regx\":\"|^(.+)\\/comment\\\\-page\\\\-([0-9]+)[\\/]?$|\",\"format\":\"%s\\/comment-page-%s\",\"params\":[\"permalink\",\"commentPage\"]},\"feed\":{\"url\":\"\\/feed[feed:string:0]\",\"widget\":\"\\\\Widget\\\\Feed\",\"action\":\"render\",\"regx\":\"|^\\/feed(.*)[\\/]?$|\",\"format\":\"\\/feed%s\",\"params\":[\"feed\"]},\"feedback\":{\"url\":\"[permalink:string]\\/[type:alpha]\",\"widget\":\"\\\\Widget\\\\Feedback\",\"action\":\"action\",\"regx\":\"|^(.+)\\/([_0-9a-zA-Z-]+)[\\/]?$|\",\"format\":\"%s\\/%s\",\"params\":[\"permalink\",\"type\"]},\"page\":{\"url\":\"\\/[slug].html\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\",\"regx\":\"|^\\/([^\\/]+)\\\\.html[\\/]?$|\",\"format\":\"\\/%s.html\",\"params\":[\"slug\"]}},\"index\":{\"url\":\"\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"archive\":{\"url\":\"\\/blog\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"do\":{\"url\":\"\\/action\\/[action:alpha]\",\"widget\":\"\\\\Widget\\\\Action\",\"action\":\"action\"},\"post\":{\"url\":\"\\/archives\\/[cid:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"attachment\":{\"url\":\"\\/attachment\\/[cid:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"category\":{\"url\":\"\\/category\\/[slug]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"tag\":{\"url\":\"\\/tag\\/[slug]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"author\":{\"url\":\"\\/author\\/[uid:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"search\":{\"url\":\"\\/search\\/[keywords]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"index_page\":{\"url\":\"\\/page\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"archive_page\":{\"url\":\"\\/blog\\/page\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"category_page\":{\"url\":\"\\/category\\/[slug]\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"tag_page\":{\"url\":\"\\/tag\\/[slug]\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"author_page\":{\"url\":\"\\/author\\/[uid:digital]\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"search_page\":{\"url\":\"\\/search\\/[keywords]\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"archive_year\":{\"url\":\"\\/[year:digital:4]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"archive_month\":{\"url\":\"\\/[year:digital:4]\\/[month:digital:2]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"archive_day\":{\"url\":\"\\/[year:digital:4]\\/[month:digital:2]\\/[day:digital:2]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"archive_year_page\":{\"url\":\"\\/[year:digital:4]\\/page\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"archive_month_page\":{\"url\":\"\\/[year:digital:4]\\/[month:digital:2]\\/page\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"archive_day_page\":{\"url\":\"\\/[year:digital:4]\\/[month:digital:2]\\/[day:digital:2]\\/page\\/[page:digital]\\/\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"},\"comment_page\":{\"url\":\"[permalink:string]\\/comment-page-[commentPage:digital]\",\"widget\":\"\\\\Widget\\\\CommentPage\",\"action\":\"action\"},\"feed\":{\"url\":\"\\/feed[feed:string:0]\",\"widget\":\"\\\\Widget\\\\Feed\",\"action\":\"render\"},\"feedback\":{\"url\":\"[permalink:string]\\/[type:alpha]\",\"widget\":\"\\\\Widget\\\\Feedback\",\"action\":\"action\"},\"page\":{\"url\":\"\\/[slug].html\",\"widget\":\"\\\\Widget\\\\Archive\",\"action\":\"render\"}}');
INSERT INTO `item_options` VALUES ('secret', 0, 'GbjvNwq6kvqgH5J*9gusikOQlO5kfl(5');
INSERT INTO `item_options` VALUES ('siteUrl', 0, 'http://localhost');
INSERT INTO `item_options` VALUES ('theme', 0, 'ITEM');
INSERT INTO `item_options` VALUES ('theme:ITEM', 0, '{\"favicon\":\"http:\\/\\/localhost\\/usr\\/themes\\/ITEM\\/assets\\/image\\/favicon.ico\",\"smalllogo\":\"http:\\/\\/localhost\\/usr\\/themes\\/ITEM\\/assets\\/image\\/favicon.ico\",\"biglogo\":\"http:\\/\\/localhost\\/usr\\/themes\\/ITEM\\/assets\\/image\\/head.png\",\"icp\":null,\"searchConfig\":\"[\\r\\n            {\\r\\n                \\\"name\\\": \\\"\\u7ad9\\u5185\\\",\\r\\n                \\\"url\\\": \\\"\\/search\\/\\\",\\r\\n                \\\"icon\\\": \\\"fa-solid fa-search-location\\\"\\r\\n            },\\r\\n            {\\r\\n                \\\"name\\\": \\\"Github\\\",\\r\\n                \\\"url\\\": \\\"https:\\/\\/github.com\\/search?q=\\\",\\r\\n                \\\"icon\\\": \\\"fa-brands fa-github\\\"\\r\\n            }\\r\\n        ]\",\"toolConfig\":null,\"subCategoryType\":\"0\",\"timelinePageSize\":\"5\",\"faviconApiSelect\":\"https:\\/\\/favicon.im\\/{hostname}?larger=true\",\"gravatarApiSelect\":\"https:\\/\\/weavatar.com\\/avatar\\/{hash}\",\"weatherApiKey\":null,\"weatherNode\":\"0\",\"faviconApi\":null,\"gravatarApi\":null,\"customHeader\":null,\"customFooter\":null}');
INSERT INTO `item_options` VALUES ('theme:ITEM::version', 0, '2.0.0');
INSERT INTO `item_options` VALUES ('timezone', 0, '28800');
INSERT INTO `item_options` VALUES ('title', 0, 'Hello World');
INSERT INTO `item_options` VALUES ('xmlrpcMarkdown', 0, '0');

-- ----------------------------
-- Table structure for item_relationships
-- ----------------------------
DROP TABLE IF EXISTS `item_relationships`;
CREATE TABLE `item_relationships`  (
  `cid` int UNSIGNED NOT NULL,
  `mid` int UNSIGNED NOT NULL,
  PRIMARY KEY (`cid`, `mid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of item_relationships
-- ----------------------------
INSERT INTO `item_relationships` VALUES (3, 2);
INSERT INTO `item_relationships` VALUES (4, 2);
INSERT INTO `item_relationships` VALUES (5, 2);
INSERT INTO `item_relationships` VALUES (6, 2);
INSERT INTO `item_relationships` VALUES (7, 6);
INSERT INTO `item_relationships` VALUES (8, 2);
INSERT INTO `item_relationships` VALUES (9, 6);

-- ----------------------------
-- Table structure for item_users
-- ----------------------------
DROP TABLE IF EXISTS `item_users`;
CREATE TABLE `item_users`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `mail` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `url` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `screenName` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created` int UNSIGNED NULL DEFAULT 0,
  `activated` int UNSIGNED NULL DEFAULT 0,
  `logged` int UNSIGNED NULL DEFAULT 0,
  `group` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'visitor',
  `authCode` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `name`(`name` ASC) USING BTREE,
  UNIQUE INDEX `mail`(`mail` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of item_users
-- ----------------------------
INSERT INTO `item_users` VALUES (1, 'dev', '$P$Bi9wNVmP3UFcCr8iVkgrARn06geFrd0', '52121395+fordes123@users.noreply.github.com', 'https://github.com/fordes123', '开发者', 1725515390, 1770194059, 1770044549, 'administrator', '17dac933259cb878abb28cf530808c99');

SET FOREIGN_KEY_CHECKS = 1;
