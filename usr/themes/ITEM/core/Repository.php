<?php

if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;

final class ThemeRepository
{

    /**
     * 初始化数据库
     * @return void
     */
    public static function init()
    {
        try {
            $db = Typecho_Db::get();
            $prefix = $db->getPrefix();

            // 获取当前版本
            $row = $db->fetchRow($db->select()->from('table.options')->where('name = ?', ThemeConfig::VERSION_OPTION));
            $version = $row ? $row['value'] : null;

            if ($version !== ThemeConfig::THEME_VERSION) {
                error_log('检测到主题更新, 重新初始化数据库...');

                // 浏览量字段
                if (empty($db->fetchAll($db->query('SHOW COLUMNS FROM `' . $prefix . 'contents` LIKE "views";')))) {
                    $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT UNSIGNED NOT NULL DEFAULT 0;');
                }

                // 点赞字段
                if (empty($db->fetchAll($db->query('SHOW COLUMNS FROM `' . $prefix . 'contents` LIKE "agree";')))) {
                    $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `agree` INT UNSIGNED NOT NULL DEFAULT 0;');
                }

                // 写入版本信息
                if ($version) {
                    $db->query($db->update('table.options')
                        ->rows(['value' => ThemeConfig::THEME_VERSION])
                        ->where('name = ?', ThemeConfig::VERSION_OPTION));
                } else {
                    $db->query($db->insert('table.options')->rows([
                        'name' => ThemeConfig::VERSION_OPTION,
                        'value' => ThemeConfig::THEME_VERSION,
                        'user' => 0
                    ]));
                }
                error_log('已从 ' . ($version ? $version : '未知版本') . ' 更新至 ' . ThemeConfig::THEME_VERSION);
            }
        } catch (Exception $e) {
            error_log('初始化数据库失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 获取分类下的文章
     * @param int $mid
     * @return array[]
     */
    public static function postsByCategory(int $mid, int $uid = 0): array
    {

        $result = array();
        $posts = Typecho_Widget::widget("Widget_Archive@category-" . $mid, "type=category", "mid=" . $mid);
        while ($posts->next()) {
            if (!is_null($posts->fields->navigation)) {
                $result[] = ThemeHelper::normalizePost($posts, $uid);
            }
        }
        return $result;
    }

    /**
     * 获取分类树
     * 当 $isIndex 为 true 时, 若 subCategoryType=1(收纳) 则只获取首个非空分类下的文章，否则获取所有分类下的文章
     * @param bool $isIndex 是否为首页数据请求
     * @return array
     */
    public static function categoryTree(bool $isIndex = false, int $uid = 0): array
    {

        $options = Helper::options();
        $db = Typecho_Db::get();
        $categorys = $db->fetchAll($db->select('mid,name,slug,parent')
            ->from('table.metas')
            ->where('type = ? ', 'category')
            ->order('parent', Typecho_Db::SORT_ASC)
            ->order('order', Typecho_Db::SORT_ASC));

        $result = array();
        foreach ($categorys as $category) {
            if ($category['parent'] == 0) {
                $result[$category['mid']] = $category;
                $result[$category['mid']]['children'] = array();
            } else {
                $result[$category['parent']]['children'][$category['mid']] = $category;
            }
        }

        $collapse = $options->subCategoryType == 1;
        if ($isIndex) {
            foreach ($result as $parentMid => &$parent) {
                if (empty($parent['children'])) {
                    // 无子分类也无文章，直接忽略
                    $parent['posts'] = self::postsByCategory($parent['mid'], $uid);
                    if (empty($parent['posts'])) {
                        unset($result[$parentMid]);
                    }
                } else {

                    $parent['posts'] = [];
                    foreach ($parent['children'] as $childMid => $child) {
                        $posts = self::postsByCategory($child['mid'], $uid);
                        $parent['children'][$childMid]['posts'] = $posts;

                        //在折叠模式下，只需要获取第一个非空分类的文章
                        if ($collapse) {
                            if (empty($posts)) {
                                unset($parent['children'][$childMid]);
                                continue;
                            } elseif (empty($parent['posts'])) {
                                $parent['posts'] = $posts;
                                break;
                            }
                        }
                    }

                    if (empty($parent['children'])) {
                        unset($result[$parentMid]);
                    }
                }
            }
        }
        unset($parent);
        return $result;
    }

    /**
     * 获取文章统计, 包含浏览量和点赞数
     * @param mixed $cid
     * @param mixed $format
     */
    public static function postStats($cid, $format = true)
    {
        $cache = unserialize(Typecho_Cookie::get(ThemeConfig::ARTICLE_METRICS_CACHE_KEY . $cid) ?: '') ?: null;
        if (!$cache) {
            $db = Typecho_Db::get();
            $row = $db->fetchRow($db->select('views,agree')->from('table.contents')->where('cid = ?', $cid));
            $cache = array('views' => $row['views'] + 1, 'agree' => $row['agree']);

            $db->query($db->update('table.contents')->rows(['views' => $cache['views']])->where('cid = ?', $cid));
            Typecho_Cookie::set(ThemeConfig::ARTICLE_METRICS_CACHE_KEY . $cid, serialize($cache), time() + 600);
        }

        if ($format) {
            $cache['views'] = ThemeHelper::formatNumber($cache['views']);
            $cache['agree'] = ThemeHelper::formatNumber($cache['agree']);
        }
        return $cache;
    }

    /**
     * 获取文章
     * @param mixed $cid
     * @return array
     */
    public static function post($cid, int $uid = 0): array
    {
        $posts = Typecho_Widget::widget("Widget_Archive@post-$cid", "pageSize=1&type=post", "cid=$cid");
        return ThemeHelper::normalizePost($posts, $uid);
    }

    /**
     * 获取浏览量前n的文章
     * @param mixed $limit
     * @return array
     */
    public static function postsByViews($limit = 5): array
    {
        $limit = max(1, min(20, intval($limit)));

        $db = Typecho_Db::get();
        $posts = $db->fetchAll($db->select('cid')
            ->from('table.contents')
            ->where('type = ? AND status = ? AND password IS NULL', 'post', 'publish')
            ->order('views', Typecho_Db::SORT_DESC)
            ->limit($limit));
        $cids = $posts ? array_column($posts, 'cid') : [];

        $result = [];
        foreach ($cids as $cid) {
            $result[] = self::post($cid);
        }

        return $result;
    }

    /**
     * 增加文章点赞数
     * @param mixed $cid
     */
    public static function increaseAgree($cid)
    {
        $db = Typecho_Db::get();
        $db->query($db->update('table.contents')->expression('agree', 'agree + 1')->where('cid = ?', $cid));

        $row = $db->fetchRow($db->select('views,agree')->from('table.contents')->where('cid = ?', $cid));
        $cache = array('views' => $row['views'] + 1, 'agree' => $row['agree']);
        Typecho_Cookie::set(ThemeConfig::ARTICLE_METRICS_CACHE_KEY . $cid, serialize($cache), time() + 600);

        return $cache['agree'];
    }

    /**
     * 分页获取文章列表
     * @param mixed $pageSize
     * @param mixed $currentPage
     * @param mixed $keyword
     * @param mixed $uid
     * @return array{currentPage: float|int|mixed, data: array, total: mixed, totalPages: float|array{currentPage: mixed, data: array, total: int, totalPages: int}}
     */
    public static function posts($pageSize, $currentPage, $keyword = null, int $uid = 0)
    {
        $db = Typecho_Db::get();
        $keyword = empty($keyword) ? null : '%' . str_replace('%', '\%', $keyword) . '%';
        if ($uid > 0) {
            // 登录用户：公开 + 私有
            $statusWhere = '(status = "publish" OR (status = "private" AND authorId = ' . $uid . '))';
        } else if ($uid == -1) {
            // 管理员：所有
            $statusWhere = 'status = "publish" OR status = "private"';
        } else {
            // 未登录：仅公开
            $statusWhere = 'status = "publish"';
        }

        // 统计总数
        $countSelect = $db->select(array('COUNT(cid)' => 'num'))
            ->from('table.contents')
            ->where('type = ?', 'post')
            ->where($statusWhere);

        if ($keyword) {
            $countSelect->where('(title LIKE ? OR text LIKE ?)', $keyword, $keyword);
        }

        $totalPosts = $db->fetchObject($countSelect)->num;

        if ($totalPosts <= 0) {
            return array(
                'data' => array(),
                'currentPage' => $currentPage,
                'totalPages' => 0,
                'total' => 0
            );
        }

        $totalPages = ceil($totalPosts / $pageSize);
        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $pageSize;

        // 查询数据
        $select = $db->select()
            ->from('table.contents')
            ->where('type = ?', 'post')
            ->where($statusWhere)
            ->order('modified', Typecho_Db::SORT_DESC)
            ->limit($pageSize)
            ->offset($offset);

        if ($keyword) {
            $select->where('(title LIKE ? OR text LIKE ?)', $keyword, $keyword);
        }

        $result = $db->fetchAll($select);

        return array(
            'data' => array_column($result, 'cid'),
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'total' => $totalPosts
        );
    }

    public static function singlePageTree()
    {
        $pages = Typecho_Widget::widget('Widget_Contents_Page_List');
        $nodes = array();

        while ($pages->next()) {
            $cid = (int) $pages->cid;
            $nodes[$cid] = array(
                'cid' => $cid,
                'title' => $pages->title,
                'slug' => $pages->slug,
                'parent' => (int) $pages->parent,
                'order' => (int) $pages->order,
                'url' => $pages->fields->navigation == '1' ? $pages->fields->url : $pages->permalink,
                'text' => ThemeHelper::isBlank($pages->fields->text) ? $pages->title : $pages->fields->text,
                'target' => $pages->fields->navigation == '1' ? '_blank' : '_self',
            );
        }

        if (empty($nodes)) {
            return array();
        }

        $childrenMap = array();
        foreach ($nodes as $cid => $node) {
            $parent = $node['parent'];
            if ($parent > 0 && !isset($nodes[$parent])) {
                $parent = 0;
            }
            $childrenMap[$parent][] = $cid;
        }

        foreach ($childrenMap as &$childIds) {
            usort($childIds, function ($a, $b) use (&$nodes) {
                $orderCmp = $nodes[$a]['order'] <=> $nodes[$b]['order'];
                if (0 !== $orderCmp) {
                    return $orderCmp;
                }
                return $a <=> $b;
            });
        }
        unset($childIds);

        $buildTree = function ($parentId) use (&$buildTree, &$childrenMap, &$nodes) {
            $branch = array();
            if (empty($childrenMap[$parentId])) {
                return $branch;
            }

            foreach ($childrenMap[$parentId] as $cid) {
                $node = $nodes[$cid];
                $node['children'] = $buildTree($cid);
                $branch[$cid] = $node;
            }
            return $branch;
        };

        return $buildTree(0);
    }
}
