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
$this->need("navbar.php");
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
                                $pageSize = ThemeHelper::isPositive($this->options->pageSize) ? (int) $this->options->pageSize : 10;
                                $currentPage = ThemeHelper::isPositive($_GET['page']) ? (int) $_GET['page'] : 1;
                                $uid = ThemeHelper::getUid();
                                $result = ThemeRepository::posts($pageSize, $currentPage, null, $uid);
                                $currentPage = $result['currentPage'];
                                $totalPages = $result['totalPages'];
                                ?>
                                <div class="timeline" id="timeline"
                                    data-current="<?php echo $currentPage; ?>"
                                    data-total="<?php echo $totalPages; ?>"
                                    data-page-size="<?php echo $pageSize; ?>">
                                    <?php foreach ($result['data'] as $cid): ?>
                                        <?php $post = ThemeRepository::post($cid, $uid); ?>
                                        <div class="timeline-element">
                                            <div>
                                                <span class="timeline-element-icon">
                                                    <i class="badge badge-dot">
                                                        <img src="<?php $this->options->themeUrl(ThemeConfig::DEFAULT_LOADING_ICON); ?>"
                                                            data-src="<?php echo $post['logo']; ?>"
                                                            class="media-content lazy" />
                                                    </i>
                                                </span>
                                                <div class="timeline-element-content">
                                                    <h4 class="timeline-title">
                                                        <a href="<?php echo $post['permalink']; ?>"><?php echo $post['title']; ?></a>
                                                    </h4>
                                                    <p><?php echo $post['text']; ?></p>
                                                    <span
                                                        class="timeline-element-date"><?php echo date('m-d, Y', $post['modified']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div id="timeline-loading" class="timeline-loading invisible">
                                    <?php ThemeView::loading(); ?>
                                </div>
                                <div id="timeline-end" class="timeline-end text-center text-muted mt-3 d-none">
                                    已经到底了
                                </div>
                            </div>
                            <div class="post-actions row g-2 mt-4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $this->need('footer.php'); ?>
