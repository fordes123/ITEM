<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$this->need('sidebar.php');
$this->need('topbar.php');

$hidden = $this->hidden;
$hasPassword = !empty($this->password);
if ($hidden && $hasPassword) {
    $password = Typecho_Cookie::get('protectPassword_' . $this->cid);
    $hasPassword = empty($password) || $password != $this->password;
}

if ($this->fields->navigation == 2): ?>
    <div class="modal fade" id="openWxModal" tabindex="-1" aria-labelledby="openWxModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body d-flex flex-column justify-content-center align-items-center gap-3">
                    <?php if ($this->fields->url): ?>
                        <img src="<?php $this->fields->url(); ?>" alt="二维码" class="img-fluid mt-3">
                    <?php elseif ($this->fields->logo): ?>
                        <img src="<?php $this->fields->logo(); ?>" alt="logo" class="img-fluid mt-3">
                    <?php endif; ?>
                    <p class="modal-title mb-3 fs-5"><?php echo $this->title(); ?></p>
                    <p class="text-muted">Tips: 长按识别二维码, 或者去微信搜索：<b class="text-success"><?php echo $this->title(); ?></b></p>
                    <a class="btn btn-primary" href="weixin://" role="button">前往微信</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

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
                                <span class="date text-muted"><?php echo Utils::timeago($this->modified); ?></span>
                                <?php if ($this->fields->score): ?>
                                    <i class="text-light mx-2">•</i>
                                    <span><?php Utils::printStars($this->fields->score) ?>（<?php echo $this->fields->score ?>）</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if ($hidden): ?>

                                <div class="password-form-container text-left mx-5">
                                    <div class="password-form py-4 mx-auto">

                                        <h4 class="mb-3"><i class=" fas fa-lock"></i>&nbsp;<?php echo $hasPassword ? '此内容受密码保护' : '此内容已隐藏' ?></h4>
                                        <p class="text-muted mb-4"><?php echo $hasPassword ? '请输入密码以查看内容' : '如有疑问请站点联系管理员' ?></p>

                                        <?php if ($hasPassword): ?>
                                            <form method="post" action="<?php echo $this->security->getTokenUrl($this->permalink) ?>">
                                                <div class="input-group mb-3">
                                                    <input type="password" name="protectPassword" class="form-control text" placeholder="请输入密码" required>
                                                    <input type="hidden" name="protectCID" value="<?php echo $this->cid; ?>">
                                                    <button type="submit" class="submit btn btn-primary">提交</button>
                                                </div>

                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="post-tags">
                                    <?php foreach ($this->tags as $tag) : ?>
                                        <a><?php echo ($tag['name']); ?></a>
                                    <?php endforeach; ?>
                                </div>

                                <div class="post-content">
                                    <div class="post-excerpt">
                                        <?php if ($this->fields->text): ?>
                                            <i class="excerpt-icon"></i>
                                            <h4><?php echo $this->fields->text; ?></h4>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-wrap text-break fs-6 mx-3">
                                        <?php $this->content(); ?>
                                    </div>

                                    <div class="post-actions row g-2 mt-4">
                                        <div class="col">
                                            <a href="#" class="btn btn-icon btn-block btn-lg disabled">
                                                <span><i class="far fa-eye"></i></span>
                                                <b class="num"><?php Utils::views($this->cid); ?></b>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a type="button" class="btn btn-icon btn-block btn-lg <?php echo Utils::agreed($this->cid); ?>"
                                                id="agree-btn" data-cid="<?php echo $this->cid; ?>">
                                                <span><i class="far fa-thumbs-up"></i></span>
                                                <b class="num"><?php Utils::agree($this->cid) ?></b>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a href="#" class="btn-share-toggler btn btn-icon btn-block btn-lg disabled">
                                                <span><i class="far fa-star"></i></span>
                                            </a>
                                        </div>
                                        <?php if ($this->fields->navigation === '2'): ?>
                                            <div class="col-12 col-md-7">
                                                <button type="button" class="btn btn-primary btn-lg btn-block btn-goto" data-bs-toggle="modal" data-bs-target="#openWxModal">
                                                    进入小程序
                                                </button>
                                            </div>
                                        <?php elseif ($this->fields->navigation === '1'): ?>
                                            <div class="col-12 col-md-7">
                                                <a href="<?php echo $this->fields->url(); ?>" target="_blank" title="<?php $this->title(); ?>" class="btn btn-primary btn-lg btn-block btn-goto">
                                                    访问网站
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($this->is('post')) : ?>
                            <?php $this->related(6, count($this->tags) > 0 ? 'tag' : 'author')->to($item); ?>
                            <?php if ($item->have()) : ?>
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
                                                        <div role="button" href="<?php $item->permalink(); ?>" title="点击查看详情" class="media w-36 rounded-circle">
                                                            <img src="<?php $this->options->themeUrl('/assets/image/default.gif'); ?>"
                                                                data-src="<?php echo Utils::favicon($item); ?>"
                                                                class="media-content lazyload" />
                                                        </div>
                                                        <div role="button" href="<?php echo empty($item->fields->url()) ? $item->permalink : $item->fields->url; ?>" cid="<?php $item->cid(); ?>" class="list-content" title="<?php $item->fields->text(); ?>">
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
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $this->need('footer.php'); ?>