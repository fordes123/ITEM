import $ from 'jquery';
window.$ = window.jQuery = $;
import { Modal, Tooltip, Dropdown, Offcanvas } from 'bootstrap';
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

    favoritesStore() {
      if (this._favoritesStore) return this._favoritesStore;
      const keys = { cids: 'favorites_cids', posts: 'favorites_posts' };
      const read = (k, d = []) => JSON.parse(localStorage.getItem(k) || JSON.stringify(d));
      const write = (k, v) => localStorage.setItem(k, JSON.stringify(v));

      this._favoritesStore = {
        getAll: () => read(keys.posts),
        has: (cid) => new Set(read(keys.cids).map(String)).has(String(cid)),
        add: (post) => {
          const cid = String(post?.cid || '');
          if (!cid) return;
          const cids = new Set(read(keys.cids).map(String));
          const posts = read(keys.posts).filter(p => String(p.cid) !== cid);
          cids.add(cid);
          posts.unshift(post);
          write(keys.cids, [...cids]);
          write(keys.posts, posts);
          return cids.size;
        },
        remove: (cid) => {
          const id = String(cid || '');
          const cids = new Set(read(keys.cids).map(String));
          const posts = read(keys.posts).filter(p => String(p.cid) !== id);
          cids.delete(id);
          write(keys.cids, [...cids]);
          write(keys.posts, posts);
          return cids.size;
        }
      };
      return this._favoritesStore;
    }
    init() {
      this.setupTheme();
      this.setupMenu();
      this.setupSearch();
      this.setupTimeline();
      this.setupScroll();
      this.setupCategory();
      this.setupFavorites();
      this.setupComponents();
      this.handleAnchor(sessionStorage.getItem('anchor'));
    }

    initEvents() {
      $(window).on('load', () => this.handleAnchor());
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
      const $aside = $('.site-aside');
      const $wrapper = $('.site-wrapper');
      const asideEl = document.getElementById('siteAside');
      const isDesktop = () => window.matchMedia('(min-width: 1200px)').matches;

      if (asideEl) {
        const offcanvas = Offcanvas.getOrCreateInstance(asideEl, { backdrop: true, scroll: false });
        $(window).on('resize', () => {
          if (isDesktop()) offcanvas.hide();
        });
      }

      $aside.hover(
        () => {
          if (isDesktop()) $wrapper.addClass('sidemenu-hover-active');
        },
        () => {
          if (isDesktop()) $wrapper.removeClass('sidemenu-hover-active');
        }
      );

      $('#menuCollasped').on('click', () => {
        if (!isDesktop()) return;
        $wrapper.toggleClass('menu-collasped-active');
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

    setupTimeline() {
      const $timeline = $('#timeline');
      if ($timeline.length === 0) return;

      let current = parseInt($timeline.data('current') || 1, 10);
      let total = parseInt($timeline.data('total') || 1, 10);
      const pageSize = parseInt($timeline.data('page-size') || 10, 10);
      const siteUrl = window.config?.siteUrl || '/';

      const $loading = $('#timeline-loading');
      const $end = $('#timeline-end');
      const $tpl = $('#tmpl-timeline-item');

      let loading = false;
      $loading.addClass('invisible');

      if (total <= 1) {
        $end.removeClass('d-none');
        return;
      }

      const buildItem = (post) => {
        const $node = $($tpl.prop('content')).clone();
        $node.find('.timeline-title a').attr('href', post.permalink || '#').text(post.title || '');
        $node.find('p').text(post.text || '');
        $node.find('.timeline-element-date').text(post.date || '');
        $node.find('img').attr('data-src', post.logo || '');
        return $node;
      };

      const fetchPage = async (page) => {
        const url = `${siteUrl}?action=posts&page=${page}&size=${pageSize}`;
        const res = await $.get(url);
        return res?.data || {};
      };

      const loadNext = async () => {
        if (loading || current >= total) return;
        loading = true;
        $loading.removeClass('invisible');

        const next = current + 1;
        try {
          const data = await fetchPage(next);
          const items = Array.isArray(data.data) ? data.data : [];

          if (items.length) {
            items.forEach(item => $timeline.append(buildItem(item)));
            this.lazy.update();
            current = data.currentPage || next;
            total = data.totalPages || total;
            $timeline.data('current', current).data('total', total);
          } else {
            total = current;
          }
        } catch (e) {
          console.error('load timeline failed', e);
        } finally {
          $loading.addClass('invisible');
          loading = false;
          if (current >= total) {
            observer.disconnect();
            $end.removeClass('d-none');
          }
        }
      };

      const observer = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting) loadNext();
      }, { rootMargin: '0px 0px 200px 0px' });

      observer.observe($loading[0]);
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

        const $parent = $row.parent();
        $parent.css('height', $parent.height());
        const loader = $($('#tmpl-loading').html()).height('100%');
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

          if ($items.length === 0) {
            const $clone = $($('#tmpl-empty').prop('content')).clone();
            $row.html($clone).removeClass('d-none');
          } else {
            $row.html($items).removeClass('d-none');
          }

          this.lazy.update();
          this.bindDynamicLinks($row);
        } catch (e) {
          const $clone = $($('#tmpl-load-failed').prop('content')).clone();
          $row.html($clone).removeClass('d-none');
        } finally {
          loader.remove();
          $parent.css('height', '');
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

      const favorites = this.favoritesStore();
      const getPost = () => JSON.parse(sessionStorage.getItem('post') || null);
      const $favBtn = $('#favorite-btn');

      if ($favBtn.length) {
        const cid = String($favBtn.data('cid'));
        const $icon = $favBtn.find('i');
        if (favorites.has(cid)) {
          $icon.removeClass('fa-regular').addClass('fa-solid');
          $favBtn.append('<b class="num">已收藏</b>');
          const post = getPost();
          if (post?.cid) favorites.add(post);
        }

        $favBtn.on('click', () => {
          const post = getPost();
          if (!post?.cid) return;
          if (favorites.has(cid)) {
            favorites.remove(cid);
            $icon.removeClass('fa-solid').addClass('fa-regular');
            $favBtn.find('.num').remove();
          } else {
            favorites.add(post);
            $icon.removeClass('fa-regular').addClass('fa-solid');
            $favBtn.append('<b class="num">已收藏</b>');
          }
        });
      }

      this.bindDynamicLinks($('body'));
    }
    setupFavorites() {
      if (!$('.site-main[data-id="index"]').length) return;
      const favorites = this.favoritesStore();
      const posts = favorites.getAll();
      if (!posts.length) return;

      const $card = $($('#tmpl-favorite-block').prop('content')).clone();
      const $grid = $card.find('.list-grid');
      const $itemTpl = $('#tmpl-favorite-item');
      const lazyPlaceholder = $('img.lazy').first().attr('src') || '';

      posts.forEach(p => {
        if (!p?.cid) return;
        const $item = $($itemTpl.prop('content')).clone();
        $item.find('.media').attr({ href: p.permalink, title: '' });
        $item.find('.media-content').attr({ src: lazyPlaceholder, 'data-src': p.logo || '' });
        $item.find('.list-content').attr({ href: p.url, target: '_blank', cid: p.cid, title: p.text });
        $item.find('.list-title').text(p.title);
        $item.find('.list-desc .h-1x').text(p.text);
        $grid.append($item);
      });

      if ($grid.children().length === 0) return;
      $('#search').parent().after($card);
      $('button.favorite-remove').on('click', (e) => {
        e.preventDefault();
        const $btn = $(e.currentTarget);
        const cid = $btn.siblings('.list-content').attr('cid');
        if (!cid) return;
        if (favorites.remove(cid) == 0) {
          $('#favorite-block').remove();
        } else {
          $btn.parent().parent().remove();
        }
      });

      this.lazy.update();
      this.bindDynamicLinks($card);
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


      $(document).on('click', '.menu-item a[data-target]', function (e) {
        e.preventDefault();
        const target = $(this).data('target');
        const success = scrollTo(target);
        if (!success) {
          sessionStorage.setItem('anchor', target);
          window.location.href = window.config.siteUrl;
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

        if (html.length === 0) {
          $box.empty().append($($('#tmpl-empty').prop('content')).clone());
        } else {
          $box.empty().append(html);
        }
      } catch (e) {
        const $item = $($('#tmpl-load-failed').prop('content')).clone();
        $box.empty().append($item);
        console.error(e);
      }
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
