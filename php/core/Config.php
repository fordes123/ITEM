<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;

final class ThemeConfig
{
    // 主题版本
    const THEME_VERSION = '1.3.0';

    // 主题版本配置项名称
    const VERSION_OPTION = 'theme:ITEM::version';

    // 文章统计缓存键 (Cookie)
    const ARTICLE_METRICS_CACHE_KEY = 'METRICS_';

    // 默认加载图标
    const DEFAULT_LOADING_ICON = '/assets/image/default.gif';

    // 默认头像API
    const DEFAULT_GRAVATAR_API = 'https://weavatar.com/avatar/{hash}';

    //默认Favicon API
    const DEFAULT_FAVICON_API = 'https://favicon.im/{hostname}?larger=true';

    //默认加密提示语
    const DEFAULT_ENCRYPT_TIP = '如有疑问请站点联系管理员';

    //默认搜索配置
    const DEFAULT_SEARCH = [
        [
            "name" => "站内",
            "url" => "/search/",
            "icon" => "fas fa-search-location"
        ]
    ];
    //详情提示语
    const DEFAULT_DETAIL_TIPS = "点击查看详情";
}
