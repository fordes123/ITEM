<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;

final class ThemeView
{

    /**
     * 单个导航项
     */
    public static function navitem($post)
    {
        $options = Helper::options();
        ?>
        <div class="list-item block shadow-none">
            <a role="button" href="<?php echo $post['permalink']; ?>" title="<?php echo ThemeConfig::DEFAULT_DETAIL_TIPS; ?>"
                class="media w-36 rounded">
                <img src="<?php echo $options->themeUrl(ThemeConfig::DEFAULT_LOADING_ICON); ?>"
                    data-src="<?php echo $post['logo']; ?>" class="media-content lazy" />
            </a>
            <a role="button" href="<?php echo $post['url']; ?>" target="_blank" title="<?php echo $post['text']; ?>"
                class="list-content">
                <div class="list-body">
                    <div class="list-title text-md h-1x">
                        <?php echo $post['title']; ?>
                    </div>
                    <div class="list-desc text-xx text-muted mt-1">
                        <span class="h-1x">
                            <?php echo $post['text']; ?>
                        </span>
                    </div>
                </div>
            </a>
        </div>
        <?php
    }

    /**
     * 导航块(分类)
     */
    public static function navblock($item, $collapse = false)
    {
        $options = Helper::options();
        ?>
        <div class="col-12">
            <div class="card card-xl" id="<?php echo $item['slug']; ?>">
                <div class="card-header d-flex flex-nowrap text-nowrap gap-2 align-items-center">
                    <div class="h4"> <i
                            class="fa-solid fa-sm fa-<?php echo $item['slug']; ?>"></i>&nbsp;<?php echo $item['name']; ?>
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
                                            class="fa-solid fa-<?php echo $c['slug']; ?>"></i> <?php echo $c['name']; ?></span>
                                </li>
                                <?php $i++;
                            endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="row g-2 g-md-3 list-grid list-grid-padding">
                        <?php $posts = $item['posts'];
                        if (empty($posts)):
                            self::empty();
                        else:
                            foreach ($posts as $post):
                                ?>
                                <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xxl-2">
                                    <?php self::navitem($post); ?>
                                </div>
                            <?php endforeach;
                        endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * 评分星级
     */
    public static function score($score, string $color = '#FFD43B'): void
    {
        $score = max(0, min(5, (float) $score));
        for ($i = 1; $i <= 5; $i++) {
            if ($score >= $i) {
                $icon = 'fa-solid fa-star';
            } elseif ($score >= $i - 0.5) {
                $icon = 'fa-solid fa-star-half-alt';
            } else {
                $icon = 'fa-regular fa-star';
            }
            printf('<i class="%s" style="color: %s;"></i>', $icon, $color);
        }
    }

    /**
     * 分页指示器
     */
    public static function paginator($baseUrl, $current, $total)
    {
        ?>
        <nav class="navigation pagination" aria-label="Posts Navigation">
            <div class="nav-links">
                <?php
                if ($current > 1) {
                    echo '<a class="prev page-numbers" href="' . $baseUrl . ($current - 1) . '">上一页</a>';
                }
                for ($i = 1; $i <= $total; $i++) {
                    if ($i == $current) {
                        echo '<span aria-current="page" class="page-numbers current">' . $i . '</span>';
                    } elseif ($i === 1 || $i === $total || abs($i - $current) <= 2) {
                        echo '<a class="page-numbers" href="' . $baseUrl . $i . '">' . $i . '</a>';
                    } elseif (abs($i - $current) === 3) {
                        echo '<span class="page-numbers dots">...</span>';
                    }
                }

                if ($current < $total) {
                    echo '<a class="next page-numbers" href="' . $baseUrl . ($current + 1) . '">下一页</a>';
                }
                ?>
            </div>
        </nav>
        <?php
    }

    public static function loading()
    {
        ?>
        <div class="d-flex justify-content-center align-items-center w-100 h-100 ">
            <div class="spinner-grow" role="status">
                <span class="visually-hidden">加载中...</span>
            </div>
        </div>
        <?php
    }

    public static function empty()
    {
        $options = Helper::options();
        ?>
        <div class="col-12 d-flex flex-column justify-content-center align-items-center">
            <img src="<?php $options->themeUrl("/assets/image/empty.svg"); ?>" alt="empty" class="img-fluid h-100">
            <p class="text-muted mt-3 mb-1">暂无数据~</p>
        </div>
        <?php
    }

    public static function failed()
    {
        $options = Helper::options();
        ?>
        <div class="col-12 d-flex flex-column justify-content-center align-items-center">
            <img src="<?php $options->themeUrl("/assets/image/load-failed.svg"); ?>" alt="load-failed" class="img-fluid h-100">
            <p class="text-muted mt-3 mb-1">加载失败~</p>
        </div>
        <?php
    }

    public static function comments(array $comments): void
    {
        if (empty($comments)) {
            ?>
            <div class="comments-empty text-center py-5 rounded-4 bg-body-tertiary">
                <div class="comments-empty-icon mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center">
                    <i class="fa-regular fa-comment-dots fs-4"></i>
                </div>
                <h5 class="mb-2">还没有评论</h5>
                <p class="text-muted mb-0">第一条回复通常最容易开启一场有价值的讨论。</p>
            </div>
            <?php
            return;
        }

        foreach ($comments as $comment) {
            self::commentItem($comment);
        }
    }

    public static function threadedComments($comments, $options): void
    {
        $createdAt = (int) $comments->created;
        $createdText = date('Y-m-d H:i', $createdAt);
        $author = htmlspecialchars((string) ($comments->author ?? ''), ENT_QUOTES, 'UTF-8');
        $url = trim((string) ($comments->url ?? ''));
        $avatar = htmlspecialchars(ThemeHelper::avatar((string) ($comments->mail ?? '')), ENT_QUOTES, 'UTF-8');
        $isAuthor = ($comments->authorId > 0) && ((int) $comments->authorId === (int) $comments->ownerId);
        ?>
        <article id="comment-<?php echo (int) $comments->coid; ?>" data-coid="<?php echo (int) $comments->coid; ?>">
            <div class="d-flex align-items-start gap-3 rounded-4 pt-3">
                <div class="comment-avatar rounded-circle overflow-hidden flex-shrink-0 lh-1">
                    <img src="<?php echo $avatar; ?>" alt="<?php echo $author; ?>" loading="lazy" class="w-100 h-100 object-fit-cover">
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <?php if (!ThemeHelper::isBlank($url)): ?>
                                <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="nofollow noopener" class="comment-author-name link-body-emphasis text-decoration-none fw-semibold"><?php echo $author; ?></a>
                            <?php else: ?>
                                <span class="comment-author-name fw-semibold text-body-emphasis"><?php echo $author; ?></span>
                            <?php endif; ?>
                            <?php if ($isAuthor): ?>
                                <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis">作者</span>
                            <?php endif; ?>
                        </div>
                        <time class="small text-body-secondary" datetime="<?php echo $createdText; ?>" title="<?php echo $createdText; ?>">
                            <?php echo ThemeHelper::formatTimeAgo($createdAt); ?>
                        </time>
                    </div>
                    <div class="my-2 text-break">
                        <?php if ('waiting' === $comments->status): ?>
                            <div class="alert alert-light border rounded-4 px-3 py-2 mb-3"><?php $options->commentStatus(); ?></div>
                        <?php endif; ?>
                        <?php $comments->content(); ?>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-3 mt-2">
                        <span class="badge rounded-pill text-bg-light text-secondary fw-normal">#<?php echo (int) $comments->coid; ?></span>
                        <button type="button" class="comment-reply-trigger badge rounded-pill text-bg-light text-secondary fw-normal border-0"
                            data-coid="<?php echo (int) $comments->coid; ?>"
                            data-author="<?php echo $author; ?>">
                            <i class="fa-solid fa-reply me-1"></i>回复
                        </button>
                    </div>
                    <?php if ($comments->children): ?>
                        <div class="comment-children">
                            <?php $comments->threadedComments(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php
    }

}
