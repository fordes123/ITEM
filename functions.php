<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

error_reporting(0);
require_once('libs/Utils.php');


/**
 * 主题初始化
 */
function themeInit($source)
{
    Utils::init();

    if ($source->request->agree) {
        return Utils::updateAgree($source->request->agree);
    }

    if ($source->request->views) {
        Utils::views($source->request->views, false, false);
    }
}

/**
 * 添加主题设置项
 */
function themeConfig($form)
{

    $options = Helper::options();

    //网站图标
    $form->addInput(
        new Typecho_Widget_Helper_Form_Element_Text('favicon', NULL, $options->themeUrl . '/assets/image/favicon.ico', _t('网站图标'), _t('建议使用CDN'))
    );

    //首页大logo
    $form->addInput(
        new Typecho_Widget_Helper_Form_Element_Text('biglogo', NULL, $options->themeUrl . '/assets/image/head.png', _t('首页大logo'), _t('侧边展开时的图标，建议使用CDN或图床'))
    );

    //首页小logo
    $form->addInput(
        new Typecho_Widget_Helper_Form_Element_Text('smalllogo', NULL, $options->themeUrl . '/assets/image/favicon.ico', _t('首页小logo'), _t('侧边栏收缩时的图标，建议使用CDN或图床'))
    );


    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea(
        'searchConfig',
        NULL,
        '[
            {
                "name": "站内搜索",
                "url": "https://localhost/search/",
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
    ));

    $form->addInput(new Typecho_Widget_Helper_Form_Element_Textarea(
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
    ));


    //备案号
    $form->addInput(
        new Typecho_Widget_Helper_Form_Element_Text('icp', NULL, NULL, _t('备案号'), _t('有就填没有就不填不要乱填'))
    );
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
        new Typecho_Widget_Helper_Form_Element_Text('url', NULL, NULL, _t('跳转地址'), _t('• 普通文章: 此字段留空即可<br/>• 网址导航: 可访问的URL<br/>• 小程序: 二维码图片URL'))
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('text', NULL, NULL, _t('简单介绍'), _t('简短描述即可，将展示于首页和详情页开头<br/>(其他内容应记录在正文中)'))
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('logo', NULL, NULL, _t('图标URL'), _t('文章/网站/小程序的图标链接<br/>留空则自动从 <a href="https://favicon.im/" target="_blank">favicon.im</a> 获取(不支持小程序)'))
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('score', NULL, NULL, _t('评分'), _t('请输入评分，1.0～5.0分'))
    );
}