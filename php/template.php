<?php if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;
if ($this->is('index')): ?>

    <template id="tmpl-category">
        <div class="col-6 col-lg-3">
            <div class="list-item block shadow-none">
                <div class="media w-36 rounded" role="button">
                    <img src="<?php $this->options->themeUrl(ThemeConfig::DEFAULT_LOADING_ICON); ?>"
                        class="media-content lazy">
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
                    <i class="fa-solid fa-cloud-sun text-muted opacity-25 fs-1"></i>
                    <i
                        class="fa-solid fa-exclamation-circle text-warning position-absolute top-50 start-100 translate-middle fs-5"></i>
                </div>
                <p class="text-muted mb-3 fw-light">无法获取天气信息,请稍后重试</p>
                <button type="button" class="btn btn-outline-primary btn-sm px-4 weather-retry-btn">
                    <i class="fa-solid fa-sync-alt me-2"></i>重试
                </button>
            </div>
        </div>
    </template>

    <template id="tmpl-weather-content">
        <div class="px-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-truncate me-2">
                    <i class="fa-solid fa-map-marker-alt text-primary me-1"></i>
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
                            <i class="fa-solid fa-thermometer-half me-1"></i>体感 <span class="weather-feels"></span>
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
                        <i class="fa-solid fa-tint text-info mb-1"></i>
                        <div class="small text-muted">湿度</div>
                        <div class="fw-bold small text-truncate weather-rh"></div>
                    </div>
                </div>
                <div class="col-4 col-md-6 col-xl-4 col-xxl-3">
                    <div class="rounded-3 p-2 text-center h-100" style="background-color: var(--bg-body);">
                        <i class="fa-solid fa-wind text-secondary mb-1"></i>
                        <div class="small text-muted weather-wind-dir">风向</div>
                        <div class="fw-bold small text-truncate px-1 weather-wind-spd"></div>
                    </div>
                </div>
                <div class="col-4 d-md-none d-xl-block d-lg-none d-xxl-block col-lg-4 col-xxl-3">
                    <div class="rounded-3 p-2 text-center h-100" style="background-color: var(--bg-body);">
                        <i class="fa-solid fa-sun text-warning mb-1"></i>
                        <div class="small text-muted">紫外线</div>
                        <div class="fw-bold small text-truncate weather-uv"></div>
                    </div>
                </div>
                <div class="d-none d-md-none d-xxl-block col-3">
                    <div class="rounded-3 p-2 text-center h-100" style="background-color: var(--bg-body);">
                        <i class="fa-solid fa-eye text-primary mb-1"></i>
                        <div class="small text-muted">能见度</div>
                        <div class="fw-bold small text-truncate weather-vis"></div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="tmpl-popular-item">
        <div class="list-item">
            <div class="list-content">
                <div class="list-body">
                    <div class="list-title h-1x"></div>
                </div>
            </div>
            <a href="#" target="_self" cid="" title="" class="list-goto nav-item"></a>
        </div>
    </template>

    <template id="tmpl-favorite-block">
        <div class="col-12" id="favorite-block">
            <div class="card card-xl">
                <div class="card-header d-flex flex-nowrap text-nowrap gap-2 align-items-center">
                    <div class="h4"><i class="fa-solid fa-sm fa-star"></i>&nbsp;我的收藏</div>
                </div>
                <div class="card-body">
                    <div class="row g-2 g-md-3 list-grid list-grid-padding"></div>
                </div>
            </div>
        </div>
    </template>

    <template id="tmpl-favorite-item">
        <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xxl-2">
            <div class="list-item block shadow-none ">
                <button type="button"
                    class="drop-favorite btn btn-link btn-sm shadow-none bg-transparent position-absolute top-50 end-0 translate-middle-y me-2"
                    aria-label="移除收藏" title="移除收藏">
                    <i class="fa-solid fa-trash"></i>
                </button>
                <a role="button" class="media w-36 rounded">
                    <img class="media-content lazy" />
                </a>
                <a role="button" class="list-content">
                    <div class="list-body">
                        <div class="list-title text-md h-1x"></div>
                        <div class="list-desc text-xx text-muted mt-1">
                            <div class="h-1x"></div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </template>

    <template id="tmpl-loading">
        <?php ThemeView::loading(); ?>
    </template>

    <template id="tmpl-empty">
        <?php ThemeView::empty(); ?>
    </template>

    <template id="tmpl-load-failed">
        <?php ThemeView::failed(); ?>
    </template>
<?php elseif ($this->template == 'timeline.php'): ?>
    <template id="tmpl-timeline-item">
        <div class="timeline-element">
            <div>
                <span class="timeline-element-icon">
                    <i class="badge badge-dot">
                        <img src="<?php $this->options->themeUrl(ThemeConfig::DEFAULT_LOADING_ICON); ?>"
                            class="media-content lazy" />
                    </i>
                </span>
                <div class="timeline-element-content">
                    <h4 class="timeline-title">
                        <a href="#"></a>
                    </h4>
                    <p></p>
                    <span class="timeline-element-date"></span>
                </div>
            </div>
        </div>
    </template>
<?php endif; ?>
