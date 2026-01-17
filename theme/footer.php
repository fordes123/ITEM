<?php if (!defined('__TYPECHO_ROOT_DIR__'))
    exit; ?>
<footer class="site-footer">
    <div class="container">
        <div class="copyright text-xs text-muted text-center">
            <span class="d-inline-block">© <?php echo date("Y"); ?></span>
            <a class="text-muted" href="<?php $this->options->siteUrl(); ?>"
                rel="home"><?php $this->options->title(); ?></a>
            <?php if (!empty($this->options->icp)): ?>
                <span class="d-inline-block">&nbsp;|&nbsp;</span>
                <a href="https://beian.miit.gov.cn" target="_blank" rel="nofollow"
                    class="text-muted"><?php $this->options->icp(); ?></a>
            <?php endif; ?>
        </div>
    </div>
</footer>
</div>
<ul class="site-fixedmenu">
    <li id="scrollToTOP"> <a href="#" class="btn btn-start btn-icon btn-rounded"><span><i
                    class="fas fa-arrow-up"></i></span></a></li>
</ul>

<?php if ($this->is('index')): ?>

    <template id="tmpl-custom-loader">
        <div class="d-flex justify-content-center align-items-center loader-container">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">加载中...</span>
            </div>
        </div>
    </template>

    <template id="tmpl-category-item">
        <div class="col-6 col-lg-3">
            <div class="list-item block">
                <div class="media w-36 rounded" role="button">
                    <img src="/usr/themes/ITEM/assets/image/default.gif" class="media-content lazy">
                </div>
                <div class="list-content" role="button" target="_blank">
                    <div class="list-body">
                        <div class="list-title text-md h-1x"></div>
                        <div class="list-desc text-xx text-muted mt-1">
                            <div class="h-1x"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="tmpl-weather-error">
        <div class="py-4 text-center">
            <div class="d-inline-block">
                <div class="mb-3 position-relative d-inline-block">
                    <i class="fas fa-cloud-sun text-muted opacity-25 fs-1"></i>
                    <i
                        class="fas fa-exclamation-circle text-warning position-absolute top-50 start-100 translate-middle fs-5"></i>
                </div>
                <p class="text-muted mb-3 fw-light">无法获取天气信息,请稍后重试</p>
                <button type="button" class="btn btn-outline-primary btn-sm px-4 weather-retry-btn">
                    <i class="fas fa-sync-alt me-2"></i>重试
                </button>
            </div>
        </div>
    </template>

    <template id="tmpl-weather-content">
        <div class="px-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-truncate me-2">
                    <i class="fas fa-map-marker-alt text-primary me-1"></i>
                    <span class="fw-bold text-dark-emphasis weather-city"></span>
                </div>
                <span class="badge rounded-pill bg-opacity-10 border border-opacity-25 px-2 weather-aqi"></span>
            </div>

            <div class="row align-items-center g-0 mb-3">
                <div class="col-7">
                    <div class="d-flex align-items-baseline">
                        <span class="display-3 fw-bold text-dark-emphasis weather-temp"></span>
                        <span class="fs-4 text-secondary ms-1 weather-unit-temp"></span>
                    </div>
                    <div class="px-2">
                        <span class="badge bg-primary text-white mb-1 weather-text"></span>
                        <div class="text-muted small">
                            <i class="fas fa-thermometer-half me-1"></i>体感 <span class="weather-feels"></span>
                        </div>
                    </div>
                </div>
                <div class="col-5 text-end">
                    <img src="" alt="weather" class="img-fluid weather-icon">
                </div>
            </div>

            <div class="row g-2">
                <div class="col-4 col-md-6 col-xl-4 col-xxl-3">
                    <div class="rounded-3 p-2 text-center h-100" style="background-color: var(--bg-body);">
                        <i class="fas fa-tint text-info mb-1"></i>
                        <div class="small text-muted">湿度</div>
                        <div class="fw-bold small text-truncate weather-rh"></div>
                    </div>
                </div>
                <div class="col-4 col-md-6 col-xl-4 col-xxl-3">
                    <div class="rounded-3 p-2 text-center h-100" style="background-color: var(--bg-body);">
                        <i class="fas fa-wind text-secondary mb-1"></i>
                        <div class="small text-muted weather-wind-dir">风向</div>
                        <div class="fw-bold small text-truncate px-1 weather-wind-spd"></div>
                    </div>
                </div>
                <div class="col-4 d-md-none d-xl-block d-lg-none d-xxl-block col-lg-4 col-xxl-3">
                    <div class="rounded-3 p-2 text-center h-100" style="background-color: var(--bg-body);">
                        <i class="fas fa-sun text-warning mb-1"></i>
                        <div class="small text-muted">紫外线</div>
                        <div class="fw-bold small text-truncate weather-uv"></div>
                    </div>
                </div>
                <div class="d-none d-md-none d-xxl-block col-3">
                    <div class="rounded-3 p-2 text-center h-100" style="background-color: var(--bg-body);">
                        <i class="fas fa-eye text-primary mb-1"></i>
                        <div class="small text-muted">能见度</div>
                        <div class="fw-bold small text-truncate weather-vis"></div>
                    </div>
                </div>
            </div>
        </div>
    </template>
<?php endif; ?>

<script>
    window.config = {
        siteUrl: "<?php $this->options->siteUrl(); ?>",
        <?php if ($this->is('index')): ?>
            weatherApiKey: "<?php echo empty($this->options->weatherApiKey) ? '0QfOX3Vn51YCzitbLaRkTTBadtWpgTN8NZLW0C1SEM' : $this->options->weatherApiKey ?>",
            weatherNode: "<?php $this->options->weatherNode(); ?>",
        <?php endif; ?>
    }
</script>

<script src="<?php $this->options->themeUrl('./assets/js/main.min.js'); ?>" type="text/javascript"></script>
<?php if ($this->is('page') || $this->is('post')): ?>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('./assets/css/prismjs.min.css'); ?>">
    <script defer src="<?php $this->options->themeUrl('./assets/js/prismjs.min.js'); ?>"></script>
<?php endif; ?>
<?php $this->options->customFooter(); ?>

</body>

</html>