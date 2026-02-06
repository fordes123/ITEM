<?php

/**
 * 搜索结果
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
                            <?php

                            $keywords = ThemeHelper::filterKeywords($this->request->keywords, 10);
                            if (ThemeHelper::isBlank($keywords)) {
                                $this->redirect($this->options->siteUrl);
                            }

                            $pageSize = ThemeHelper::isPositive($this->options->pageSize) ? (int) $this->options->pageSize : 10;
                            $uid = $this->user->group == 'administrator' ? -1 : $this->user->uid;

                            $result = ThemeRepository::posts($pageSize, $this->getCurrentPage(), $keywords, $uid);
                            ?>
                            <h1 class="post-title">
                                <?php echo '包含 "' . $keywords . '" 的文章'; ?>
                            </h1>
                            <div
                                class="post-meta d-flex flex-fill justify-content-center align-items-center text-base mt-3 mt-md-3">
                                <span class="text-muted">共 <?php echo $result['total']; ?> 条结果</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-2 g-md-3 list-grid list-grid-padding">
                                <?php foreach ($result['data'] as $cid): ?>
                                    <?php $item = ThemeRepository::post($cid); ?>
                                    <div class="col-12 col-md-6">
                                        <?php ThemeView::navitem($item); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $baseUrl = $this->options->siteUrl . 'search/' . rawurlencode($keywords) . '/';
            ThemeView::paginator($baseUrl, $result['currentPage'], $result['totalPages']);
            ?>
        </div>
    </div>
</main>
<?php $this->need('footer.php'); ?>