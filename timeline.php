<?php

/**
 * 目录/时间线
 *
 * @package custom
 */

if (!defined("__TYPECHO_ROOT_DIR__")) {
    exit();
}
$this->need("header.php");
$this->need("sidebar.php");
$this->need("topbar.php");
?>

<main class="site-main">
    <div class="container">
        <div class="container">
            <div class="row gx-3 gx-md-4">
                <div class="post card card-md mb-3 mb-md-4">
                    <div class="post-other-style">
                        <div class="post-heading text-center pt-5 pt-md-5 pb-3 pb-xl-4">
                            <h1 class="post-title"> <?php $this->title(); ?></h1>
                        </div>
                        <div class="card-body">
                            <div class="post-content">
                                <?php
                                $pageSize = $this->options->pageSize;
                                $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
                                $result = Utils::page($pageSize, $currentPage);
                                ?>
                                <div class="timeline">
                                    <?php foreach ($result['data'] as $cid): ?>
                                        <?php $post = Helper::widgetById('Contents', $cid); ?>
                                        <div class="timeline-element">
                                            <div>
                                                <span class="timeline-element-icon">
                                                    <i class="badge badge-dot">
                                                        <img src="<?php $this->options->themeUrl('/assets/image/default.gif'); ?>" data-src="<?php echo Utils::favicon($post); ?>" class="media-content lazyload" />
                                                    </i>
                                                </span>
                                                <div class="timeline-element-content">
                                                    <h4 class="timeline-title">
                                                        <a href="<?php $post->permalink(); ?>"><?php $post->title(); ?></a>
                                                    </h4>
                                                    <p><?php if ($post->fields->text) $post->fields->text();
                                                        else $post->excerpt(80, '...'); ?></p>
                                                    <span class="timeline-element-date"><?php echo date('m-d, Y', $post->modified); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="post-actions row g-2 mt-4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $pageLink = $this->permalink . '?page=';
            $currentPage = $result['currentPage'];
            $totalPages = $result['totalPages'];
            echo Utils::pagination($pageLink, $currentPage, $totalPages); ?>
        </div>
    </div>
</main>
<?php $this->need('footer.php'); ?>