<?php


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
                            <h1 class="post-title">
                                <?php $this->archiveTitle('标签：', '', ''); ?>
                            </h1>
                            <div class="post-meta d-flex flex-fill justify-content-center align-items-center text-base mt-3 mt-md-3">
                                <span class="text-muted">共 <?php echo $this->getTotal(); ?> 项内容</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-2 g-md-3 list-grid list-grid-padding">
                                <?php if ($this->have()): ?>
                                    <?php while ($this->next()): ?>
                                        <?php $post = ThemeHelper::normalizePost($this); ?>
                                        <div class="col-12 col-md-6">
                                            <?php ThemeView::navitem($post); ?>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <?php ThemeView::empty(); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $totalPages = ceil($this->getTotal() / $this->options->pageSize);
            $baseUrl = $this->options->siteUrl . 'tag/' . $this->getArchiveSlug() . '/';
            ThemeView::paginator($baseUrl, $this->getCurrentPage(), $totalPages);
            ?>
        </div>
    </div>
</main>
<?php $this->need('footer.php'); ?>