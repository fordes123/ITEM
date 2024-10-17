!function (n) {
    "use strict";
    var a = {
        initialize: function () {
            this.event(), this.toggler(), this.sideMenu(), this.siteSearch()
        }, event: function () {
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
            n(".search-toggler").on("click", function () {
                n(".search-form-wrapper").toggleClass("in"), n("body").toggleClass("modal-open")
            }), n(".search-form-wrapper .search-close").on("click", function () {
                n(".search-form-wrapper").removeClass("in"), n("body").removeClass("modal-open")
            })
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
        }
    };
    n(document).ready(function () {
        a.initialize()
    }), n(window).on("load", function () {
        a.mobileMenu()
    })
}(jQuery);
jQuery(document).ready(function ($) {
    $("img.lazyload").lazyload();
    $("div[href]").each(function () {
        $(this).click(function () {
            if ($(this).attr("cid")) $.ajax({
                url: "/",
                data: {cid: $(this).attr("cid")},
                type: "post",
                success: function () {
                    console.log("jump success")
                }
            });
            window.open($(this).attr("href"), $(this).attr("target") ? "_blank" : "_self")
        })
    });
    $(window).scroll(function () {
        if ($(this).scrollTop() > 500) {
            $("#scrollToTOP").fadeIn(200)
        } else $("#scrollToTOP").fadeOut(200)
    });
    $("#scrollToTOP").on("click", function () {
        $("html, body").animate({scrollTop: 0}, 300);
        return false
    });
    $(".start-search-input").on("focus", function () {
        $(".start-poster").addClass("is-focus")
    });
    $(".start-search-input").on("blur", function () {
        $(".start-poster").removeClass("is-focus")
    });
    $(document).on("click", ".btn-share-toggler", function (event) {
        event.preventDefault();
        ncPopup("mini", $("#single-share-template").html())
    });
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
    });/*导航详情页面点击图片*/
    $('.nav-thumbnail').on('click', function () {
        const largeImage = $('#nav-large-image');
        largeImage.attr('src', this.src);
    });/*导航详情页面点击进入小程序*/
    $('#copyTitleButton').on('click', function (e) {
        e.preventDefault();
        const title = $(this).data('value');
        navigator.clipboard.writeText(title).then(function () {
            const openWxModal = new bootstrap.Modal($('#openWxModal')[0]);
            openWxModal.show();
        }).catch(function (err) {
            console.error('复制失败:', err);
        });
    });/*点赞按钮点击*/
    $('#agree-btn').on('click', function () {
        var cid = $(this).attr('data-cid');
        var url = $(this).attr('data-url');
        $(this).prop('disabled', true);
        $.ajax({
            type: 'post',
            url: url,
            data: {agree: cid},
            async: true,
            timeout: 30000,
            cache: false,
            success: function (data) {
                $('#agree-btn').prop('disabled', true);
                var re = /\d/; /*匹配数字的正则表达式*/
                if (re.test(data)) {
                    $('#agree-btn .num').html(data);
                    $('#agree-btn').addClass('disabled');
                }
            },
            error: function () {
                $('#agree-btn').prop('disabled', false);
            },
        });
    });
});