<?php

/**
 * 搜索结果
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
                            <?php
                            $keywords = mb_substr($this->request->keywords, 0, 20, 'UTF-8');
                            $ellipsis = (mb_strlen($keywords, 'UTF-8') > 10) ? mb_substr($keywords, 0, 10, 'UTF-8') . '...' : $keywords;
                            $pageSize = $this->options->pageSize;
                            $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
                            $result = Utils::page($pageSize, $currentPage, $keywords);
                            ?>
                            <h1 class="post-title">
                                <?php echo '包含 "' . $ellipsis . '" 的文章'; ?>
                            </h1>
                            <div class="post-meta d-flex flex-fill justify-content-center align-items-center text-base mt-3 mt-md-3">
                                <span class="text-muted">共 <?php echo $result['total']; ?> 条结果</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-2 g-md-3 list-grid list-grid-padding">
                                <?php foreach ($result['data'] as $cid): ?>
                                    <?php $item = Helper::widgetById('Contents', $cid); ?>
                                    <div class="col-12 col-md-6">
                                        <div class="list-item block">
                                            <?php $encrypt = false;
                                            if (!empty($item->password)) {
                                                $password = Typecho_Cookie::get('protectPassword_' . $cid);
                                                $encrypt = empty($password) || $password != $item->password;
                                            } ?>
                                            <div role="button" href="<?php $item->permalink(); ?>" title="点击查看详情" class="media w-36 rounded-circle">
                                                <img src="<?php $this->options->themeUrl('/assets/image/default.gif'); ?>"
                                                    data-src="<?php echo Utils::favicon($item); ?>"
                                                    class="media-content lazyload" />
                                            </div>
                                            <div role="button" href="<?php ($item->fields->navigation == '1' && !$encrypt) ? $item->fields->url() : $item->permalink(); ?>" cid="<?php $item->cid(); ?>" class="list-content" title="<?php $item->fields->text(); ?>">
                                                <div class="list-body">
                                                    <div class="list-title text-md h-1x"><?php $item->title(); ?></div>
                                                    <div class="list-desc text-xx text-muted mt-1">
                                                        <div class="h-1x"><?php echo $encrypt ? '验证后可查看内容' : $item->fields->text ?></div>
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
            </div>
            <?php
            $pageLink = $this->permalink . '?page=';
            echo Utils::pagination($pageLink, $result['currentPage'], $$result['totalPages']); ?>
        </div>
    </div>
</main>
<?php $this->need('footer.php'); ?>