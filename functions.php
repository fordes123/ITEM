<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
define('RANKED_ITEM_TEMPLATE', <<<HTML
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
        pageview($cid, false);
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
                "url": "https://www.iddh.cn/search/",
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
                1 => _t('小程序导航'),
                2 => _t('网址导航'),
                0 => _t('普通文章')
            ),
            1,
            _t('文章类型'),
            _t("普通文章: 点击会前往详情页; 网址导航: 点击图标前往详情，点击其他位置直接跳转至对应url")
        )
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('url', NULL, NULL, _t('跳转链接'), _t('请输入跳转URL，小程序不填'))
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('text', NULL, NULL, _t('导航描述'), _t('请输入导航描述'))
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('logo', NULL, NULL, _t('链接logo'), _t('请输入Logo URL链接，网址加favicon.ico就是图标，默认是网站logo')));
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('qrcode', NULL, NULL, _t('小程序二维码'), _t('请输入小程序二维码链接！'))
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('screenshot', NULL, NULL, _t('功能预览'), _t('请输入功能预览链接！'))
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Text('score', NULL, NULL, _t('评分'), _t('请输入评分，1.0～5.0分'))
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Radio(
            'advertisement',
            array(
                1 => _t('有'),
                0 => _t('无')
            ),
            0, // 默认值为 0 (无广告)
            _t('是否存在广告'),
            _t("请选择是否有广告！")
        )
    );
    $layout->addItem(
        new Typecho_Widget_Helper_Form_Element_Radio(
            'official',
            array(
                1 => _t('是'),
                0 => _t('否')
            ),
            0, // 默认值为 0 (非官方小程序)
            _t('是否是官方小程序'),
            _t("请选择是否为官方小程序！")
        )
    );
}

/**
 * 更新并输出指定文章浏览量
 */
function pageview($cid, $display = true)
{
    $num = 0;
    if (!array_key_exists($cid, $_COOKIE) || is_null($_COOKIE[$cid])) :

        //如不存在，从数据库中查询
        $db = Typecho_Db::get();
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

function agreeNum($cid) {
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();

    //  判断点赞数量字段是否存在
    if (!array_key_exists('agree', $db->fetchRow($db->select()->from('table.contents')))) {
        //  在文章表中创建一个字段用来存储点赞数量
        $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `agree` INT(10) NOT NULL DEFAULT 0;');
    }

    //  查询出点赞数量
    $agree = $db->fetchRow($db->select('table.contents.agree')->from('table.contents')->where('cid = ?', $cid));
    //  获取记录点赞的 Cookie
    $AgreeRecording = Typecho_Cookie::get('typechoAgreeRecording');
    //  判断记录点赞的 Cookie 是否存在
    if (empty($AgreeRecording)) {
        //  如果不存在就写入 Cookie
        Typecho_Cookie::set('typechoAgreeRecording', json_encode(array(0)));
    }

    //  返回
    return array(
        //  点赞数量
        'agree' => $agree['agree'],
        //  文章是否点赞过
        'recording' => in_array($cid, json_decode(Typecho_Cookie::get('typechoAgreeRecording')))?true:false
    );
}

function agree($cid) {
    $db = Typecho_Db::get();
    //  根据文章的 `cid` 查询出点赞数量
    $agree = $db->fetchRow($db->select('table.contents.agree')->from('table.contents')->where('cid = ?', $cid));

    //  获取点赞记录的 Cookie
    $agreeRecording = Typecho_Cookie::get('typechoAgreeRecording');
    //  判断 Cookie 是否存在
    if (empty($agreeRecording)) {
        //  如果 cookie 不存在就创建 cookie
        Typecho_Cookie::set('typechoAgreeRecording', json_encode(array($cid)));
    }else {
        //  把 Cookie 的 JSON 字符串转换为 PHP 对象
        $agreeRecording = json_decode($agreeRecording);
        //  判断文章是否点赞过
        if (in_array($cid, $agreeRecording)) {
            //  如果当前文章的 cid 在 cookie 中就返回文章的赞数，不再往下执行
            return $agree['agree'];
        }
        //  添加点赞文章的 cid
        array_push($agreeRecording, $cid);
        //  保存 Cookie
        Typecho_Cookie::set('typechoAgreeRecording', json_encode($agreeRecording));
    }

    //  更新点赞字段，让点赞字段 +1
    $db->query($db->update('table.contents')->rows(array('agree' => (int)$agree['agree'] + 1))->where('cid = ?', $cid));
    //  查询出点赞数量
    $agree = $db->fetchRow($db->select('table.contents.agree')->from('table.contents')->where('cid = ?', $cid));
    //  返回点赞数量
    return $agree['agree'];
}

/**
 * 输出浏览量排名前n的文章列表
 */
function ranked($limit = 5)
{
    if (!array_key_exists("mostViewed", $_COOKIE) || is_null($_COOKIE['mostViewed'])) :

        $db = Typecho_Db::get();
        $limit = is_numeric($limit) ? $limit : 5;
        $posts = $db->fetchAll($db->select('cid')->from('table.contents')
            ->where('type = ? AND status = ? AND password IS NULL', 'post', 'publish')
            ->order('views', Typecho_Db::SORT_DESC)
            ->limit($limit));

        $cids = $posts ? array_column($posts, 'cid') : [];
        printRanked($cids);
        setcookie('mostViewed', implode('.', $cids), time() + 3600);
    else :
        printRanked(explode('.', $_COOKIE['mostViewed']));
    endif;
}

function printRanked($cids)
{
    foreach ($cids as $cid) :
        $item = Typecho_Widget::widget("Widget_Archive@post-$cid", "pageSize=1&type=post", "cid=$cid");
        $url = $item->fields->url ? $item->fields->url : $item->permalink;
        echo sprintf(RANKED_ITEM_TEMPLATE, $item->title . ($item->fields->text ? ' - ' . $item->fields->text : ''), $url, $cid, $item->fields->text);
    endforeach;
}

function timeago($timestamp)
{
    $diff = time() - $timestamp;
    $units = array(
        '年前' => 31536000,
        '个月前' => 2592000,
        '天前' => 86400,
        '小时前' => 3600,
        '分钟前' => 60,
        '秒前' => 1,
    );
    foreach ($units as $unit => $value) {
        if ($diff >= $value) {
            $time = floor($diff / $value);
            return $time . $unit;
        }
    }
}

function getSiteFavicon($posts) {
    $logo = $posts->fields->logo;
    $url = $posts->fields->url;
    if (empty($logo) && $url) {
        $logo = 'https://favicon.im/' . parse_url($url, PHP_URL_HOST);
    }
    return $logo;
}