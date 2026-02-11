<?php if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;

$isRedirect = isset($_GET['go']);
$hidden = $this->status === 'hidden';
$hasPassword = ThemeHelper::hasPasswd($this);
$metrics = ThemeRepository::postStats($this->cid);
if ($isRedirect && !$hidden && !$hasPassword && $this->fields->navigation === '1') {
    $this->response->redirect($this->fields->url, true, 302);
}

$this->need('header.php');
$this->need('sidebar.php');
$this->need('navbar.php');

$hidden = $this->status === 'hidden';
$hasPassword = ThemeHelper::hasPasswd($this);

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
                    <p class="text-muted">Tips: 长按识别二维码, 或者去微信搜索: <b class="text-success"><?php echo $this->title(); ?></b>
                    </p>
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
                            <div
                                class="d-flex flex-column flex-md-row justify-content-center align-items-center position-relative">
                                <div class="d-flex align-items-center">
                                    <img src="<?php $this->options->themeUrl(ThemeConfig::DEFAULT_LOADING_ICON); ?>"
                                        data-src="<?php echo ThemeHelper::favicon($this); ?>"
                                        class="rounded w-auto lazy" style="height: 2.5rem;" />
                                    <h1 class="post-title m-0 ms-2 text-truncate"> <?php $this->title(); ?><sup>
                                            <?php if ($this->user->hasLogin() && ($this->user->group == 'administrator' || $this->authorId == $this->user->uid)): ?>
                                                <a href="<?php $this->options->adminUrl(); ?>write-post.php?cid=<?php echo $this->cid; ?>"
                                                    target="_blank" class="fs-6 text-muted" title="跳转编辑文章">
                                                    <i class="fa-solid fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                        </sup></h1>
                                </div>

                            </div>
                            <div
                                class="post-meta d-flex flex-fill justify-content-center align-items-center text-base mt-3 mt-md-3">
                                <a href="<?php $this->author->url(); ?>" class="d-flex align-items-center text-muted">
                                    <div class="flex-avatar w-16 me-2">
                                        <img alt="Avatar"
                                            src="<?php $this->options->themeUrl(ThemeConfig::DEFAULT_LOADING_ICON); ?>"
                                            data-src="<?php echo ThemeHelper::avatar($this->author->mail); ?>"
                                            width="16" height="16" class="lazy" />
                                    </div>
                                    <?php $this->author(); ?>
                                </a>
                                <i class="text-light mx-2">•</i>
                                <span
                                    class="date text-muted"><?php echo ThemeHelper::formatTimeAgo($this->modified); ?></span>
                                <?php if ($this->fields->score): ?>
                                    <i class="text-light mx-2">•</i>
                                    <span><?php ThemeView::score($this->fields->score); ?>&nbsp;
                                        (<?php echo $this->fields->score; ?>)</span>
                                <?php endif; ?>

                            </div>
                        </div>
                        <div class="card-body">
                            <?php if ($hidden || $hasPassword): ?>

                                <div class="password-form-container text-left mx-5">
                                    <div class="password-form py-4 mx-auto">
                                        <h4 class="mb-3"><i
                                                class="fa-solid fa-lock"></i>&nbsp;<?php echo $hasPassword ? '验证后可查看内容' : '此内容已隐藏' ?>
                                        </h4>
                                        <p class="text-muted mb-4">
                                            <?php
                                            if ($hasPassword):
                                                echo ThemeHelper::isBlank($this->fields->encryptTip) ? ThemeConfig::DEFAULT_ENCRYPT_TIP : $this->fields->encryptTip;
                                            else:
                                                echo ThemeConfig::DEFAULT_ENCRYPT_TIP;
                                            endif; ?>
                                        </p>
                                        <?php if ($hasPassword): ?>
                                            <form method="post"
                                                action="<?php echo $this->security->getTokenUrl($this->permalink) ?>">
                                                <div class="input-group mb-3">
                                                    <input type="password" name="protectPassword" class="form-control text"
                                                        placeholder="请输入密码" required>
                                                    <input type="hidden" name="protectCID" value="<?php echo $this->cid; ?>">
                                                    <button type="submit" class="submit btn btn-primary">提交</button>
                                                </div>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="post-tags">
                                    <?php foreach ($this->tags as $tag): ?>
                                        <a href="<?php echo $tag['permalink']; ?>"><?php echo ($tag['name']); ?></a>
                                    <?php endforeach; ?>
                                </div>

                                <div class="post-content">
                                    <div class="post-excerpt">
                                        <?php if ($this->fields->text): ?>
                                            <i class="fa-solid fa-quote-left fa-2x align-text-bottom"></i>
                                            <h4 class="d-inline"><?php echo $this->fields->text; ?></h4>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-wrap text-break fs-6 mx-3">
                                        <?php $this->content(); ?>
                                    </div>

                                    <div class="post-actions row g-2 mt-4">
                                        <div class="col">
                                            <a href="#" class="btn btn-icon btn-block btn-lg disabled">
                                                <span><i class="fa-regular fa-eye"></i></span>
                                                <b class="num"><?php echo $metrics['views']; ?></b>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a id="agree-btn" data-cid="<?php echo $this->cid; ?>" type="button"
                                                class="btn btn-icon btn-block btn-lg disabled">
                                                <span><i class="fa-regular fa-thumbs-up"></i></span>
                                                <b class="num"><?php echo $metrics['agree'] ?></b>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a id="favorite-btn" data-cid="<?php echo $this->cid; ?>" type="button"
                                                class="btn btn-icon btn-block btn-lg">
                                                <span><i class="fa-regular fa-star"></i></span>
                                            </a>
                                        </div>
                                        <?php if ($this->fields->navigation === '2'): ?>
                                            <div class="col-12 col-md-7">
                                                <button type="button" class="btn btn-primary btn-lg btn-block btn-goto"
                                                    data-bs-toggle="modal" data-bs-target="#openWxModal">
                                                    进入小程序
                                                </button>
                                            </div>
                                        <?php elseif ($this->fields->navigation === '1'): ?>
                                            <div class="col-12 col-md-7">
                                                <a href="<?php echo $this->fields->url(); ?>" target=" _blank"
                                                    title="<?php $this->title(); ?>"
                                                    class="btn btn-primary btn-lg btn-block btn-goto">
                                                    访问网站
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($this->is('post')): ?>
                            <?php $this->related(6, count($this->tags) > 0 ? 'tag' : 'author')->to($posts); ?>
                            <?php if ($posts->have()): ?>
                                <div class="card card-xl shadow-none rounded-0">
                                    <div class="card-header d-flex flex-nowrap text-nowrap gap-2 align-items-center">
                                        <div class="h4"> <i class="fa-solid fa-wand-magic-sparkles fa-sm"></i>&nbsp;相关推荐
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2 g-md-3 list-grid list-grid-padding">
                                            <?php while ($posts->next()):
                                                $item = ThemeHelper::normalizePost($posts);
                                                ?>
                                                <div class="col-12 col-md-6">
                                                    <?php ThemeView::navitem($item); ?>
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