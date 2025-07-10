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
          if ($row.hasClass('d-none')) return;

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
        url: '/',
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
          url: '/',
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
          $.post('/', { event: 'views', cid: $el.attr('cid') });
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
          window.location.href = '/';
          e.preventDefault();
        }
        document.getElementById($link.data('target'))?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      });
    }
  }

  $(document).ready(() => new App());

})($);
