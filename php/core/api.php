<?php
require_once 'constants.php';
require_once 'utils.php';
require_once 'models.php';
class Theme_Api
{

    /**
     * 获取分类及其下所有文章
     * @param mixed $mid
     * @return array{cid: mixed, logo: mixed, permalink: mixed, text: mixed, title: mixed, url: mixed[]}
     */
    public static function getCategory($mid): array
    {

        $result = array();
        $posts = Typecho_Widget::widget("Widget_Archive@category-" . $mid, "type=category", "mid=" . $mid);

        while ($posts->next()) {
            if (!is_null($posts->fields->navigation)) {
                $result[] = array(
                    'cid' => $posts->cid,
                    'title' => $posts->title,
                    'permalink' => $posts->permalink,
                    'url' => $posts->fields->navigation == '1' && !$posts->hidden ? $posts->fields->url : $posts->permalink,
                    'text' => $posts->password ? '验证后可查看内容' : $posts->fields->text,
                    'logo' => Theme_Utils::getFavicon($posts),
                    'hidden' => $posts->hidden
                );
            }
        }

        return $result;

    }

    /**
     * 获取首页数据,包含所有分类及其下所有文章
     * @return array
     */
    public static function getHomepageData(): array
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

        foreach ($result as $parentMid => &$parent) {
            if (empty($parent['children'])) {
                $parent['posts'] = self::getCategory($parent['mid']);

                if (empty($parent['posts'])) {
                    unset($result[$parentMid]);
                }
            } else {
                $parent['posts'] = [];

                foreach ($parent['children'] as $childMid => $child) {
                    $posts = self::getCategory($child['mid']);

                    if (empty($posts)) {
                        unset($parent['children'][$childMid]);
                        continue;
                    }

                    $parent['children'][$childMid]['posts'] = $posts;

                    if ($options->subCategoryType == 1) {
                        $parent['posts'] = $posts;
                        break;
                    }
                }

                if (empty($parent['children'])) {
                    if (empty($parent['posts'])) {
                        unset($result[$parentMid]);
                    }
                }
            }
        }
        unset($parent);

        return $result;
    }

    /**
     * 获取文章浏览量和点赞数等指标
     * @param int $cid 文章ID
     * @param bool $format 是否格式化
     * @return ArticleCache 文章缓存
     */
    public static function getArticleMetrics($cid, $format = true)
    {

        $cache = unserialize(Typecho_Cookie::get(Theme_Constants::ARTICLE_METRICS_CACHE_KEY . $cid) ?: '') ?: null;
        if (empty($cache)) {
            $db = Typecho_Db::get();
            $row = $db->fetchRow($db->select('views,agree')->from('table.contents')->where('cid = ?', $cid));
            $cache = new ArticleCache($row['views'] + 1, $row['agree']);

            $db->query($db->update('table.contents')->rows(['views' => $cache->views])->where('cid = ?', $cid));
            Typecho_Cookie::set(Theme_Constants::ARTICLE_METRICS_CACHE_KEY . $cid, serialize($cache), time() + 600);
        }

        if ($format) {
            $cache->views = Theme_Utils::formatNumber($cache->views);
            $cache->agree = Theme_Utils::formatNumber($cache->agree);
        }
        return $cache;
    }


    /**
     * 更新文章点赞数
     * @param int $cid 文章ID
     * @return int 更新后的点赞数
     */
    public static function updateAgree($cid)
    {
        $db = Typecho_Db::get();
        $db->query($db->update('table.contents')->expression('agree', 'agree + 1')->where('cid = ?', $cid));
        // 更新缓存
        $row = $db->fetchRow($db->select('views,agree')->from('table.contents')->where('cid = ?', $cid));
        $cache = new ArticleCache($row['views'] + 1, $row['agree']);
        Typecho_Cookie::set(Theme_Constants::ARTICLE_METRICS_CACHE_KEY . $cid, serialize($cache), time() + 600);

        return $cache->agree;
    }


    /**
     * 分页查询文章
     * @param int $pageSize 每页文章数
     * @param int $currentPage 当前页码
     * @param string|null $keyword 搜索关键字
     * @return array 包含文章cid列表和分页信息的数组
     */
    public static function searchPage($pageSize, $currentPage, $keyword = null, $uid = null)
    {
        $db = Typecho_Db::get();
        $keyword = empty($keyword) ? null : '%' . str_replace('%', '\%', $keyword) . '%';
        if ($uid > 0) {
            // 登录用户：公开 + 自己的私有
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

    /**
     * 渲染浏览量排名前n的文章列表
     * @param int $limit 显示数量
     * @param int $cacheTime 缓存时间（秒）
     */
    public static function renderTopArticlesByViews($limit = 5, $cacheTime = 3600)
    {

        $limit = max(1, min(20, intval($limit)));

        $db = Typecho_Db::get();
        $posts = $db->fetchAll($db->select('cid')
            ->from('table.contents')
            ->where('type = ? AND status = ? AND password IS NULL', 'post', 'publish')
            ->order('views', Typecho_Db::SORT_DESC)
            ->limit($limit));
        $cids = $posts ? array_column($posts, 'cid') : [];

        // 输出文章列表
        $cids = array_slice($cids, 0, $limit);
        $i = 0;
        foreach ($cids as $cid) {
            $item = Typecho_Widget::widget("Widget_Archive@post-$cid", "pageSize=1&type=post", "cid=$cid");
            if ($item->have()) {
                $url = $item->fields->url ?: $item->permalink;
                $subtitle = $item->fields->text ? ' - ' . $item->fields->text : '';
                $title = $item->title . $subtitle;
                ?>
                <div class="list-item">
                    <div class="list-content">
                        <div class="list-body">
                            <div class="list-title h-1x"><?php echo $title; ?></div>
                        </div>
                    </div>
                    <a href="<?php echo $url; ?>" target="_blank" cid="<?php echo $cid; ?>" title="<?php echo $item->fields->text; ?>"
                        class="list-goto nav-item"></a>
                </div>
                <?php

                if (++$i >= $limit)
                    break;
            }
        }

        // 填充空白项
        for (; $i < $limit; $i++) {
            ?>
            <div class="list-item">
                <div class="list-content">
                    <div class="list-body">
                        <div class="list-title h-1x">暂无数据</div>
                    </div>
                </div>
                <a href="" target="_blank" cid="0" title="" class="list-goto nav-item"></a>
            </div>
            <?php
        }
    }

    /**
     * 渲染分类
     * @param mixed $item
     * @param mixed $collapse
     * @return void
     */
    public static function renderCategory($item, $collapse = false): void
    {
        $options = Helper::options();
        ?>

        <div class="col-12">
            <div class="card card-xl" id="<?php echo $item['slug']; ?>">
                <div class="card-header d-flex flex-nowrap text-nowrap gap-2 align-items-center">
                    <div class="h4"> <i class="fas fa-sm fa-<?php echo $item['slug']; ?>"></i>&nbsp;<?php echo $item['name']; ?>
                    </div>
                    <?php if ($collapse): ?>
                        <ul class="card-tab d-flex flex-nowrap nav text-sm overflow-x-auto">
                            <?php $i = 0;
                            $first = null;
                            foreach ($item['children'] as $c): ?>
                                <li class="nav-item">
                                    <?php $first = $i === 0 ? $c : $first; ?>
                                    <span data-mid="<?php echo $c['mid']; ?>"
                                        class="nav-link<?php echo $i === 0 ? ' active' : ''; ?>"><i
                                            class="fas fa-<?php echo $c['slug']; ?>"></i> <?php echo $c['name']; ?></span>
                                </li>
                                <?php $i++;
                            endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="row g-2 g-md-3 list-grid list-grid-padding">
                        <?php $posts = $item['posts'];
                        foreach ($posts as $post):
                            ?>
                            <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xxl-2">
                                <div class="list-item block">
                                    <div role="button" href="<?php echo $post['permalink']; ?>" title="点击查看详情"
                                        class="media w-36 rounded">
                                        <img src="<?php echo $options->themeUrl('/assets/image/default.gif'); ?>"
                                            data-src="<?php echo $post['logo']; ?>" class="media-content lazy" />
                                    </div>
                                    <div role="button" href="<?php echo $post['url']; ?>" target="_blank"
                                        cid="<?php echo $post['cid']; ?>" title="<?php echo $post['text']; ?>" class="list-content">
                                        <div class="list-body">
                                            <div class="list-title text-md h-1x">
                                                <?php echo $post['title']; ?>
                                            </div>
                                            <div class="list-desc text-xx text-muted mt-1">
                                                <div class="h-1x">
                                                    <?php echo $post['text']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}