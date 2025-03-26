!function ($) {
    "use strict";
    const func = {
        initialize() {
            this.bindEvents()
        }, bindEvents() {
            const methods = ['scroll2top', 'div_href', 'ajax_agree', 'ajax_load_category', 'toggler', 'sideMenu', 'siteSearch', 'lazyload', 'theme'];
            methods.forEach(method => this[method]())
        }, toggler: function () {
            $(".menu-toggler").each(function () {
                var a = $(this);
                a.on("click", function () {
                    a.toggleClass("active")
                }), $(window).resize(function () {
                    $(".menu-toggler").removeClass("active")
                })
            })
        }, mobileMenu: function () {
            $(".menu-toggler").on("click", function () {
                $(".site-aside").toggleClass("in"), $("body").toggleClass("modal-open")
            }), $(".site-aside .aside-overlay").on("click", function () {
                $(".site-aside").removeClass("in"), $("body").removeClass("modal-open"), $(".menu-toggler").removeClass("active")
            }), $(window).resize(function () {
                $(".site-aside").removeClass("in"), $("body").removeClass("modal-open")
            })
        }, siteSearch: function () {
            var searchBlock = $("#search");
            searchBlock.find(".search-tab a").click(function () {
                var $this = $(this);
                searchBlock.find(".search-tab a").removeClass("active");
                $this.addClass("active")
            });
            searchBlock.find("form").submit(function (e) {
                e.preventDefault();
                window.open(searchBlock.find(".search-tab a.active").data("url") + searchBlock.find("input").val());
                return false
            })
        }, sideMenuNavigation: function (a) {
            a.find(".menu-item-has-children > a").on("click", function (s) {
                var i = $(this);
                i.siblings(".sub-menu")[0] && (s.preventDefault(), i.parent().hasClass("in") ? (i.parent().removeClass("in"), i.parent().find(".in").removeClass("in"), i.parent().find(".sub-menu").stop(!0).slideUp(300)) : (i.closest(".in")[0] || (a.find(".menu-item-has-children.in .sub-menu").stop(!0).slideUp(300), a.find(".menu-item-has-children.in").removeClass("in")), i.parent().addClass("in"), i.parent().siblings(".in").find(".sub-menu").stop(!0).slideUp(300), i.parent().siblings(".in").removeClass("in"), i.siblings(".sub-menu").stop(!0).slideDown(300)))
            })
        }, sideMenu: function () {
            var pageWrapper = $(".site-wrapper");
            var sideMenuArea = $(".site-aside");
            var menuCollasped = $("#menuCollasped");
            sideMenuArea[0] && this.sideMenuNavigation(sideMenuArea), sideMenuArea.find(".menu-item-has-children").children("a").append('<span class="menu-sign iconfont icon-chevron-down"></span>');
            sideMenuArea[0], $(".navbar-site").find(".menu-item-has-children").children("a").append('<span class="menu-sign iconfont icon-chevron-down"></span>');
            sideMenuArea[0] && menuCollasped.on("click", function () {
                pageWrapper.toggleClass("menu-collasped-active");
                sideMenuArea.toggleClass("folded")
            });
            sideMenuArea[0] && sideMenuArea.on("mouseenter", function () {
                pageWrapper.addClass("sidemenu-hover-active");
                pageWrapper.removeClass("sidemenu-hover-deactive")
            });
            sideMenuArea[0] && sideMenuArea.on("mouseleave", function () {
                pageWrapper.removeClass("sidemenu-hover-active");
                pageWrapper.addClass("sidemenu-hover-deactive")
            })
        }, lazyload: function (target) {
            let _target = target || $("body");
            _target.find("img.lazyload").lazyload()
        }, theme: function () {
            const themeButtons = {
                'default': $("#default"),
                'dark': $("#dark"),
                'light': $("#light")
            };
            const setTheme = (theme, $button) => {
                localStorage.setItem("data-bs-theme", theme);
                document.documentElement.setAttribute("data-bs-theme", theme);
                $("#theme-toggle").html($button.find("i").prop('outerHTML'));
                $(".dropdown-item").removeClass('active');
                $button.addClass('active')
            };
            const savedTheme = localStorage.getItem("data-bs-theme") || 'default';
            setTheme(savedTheme, themeButtons[savedTheme]);
            $(".dropdown-item").on('click', function () {
                setTheme($(this).attr('id'), $(this))
            })
        }, ajax_load_category: function () {
            $(".container .card").each(function () {
                let $card = $(this);
                $card.find('.nav-link').on('click', async function () {
                    const $this = $(this);
                    const mid = $this.attr('data-mid');
                    const $body = $card.find('.card-body');
                    const $row = $body.find('.row');
                    if ($row.hasClass('d-none')) {
                        return
                    }
                    $card.find('.nav-link').removeClass('active');
                    $this.addClass('active');
                    let $loading = $(`<div class="d-flex justify-content-center align-items-center"style="height: ${$body.height()}px"><div class="spinner-border"role="status"><span class="visually-hidden">加载中...</span></div></div>`);
                    $row.before($loading);
                    $row.addClass('d-none');
                    await $.ajax({
                        url: '/',
                        data: {
                            event: 'category',
                            mid: mid
                        },
                        type: "post",
                        success: function (r) {
                            let data = r.status !== 'success' ? [] : r.data;
                            const html = data.map(item => `<div class="col-6 col-lg-3"><div class="list-item block"><div role="button" href="${item.permalink}"class="media w-36 rounded-circle"><img src="/usr/themes/ITEM/assets/image/default.gif"data-src="${item.logo}"alt="${item.title}"class="media-content lazyload"/></div><div role="button" href="${item.url}"target="_blank"cid="${item.cid}"title="${item.text}"class="list-content"><div class="list-body"><div class="list-title text-md h-1x">${item.title}</div><div class="list-desc text-xx text-muted mt-1"><div class="h-1x">${item.text}</div></div></div></div></div></div>`).join('');
                            $row.html(html);
                            func.lazyload($row);
                            func.div_href($row);
                            $row.removeClass('d-none');
                            $loading.remove()
                        },
                        error: function (r) {
                            console.error(`ajax load category ${mid}failed:`, r);
                            $row.html(`<div class="alert alert-danger">加载失败</div>`)
                        }
                    })
                })
            })
        }, scroll2top: function () {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 500) {
                    $("#scrollToTOP").fadeIn(200)
                } else $("#scrollToTOP").fadeOut(200)
            });
            $("#scrollToTOP").on("click", function () {
                $("html, body").animate({
                    scrollTop: 0
                }, 300);
                return false
            })
        }, div_href: function (target) {
            let _target = target || $("body");
            _target.find('div[href]').each(function () {
                var cid = $(this).attr('cid');
                $(this).click(function () {
                    $.ajax({
                        url: "/",
                        data: {
                            event: 'views',
                            cid: cid
                        },
                        type: "post"
                    });
                    window.open($(this).attr("href"), $(this).attr("target") ? "_blank" : "_self")
                })
            })
        }, ajax_agree: function () {
            $("#agree-btn").on('click', function () {
                var cid = $(this).attr('data-cid');
                var agree = $("#agree-btn .num").html();
                $.ajax({
                    type: 'post',
                    url: '/',
                    data: {
                        event: 'agree',
                        cid: cid
                    },
                    async: true,
                    timeout: 30000,
                    cache: false,
                    success: function () {
                        $("#agree-btn").prop('disabled', true);
                        $("#agree-btn .num").html(parseInt(agree) + 1);
                        $("#agree-btn").addClass('disabled')
                    },
                    error: function () {
                        $("#agree-btn").prop('disabled', false)
                    },
                })
            })
        }, menuItemAnchor: function () {
            let anchor = sessionStorage.getItem('anchor');
            if (anchor) {
                document.getElementById(anchor).scrollIntoView({behavior: 'smooth', block: 'center'});
                sessionStorage.removeItem('anchor');
            }
            $('.menu-item a').click(function (e) {
                let target = $(this).attr('data-target');
                if (target) {
                    let isIndex = $(this).attr('data-index');
                    if (!isIndex) {
                        sessionStorage.setItem('anchor', target);
                        window.location.href = '/';
                        e.preventDefault();
                    }

                    document.getElementById(target).scrollIntoView({behavior: 'smooth', block: 'center'});
                    e.preventDefault();
                }
            });
        }
    };
    $(document).ready(() => func.initialize());
    $(window).on("load", () => func.mobileMenu(), func.menuItemAnchor());
}(jQuery);