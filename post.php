<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
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
                                        <img alt="" src="<?php $this->options->themeUrl('/assets/image/default.gif'); ?>" data-src="https://cravatar.cn/avatar/<?php echo md5($this->author->mail); ?>?s=16" width="16" height="16" class="lazyload" />
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
                                <div class="post-excerpt"> <i class="excerpt-icon"></i>
                                    <h4><?php echo $this->fields->text ? $this->fields->text : $this->title; ?></h4>
                                </div>
                                <div class="text-wrap text-break fs-6 mx-3"><?php $this->content(); ?></div>
                            </div>
                            <div class="post-actions row g-2 mt-4">
                                <div class="col">
                                    <a href="#" class="btn btn-light btn-icon btn-block btn-lg disabled">
                                        <span><i class="far fa-eye"></i></span>
                                        <b class="num"><?php pageview($this->cid) ?></b>
                                    </a>
                                </div>
                                <div class="col">
                                    <a href="#" class="btn btn-light btn-icon btn-block btn-lg disabled">
                                        <span><i class="far fa-thumbs-up"></i></span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a href="#" class="btn-share-toggler btn btn-light btn-icon btn-block btn-lg disabled">
                                        <span><i class="far fa-star"></i></span>
                                    </a>
                                </div>
                                <div class="col-12 col-md-7">
                                    <?php if ($this->fields->navigation) : ?>
                                        <a href="<?php $this->fields->url(); ?>" title="<?php $this->title() ?>" target="_blank" class="btn btn-primary btn-lg btn-block btn-goto">立即访问</a>
                                    <?php else : ?>
                                        <a href="#" title="<?php $this->title() ?>" class="disabled btn btn-primary btn-lg btn-block btn-goto">这篇是站内文章哦~</a>
                                    <?php endif; ?>
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
                                                            <img src="<?php $this->options->themeUrl('/assets/image/default.gif'); ?>" data-src="<?php echo getSiteFavicon($item); ?>" class="media-content lazyload" />
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