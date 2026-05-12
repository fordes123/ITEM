<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$this->comments()->to($comments);
$allowComment = $this->allow('comment');
$commentHint = ThemeHelper::isBlank($this->options->commentHint ?? '')
    ? ThemeConfig::DEFAULT_COMMENT_HINT
    : (string) $this->options->commentHint;

if (!function_exists('threadedComments')) {
    function threadedComments($comments, $options): void
    {
        ThemeView::threadedComments($comments, $options);
    }
}
?>
<div id="comments" class="card card-xl shadow-none rounded-0 px-0 pb-0">
    <div class="card-header d-flex flex-wrap text-nowrap gap-2 align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3 h4 mb-0">
            <i class="fa-solid fa-comment-dots"></i>
            <div class="mb-0">
                评论
                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary fw-normal ms-1 fs-7">
                    <?php echo (int) $this->commentsNum; ?>
                </span>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div id="comment-list">
            <?php if ($comments->have()): ?>
                <?php $comments->listComments(['before' => '', 'after' => '']); ?>
            <?php else: ?>
                <div class="comments-empty text-center py-5 rounded-4 bg-body-tertiary">
                    <div class="comments-empty-icon mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center">
                        <i class="fa-regular fa-comment-dots fs-4"></i>
                    </div>
                    <h5 class="mb-2">还没有评论</h5>
                    <p class="text-muted mb-0">第一条回复通常最容易开启一场有价值的讨论。</p>
                </div>
            <?php endif; ?>
        </div>

        <?php $comments->pageNav('上一页', '下一页'); ?>

        <?php if ($allowComment): ?>
            <div id="respond-post-<?php echo (int) $this->cid; ?>" class="mt-4 mt-md-5 pt-4 border-top">
                <div id="comment-reply-state" class="alert alert-light border rounded-4 px-3 py-2 mb-3 d-none">
                    正在回复 <span class="fw-semibold" data-reply-author></span>
                    <button type="button" id="comment-reply-cancel" class="btn btn-link btn-sm text-decoration-none p-0 ms-2 text-danger">取消</button>
                </div>

                <form method="post" action="<?php $this->commentUrl(); ?>" id="comment-form">
                    <input type="hidden" name="parent" id="comment-parent" value="0">

                    <?php if (!$this->user->hasLogin()): ?>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <input type="text" name="author" class="comment-form-control form-control form-control-sm border-0 bg-light px-3 py-2" placeholder="昵称 *" required>
                            </div>
                            <div class="col-md-4">
                                <input type="email" name="mail" class="comment-form-control form-control form-control-sm border-0 bg-light px-3 py-2" placeholder="邮箱 *" required>
                            </div>
                            <div class="col-md-4">
                                <input type="url" name="url" class="comment-form-control form-control form-control-sm border-0 bg-light px-3 py-2" placeholder="网址">
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="position-relative">
                        <textarea name="text" id="textarea" class="comment-form-control form-control border-0 bg-light px-3 py-3" rows="4" placeholder="说点什么吧..." required></textarea>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3">
                            <span class="small text-muted px-1"><i class="fa-solid fa-circle-info me-1"></i><?php echo htmlspecialchars($commentHint, ENT_QUOTES, 'UTF-8'); ?></span>
                            <button type="submit" class="comment-submit-btn btn btn-primary btn-sm rounded-pill px-4 py-2 fw-medium align-self-start align-self-md-auto">
                                发送评论
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>
