<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$this->need('sidebar.php');
$this->need('topbar.php');
?>

<main class="site-main">
    <div class="container">
        <div class="container error-page">
            <div class="row gx-3 gx-md-4">
                <div class="post mb-3 mb-md-4">
                    <div class="text-center p-4 mb-5">
                        <h1 class="display-1 mb-2">404</h1>
                        <p class="fs-2 fw-bolder text-secondary mb-2">🙄 页面被🦖吃掉啦~</p>
                        <p class="fs-6 mb-4 text-muted">
                            按 <kbd>Space</kbd> 玩一会小恐龙dino快跑
                            <a href="https://github.com/zzzmhcn/dino" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="游戏源码来自 Chromium, 本页修改自: zzzmhcn/dino, 点击前往原仓库">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </p>
                        <a href="<?php $this->options->siteUrl(); ?>" class="btn btn-primary">
                            回到首页
                        </a>
                    </div>

                    <div class="container offline" id="t">
                        <div id="main-frame-error" class="interstitial-wrapper">
                            <div id="main-content">
                                <div class="icon icon-offline" alt=""></div>
                            </div>
                            <div id="offline-resources">
                                <img id="offline-resources-1x" src="<?php $this->options->themeUrl('/assets/image/dino-resources.png'); ?>"
                                    style="display: none" alt="">
                                <template id="audio-resources">
                                    <audio id="offline-sound-press" src="<?php $this->options->themeUrl('/assets/audio/dino-press.mp3'); ?>"></audio>
                                    <audio id="offline-sound-hit" src="<?php $this->options->themeUrl('/assets/audio/dino-hit.png'); ?>"></audio>
                                    <audio id="offline-sound-reached" src="<?php $this->options->themeUrl('/assets/audio/dino-reached.mp3'); ?>"></audio>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src=" <?php $this->options->themeUrl('./assets/js/dino.min.js'); ?>" type="text/javascript"></script>
<?php $this->need('footer.php'); ?>