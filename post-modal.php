<!-- 点击进入小程序 -->
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