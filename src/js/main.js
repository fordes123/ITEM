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
        url: $('.aside-wrapper > a:first-child').attr('href'),
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
          url: $('.aside-wrapper > a:first-child').attr('href'),
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
      const CACHE_TIME = 10 * 60 * 1000;

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
          <p class="text-muted mb-3 fw-light">无法获取天气信息，请稍后重试</p>
          <button type="button" class="btn btn-outline-primary btn-sm px-4" id="weather-retry">
            <i class="fas fa-sync-alt me-2"></i>重试
          </button>
        </div>
      </div>
    `);
        $('#weather-retry').one('click', fetchWeather);
      };

      const renderWeather = (weather) => {
        const cur = weather.current || {};
        const units = weather.units || {};
        const loc = weather.location || {};

        const iconHtml = cur.urlIcon
          ? `<img src="${cur.urlIcon}" alt="weather" class="me-3 align-self-start object-fit-contain" style="width:64px;height:64px;">`
          : `<div class="me-3 align-self-start position-relative d-inline-block">
           <i class="fas fa-cloud-sun text-muted opacity-25 display-3"></i>
           <i class="fas fa-exclamation-circle text-warning position-absolute top-50 start-100 translate-middle fs-5"></i>
         </div>`;

        $container.html(`
      <div class="px-2">
        <div class="text-muted text-center">
          <i class="fas fa-map-marker-alt me-1"></i>${loc.displayName || '未知地区'}
        </div>
        <div class="d-flex align-items-center justify-content-center my-3">
          ${iconHtml}
          <div class="d-flex flex-column align-items-start">
            <div class="fw-bold text-primary display-3 lh-1">${cur.temp || 'N/A'}<span class="fs-5 ms-1">${units.temperature || '°C'}</span></div>
            <div class="text-muted ms-2">${cur.pvdrCap || cur.cap || '无法获取'}</div>
          </div>
        </div>
        <div class="d-flex justify-content-center flex-wrap gap-2 mb-2">
          <span class="badge text-bg-primary">紫外线${cur.uvDesc || 'N/A'}</span>
          <span class="badge text-bg-primary">${cur.aqiSeverity || '空气质量未知'}</span>
        </div>
        <div class="text-muted">
          <div class="d-flex justify-content-center flex-wrap gap-2 text-center mb-1">
            <span><i class="fas fa-thermometer-half me-1"></i>体感 ${cur.feels || 'N/A'}${units.temperature || '°C'}</span>
            <span><i class="fas fa-tint me-1"></i>湿度 ${cur.rh || 'N/A'}%</span>
            <span><i class="fas fa-wind me-1"></i>风 ${cur.windDir || 'N/A'}°, ${cur.windSpd || 'N/A'} ${units.speed || 'km/h'}</span>
          </div>
        </div>
      </div>
    `);
      };

      const fetchWeather = async () => {
        const cached = getCache();
        if (cached) {
          renderWeather(cached);
          return;
        }

        try {
          const res = await fetch('https://api.fordes.dev/api/weather/current?locale=zh-cn');
          const data = await res.json();
          setCache(data);
          renderWeather(data);
        } catch {
          renderError();
        }
      };

      fetchWeather().then();
    }
  }

  $(document).ready(() => new App());

})($);
