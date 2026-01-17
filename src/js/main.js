import { Modal, Tooltip, Dropdown } from 'bootstrap';
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
      this.lazy = new LazyLoad();
      this.weatherInitialized = false;
      this.initEventListeners();
    }

    initEventListeners() {
      $(document).ready(() => this.initialize());
      $(window).on('load', () => this.handleWindowLoad());
    }

    initialize() {
      this.setupCoreModules();
      this.initThemeSystem();
      this.setupWeatherSystem();
    }

    handleWindowLoad() {
      this.handleMenuAnchors();
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
      const $overlay = $('.aside-overlay');

      const closeMenu = () => {
        $togglers.removeClass('active');
        $aside.removeClass('in');
        $body.removeClass('modal-open');
      };

      $togglers.on('click', () => {
        $togglers.toggleClass('active');
        $aside.toggleClass('in');
        $body.toggleClass('modal-open');
      });

      $overlay.on('click', closeMenu);

      $(window).on('resize', closeMenu);

      $aside.on({
        mouseenter: () => $wrapper.addClass('sidemenu-hover-active'),
        mouseleave: () => $wrapper.removeClass('sidemenu-hover-active')
      });

      $('#menuCollasped').on('click', () => {
        $wrapper.toggleClass('menu-collasped-active');
        $aside.toggleClass('folded');
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
        if (!keyword) return;

        const baseUrl = $search.find('.search-tab a.active').data('url');
        window.open(baseUrl + keyword);
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
      const isSystemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      const actualTheme = theme === 'default'
        ? (isSystemDark ? 'dark' : 'light')
        : theme;

      document.documentElement.setAttribute('data-bs-theme', actualTheme);
      localStorage.setItem('data-bs-theme', theme);

      const icon = this.themeMap.get(theme)?.find('i').clone();
      if (icon) $('#theme-toggle').html(icon);

      $('.dropdown-item').removeClass('active').filter(`#${theme}`).addClass('active');
    }

    setupScrollBehavior() {
      const $scrollBtn = $('#scrollToTOP');

      $(window).on('scroll', () => {
        $scrollBtn.toggle($(window).scrollTop() > 500);
      });

      $scrollBtn.on('click', () => {
        $('html, body').animate({ scrollTop: 0 }, 300);
      });
    }

    initAjaxComponents() {
      this.setupCategoryLoader();
      this.setupAgreementSystem();
      this.setupDynamicLinks();
    }

    setupCategoryLoader() {
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
          } catch {
            $row.html('<div class="alert alert-danger">加载失败</div>');
          } finally {
            loader.remove();
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
      const template = document.getElementById('tmpl-category-item');
      if (!template) return '';

      const nodes = items.map(item => {
        const clone = template.content.cloneNode(true);
        const $clone = $(clone);

        $clone.find('.media').attr('href', item.permalink);
        $clone.find('.media-content')
          .attr('data-src', item.logo)
          .attr('alt', item.title);

        $clone.find('.list-content')
          .attr('href', item.url)
          .attr('cid', item.cid)
          .attr('title', item.text);

        $clone.find('.list-title').text(item.title);
        $clone.find('.list-desc .h-1x').text(item.text);

        return $clone[0];
      });

      return $(nodes);
    }

    setupAgreementSystem() {
      $('#agree-btn').on('click', function () {
        const $btn = $(this);
        $.ajax({
          type: 'POST',
          url: window.config.siteUrl,
          data: { event: 'agree', cid: $btn.data('cid') },
          success: () => {
            const currentCount = parseInt($btn.find('.num').text(), 10) || 0;
            $btn.prop('disabled', true)
              .addClass('disabled')
              .find('.num').text(currentCount + 1);
          }
        });
      });
    }

    setupDynamicLinks(target = $('body')) {
      const viewsUrl = $('.aside-wrapper > a:first-child').attr('href');

      target.find('div[href]').each((_, el) => {
        const $el = $(el);
        $el.on('click', () => {
          if (viewsUrl) {
            $.post(viewsUrl, { event: 'views', cid: $el.attr('cid') });
          }
          window.open($el.attr('href'), $el.attr('target') || '_self');
        });
      });
    }

    createLoader(height) {
      const template = document.getElementById('tmpl-custom-loader');
      const clone = template.content.cloneNode(true);
      const $clone = $(clone);
      $clone.find('.loader-container').css('height', height + 'px');
      return $clone.contents();
    }

    setupNavigationMenu() {
      $('.menu-item-has-children > a').each((_, el) => {
        const $link = $(el);
        const $icon = $('<span class="menu-sign iconfont icon-chevron-down"></span>');

        $link.append($icon).on('click', e => {
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
        const targetId = $link.data('target');

        if (!$link.data('index')) {
          sessionStorage.setItem('anchor', targetId);
          const homeUrl = $('.aside-wrapper > a:first-child').attr('href');
          if (homeUrl) window.location.href = homeUrl;
          e.preventDefault();
        } else {
          document.getElementById(targetId)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      });
    }

    setupWeatherSystem() {
      const $container = $('#card__weather');
      if (!$container.length) return;

      const observer = new IntersectionObserver((entries) => {
        const entry = entries[0];
        if (entry.isIntersecting && !this.weatherInitialized) {
          this.weatherInitialized = true;
          this.executeWeatherLogic($container);
          observer.disconnect();
        }
      });

      observer.observe($container[0]);
    }

    async executeWeatherLogic($container) {
      const CACHE_KEY = 'weather_cache';
      const CACHE_TIME = 15 * 60 * 1000;

      const getCache = () => {
        try {
          const cache = JSON.parse(sessionStorage.getItem(CACHE_KEY));
          return (cache && Date.now() - cache.time < CACHE_TIME) ? cache.data : null;
        } catch {
          return null;
        }
      };

      const setCache = (data) => {
        sessionStorage.setItem(CACHE_KEY, JSON.stringify({ data, time: Date.now() }));
      };

      try {
        if (!window.config.weatherApiKey) {
          throw new Error("Weather API Key missing");
        }

        let weatherData = getCache();

        if (!weatherData) {
          const host = window.config.weatherNode === '1' ? 'assets.msn.com' : 'assets.msn.cn';
          const apiUrl = `https://${host}/service/segments/recoitems/weather?apikey=${window.config.weatherApiKey}&cuthour=false&market=zh-cn&locale=zh-cn`;

          const res = await fetch(apiUrl);
          const data = await res.json();

          if (Array.isArray(data) && data[0]?.data) {
            weatherData = JSON.parse(data[0].data);
            setCache(weatherData);
          } else {
            throw new Error('Invalid API response');
          }
        }

        this.renderWeather($container, weatherData);
      } catch (error) {
        console.error('Weather System Error:', error);
        this.renderWeatherError($container);
      }
    }

    renderWeatherError($container) {
      const template = document.getElementById('tmpl-weather-error');
      const clone = template.content.cloneNode(true);
      const $clone = $(clone);

      $clone.find('.weather-retry-btn').one('click', () => this.executeWeatherLogic($container));
      $container.empty().append($clone);
    }

    renderWeather($container, data) {
      const weather = data.responses?.[0]?.weather?.[0];
      const cur = weather?.current || {};
      const units = data.units || {};
      const location = data.userProfile?.location || {};
      const iconMap = weather?.iconMap || {};

      let iconUrl = '';
      if (iconMap.iconBase && iconMap.symbolMap && cur.symbol) {
        iconUrl = iconMap.iconBase + (iconMap.symbolMap[cur.symbol] || '');
      }

      const displayName = location.City || '未知地区';
      const aqiBg = cur.aqLevel <= 1 ? 'success' : cur.aqLevel <= 2 ? 'warning' : 'danger';

      const template = document.getElementById('tmpl-weather-content');
      const clone = template.content.cloneNode(true);
      const $clone = $(clone);

      $clone.find('.weather-city').text(displayName);
      $clone.find('.weather-aqi')
        .addClass(`bg-${aqiBg} text-${aqiBg} border-${aqiBg}`)
        .text(cur.aqiSeverity || 'N/A');

      $clone.find('.weather-temp').text(cur.temp);
      $clone.find('.weather-unit-temp').text(units.temperature || '°C');
      $clone.find('.weather-text').text(cur.pvdrCap || cur.cap);
      $clone.find('.weather-feels').text((cur.feels || '-') + (units.temperature || '°C'));

      $clone.find('.weather-icon').attr('src', iconUrl);

      $clone.find('.weather-rh').text(cur.rh + '%');
      $clone.find('.weather-wind-dir').text(cur.pvdrWindDir || '风向');
      $clone.find('.weather-wind-spd').text(cur.pvdrWindSpd || (cur.windSpd + 'km/h'));
      $clone.find('.weather-uv').text(cur.uvDesc || 'N/A');
      $clone.find('.weather-vis').text((cur.vis || 'N/A') + ' ' + (units.distance || 'km'));

      $container.empty().append($clone);
    }
  }

  new App();

})($);
