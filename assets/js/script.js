!function (n) {
    "use strict";
    var a = {
        initialize: function () {
            this.event(), this.toggler(), this.sideMenu(), this.siteSearch(), this.lazyload(), this.theme()
        }, event: function () {
            /** 返回顶部 */
            $(window).scroll(function () {
                if ($(this).scrollTop() > 500) {
                    $("#scrollToTOP").fadeIn(200)
                } else $("#scrollToTOP").fadeOut(200)
            });
            $("#scrollToTOP").on("click", function () {
                $("html, body").animate({ scrollTop: 0 }, 300);
                return false
            });

            /** 跳转 */
            $("div[href]").each(function () {
                var cid = $(this).attr('cid');
                $(this).click(function () {
                    $.ajax({
                        url: "/",
                        data: { views: cid },
                        type: "post"
                    });
                    window.open($(this).attr("href"), $(this).attr("target") ? "_blank" : "_self")
                })
            });

            /** 点赞 */
            $('#agree-btn').on('click', function () {
                var cid = $(this).attr('data-cid');
                var agree = $('#agree-btn .num').html();
                $.ajax({
                    type: 'post',
                    url: '/',
                    data: { agree: cid },
                    async: true,
                    timeout: 30000,
                    cache: false,
                    success: function () {
                        $('#agree-btn').prop('disabled', true);
                        $('#agree-btn .num').html(parseInt(agree) + 1);
                        $('#agree-btn').addClass('disabled');
                    },
                    error: function () {
                        $('#agree-btn').prop('disabled', false);
                    },
                });
            });
        }, toggler: function () {
            n(".menu-toggler").each(function () {
                var a = n(this);
                a.on("click", function () {
                    a.toggleClass("active")
                }), n(window).resize(function () {
                    n(".menu-toggler").removeClass("active")
                })
            })
        }, mobileMenu: function () {
            n(".menu-toggler").on("click", function () {
                n(".site-aside").toggleClass("in"), n("body").toggleClass("modal-open")
            }), n(".site-aside .aside-overlay").on("click", function () {
                n(".site-aside").removeClass("in"), n("body").removeClass("modal-open"), n(".menu-toggler").removeClass("active")
            }), n(window).resize(function () {
                n(".site-aside").removeClass("in"), n("body").removeClass("modal-open")
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
            });
        }, sideMenuNavigation: function (a) {
            a.find(".menu-item-has-children > a").on("click", function (s) {
                var i = n(this);
                i.siblings(".sub-menu")[0] && (s.preventDefault(), i.parent().hasClass("in") ? (i.parent().removeClass("in"), i.parent().find(".in").removeClass("in"), i.parent().find(".sub-menu").stop(!0).slideUp(300)) : (i.closest(".in")[0] || (a.find(".menu-item-has-children.in .sub-menu").stop(!0).slideUp(300), a.find(".menu-item-has-children.in").removeClass("in")), i.parent().addClass("in"), i.parent().siblings(".in").find(".sub-menu").stop(!0).slideUp(300), i.parent().siblings(".in").removeClass("in"), i.siblings(".sub-menu").stop(!0).slideDown(300)))
            })
        }, sideMenu: function () {
            var pageWrapper = $(".site-wrapper");
            var sideMenuArea = $(".site-aside");
            var menuCollasped = $("#menuCollasped");
            n(".site-aside")[0] && this.sideMenuNavigation(n(".site-aside")), $(".site-aside .menu-item-has-children").children("a").append('<span class="menu-sign iconfont icon-chevron-down"></span>');
            n(".navbar-site")[0], $(".navbar-site .menu-item-has-children").children("a").append('<span class="menu-sign iconfont icon-chevron-down"></span>');
            n(".site-aside")[0] && menuCollasped.on("click", function () {
                pageWrapper.toggleClass("menu-collasped-active");
                sideMenuArea.toggleClass("folded")
            });
            n(".site-aside")[0] && sideMenuArea.on("mouseenter", function () {
                pageWrapper.addClass("sidemenu-hover-active");
                pageWrapper.removeClass("sidemenu-hover-deactive")
            });
            n(".site-aside")[0] && sideMenuArea.on("mouseleave", function () {
                pageWrapper.removeClass("sidemenu-hover-active");
                pageWrapper.addClass("sidemenu-hover-deactive")
            })
        }, lazyload: function () {
            $("img.lazyload").lazyload();
        }, theme: function () {
            let themeButtons = {
                'default': $('#default'),
                'dark': $('#dark'),
                'light': $('#light')
            };
            let savedTheme = localStorage.getItem("data-bs-theme") || 'default';
            let target = themeButtons[savedTheme];
            $('.dropdown-item').removeClass('active');
            target.addClass('active');
            $('#theme-toggle').html(target.find("i").prop('outerHTML'));

            $('.dropdown-item').on('click', function () {
                let $this = $(this);
                let theme = $this.attr('id');

                localStorage.setItem("data-bs-theme", theme);
                document.documentElement.setAttribute("data-bs-theme", theme);
                $('#theme-toggle').html($this.find("i").prop('outerHTML'));
                $('.dropdown-item').removeClass('active');
                $this.addClass('active');
            });
        }
    };
    n(document).ready(function () {
        a.initialize()
    }), n(window).on("load", function () {
        a.mobileMenu();
    })
}(jQuery);