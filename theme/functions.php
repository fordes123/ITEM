<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

error_reporting(0);
require_once('libs/Utils.php');


/**
 * 主题初始化
 */
function themeInit($source)
{

    if ($source->request->isPost()) {
        Utils::api($source);
    }

    Utils::init();
}

/**
 * 添加主题设置项
 */
function themeConfig(Typecho_Widget_Helper_Form $form)
{
    require_once('libs/Badge.php');
    $options = Helper::options();

    $baseSetting = new Typecho_Widget_Helper_Layout();
    $baseSetting->html(_t('<h3>基础设置</h3>'));
    $form->addItem($baseSetting);

    //网站图标
    $favicon = new Typecho_Widget_Helper_Form_Element_Text(
        'favicon',
        NULL,
        $options->themeUrl . '/assets/image/favicon.ico',
        _t('网站图标'),
        _t('应为 ico 格式，建议使用 CDN 链接</br>亦可将此项留空，直接将图标文件替换到主题 <b>assets/image</b> 路径下')
    );
    $form->addInput($favicon->addRule('url', _t('请填入一个有效的URL')));

    //首页小logo
    $smalllogo = new Typecho_Widget_Helper_Form_Element_Text(
        'smalllogo',
        NULL,
        $options->themeUrl . '/assets/image/favicon.ico',
        _t('侧边图标'),
        _t('侧边栏收缩时的图标，建议使用CDN或图床 (建议和网站图标保持一致)')
    );
    $form->addInput($smalllogo->addRule('url', _t('请填入一个有效的URL')));

    //首页大logo
    $biglogo = new Typecho_Widget_Helper_Form_Element_Text(
        'biglogo',
        NULL,
        $options->themeUrl . '/assets/image/head.png',
        _t('横幅图标'),
        _t('侧边展开时的图标，建议使用CDN或图床 (推荐尺寸: 824x200)')
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
    $advancedSetting->html(_t('<hr color="#ECECEC"/><h3>进阶设置</h3>'));
    $form->addItem($advancedSetting);

    // 搜索引擎
    $searchConfig = new Typecho_Widget_Helper_Form_Element_Textarea(
        'searchConfig',
        NULL,
        '[
            {
                "name": "站内搜索",
                "url": "' . $options->siteUrl . 'search/",
                "icon": "fas fa-search-location"
            },
            {
                "name": "谷歌",
                "url": "https://www.google.com/search?q=",
                "icon": "fab fa-google"
            },
            {
                "name": "Yandex",
                "url": "https://yandex.com/search/?text=",
                "icon": "fab fa-yandex"
            },
            {
                "name": "Github",
                "url": "https://github.com/search?q=",
                "icon": "fab fa-github"
            }
        ]',
        _t('搜索引擎配置'),
        _t('首页搜索引擎配置信息')
    );
    $form->addInput($searchConfig);

    // 工具直达
    $toolConfig = new Typecho_Widget_Helper_Form_Element_Textarea(
        'toolConfig',
        NULL,
        '[
            {
                "name": "热榜速览",
                "url": "https://www.hsmy.fun",
                "icon": "fas fa-fire",
                "background": "linear-gradient(45deg, #97b3ff, #2f66ff)"
            },
            {
                "name": "地图",
                "url": "https://ditu.amap.com/",
                "icon": "fas fa-fire",
                "background": "red"
            },
            {
                "name": "微信文件助手",
                "url": "https://filehelper.weixin.qq.com",
                "icon": "fab fa-weixin",
                "background": "#1ba784"
            },
            {
                "name": "Font Awesome",
                "url": "https://fontawesome.com/v5/search?o=r&m=free",
                "icon": "fab fa-font-awesome-flag",
                "background": "linear-gradient(0deg,#434343 0%, #000000 100%)"
            }
        ]',
        _t('工具直达配置'),
        _t('首页工具直达配置信息')
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
            'https://favicon.im/' => ' Favicon.im (默认) ',
            'https://favicon.yandex.net/favicon/' => ' Yandex Favicon ',
            'https://toolb.cn/favicon/' => ' Toolb Favicon ',
            'https://api.xinac.net/icon/?url=' => ' Xinac Icon ',
            'https://tools.ly522.com/ico/favicon.php?url=' => ' Ly522 Favicon ',
            'https://api.qqsuu.cn/api/dm-get?url=' =>  'QQsuu API ',
            'custom' => '自定义'
        ),
        Utils::DEFAULT_FAVICON_API,
        _t('导航图标来源'),
        _t('当文章类型为 <b>网址导航</b> 且 <b>图标URL</b> 为空时将通过此API自动获取图标</br>选择“自定义”后，需在下方填写自定义值')
    );
    $form->addInput($faviconApiSelect->multiMode());

    //自定义Favicon API地址
    $faviconApi = new Typecho_Widget_Helper_Form_Element_Text(
        'faviconApi',
        NULL,
        '',
        _t('自定义图标 API'),
        _t('仅在上方选择“自定义”时需填写，否则无效')
    );
    $form->addInput($faviconApi->addRule('url', _t('请填入一个有效的URL')));
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
        new Typecho_Widget_Helper_Form_Element_Text('logo', NULL, NULL, _t('图标URL'), _t('文章/网站/小程序的图标链接<br/>留空则自动从通过API自动获取(不支持小程序)'))
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('score', NULL, NULL, _t('评分'), _t('请输入评分，1.0～5.0分'))
    );
}
