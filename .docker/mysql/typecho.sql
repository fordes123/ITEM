USE typecho;
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
  PRIMARY KEY (`cid`) USING BTREE,
  UNIQUE INDEX `slug`(`slug` ASC) USING BTREE,
  INDEX `created`(`created` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of item_contents
-- ----------------------------
INSERT INTO `item_contents` VALUES (2, '关于', 'start-page', 1725515390, 1725515390, '<!--markdown-->本页面由 Typecho 创建, 这只是个测试页面.', 0, 1, NULL, 'page', 'publish', NULL, 0, '1', '1', '1', 0, 0);
INSERT INTO `item_contents` VALUES (3, 'Steam', '3', 1725517459, 1725517459, '<!--markdown-->', 0, 1, NULL, 'post', 'publish', NULL, 0, '1', '1', '1', 0, 10);
INSERT INTO `item_contents` VALUES (4, 'Epic Games', '4', 1725517500, 1725517577, '<!--markdown-->', 0, 1, NULL, 'post', 'publish', NULL, 0, '1', '1', '1', 0, 9);
INSERT INTO `item_contents` VALUES (5, 'Netflix', '5', 1725517674, 1725517674, '<!--markdown-->', 0, 1, NULL, 'post', 'publish', NULL, 0, '1', '1', '1', 0, 8);
INSERT INTO `item_contents` VALUES (6, 'Disney+', '6', 1725517740, 1725517788, '<!--markdown-->', 0, 1, NULL, 'post', 'publish', NULL, 0, '1', '1', '1', 0, 7);
INSERT INTO `item_contents` VALUES (7, 'README', '7', 1725518340, 1725518533, '<!--markdown--><!-- ABOUT THE PROJECT -->\r\n\r\n<h2 id=\'1\'>🎉 项目说明</h2>\r\n\r\n[![Product Name Screen Shot][product-screenshot]](https://example.com)\r\n\r\n在编程语言中，\"item\" 这个单词常用来代表一个元素、一个选项  \r\n希望这个主题能够承载更多的 \"item\"，链接每一个选项~\r\n\r\n---\r\n\r\n<!-- GETTING STARTED -->\r\n\r\n<h2 id=\'2\'>🛠️ 快速开始</h2>\r\n\r\n<a href=\"https://vercel.com/new/clone?project-name=ITEM-vercel&repository-name=ITEM-vercel&repository-url=https://github.com/fordes123/ITEM-vercel&from=templates&integration-ids=oac_coKBVWCXNjJnCEth1zzKoF1j\"><img src=\"https://vercel.com/button\"></a>\r\n> 通过 Vercel 托管需要添加一个 MySQL 集成，如 [TiDB](https://tidbcloud.com/)、[PlanetScale](https://planetscale.com/)，参考: [Vercel 托管 Typecho](https://www.fordes.dev/posts/tutorials/typecho-vercel/)\r\n\r\n### 本地部署\r\n\r\n这是一个 Typecho 主题，因此你必须要先安装 Typecho 才能使用它，同时还需要满足以下条件:\r\n\r\n- php 7.4+\r\n- MySQL 8+\r\n\r\n1. 获取主题文件\r\n   克隆仓库源码或下载最新 [Releases](https://github.com/fordes123/ITEM/releases)，\r\n   ```shell\r\n   git clone https://github.com/fordes123/ITEM.git\r\n   ```\r\n2. 将主题文件重名为 <code>ITEM</code> 并移动至 Typecho 根目录<code>usr/themes</code> 文件夹中\r\n3. 在 Typecho 管理面板中选择更换外观并启用主题\r\n\r\n### 本地开发\r\n\r\n安装 Docker 及 Docker Compose 后，在项目根目录下执行以下命令：\r\n```shell\r\ncd .docker\r\ndocker compose up -d\r\n```\r\n打开浏览器即可访问 `http://localhost:80`，账号: `dev`，密码: `12345678`\r\n\r\n---\r\n\r\n### 配置说明\r\n\r\n#### 文章配置\r\n\r\n**文章类型** 为 **网址导航** 时，点击图标前往详情，点击其他位置直接跳转至对应url；**文章类型** 为 **普通文章** 时，\r\n代表站内文章，点击会前往文章页，所以普通文章无需跳转链接\r\n\r\n#### 分类配置\r\n\r\n分类略缩名表示对应图标名称，可用图标可在 [FontAwesome 5](https://fontawesome.com/v5/search?o=r&m=free) 图标库中浏览；  \r\n(例: FontAwesome 图标类名为 `<i class=\"fas fa-vihara\"></i>` 那么对应略缩名应为 `vihara`)\r\n\r\n#### 搜索引擎配置\r\n\r\n配置格式为 JSON，其中 icon 为 [FontAwesome 5](https://fontawesome.com/v5/search?o=r&m=free) 图标， 需要使用 **完整类名**。\r\n示例如下：\r\n\r\n```json\r\n[\r\n  {\r\n    \"name\": \"谷歌\",\r\n    \"url\": \"https://www.google.com/search?q=\",\r\n    \"icon\": \"fab fa-google\"\r\n  },\r\n  {\r\n    \"name\": \"Github\",\r\n    \"url\": \"https://github.com/search?q=\",\r\n    \"icon\": \"fab fa-github\"\r\n  }\r\n]\r\n\r\n```\r\n\r\n#### 工具直达配置\r\n\r\n配置格式为 JSON，结构类似 搜索引擎配置，增加了 `background` 控制背景色，填写 css 格式的颜色值即可。\r\n示例如下：\r\n\r\n```json\r\n[\r\n  {\r\n    \"name\": \"热榜速览\",\r\n    \"url\": \"https://www.hsmy.fun\",\r\n    \"icon\": \"fas fa-fire\",\r\n    \"background\": \"linear-gradient(45deg, #97b3ff, #2f66ff)\"\r\n  },\r\n  {\r\n    \"name\": \"地图\",\r\n    \"url\": \"https://ditu.amap.com/\",\r\n    \"icon\": \"fas fa-fire\",\r\n    \"background\": \"red\"\r\n  },\r\n  {\r\n    \"name\": \"微信文件助手\",\r\n    \"url\": \"https://filehelper.weixin.qq.com\",\r\n    \"icon\": \"fab fa-weixin\",\r\n    \"background\": \"#1ba784\"\r\n  }\r\n]\r\n```  \r\n\r\n---\r\n\r\n<!-- CONTACT -->\r\n<h2 id=\'3\'>💬 问题反馈</h2>\r\n\r\nIssues - [https://github.com/fordes123/ITEM/issues](https://github.com/fordes123/ITEM/issues)\r\n\r\n博客 - [https://fordes.dev](https://fordes.dev)\r\n\r\n---\r\n\r\n<!-- LICENSE -->\r\n<h2>📃 开源许可</h2>\r\n\r\n基于 GNU General Public License v3.0 协议开源.\r\n\r\n<!-- MARKDOWN LINKS & IMAGES -->\r\n\r\n[contributors-shield]:https://img.shields.io/github/contributors/fordes123/ITEM.svg?style=for-the-badge\r\n\r\n[contributors-url]:https://github.com/fordes123/ITEM/graphs/contributors\r\n\r\n[forks-shield]:https://img.shields.io/github/forks/fordes123/ITEM.svg?style=for-the-badge\r\n\r\n[forks-url]:https://github.com/fordes123/ITEM/network/members\r\n\r\n[stars-shield]:https://img.shields.io/github/stars/fordes123/ITEM.svg?style=for-the-badge\r\n\r\n[stars-url]:https://github.com/fordes123/ITEM/stargazers\r\n\r\n[issues-shield]:https://img.shields.io/github/issues/fordes123/ITEM.svg?style=for-the-badge\r\n\r\n[issues-url]:https://github.com/fordes123/ITEM/issues\r\n\r\n[license-shield]:https://img.shields.io/github/license/fordes123/ITEM.svg?style=for-the-badge\r\n\r\n[license-url]:https://github.com/fordes123/ITEM/blob/master/LICENSE.txt\r\n\r\n[product-screenshot]:https://github.com/fordes123/ITEM/raw/main/screenshot.png\r\n', 0, 1, NULL, 'post', 'publish', NULL, 0, '1', '1', '1', 0, 6);

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
INSERT INTO `item_fields` VALUES (3, 'logo', 'str', 'https://favicon.im/steamcommunity.com', 0, 0);
INSERT INTO `item_fields` VALUES (3, 'navigation', 'str', '1', 0, 0);
INSERT INTO `item_fields` VALUES (3, 'text', 'str', '全球最大的游戏平台', 0, 0);
INSERT INTO `item_fields` VALUES (3, 'url', 'str', 'https://steamcommunity.com/', 0, 0);
INSERT INTO `item_fields` VALUES (4, 'logo', 'str', 'https://favicon.im/store.epicgames.com?larger=true', 0, 0);
INSERT INTO `item_fields` VALUES (4, 'navigation', 'str', '1', 0, 0);
INSERT INTO `item_fields` VALUES (4, 'text', 'str', '游戏平台中最大的慈善家', 0, 0);
INSERT INTO `item_fields` VALUES (4, 'url', 'str', 'https://store.epicgames.com', 0, 0);
INSERT INTO `item_fields` VALUES (5, 'logo', 'str', 'https://favicon.im/netflix.com', 0, 0);
INSERT INTO `item_fields` VALUES (5, 'navigation', 'str', '1', 0, 0);
INSERT INTO `item_fields` VALUES (5, 'text', 'str', '又称网飞，全球知名流媒体平台', 0, 0);
INSERT INTO `item_fields` VALUES (5, 'url', 'str', 'https://www.netflix.com/', 0, 0);
INSERT INTO `item_fields` VALUES (6, 'logo', 'str', 'https://favicon.im/www.disneyplus.com', 0, 0);
INSERT INTO `item_fields` VALUES (6, 'navigation', 'str', '1', 0, 0);
INSERT INTO `item_fields` VALUES (6, 'text', 'str', '迪士尼', 0, 0);
INSERT INTO `item_fields` VALUES (6, 'url', 'str', 'https://www.disneyplus.com/', 0, 0);
INSERT INTO `item_fields` VALUES (7, 'logo', 'str', 'https://favicon.im/github.com', 0, 0);
INSERT INTO `item_fields` VALUES (7, 'navigation', 'str', '0', 0, 0);
INSERT INTO `item_fields` VALUES (7, 'text', 'str', '演示一下站内文章', 0, 0);
INSERT INTO `item_fields` VALUES (7, 'url', 'str', '', 0, 0);

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
INSERT INTO `item_metas` VALUES (2, '影音直播', 'hashtag', 'category', '', 2, 1, 0);
INSERT INTO `item_metas` VALUES (5, '游戏仓库', 'gamepad', 'category', '', 0, 2, 0);
INSERT INTO `item_metas` VALUES (6, '开源项目', 'code-branch', 'category', '', 1, 3, 0);
INSERT INTO `item_metas` VALUES (7, '游戏平台', 'chess-rook', 'category', '', 2, 1, 5);
INSERT INTO `item_metas` VALUES (8, '玩家社区', 'comments', 'category', '', 0, 2, 5);

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
INSERT INTO `item_options` VALUES ('actionTable', 0, 'a:0:{}');
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
INSERT INTO `item_options` VALUES ('commentsRequireURL', 0, '0');
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
INSERT INTO `item_options` VALUES ('generator', 0, 'Typecho 1.2.1');
INSERT INTO `item_options` VALUES ('gzip', 0, '0');
INSERT INTO `item_options` VALUES ('installed', 0, '1');
INSERT INTO `item_options` VALUES ('keywords', 0, 'typecho,php,blog');
INSERT INTO `item_options` VALUES ('lang', 0, 'zh_CN');
INSERT INTO `item_options` VALUES ('markdown', 0, '1');
INSERT INTO `item_options` VALUES ('pageSize', 0, '5');
INSERT INTO `item_options` VALUES ('panelTable', 0, 'a:0:{}');
INSERT INTO `item_options` VALUES ('plugins', 0, 'a:0:{}');
INSERT INTO `item_options` VALUES ('postDateFormat', 0, 'Y-m-d');
INSERT INTO `item_options` VALUES ('postsListSize', 0, '10');
INSERT INTO `item_options` VALUES ('rewrite', 0, '0');
INSERT INTO `item_options` VALUES ('routingTable', 0, 'a:26:{i:0;a:25:{s:5:\"index\";a:6:{s:3:\"url\";s:1:\"/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:8:\"|^[/]?$|\";s:6:\"format\";s:1:\"/\";s:6:\"params\";a:0:{}}s:7:\"archive\";a:6:{s:3:\"url\";s:6:\"/blog/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:13:\"|^/blog[/]?$|\";s:6:\"format\";s:6:\"/blog/\";s:6:\"params\";a:0:{}}s:2:\"do\";a:6:{s:3:\"url\";s:22:\"/action/[action:alpha]\";s:6:\"widget\";s:14:\"\\Widget\\Action\";s:6:\"action\";s:6:\"action\";s:4:\"regx\";s:32:\"|^/action/([_0-9a-zA-Z-]+)[/]?$|\";s:6:\"format\";s:10:\"/action/%s\";s:6:\"params\";a:1:{i:0;s:6:\"action\";}}s:4:\"post\";a:6:{s:3:\"url\";s:24:\"/archives/[cid:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:26:\"|^/archives/([0-9]+)[/]?$|\";s:6:\"format\";s:13:\"/archives/%s/\";s:6:\"params\";a:1:{i:0;s:3:\"cid\";}}s:10:\"attachment\";a:6:{s:3:\"url\";s:26:\"/attachment/[cid:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:28:\"|^/attachment/([0-9]+)[/]?$|\";s:6:\"format\";s:15:\"/attachment/%s/\";s:6:\"params\";a:1:{i:0;s:3:\"cid\";}}s:8:\"category\";a:6:{s:3:\"url\";s:17:\"/category/[slug]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:25:\"|^/category/([^/]+)[/]?$|\";s:6:\"format\";s:13:\"/category/%s/\";s:6:\"params\";a:1:{i:0;s:4:\"slug\";}}s:3:\"tag\";a:6:{s:3:\"url\";s:12:\"/tag/[slug]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:20:\"|^/tag/([^/]+)[/]?$|\";s:6:\"format\";s:8:\"/tag/%s/\";s:6:\"params\";a:1:{i:0;s:4:\"slug\";}}s:6:\"author\";a:6:{s:3:\"url\";s:22:\"/author/[uid:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:24:\"|^/author/([0-9]+)[/]?$|\";s:6:\"format\";s:11:\"/author/%s/\";s:6:\"params\";a:1:{i:0;s:3:\"uid\";}}s:6:\"search\";a:6:{s:3:\"url\";s:19:\"/search/[keywords]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:23:\"|^/search/([^/]+)[/]?$|\";s:6:\"format\";s:11:\"/search/%s/\";s:6:\"params\";a:1:{i:0;s:8:\"keywords\";}}s:10:\"index_page\";a:6:{s:3:\"url\";s:21:\"/page/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:22:\"|^/page/([0-9]+)[/]?$|\";s:6:\"format\";s:9:\"/page/%s/\";s:6:\"params\";a:1:{i:0;s:4:\"page\";}}s:12:\"archive_page\";a:6:{s:3:\"url\";s:26:\"/blog/page/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:27:\"|^/blog/page/([0-9]+)[/]?$|\";s:6:\"format\";s:14:\"/blog/page/%s/\";s:6:\"params\";a:1:{i:0;s:4:\"page\";}}s:13:\"category_page\";a:6:{s:3:\"url\";s:32:\"/category/[slug]/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:34:\"|^/category/([^/]+)/([0-9]+)[/]?$|\";s:6:\"format\";s:16:\"/category/%s/%s/\";s:6:\"params\";a:2:{i:0;s:4:\"slug\";i:1;s:4:\"page\";}}s:8:\"tag_page\";a:6:{s:3:\"url\";s:27:\"/tag/[slug]/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:29:\"|^/tag/([^/]+)/([0-9]+)[/]?$|\";s:6:\"format\";s:11:\"/tag/%s/%s/\";s:6:\"params\";a:2:{i:0;s:4:\"slug\";i:1;s:4:\"page\";}}s:11:\"author_page\";a:6:{s:3:\"url\";s:37:\"/author/[uid:digital]/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:33:\"|^/author/([0-9]+)/([0-9]+)[/]?$|\";s:6:\"format\";s:14:\"/author/%s/%s/\";s:6:\"params\";a:2:{i:0;s:3:\"uid\";i:1;s:4:\"page\";}}s:11:\"search_page\";a:6:{s:3:\"url\";s:34:\"/search/[keywords]/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:32:\"|^/search/([^/]+)/([0-9]+)[/]?$|\";s:6:\"format\";s:14:\"/search/%s/%s/\";s:6:\"params\";a:2:{i:0;s:8:\"keywords\";i:1;s:4:\"page\";}}s:12:\"archive_year\";a:6:{s:3:\"url\";s:18:\"/[year:digital:4]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:19:\"|^/([0-9]{4})[/]?$|\";s:6:\"format\";s:4:\"/%s/\";s:6:\"params\";a:1:{i:0;s:4:\"year\";}}s:13:\"archive_month\";a:6:{s:3:\"url\";s:36:\"/[year:digital:4]/[month:digital:2]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:30:\"|^/([0-9]{4})/([0-9]{2})[/]?$|\";s:6:\"format\";s:7:\"/%s/%s/\";s:6:\"params\";a:2:{i:0;s:4:\"year\";i:1;s:5:\"month\";}}s:11:\"archive_day\";a:6:{s:3:\"url\";s:52:\"/[year:digital:4]/[month:digital:2]/[day:digital:2]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:41:\"|^/([0-9]{4})/([0-9]{2})/([0-9]{2})[/]?$|\";s:6:\"format\";s:10:\"/%s/%s/%s/\";s:6:\"params\";a:3:{i:0;s:4:\"year\";i:1;s:5:\"month\";i:2;s:3:\"day\";}}s:17:\"archive_year_page\";a:6:{s:3:\"url\";s:38:\"/[year:digital:4]/page/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:33:\"|^/([0-9]{4})/page/([0-9]+)[/]?$|\";s:6:\"format\";s:12:\"/%s/page/%s/\";s:6:\"params\";a:2:{i:0;s:4:\"year\";i:1;s:4:\"page\";}}s:18:\"archive_month_page\";a:6:{s:3:\"url\";s:56:\"/[year:digital:4]/[month:digital:2]/page/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:44:\"|^/([0-9]{4})/([0-9]{2})/page/([0-9]+)[/]?$|\";s:6:\"format\";s:15:\"/%s/%s/page/%s/\";s:6:\"params\";a:3:{i:0;s:4:\"year\";i:1;s:5:\"month\";i:2;s:4:\"page\";}}s:16:\"archive_day_page\";a:6:{s:3:\"url\";s:72:\"/[year:digital:4]/[month:digital:2]/[day:digital:2]/page/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:55:\"|^/([0-9]{4})/([0-9]{2})/([0-9]{2})/page/([0-9]+)[/]?$|\";s:6:\"format\";s:18:\"/%s/%s/%s/page/%s/\";s:6:\"params\";a:4:{i:0;s:4:\"year\";i:1;s:5:\"month\";i:2;s:3:\"day\";i:3;s:4:\"page\";}}s:12:\"comment_page\";a:6:{s:3:\"url\";s:53:\"[permalink:string]/comment-page-[commentPage:digital]\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:36:\"|^(.+)/comment\\-page\\-([0-9]+)[/]?$|\";s:6:\"format\";s:18:\"%s/comment-page-%s\";s:6:\"params\";a:2:{i:0;s:9:\"permalink\";i:1;s:11:\"commentPage\";}}s:4:\"feed\";a:6:{s:3:\"url\";s:20:\"/feed[feed:string:0]\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:4:\"feed\";s:4:\"regx\";s:17:\"|^/feed(.*)[/]?$|\";s:6:\"format\";s:7:\"/feed%s\";s:6:\"params\";a:1:{i:0;s:4:\"feed\";}}s:8:\"feedback\";a:6:{s:3:\"url\";s:31:\"[permalink:string]/[type:alpha]\";s:6:\"widget\";s:16:\"\\Widget\\Feedback\";s:6:\"action\";s:6:\"action\";s:4:\"regx\";s:29:\"|^(.+)/([_0-9a-zA-Z-]+)[/]?$|\";s:6:\"format\";s:5:\"%s/%s\";s:6:\"params\";a:2:{i:0;s:9:\"permalink\";i:1;s:4:\"type\";}}s:4:\"page\";a:6:{s:3:\"url\";s:12:\"/[slug].html\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";s:4:\"regx\";s:22:\"|^/([^/]+)\\.html[/]?$|\";s:6:\"format\";s:8:\"/%s.html\";s:6:\"params\";a:1:{i:0;s:4:\"slug\";}}}s:5:\"index\";a:3:{s:3:\"url\";s:1:\"/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:7:\"archive\";a:3:{s:3:\"url\";s:6:\"/blog/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:2:\"do\";a:3:{s:3:\"url\";s:22:\"/action/[action:alpha]\";s:6:\"widget\";s:14:\"\\Widget\\Action\";s:6:\"action\";s:6:\"action\";}s:4:\"post\";a:3:{s:3:\"url\";s:24:\"/archives/[cid:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:10:\"attachment\";a:3:{s:3:\"url\";s:26:\"/attachment/[cid:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:8:\"category\";a:3:{s:3:\"url\";s:17:\"/category/[slug]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:3:\"tag\";a:3:{s:3:\"url\";s:12:\"/tag/[slug]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:6:\"author\";a:3:{s:3:\"url\";s:22:\"/author/[uid:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:6:\"search\";a:3:{s:3:\"url\";s:19:\"/search/[keywords]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:10:\"index_page\";a:3:{s:3:\"url\";s:21:\"/page/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:12:\"archive_page\";a:3:{s:3:\"url\";s:26:\"/blog/page/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:13:\"category_page\";a:3:{s:3:\"url\";s:32:\"/category/[slug]/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:8:\"tag_page\";a:3:{s:3:\"url\";s:27:\"/tag/[slug]/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:11:\"author_page\";a:3:{s:3:\"url\";s:37:\"/author/[uid:digital]/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:11:\"search_page\";a:3:{s:3:\"url\";s:34:\"/search/[keywords]/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:12:\"archive_year\";a:3:{s:3:\"url\";s:18:\"/[year:digital:4]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:13:\"archive_month\";a:3:{s:3:\"url\";s:36:\"/[year:digital:4]/[month:digital:2]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:11:\"archive_day\";a:3:{s:3:\"url\";s:52:\"/[year:digital:4]/[month:digital:2]/[day:digital:2]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:17:\"archive_year_page\";a:3:{s:3:\"url\";s:38:\"/[year:digital:4]/page/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:18:\"archive_month_page\";a:3:{s:3:\"url\";s:56:\"/[year:digital:4]/[month:digital:2]/page/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:16:\"archive_day_page\";a:3:{s:3:\"url\";s:72:\"/[year:digital:4]/[month:digital:2]/[day:digital:2]/page/[page:digital]/\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:12:\"comment_page\";a:3:{s:3:\"url\";s:53:\"[permalink:string]/comment-page-[commentPage:digital]\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}s:4:\"feed\";a:3:{s:3:\"url\";s:20:\"/feed[feed:string:0]\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:4:\"feed\";}s:8:\"feedback\";a:3:{s:3:\"url\";s:31:\"[permalink:string]/[type:alpha]\";s:6:\"widget\";s:16:\"\\Widget\\Feedback\";s:6:\"action\";s:6:\"action\";}s:4:\"page\";a:3:{s:3:\"url\";s:12:\"/[slug].html\";s:6:\"widget\";s:15:\"\\Widget\\Archive\";s:6:\"action\";s:6:\"render\";}}');
INSERT INTO `item_options` VALUES ('secret', 0, 'GbjvNwq6kvqgH5J*9gusikOQlO5kfl(5');
INSERT INTO `item_options` VALUES ('siteUrl', 0, 'http://localhost');
INSERT INTO `item_options` VALUES ('theme', 0, 'ITEM');
INSERT INTO `item_options` VALUES ('theme:ITEM', 0, 'a:6:{s:7:\"favicon\";s:57:\"http://localhost/usr/themes/ITEM/assets/image/favicon.ico\";s:7:\"biglogo\";s:54:\"http://localhost/usr/themes/ITEM/assets/image/head.png\";s:9:\"smalllogo\";s:57:\"http://localhost/usr/themes/ITEM/assets/image/favicon.ico\";s:12:\"searchConfig\";s:508:\"[\r\n            {\r\n                \"name\": \"谷歌\",\r\n                \"url\": \"https://www.google.com/search?q=\",\r\n                \"icon\": \"fab fa-google\"\r\n            },\r\n            {\r\n                \"name\": \"Yandex\",\r\n                \"url\": \"https://yandex.com/search/?text=\",\r\n                \"icon\": \"fab fa-yandex\"\r\n            },\r\n            {\r\n                \"name\": \"Github\",\r\n                \"url\": \"https://github.com/search?q=\",\r\n                \"icon\": \"fab fa-github\"\r\n            }\r\n        ]\";s:10:\"toolConfig\";s:659:\"[\r\n            {\r\n                \"name\": \"热榜速览\",\r\n                \"url\": \"https://www.hsmy.fun\",\r\n                \"icon\": \"fas fa-fire\",\r\n                \"background\": \"linear-gradient(45deg, #97b3ff, #2f66ff)\"\r\n            },\r\n            {\r\n                \"name\": \"地图\",\r\n                \"url\": \"https://ditu.amap.com/\",\r\n                \"icon\": \"fas fa-fire\",\r\n                \"background\": \"red\"\r\n            },\r\n            {\r\n                \"name\": \"微信文件助手\",\r\n                \"url\": \"https://filehelper.weixin.qq.com\",\r\n                \"icon\": \"fab fa-weixin\",\r\n                \"background\": \"#1ba784\"\r\n            }\r\n        ]\";s:3:\"icp\";N;}');
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
INSERT INTO `item_relationships` VALUES (3, 7);
INSERT INTO `item_relationships` VALUES (4, 7);
INSERT INTO `item_relationships` VALUES (5, 2);
INSERT INTO `item_relationships` VALUES (6, 2);
INSERT INTO `item_relationships` VALUES (7, 6);

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
INSERT INTO `item_users` VALUES (1, 'dev', '$P$Bi9wNVmP3UFcCr8iVkgrARn06geFrd0', 'none@example.org', 'http://localhost', 'dev', 1725515390, 1725518648, 0, 'administrator', 'ee02d1b0c41b1e7bbad1b7d0bc4714a8');

SET FOREIGN_KEY_CHECKS = 1;
