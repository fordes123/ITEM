import { Modal, Tooltip, Dropdown, } from 'bootstrap';
import LazyLoad from "vanilla-lazyload";

(function ($) {
  'use strict';

  class App {
    constructor() {
      this.themeMap = new Map([
        ['default', $('#default')],
        ['dark', $('#dark')],
        ['light', $('#light')]
      ]);
      this.initEventListeners();
      this.lazy = new LazyLoad();
    }

    initEventListeners() {
      $(document).ready(() => this.initialize());
      $(window).on('load', () => {
        this.handleMenuOverlay();
        this.handleMenuAnchors();
      });
    }

    initialize() {
      this.setupCoreModules();
      this.initThemeSystem();
      this.setupWeatherCard();
    }

    setupCoreModules() {
      this.setupMenuSystem();
      this.setupSearchSystem();
      this.setupScrollBehavior();
      this.initAjaxComponents();
      this.setupNavigationMenu();
    }

    setupMenuSystem() {
      const $togglers = $('.menu-toggler');
      const $aside = $('.site-aside');
      const $wrapper = $('.site-wrapper');
      const $body = $('body');

      $togglers.on('click', () => {
        $togglers.toggleClass('active');
        $aside.toggleClass('in');
        $body.toggleClass('modal-open');
      });

      $(window).on('resize', () => {
        $togglers.removeClass('active');
        $aside.removeClass('in');
        $body.removeClass('modal-open');
      });

      $aside.on({
        mouseenter: () => $wrapper.addClass('sidemenu-hover-active'),
        mouseleave: () => $wrapper.removeClass('sidemenu-hover-active')
      });

      $('#menuCollasped').on('click', () => {
        $wrapper.toggleClass('menu-collasped-active');
        $aside.toggleClass('folded');
      });
    }

    handleMenuOverlay() {
      $('.site-aside .aside-overlay').on('click', () => {
        $('.menu-toggler, .site-aside, body').removeClass('active in modal-open');
      });
    }

    setupSearchSystem() {
      const $search = $('#search');
      $search.find('.search-tab a').on('click', ({ currentTarget }) => {
        $(currentTarget).addClass('active').siblings().removeClass('active');
      });

      $search.find('form').on('submit', e => {
        e.preventDefault();
        const keyword = $search.find('input').val().trim();
        if (!keyword) {
          return;
        }
        window.open($search.find('.search-tab a.active').data('url') + keyword);
      });
    }

    initThemeSystem() {
      const savedTheme = localStorage.getItem('data-bs-theme') || 'default';
      this.applyTheme(savedTheme);

      $('.dropdown-item').on('click', ({ currentTarget }) => {
        const theme = $(currentTarget).attr('id');
        this.applyTheme(theme);
        $('.dropdown-menu').removeClass('show');
      });
    }

    applyTheme(theme) {
      const actualTheme = theme === 'default'
        ? window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
        : theme;

      document.documentElement.setAttribute('data-bs-theme', actualTheme);
      localStorage.setItem('data-bs-theme', theme);
      $('#theme-toggle').html(this.themeMap.get(theme).find('i').clone());
      $('.dropdown-item').removeClass('active').filter(`#${theme}`).addClass('active');
    }

    setupScrollBehavior() {
      const $scrollBtn = $('#scrollToTOP');
      $(window).on('scroll', () => $scrollBtn.toggle($(window).scrollTop() > 500));
      $scrollBtn.on('click', () => $('html, body').animate({ scrollTop: 0 }, 300));
    }

    initAjaxComponents() {
      this.setupCategoryLoader();
      this.setupAgreementSystem();
      this.setupDynamicLinks();
    }

    async setupCategoryLoader() {
      $('.container .card').each((_, card) => {
        const $card = $(card);
        $card.find('.nav-link').on('click', async ({ currentTarget }) => {
          const $link = $(currentTarget);
          const $row = $card.find('.card-body .row');
          if ($link.hasClass('active') || $row.hasClass('d-none')) return;

          $card.find('.nav-link').removeClass('active');
          $link.addClass('active');

          const loader = this.createLoader($card.find('.card-body').height());
          $row.before(loader).addClass('d-none');

          try {
            const data = await this.fetchCategoryData($link.data('mid'));
            $row.html(this.generateCategoryItems(data)).removeClass('d-none');
            this.lazy.update();
            this.setupDynamicLinks($row);
            loader.remove();
          } catch {
            $row.html('<div class="alert alert-danger">加载失败</div>');
          }
        });
      });
    }

    async fetchCategoryData(mid) {
      const response = await $.ajax({
        url: window.config.siteUrl,
        method: 'POST',
        data: { event: 'category', mid }
      });
      return response.status === 'success' ? response.data : [];
    }

    generateCategoryItems(items) {
      return items.map(item => `<div class="col-6 col-lg-3"><div class="list-item block"><div class="media w-36 rounded"role="button"href="${item.permalink}"><img src="/usr/themes/ITEM/assets/image/default.gif"data-src="${item.logo}"alt="${item.title}"class="media-content lazy"></div><div class="list-content"role="button"href="${item.url}"target="_blank"cid="${item.cid}"title="${item.text}"><div class="list-body"><div class="list-title text-md h-1x">${item.title}</div><div class="list-desc text-xx text-muted mt-1"><div class="h-1x">${item.text}</div></div></div></div></div></div>`).join('');
    }

    setupAgreementSystem() {
      $('#agree-btn').on('click', function () {
        const $btn = $(this);
        $.ajax({
          type: 'POST',
          url: window.config.siteUrl,
          data: { event: 'agree', cid: $btn.data('cid') },
          success: () => {
            $btn.prop('disabled', true)
              .addClass('disabled')
              .find('.num').text(+$btn.find('.num').text() + 1);
          }
        });
      });
    }

    setupDynamicLinks(target = $('body')) {
      target.find('div[href]').each((_, el) => {
        const $el = $(el);
        $el.on('click', () => {
          $.post($('.aside-wrapper > a:first-child').attr('href'), { event: 'views', cid: $el.attr('cid') });
          window.open($el.attr('href'), $el.attr('target') || '_self');
        });
      });
    }

    createLoader(height) {
      return $(`<div class="d-flex justify-content-center align-items-center"style="height: ${height}px"><div class="spinner-border"role="status"><span class="visually-hidden">加载中...</span></div></div>`);
    }

    setupNavigationMenu() {
      $('.menu-item-has-children > a').each((_, el) => {
        const $link = $(el);
        $link.append('<span class="menu-sign iconfont icon-chevron-down"></span>')
          .on('click', e => {
            const $parent = $link.parent();
            const $submenu = $link.siblings('.sub-menu');

            if (!$submenu.length) return;

            e.preventDefault();
            $parent.toggleClass('in');
            $submenu.stop(true).slideToggle(300);

            $parent.siblings('.in').removeClass('in')
              .find('.sub-menu').stop(true).slideUp(300);
          });
      });
    }

    handleMenuAnchors() {
      const anchor = sessionStorage.getItem('anchor');
      if (anchor) {
        document.getElementById(anchor)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        sessionStorage.removeItem('anchor');
      }

      $('.menu-item a[data-target]').on('click', e => {
        const $link = $(e.currentTarget);
        if (!$link.data('index')) {
          sessionStorage.setItem('anchor', $link.data('target'));
          window.location.href = $('.aside-wrapper > a:first-child').attr('href');
          e.preventDefault();
        }
        document.getElementById($link.data('target'))?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      });
    }

    setupWeatherCard() {
      const $container = $('#card__weather');
      if (!$container.length || !$container.is(':visible')) return;

      const CACHE_KEY = 'weather_cache';
      const CACHE_TIME = 15 * 60 * 1000;

      const getCache = () => {
        const cache = JSON.parse(sessionStorage.getItem(CACHE_KEY) || 'null');
        return (cache && Date.now() - cache.time < CACHE_TIME) ? cache.data : null;
      };

      const setCache = (data) => {
        sessionStorage.setItem(CACHE_KEY, JSON.stringify({ data, time: Date.now() }));
      };

      const renderError = () => {
        $container.html(`
          <div class="py-4 text-center">
            <div class="d-inline-block">
              <div class="mb-3 position-relative d-inline-block">
                <i class="fas fa-cloud-sun text-muted opacity-25 fs-1"></i>
                <i class="fas fa-exclamation-circle text-warning position-absolute top-50 start-100 translate-middle fs-5"></i>
              </div>
              <p class="text-muted mb-3 fw-light">无法获取天气信息,请稍后重试</p>
              <button type="button" class="btn btn-outline-primary btn-sm px-4" id="weather-retry">
                <i class="fas fa-sync-alt me-2"></i>重试
              </button>
            </div>
          </div>
        `);
        $('#weather-retry').one('click', fetchWeather);
      };

      const renderWeather = (data) => {
        const weather = data.responses?.[0]?.weather?.[0];
        const cur = weather?.current || {};
        const units = data.units || {};
        const location = data.userProfile?.location || {};
        const iconMap = weather?.iconMap || {};

        let iconUrl = '';
        if (iconMap.iconBase && iconMap.symbolMap && cur.symbol) {
          const symbolFile = iconMap.symbolMap[cur.symbol];
          if (symbolFile) iconUrl = iconMap.iconBase + symbolFile;
        }

        const displayName = location.City || '未知地区';
        const aqiBg = cur.aqLevel <= 1 ? 'success' : cur.aqLevel <= 2 ? 'warning' : 'danger';

        $container.html(`
            <div class="px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-truncate me-2">
                        <i class="fas fa-map-marker-alt text-primary me-1"></i>
                        <span class="fw-bold text-dark-emphasis">${displayName}</span>
                    </div>
                    <span class="badge rounded-pill bg-${aqiBg} bg-opacity-10 text-${aqiBg} border border-${aqiBg} border-opacity-25 px-2">
                        ${cur.aqiSeverity}
                    </span>
                </div>
    
                <div class="row align-items-center g-0 mb-3">
                    <div class="col-7">
                        <div class="d-flex align-items-baseline">
                            <span class="display-3 fw-bold text-dark-emphasis">${cur.temp}</span>
                            <span class="fs-4 text-secondary ms-1">${units.temperature || '°C'}</span>
                        </div>
                        <div class="px-2">
                            <span class="badge bg-primary text-white mb-1">${cur.pvdrCap || cur.cap}</span>
                            <div class="text-muted small">
                                <i class="fas fa-thermometer-half me-1"></i>体感 ${cur.feels}${units.temperature || '°C'}
                            </div>
                        </div>
                    </div>
                    <div class="col-5 text-end">
                        <img src="${iconUrl}" alt="weather" class="img-fluid">
                    </div>
                </div>
    
                <div class="row g-2">
                    <div class="col-3">
                        <div class="rounded-3 p-2 text-center h-100" style="background-color: var(--bg-body);">
                            <i class="fas fa-tint text-info mb-1"></i>
                            <div class="small text-muted">湿度</div>
                            <div class="fw-bold small text-truncate">${cur.rh}%</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="rounded-3 p-2 text-center h-100" style="background-color: var(--bg-body);">
                            <i class="fas fa-wind text-secondary mb-1"></i>
                            <div class="small text-muted">${cur.pvdrWindDir || '风向'}</div>
                            <div class="fw-bold small text-truncate px-1">${cur.pvdrWindSpd || (cur.windSpd + 'km/h')}</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="rounded-3 p-2 text-center h-100" style="background-color: var(--bg-body);">
                            <i class="fas fa-sun text-warning mb-1"></i>
                            <div class="small text-muted">紫外线</div>
                            <div class="fw-bold small text-truncate">${cur.uvDesc || 'N/A'}</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="rounded-3 p-2 text-center h-100" style="background-color: var(--bg-body);">
                            <i class="fas fa-eye text-primary mb-1"></i>
                            <div class="small text-muted">能见度</div>
                            <div class="fw-bold small text-truncate">${cur.vis || 'N/A'} ${units.distance || 'km'}</div>
                        </div>
                    </div>
                </div>
            </div>
        `);
      };

      const fetchWeather = async () => {
        try {

          if (!window.config.weatherApiKey) {
            throw new Error("Weather API Key is not set, please set it in the theme settings");
          }
          let weatherData = getCache();
          if (!weatherData) {
            const host = window.config.weatherRegion === '0' ? 'assets.msn.cn' : 'assets.msn.com';
            const res = await fetch(`https://${host}/service/segments/recoitems/weather?apikey=${window.config.weatherApiKey}&cuthour=false&market=zh-cn&locale=zh-cn`);
            const data = await res.json();
            if (Array.isArray(data) && data[0]?.data) {
              weatherData = JSON.parse(data[0].data);
            } else {
              throw new Error('Unexpected API response format');
            }
            setCache(weatherData);
          }
          renderWeather(weatherData);
        } catch (error) {
          console.error('Weather fetch failed:', error);
          renderError();
        }
      };

      fetchWeather().then();
    }
  }

  $(document).ready(() => new App());

})($);
