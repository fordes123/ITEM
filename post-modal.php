<!-- 点击进入小程序 -->
<div class="modal fade" id="openWxModal" tabindex="-1" aria-labelledby="openWxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column justify-content-center align-items-center gap-3">
                <?php if ($this->fields->url): ?>
                    <img src="<?php $this->fields->url(); ?>" alt="二维码" class="img-fluid mt-3">
                <?php elseif ($this->fields->logo): ?>
                    <img src="<?php $this->fields->logo(); ?>" alt="logo" class="img-fluid mt-3">
                <?php endif; ?>
                <p class="modal-title mb-3">小程序名称：<?php echo $this->title(); ?></p>
                <p class="text-danger">恭喜！小程序名称复制成功～</p>
                <p class="text-danger">快去微信-搜索小程序体验吧！</p>
                <a class="btn btn-success" href="weixin://" role="button">前往微信</a>
            </div>
        </div>
    </div>
</div>

<!-- 点击图片大图 -->
<div class="modal fade" id="navModal" tabindex="-1" aria-labelledby="navModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column justify-content-center align-items-center gap-3">
                <img id="nav-large-image" src="" alt="<?php echo $this->title(); ?>" class="img-fluid">
            </div>
        </div>
    </div>
</div>
