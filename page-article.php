<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
if (isset($_POST['agree'])) {
    if ($_POST['agree'] == $this->cid) {
        exit(agree($this->cid));
    }
    exit('error');
}
$agree = $this->hidden ? array('agree' => 0, 'recording' => true) : agreeNum($this->cid);
$this->need('header.php');
$this->need('sidebar.php');
$this->need('topbar.php'); ?>


<main class="site-main">
    <div class="container">
        <div class="container">
            <div class="row gx-3 gx-md-4">
                <div class="post card card-md mb-3 mb-md-4">
                    <div class="post-other-style">
                        <div class="post-heading text-center pt-5 pt-md-5 pb-3 pb-xl-4">
                            <h1 class="post-title"> <?php $this->title(); ?></h1>
                            <div class="post-meta d-flex flex-fill justify-content-center align-items-center text-base mt-3 mt-md-3">
                                <a href="<?php $this->author->url(); ?>" class="d-flex align-items-center text-muted">
                                    <div class="flex-avatar w-16 me-2">
                                        <img alt="" src="<?php $this->options->themeUrl('/assets/image/default.png'); ?>" data-src="https://cravatar.cn/avatar/<?php echo md5($this->author->mail); ?>?s=16" width="16" height="16" class="lazyload" />
                                    </div>
                                    <?php $this->author(); ?>
                                </a>
                                <i class="text-light mx-2">•</i>
                                <span class="date text-muted"><?php echo timeago($this->modified); ?></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="post-tags">
                                <?php foreach ($this->tags as $tag) : ?>
                                    <a><?php echo ($tag['name']); ?></a>
                                <?php endforeach; ?>
                            </div>
                            <div class="post-content">
                                <div class="text-wrap text-break fs-6 mx-3">
                                    <!-- 显示 文章 -->
                                    <?php $this->content(); ?>
                                </div>
                            </div>
                            <div class="post-actions row g-2 mt-4">
                                <div class="col">
                                    <a href="#" class="btn btn-light btn-icon btn-block btn-lg disabled">
                                        <span><i class="far fa-eye"></i></span>
                                        <b class="num"><?php pageview($this->cid) ?></b>
                                    </a>
                                </div>
                                <div class="col">
                                    <a type="button"
                                       class="btn-share-toggler btn btn-light btn-icon btn-block btn-lg <?php echo $agree['recording']?'disabled':''; ?>"
                                       id="agree-btn"
                                       data-cid="<?php echo $this->cid; ?>"
                                       data-url="<?php $this->permalink(); ?>">
                                        <span><i class="far fa-thumbs-up"></i></span>
                                        <b class="num"><?php echo $agree['agree']; ?></b>
                                    </a>
                                </div>
                                <div class="col">
                                    <a href="#" class="btn-share-toggler btn btn-light btn-icon btn-block btn-lg disabled">
                                        <span><i class="far fa-star"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php if ($this->is('post')) :
                            $this->related(6, count($this->tags) > 0 ? 'tag' : 'author')->to($item);
                            if ($item->have()) : ?>
                                <div class="post-related card card-xl mt-4 ">
                                    <div class="card-header">
                                        <div class="related-header">
                                            <div class="related-icon"></div>
                                            <div class="h4">猜你喜欢</div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2 g-md-3 list-grid list-grid-padding">
                                            <?php while ($item->next()) : ?>
                                                <div class="col-12 col-md-6">
                                                    <div class="list-item block">
                                                        <div href="<?php $item->permalink(); ?>" title="点击查看详情" class="media w-36 rounded-circle">
                                                            <img src="<?php $this->options->themeUrl('/assets/image/default.png'); ?>" data-src="<?php $item->fields->logo(); ?>" class="media-content lazyload" />
                                                        </div>
                                                        <div href="<?php $item->fields->url(); ?>" cid="<?php $item->cid(); ?>" class="list-content" title="<?php $item->fields->text(); ?>">
                                                            <div class="list-body">
                                                                <div class="list-title text-md h-1x"><?php $item->title(); ?></div>
                                                                <div class="list-desc text-xx text-muted mt-1">
                                                                    <div class="h-1x"><?php $item->fields->text(); ?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>
                                    </div>
                                </div>
                        <? endif;
                        endif;   ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php $this->need('footer.php'); ?>
