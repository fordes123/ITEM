<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;
error_reporting(0);
require_once('core/Api.php');
require_once('core/Config.php');
require_once('core/Repository.php');
require_once('core/Helper.php');
require_once('core/View.php');

/**
 * 主题初始化
 */
function themeInit($source)
{
    $request = Typecho_Request::getInstance();
    $action = $request->get('action');
    if ($action) {
        ThemeApi::dispatch($action);
    } else {
        ThemeRepository::init();
    }
}

/**
 * 添加主题设置项
 */
function themeConfig(Typecho_Widget_Helper_Form $form)
{
    $options = Helper::options();
?>
    <link rel="stylesheet" href="<?php $options->themeUrl('./assets/css/backend-ui.min.css'); ?>">
    <script defer src="<?php $options->themeUrl('./assets/js/backend-ui.min.js'); ?>"></script>
    <div class="col-mb-12">
        <div class="badge">
            <img src="<?php $options->themeUrl('./assets/image/favicon.ico'); ?>" alt="ITEM">
            <h1>ITEM<sup id="currentVersion"><?php echo ThemeConfig::THEME_VERSION ?></sup></h1>
        </div>
        <p><span id="versionDesc">🎉 欢迎使用 ITEM 主题 ~</span></p>
        <ul id="start-link">
            <li><a href="https://github.com/fordes123/ITEM">项目主页</a></li>
            <li><a href="https://github.com/fordes123/ITEM/issues">问题反馈</a></li>
            <li><a id="checkUpdate">检查更新</a></li>
        </ul>
    </div>
<?php

    $baseSetting = new Typecho_Widget_Helper_Layout();
    $baseSetting->html(_t('<h2>基础设置</h2>'));
    $form->addItem($baseSetting);

    //网站图标
    $favicon = new Typecho_Widget_Helper_Form_Element_Text(
        'favicon',
        NULL,
        $options->themeUrl . '/assets/image/favicon.ico',
        _t('网站图标'),
        _t('应为 <b>ico</b> 格式，亦可将此项留空，直接将 <b>favicon.ico</b> 图标文件替换到主题 <b>assets/image</b> 路径下')
    );
    $form->addInput($favicon->addRule('url', _t('请填入一个有效的URL')));

    //首页小logo
    $smalllogo = new Typecho_Widget_Helper_Form_Element_Text(
        'smalllogo',
        NULL,
        $options->themeUrl . '/assets/image/favicon.ico',
        _t('侧边图标'),
        _t('侧边栏收缩时的图标，建议和网站图标保持一致')
    );
    $form->addInput($smalllogo->addRule('url', _t('请填入一个有效的URL')));

    //首页大logo
    $biglogo = new Typecho_Widget_Helper_Form_Element_Text(
        'biglogo',
        NULL,
        $options->themeUrl . '/assets/image/head.png',
        _t('横幅图标'),
        _t('侧边展开时的图标，推荐尺寸: 824x200')
    );
    $form->addInput($biglogo->addRule('url', _t('请填入一个有效的URL')));

    //备案号
    $icp = new Typecho_Widget_Helper_Form_Element_Text(
        'icp',
        NULL,
        NULL,
        _t('备案号'),
        _t('有就填没有就不填不要乱填')
    );
    $form->addInput($icp);

    $advancedSetting = new Typecho_Widget_Helper_Layout();
    $advancedSetting->html(_t('<hr color="#ECECEC"/><h2>进阶设置</h2>'));
    $form->addItem($advancedSetting);

    // 搜索引擎
    $searchConfig = new Typecho_Widget_Helper_Form_Element_Textarea(
        'searchConfig',
        NULL,
        '[
            {
                "name": "站内",
                "url": "/search/",
                "icon": "fa-solid fa-search-location"
            },
            {
                "name": "Github",
                "url": "https://github.com/search?q=",
                "icon": "fa-brands fa-github"
            }
        ]',
        _t('搜索引擎配置'),
        _t('首页搜索引擎配置信息，应为有效 JSON Array')
    );
    $form->addInput($searchConfig);

    // 工具直达
    $toolConfig = new Typecho_Widget_Helper_Form_Element_Textarea(
        'toolConfig',
        NULL,
        '[
            {
                "name": "主题文档",
                "url": "https://github.com/fordes123/ITEM",
                "icon": "fa-solid fa-book",
                "background": "linear-gradient(45deg, #97b3ff, #2f66ff)"
            },
            {
                "name": "求个star",
                "url": "https://github.com/fordes123/ITEM",
                "icon": "fa-solid fa-star",
                "background": "red"
            }
        ]',
        _t('工具直达配置'),
        _t('首页工具直达配置信息，应为有效 JSON Array')
    );
    $form->addInput($toolConfig);

    //子分类的展示方式
    $subCategoryType = new Typecho_Widget_Helper_Form_Element_Radio(
        'subCategoryType',
        array(
            '0' => '平铺',
            '1' => '收纳',
        ),
        '0',
        _t('子分类的展示方式'),
        _t('默认为 <b>平铺</b>，如果文章较多推荐使用 <b>收纳</b>，可减少首页数据查询从而提高加载速度')
    );
    $form->addInput($subCategoryType);

    //时间线分页页大小
    $timelinePageSize = new Typecho_Widget_Helper_Form_Element_Text(
        'timelinePageSize',
        NULL,
        5,
        _t('时间线每页文章数'),
        _t('默认 5，应为有效正整数且不宜过大')
    );
    $form->addInput($timelinePageSize->addRule('isInteger', _t('请填入一个数字')));

    //Favicon API选择
    $faviconApiSelect = new Typecho_Widget_Helper_Form_Element_Select(
        'faviconApiSelect',
        array(
            'https://favicon.im/{hostname}?larger=true' => 'Favicon.im (默认)',
            'https://favicon.yandex.net/favicon/{hostname}?size=32' => 'Yandex Favicon',
            'https://api.xinac.net/icon/?url={hostname}' => 'Xinac Icon',
            'https://www.google.com/s2/favicons?sz=32&domain_url={hostname}' => 'Google API',
            'https://f1.allesedv.com/32/{hostname}' => 'Allese API',
            'custom' => '自定义'
        ),
        ThemeConfig::DEFAULT_FAVICON_API,
        _t('图标源'),
        _t('当文章类型为 <b>网址导航</b> 且 <b>图标URL</b> 为空时将通过此API自动获取图标</br>选择 <b>自定义</b> 后，需配置 <b>自定义图标源</b>')
    );
    $form->addInput($faviconApiSelect->multiMode());

    //Gravatar API
    $gravatarApiSelect = new Typecho_Widget_Helper_Form_Element_Select(
        'gravatarApiSelect',
        array(
            'https://weavatar.com/avatar/{hash}' => 'WeAvatar (默认)',
            'https://gravatar.com/avatar/{hash}' => 'Gravatar',
            'https://gravatar.webp.se/avatar/{hash}' => 'WebP',
            'https://gravatar.loli.net/avatar/{hash}' => 'SM.MS',
            'https://gravatar.zyq.today/avatar/{hash}' => 'zyq.today',
            'custom' => '自定义'
        ),
        ThemeConfig::DEFAULT_GRAVATAR_API,
        _t('头像源'),
        _t('Typecho 使用 Gravatar 头像，若无法显示请先前往 <a href="https://gravatar.com">Gravatar</a> 注册账号并上传头像</br>选择 <b>自定义</b> 后，需配置 <b>自定义头像源</b>')
    );
    $form->addInput($gravatarApiSelect->multiMode());

    $advancedSetting = new Typecho_Widget_Helper_Layout();
    $advancedSetting->html(_t('<hr color="#ECECEC"/><h2>高级设置</h2>'));
    $form->addItem($advancedSetting);

    // 天气API Key
    $weatherApiKey = new Typecho_Widget_Helper_Form_Element_Text(
        'weatherApiKey',
        NULL,
        _t(''),
        _t('天气接口密钥'),
        _t('来自必应天气，获取方式请查看本项目 <a href="https://github.com/fordes123/ITEM/wiki">文档</a>')
    );
    $form->addInput($weatherApiKey);

    //天气API CDN区域
    $weatherNode = new Typecho_Widget_Helper_Form_Element_Radio(
        'weatherNode',
        array(
            '0' => '中国',
            '1' => '全球',
        ),
        '0',
        _t('天气接口节点'),
        _t('此选项可能会影响天气接口查询速度以及区域识别, 默认为 <b>中国</b>')
    );
    $form->addInput($weatherNode);

    //自定义Favicon API地址
    $faviconApi = new Typecho_Widget_Helper_Form_Element_Text(
        'faviconApi',
        NULL,
        '',
        _t('自定义图标源'),
        _t('仅在 <b>图标源</b> 为 <b>自定义</b> 时有效<br>必须包含占位符 <b>{hostname}</b> 来表示目标域名，示例：https://example.org/{hostname}')
    );
    $form->addInput($faviconApi->addRule('url', _t('请填入一个有效的URL')));

    //自定义Gravatar API地址
    $gravatarApi = new Typecho_Widget_Helper_Form_Element_Text(
        'gravatarApi',
        NULL,
        '',
        _t('自定义头像源'),
        _t('仅在 <b>头像源</b> 为 <b>自定义</b> 时有效<br>必须包含占位符 <b>{hash}</b> 来表示邮箱的 SHA256 哈希值，示例：https://example.org/{hash}')
    );
    $form->addInput($gravatarApi->addRule('url', _t('请填入一个有效的URL')));

    // 自定义Header
    $customHeader = new Typecho_Widget_Helper_Form_Element_Textarea(
        'customHeader',
        NULL,
        NULL,
        _t('自定义头部代码'),
        _t('代码将以<b>原样</b>追加在页面头部，通常用来在添加自定义CSS样式或是标签<br>
        <b style="color: #f17666;">此操作存在风险，切勿轻信任何未知来源的代码!</b>')
    );

    $form->addInput($customHeader);

    // 底部JS
    $customFooter = new Typecho_Widget_Helper_Form_Element_Textarea(
        'customFooter',
        NULL,
        NULL,
        _t('自定义底部代码'),
        _t('代码将以<b>原样</b>追加在页面尾部，通常用来在添加自定义JS，统计、分析工具等<br>
        <b style="color: #f17666;">此操作存在风险，切勿轻信任何未知来源的代码!</b>')
    );
    $form->addInput($customFooter);
}

/**
 * 添加自定义字段
 */
function themeFields($layout)
{

    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Radio(
            'navigation',
            array(
                0 => _t('站内文章'),
                1 => _t('网址导航'),
                2 => _t('微信小程序'),
            ),
            1,
            _t('文章类型'),
            _t("• 普通文章: 点击会前往文章页<br/>• 网址导航: 点击图标前往详情，点击其他位置直接跳转至对应url<br/>• 小程序: 点击往详情页")
        )
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('url', NULL, NULL, _t('跳转地址'), _t('• 普通文章: 此字段留空即可<br/>• 网址导航: 可访问的URL<br/>• 小程序: 二维码图片URL<br/>• 独立页面，无视文章类型，访问时将直接重定向至此地址</b>'))
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('text', NULL, NULL, _t('简单介绍'), _t('简短描述即可，将展示于首页和详情页开头<br/>(其他内容应记录在正文中)'))
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('logo', NULL, NULL, _t('图标 URL'), _t('文章/网站/小程序的图标链接<br/>留空则自动从通过API自动获取(不支持小程序)'))
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('score', NULL, NULL, _t('站点评分'), _t('请输入评分，1.0～5.0分'))
    );

    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('keywords', NULL, NULL, _t('SEO 关键词'), _t('请输入关键词，多个关键词用逗号分隔<br/>可能有助于被搜索引擎收录...'))
    );

    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('description', NULL, NULL, _t('SEO 描述'), _t('SEO 描述，如为空则使用上面的 <b>简单介绍</b>'))
    );

    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('encryptTip', NULL, NULL, _t('密码提示'), _t('加密文章提示语，可作为获取密码的指引'))
    );
}
