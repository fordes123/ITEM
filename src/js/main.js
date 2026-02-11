import ls from 'localstorage-slim';
import $ from "cash-dom";
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

    init() {
      this.setupTheme();
      this.setupMenu();
      this.setupSearch();
      this.setupTimeline();
      this.setupScroll();
      this.setupCategory();
      this.setupFavorites();
      this.setupComponents();
    }

    initEvents() {
      $(window).on('load', () => this.handleAnchor());
      const observe = (selector, callback) =>{
        const el = document.querySelector(selector);
        if (!el) return;
        new IntersectionObserver((entries, observer) => {
          if (entries[0].isIntersecting) {
            callback();
            observer.disconnect();
          }
        }).observe(el);
      }
      observe('#card__weather', () => this.loadWeather());
      observe('#card__popular', () => this.loadPopular());
    }

    setupTheme() {
      const $items = $('.theme-toggle .dropdown-item');
      const media = window.matchMedia('(prefers-color-scheme: dark)');
      let theme = ls.get('data-bs-theme') || 'default';

      const apply = (t) => {
        const nextTheme = ['default', 'dark', 'light'].includes(t) ? t : 'default';
        theme = nextTheme;

        document.documentElement.setAttribute(
          'data-bs-theme',
          nextTheme === 'default' ? (media.matches ? 'dark' : 'light') : nextTheme
        );

        ls.set('data-bs-theme', nextTheme);

        const icon = this.themeMap.get(nextTheme)?.find('i').clone();
        if (icon) $('#theme-toggle').empty().append(icon);

        $items.removeClass('active').filter(`#${nextTheme}`).addClass('active');
      };

      apply(theme);

      $items.off('click.theme').on('click.theme', e => {
        apply(e.currentTarget.id);
        $('.dropdown-menu').removeClass('show');
      });
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

      $aside.on('mouseenter', () => {
        if (isDesktop()) $wrapper.addClass('sidemenu-hover-active');
      });

      $aside.on('mouseleave', () => {
        if (isDesktop()) $wrapper.removeClass('sidemenu-hover-active');
      });

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
        if (key) window.open($search.find('.search-tab a.active').data('url') + encodeURIComponent(key));
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
        return fetch(url, { credentials: 'same-origin' }).then(r => r.json()).then(r => r?.data || {});
      };

      const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
          loadNext();
        }
      }, { rootMargin: '0px 0px 200px 0px' });

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

      if ($loading[0]) observer.observe($loading[0]);
    }

    setupScroll() {
      const $btn = $('#scrollToTOP');
      $(window).on('scroll', () => $btn.toggle($(window).scrollTop() > 500));
      $btn.on('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    setupCategory() {
      $('.container .card .nav-link').on('click', async function (event) {
        event.preventDefault();
        const $this = $(event.currentTarget), $row = $this.closest('.card').find('.card-body .row');
        if ($this.hasClass('active') || $row.hasClass('d-none')) return;

        $this.closest('.card').find('.nav-link').removeClass('active');
        $this.addClass('active');

        const $parent = $row.parent();
        $parent.css('height', $parent.height());
        const loader = $($('#tmpl-loading').html()).height('100%');
        $row.addClass('d-none').before(loader);

        try {
          const data = await fetch(`${window.config.siteUrl}?action=category&mid=${$this.data('mid')}`, { credentials: 'same-origin' })
            .then(res => res.json())
            .then(res => Array.isArray(res?.data) ? res.data : []);
          const $items = $(data.map(item => {
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

        } catch (e) {
          const $clone = $($('#tmpl-load-failed').prop('content')).clone();
          $row.html($clone).removeClass('d-none');
        } finally {
          loader.remove();
          $parent.css('height', '');
        }
      }.bind(this));
    }

    setupFavorites() {
      if (!$('.site-main[data-id="index"]').length) return;
      const fp = ls.get('favorites') || {};
      if (Object.keys(fp).length === 0) return;

      const $card = $($('#tmpl-favorite-block').prop('content')).clone();
      const $grid = $card.find('.list-grid');
      const $itemTpl = $('#tmpl-favorite-item');
      for (const [id, post] of Object.entries(fp)) {
        const $item = $($itemTpl.prop('content')).clone();
        $item.find('a.media').attr({ href: post.permalink, title: '' });
        $item.find('img.lazy').attr({ src: window.config.loading, 'data-src': post.logo || '' });
        $item.find('a.list-content').attr({ href: post.url, target: '_blank', title: post.text });
        $item.find('.list-title').text(post.title);
        $item.find('.list-desc .h-1x').text(post.text);
        $item.find('.drop-favorite').attr('data-id', post.cid)
        $grid.append($item);
      }

      if ($grid.children().length === 0) return;
      $('#search').parent().after($card);
      $('button.drop-favorite').on('click', (e) => {
        e.preventDefault();
        const $btn = $(e.currentTarget);
        const cid = $btn.attr('data-id');
        if (!cid) return;
        const map = ls.get('favorites') || {};
        delete map[String(cid)];
        ls.set('favorites', map);
        if (Object.keys(map).length === 0) {
          $('#favorite-block').remove();
        } else {
          $btn.parent().parent().remove();
        }
      });

      this.lazy.update();
    }

    setupComponents() {
      const likes = new Set(ls.get('likes') || []);
      $('#agree-btn').each((_, btn) => {
        const $btn = $(btn), cid = String($btn.data('cid'));
        if (!likes.has(cid)) {
          $btn.removeClass('disabled').one('click', () => {
            const body = new URLSearchParams({ action: 'likes', cid });
            fetch(window.config.siteUrl, {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
              body: body.toString(),
              credentials: 'same-origin'
            }).then(res => res.json()).then(({ data }) => {
              $btn.addClass('disabled').find('.num').text(data);
              likes.add(cid);
              ls.set('likes', [...likes]);
            }).catch(() => {
              console.error('agree failed');
            });
          });
        }
      });

      const $favBtn = $('#favorite-btn');
      if ($favBtn.length) {
        const cid = String($favBtn.data('cid'));
        const $icon = $favBtn.find('i');
        const current = ls.get('favorites') || {};
        if (current[cid]) {
          $icon.removeClass('fa-regular').addClass('fa-solid');
          $favBtn.append('<b class="num">已收藏</b>');
          const post = JSON.parse(sessionStorage.getItem('post') || 'null');
          if (post?.cid) {
            current[String(post.cid)] = post;
            ls.set('favorites', current);
          }
        }

        $favBtn.on('click', () => {
          const post = JSON.parse(sessionStorage.getItem('post') || 'null');
          if (!post?.cid) return;
          const map = ls.get('favorites') || {};
          if (map[cid]) {
            delete map[cid];
            ls.set('favorites', map);
            $icon.removeClass('fa-solid').addClass('fa-regular');
            $favBtn.find('.num').remove();
          } else {
            map[String(post.cid)] = post;
            ls.set('favorites', map);
            $icon.removeClass('fa-regular').addClass('fa-solid');
            $favBtn.append('<b class="num">已收藏</b>');
          }
        });
      }


    }

    handleAnchor(id) {

      const scrollToAnchor = (anchor) => {
        const el = document.getElementById(anchor);
        if (el) {
          el.scrollIntoView({ behavior: 'smooth', block: 'center' });
          sessionStorage.removeItem('anchor');
          return true;
        }
        return false;
      }

      const anchor = sessionStorage.getItem('anchor');
      if (anchor) scrollToAnchor(anchor);
      $(document)
        .off('click.anchor', '.menu-item a[data-target]')
        .on('click.anchor', '.menu-item a[data-target]', function (e) {
        e.preventDefault();
        const target = $(this).data('target');
        const success = scrollToAnchor(target);
        if (!success) {
          sessionStorage.setItem('anchor', target);
          window.location.href = window.config.siteUrl;
        }
      });

    }

    async loadPopular() {
      const $box = $('#card__popular');
      const url = `${window.config.siteUrl}?action=popular&size=5`;

      try {
        let list = ls.get('popular') || [];
        if (!list || list.length === 0) {
          list = await fetch(url, { credentials: 'same-origin' }).then(r => r.json())
            .then(r => r?.data ?? []);

          if (list.length > 0) ls.set('popular', list, { ttl: 600 });
        }

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
        console.error('loadPopular failed', e);
        const $item = $($('#tmpl-load-failed').prop('content')).clone();
        $box.empty().append($item);
      }


    }

    async loadWeather() {
      const $box = $('#card__weather');
      const { weatherApiKey: key, weatherNode } = window.config;

      try {
        if (!key) throw new Error('Weather API Key is not set');
        let data = ls.get('weather-data');
        if (!data) {
          const host = weatherNode === '1' ? 'assets.msn.com' : 'assets.msn.cn';
          const url = `https://${host}/service/segments/recoitems/weather?apikey=${key}&cuthour=false&market=zh-cn&locale=zh-cn`;
          data = await fetch(url).then(r => r.json())
            .then(r => JSON.parse(r[0]?.data) ?? {});

          ls.set('weather-data', data, { ttl: 600 });
        }

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

        const aqi = Number(cur.aqLevel);
        const bg = Number.isFinite(aqi) ? (aqi <= 1 ? 'success' : aqi <= 2 ? 'warning' : 'danger') : 'secondary';
        $el.find('.weather-aqi').addClass(`bg-${bg} text-${bg} border-${bg}`).text(cur.aqiSeverity || 'N/A');

        const iconMap = data.responses?.[0]?.weather?.[0]?.iconMap;
        if (iconMap && cur.symbol) {
          $el.find('.weather-icon').attr('src', `${iconMap.iconBase}${iconMap.symbolMap[cur.symbol] || ''}`);
        }

        $box.empty().append($el);
      } catch (e) {
        console.error('loadWeather failed', e);
        $box.empty().append($($('#tmpl-weather-error').prop('content')).clone());
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
