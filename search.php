<?php

/**
 * 搜索结果
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$this->need('header.php');
$this->need('sidebar.php');
$this->need('topbar.php'); ?>
<main class="site-main">
    <div class="container">
        <div class="row g-3 g-xl-4">
            <div class="col-12">
                <div class="card" id="<?php echo $item['slug']; ?>">
                    <div class="card-body">
                        <div class="row g-2 g-md-3 list-grid list-grid-padding">
                            <form id="search" method="post" role="search">
                                <input type="text" id="s" name="s" class="text form-control" placeholder="<?php $this->archiveTitle('', '', ''); ?>" value="<?php echo $this->_keywords; ?>"/>
                            </form>
                            <hr>
                            <?php
                            $hasData = false;
                            while ($this->next()) :
                                $hasData = true;
                                if (!is_null($this->fields->navigation)) : ?>
                                    <div class="col-6 col-lg-3">
                                        <div class="list-item block">
                                            <div href="<?php $this->permalink() ?>" title="点击进入详情"
                                                 class="media w-36 rounded-circle">
                                                <img src="<?php $this->options->themeUrl('/assets/image/default.png'); ?>"
                                                     data-src="<?php echo getSiteFavicon($this); ?>"
                                                     class="media-content lazyload"
                                                />
                                            </div>
                                            <div href="<?php
                                                    if ($this->fields->navigation == '1') {
                                                        echo $this->fields->url();
                                                    } else {
                                                        echo $this->permalink();
                                                    }
                                                    ?>"
                                                 target='_blank'
                                                 cid="<?php $this->cid(); ?>"
                                                 title="<?php $this->fields->text(); ?>" class="list-content">
                                                <div class="list-body">
                                                    <div class="list-title text-md h-1x">
                                                        <?php $this->title(); ?>
                                                    </div>
                                                    <div class="list-desc text-xx text-muted mt-1">
                                                        <div class="h-1x"><?php $this->fields->text(); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif;
                            endwhile;
                            // 检查是否有数据
                            if (!$hasData) :
                                ?>
                                <div class="col-12 text-center">
                                    <p>抱歉，没有搜到<?php $this->archiveTitle('', '「', '」'); ?>相关的内容~</p>
                                </div>
                            <?php endif;
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $this->need('footer.php'); ?>
