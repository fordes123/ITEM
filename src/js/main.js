import { Modal, Tooltip, Dropdown } from 'bootstrap';
import LazyLoad from "vanilla-lazyload";

(function ($) {
  'use strict';

  class App {
    constructor() {
      this.lazy = new LazyLoad();
      this.themeMap = new Map([
        ['default', $('#default')],
        ['dark', $('#dark')],
        ['light', $('#light')]
      ]);

      $(() => {
        this.init();
        this.initEvents();
      });
    }

    async fetchWithCache(key, url, expireTime = 15 * 60 * 1000, transform = data => data) {
      try {
        const cache = JSON.parse(localStorage.getItem(key));
        if (cache?.time && Date.now() - cache.time < expireTime) {
          return cache.data;
        }
      } catch { }

      const rawData = await $.get(url);
      const data = transform(rawData);

      if (!data) throw new Error('Invalid data');

      localStorage.setItem(key, JSON.stringify({ data, time: Date.now() }));
      return data;
    }

    init() {
      this.setupTheme();
      this.setupMenu();
      this.setupSearch();
      this.setupScroll();
      this.setupCategory();
      this.setupComponents();
      this.handleAnchor(sessionStorage.getItem('anchor'));
    }

    initEvents() {
      $(window).on('load', () => this.handleAnchor());
      $('.menu-item-has-children > a').append('<span class="menu-sign iconfont icon-chevron-down"></span>')
        .on('click', e => {
          e.preventDefault();
          const $parent = $(e.currentTarget).parent();
          $parent.toggleClass('in').siblings().removeClass('in').find('.sub-menu').slideUp(300);
          $parent.find('.sub-menu').slideToggle(300);
        });

      this.observe('#card__weather', () => this.loadWeather());
      this.observe('#card__popular', () => this.loadPopular());
    }

    observe(selector, callback) {
      const el = document.querySelector(selector);
      if (!el) return;
      new IntersectionObserver((entries, observer) => {
        if (entries[0].isIntersecting) {
          callback();
          observer.disconnect();
        }
      }).observe(el);
    }

    setupMenu() {
      const $togglers = $('.menu-toggler'), $aside = $('.site-aside'), $body = $('body');
      const toggle = (force) => {
        $togglers.toggleClass('active', force);
        $aside.toggleClass('in', force);
        $body.toggleClass('modal-open', force);
      };

      $togglers.on('click', () => toggle());
      $('.aside-overlay, .menu-item a').on('click', () => toggle(false));
      $(window).on('resize', () => toggle(false));

      $aside.hover(
        () => $('.site-wrapper').addClass('sidemenu-hover-active'),
        () => $('.site-wrapper').removeClass('sidemenu-hover-active')
      );

      $('#menuCollasped').on('click', () => {
        $('.site-wrapper').toggleClass('menu-collasped-active');
        $aside.toggleClass('folded');
      });
    }

    setupSearch() {
      const $search = $('#search');
      $search.find('.search-tab a').on('click', function () {
        $(this).addClass('active').siblings().removeClass('active');
      });

      $search.find('form').on('submit', e => {
        e.preventDefault();
        const key = $search.find('input').val().trim();
        if (key) window.open($search.find('.search-tab a.active').data('url') + key);
      });
    }

    setupTheme() {
      const apply = (theme) => {
        const isDark = theme === 'default' ? window.matchMedia('(prefers-color-scheme: dark)').matches : theme === 'dark';
        document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
        localStorage.setItem('data-bs-theme', theme);

        const icon = this.themeMap.get(theme)?.find('i').clone();
        if (icon) $('#theme-toggle').html(icon);
        $('.dropdown-item').removeClass('active').filter(`#${theme}`).addClass('active');
      };

      apply(localStorage.getItem('data-bs-theme') || 'default');
      $('.dropdown-item').on('click', function () {
        apply(this.id);
        $('.dropdown-menu').removeClass('show');
      });
    }

    setupScroll() {
      const $btn = $('#scrollToTOP');
      $(window).on('scroll', () => $btn.toggle($(window).scrollTop() > 500));
      $btn.on('click', () => $('html, body').animate({ scrollTop: 0 }, 300));
    }

    setupCategory() {
      $('.container .card .nav-link').on('click', async function (event) {
        const $this = $(event.currentTarget), $row = $this.closest('.card').find('.card-body .row');
        if ($this.hasClass('active') || $row.hasClass('d-none')) return;

        $('.container .card .nav-link').removeClass('active');
        $this.addClass('active');
        const loader = $($('#tmpl-loading').html()).height($row.parent().height());
        $row.addClass('d-none').before(loader);

        try {
          const res = await $.get(`${window.config.siteUrl}?action=category&mid=${$this.data('mid')}`);
          const $items = $(res.data.map(item => {
            const $clone = $($('#tmpl-category').prop('content')).clone();
            $clone.find('.media').attr('href', item.permalink);
            $clone.find('.media-content').attr('data-src', item.logo).attr('alt', item.title);
            $clone.find('.list-content').attr('href', item.url).attr('cid', item.cid).attr('title', item.text);
            $clone.find('.list-title').text(item.title);
            $clone.find('.list-desc .h-1x').text(item.text);
            return $clone[0];
          }));

          $row.html($items).removeClass('d-none');
          this.lazy.update();
          this.bindDynamicLinks($row);
        } catch (e) {
          console.error('loadCategory failed', e);
          $row.html('<div class="alert alert-danger">Loading Failed</div>');
        } finally {
          loader.remove();
          event.preventDefault();
        }
      }.bind(this));
    }

    setupComponents() {
      let likes;
      try { likes = new Set(JSON.parse(localStorage.getItem('likes') || '[]')); } catch (e) { likes = new Set(); }

      $('#agree-btn').each((_, btn) => {
        const $btn = $(btn), cid = $btn.data('cid');
        if (!likes.has(cid)) {
          $btn.removeClass('disabled').one('click', () => {
            $.post(window.config.siteUrl, { action: 'likes', cid }, ({ data }) => {
              $btn.addClass('disabled').find('.num').text(data);
              likes.add(cid);
              localStorage.setItem('likes', JSON.stringify([...likes]));
            });
          });
        }
      });
      this.bindDynamicLinks($('body'));
    }

    bindDynamicLinks($container) {
      const viewsUrl = $('.aside-wrapper > a:first-child').attr('href');
      $container.find('div[href]').on('click', function () {
        if (viewsUrl) $.post(viewsUrl, { event: 'views', cid: $(this).attr('cid') });
        window.open($(this).attr('href'), $(this).attr('target') || '_self');
      });
    }

    handleAnchor(id) {
      const scrollTo = (tid) => {
        const el = document.getElementById(tid);
        if (el) {
          el.scrollIntoView({ behavior: 'smooth', block: 'center' });
          sessionStorage.removeItem('anchor');
          return true;
        }
        return false;
      };

      if (id) scrollTo(id);

      $('.menu-item a[data-target]').off('click').on('click', function (e) {
        const target = $(this).data('target');
        if (!scrollTo(target)) {
          sessionStorage.setItem('anchor', target);
          const home = $('.aside-wrapper > a:first-child').attr('href');
          if (home) window.location.href = home;
          e.preventDefault();
        }
      });
    }

    async loadPopular() {
      const $box = $('#card__popular');
      try {
        const url = `${window.config.siteUrl}?action=popular&size=5`;
        const list = await this.fetchWithCache('popular_list', url, 15 * 60 * 1000, data => data?.data);

        const html = list.map(e => {
          const $item = $($('#tmpl-popular-item').prop('content')).clone();
          $item.find('.list-title').text(`${e.title}${e.text ? ' - ' + e.text : ''}`);
          $item.find('.list-goto').attr('cid', e.cid).attr('title', e.text).attr('href', e.permalink);
          return $item[0];
        });
        $box.empty().append(html);
      } catch (e) { console.error(e); }
    }

    async loadWeather() {
      const $box = $('#card__weather');
      const { weatherApiKey: key, weatherNode } = window.config;

      try {
        if (!key) throw new Error('Weather API Key is not set');

        const host = weatherNode === '1' ? 'assets.msn.com' : 'assets.msn.cn';
        const url = `https://${host}/service/segments/recoitems/weather?apikey=${key}&cuthour=false&market=zh-cn&locale=zh-cn`;

        const data = await this.fetchWithCache('weather', url, 15 * 60 * 1000, raw => {
          if (Array.isArray(raw) && raw[0]?.data) return JSON.parse(raw[0].data);
          return null;
        });

        const cur = data.responses?.[0]?.weather?.[0]?.current || {};
        const unit = data.units?.temperature || '°C';
        const $el = $($('#tmpl-weather-content').prop('content')).clone();

        const map = {
          '.weather-city': data.userProfile?.location?.City || '未知',
          '.weather-temp': cur.temp,
          '.weather-unit-temp': unit,
          '.weather-text': cur.pvdrCap || cur.cap,
          '.weather-feels': `${cur.feels || '-'}${unit}`,
          '.weather-rh': `${cur.rh}%`,
          '.weather-wind-dir': cur.pvdrWindDir || '风向',
          '.weather-wind-spd': cur.pvdrWindSpd || `${cur.windSpd}km/h`,
          '.weather-uv': cur.uvDesc || 'N/A',
          '.weather-vis': `${cur.vis || 'N/A'} ${data.units?.distance || 'km'}`
        };

        Object.entries(map).forEach(([sel, val]) => $el.find(sel).text(val));

        const aqi = cur.aqLevel;
        const bg = aqi <= 1 ? 'success' : aqi <= 2 ? 'warning' : 'danger';
        $el.find('.weather-aqi').addClass(`bg-${bg} text-${bg} border-${bg}`).text(cur.aqiSeverity || 'N/A');

        const iconMap = data.responses?.[0]?.weather?.[0]?.iconMap;
        if (iconMap && cur.symbol) {
          $el.find('.weather-icon').attr('src', `${iconMap.iconBase}${iconMap.symbolMap[cur.symbol] || ''}`);
        }

        $box.empty().append($el);
      } catch (e) {
        console.error('loadWeather failed', e);
        $box.html($($('#tmpl-weather-error').prop('content')).clone());
        if (!key) {
          $box.find('.weather-retry-btn').prop('disabled', true);
        } else {
          $box.find('.weather-retry-btn').one('click', () => this.loadWeather());
        }
      }
    }
  }

  new App();

})($);
