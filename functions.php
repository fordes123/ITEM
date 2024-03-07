<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * 主题初始化
 */
function themeInit($archive)
{
    //初始化表结构
    $options = Helper::options();
    if (!$options->get('ITEM-isInit')) {
        $db = Typecho_Db::get();
        if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')->limit(1)))) {
            $db->query('ALTER TABLE `' . $db->getPrefix() . 'contents` ADD `views` INT(10) NOT NULL DEFAULT 0;');
        }
        $options->set('ITEM-isInit', true);
    }

    //伪跳转，用于记录点击量
    $cid = $archive->request->cid;
    if (!is_null($cid) && is_numeric($cid)) {
        getClicks($cid, false);
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
                true => _t('网址导航'),
                false => _t('普通文章')
            ),
            true,
            _t('文章类型'),
            _t("普通文章: 点击会前往详情页; 网址导航: 点击图标前往详情，点击其他位置直接跳转至对应url")
        )
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('url', NULL, NULL, _t('跳转链接'), _t('请输入跳转URL'))
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('text', NULL, NULL, _t('链接描述'), _t('请输入链接描述'))
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('logo', NULL, NULL, _t('链接logo'), _t('请输入Logo URL链接'))
    );
}

/**
 * 获取浏览量
 */
function getClicks($cid, $display = true)
{
    $num = 0;
    if (!array_key_exists($cid, $_COOKIE) || is_null($_COOKIE[$cid])) :

        //如不存在，从数据库中查询
        $db     = Typecho_Db::get();
        $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
        $num = $row['views'] + 1;

        //更新数据库
        $db->query('UPDATE `' . $db->getPrefix() . 'contents` SET views = views + 1 WHERE cid = ' . $cid);

        // 60s内，同一客户端、同一篇文章不累计点击量
        setcookie($cid, $num, time() + 60);
    else :
        $num = $_COOKIE[$cid];
    endif;

    if ($display) :
        echo $num < 1000 ? $num : floor($num / 1000) . 'K';
    endif;
}



define('HOT_LIST_ITEM_TEMPLATE', <<<HTML
<div class="list-item">
    <div class="list-content">
        <div class="list-body">
            <div class="list-title h-1x">%s</div>
        </div>
    </div>
    <a href="%s" target="_blank" cid="%d" title="%s" class="list-goto nav-item"></a>
</div>
HTML);

/**
 * 点击量排名文章
 */
function theMostViewed($limit = 5)
{
    if (!array_key_exists("mostViewed", $_COOKIE) || is_null($_COOKIE['mostViewed'])) :

        $db = Typecho_Db::get();
        $limit = is_numeric($limit) ? $limit : 5;
        $posts = $db->fetchAll(
            $db->select('cid')->from('table.contents')
                ->where('type = ? AND status = ? AND password IS NULL', 'post', 'publish')
                ->order('views', Typecho_Db::SORT_DESC)
                ->limit($limit)
        );

        $cids = $posts ? array_column($posts, 'cid') : [];
        printfHotList($cids);
        setcookie('mostViewed', implode('.', $cids), time() + 3600);
    else :
        printfHotList(explode('.', $_COOKIE['mostViewed']));
    endif;
}

function printfHotList($cids)
{
    foreach ($cids as $cid) :
        $item = Typecho_Widget::widget('Widget_Archive@' . $cid, 'pageSize=1&type=post', 'cid=' . $cid);
        $url = $item->fields->url ? $item->fields->url : $item->permalink;
        echo sprintf(HOT_LIST_ITEM_TEMPLATE, $item->title . ($item->fields->text ? ' - ' . $item->fields->text : ''), $url, $cid, $item->fields->text);
    endforeach;
}
