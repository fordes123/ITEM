$(document).ready(function () {
    // 导航详情页面点击图片
    $('.nav-thumbnail').on('click', function () {
        const largeImage = $('#nav-large-image');
        largeImage.attr('src', this.src);
    });
    // 导航详情页面点击进入小程序
    $('#copyTitleButton').on('click', function (e) {
        e.preventDefault();
        const title = $(this).data('value');
        navigator.clipboard.writeText(title)
            .then(function () {
                const openWxModal = new bootstrap.Modal($('#openWxModal')[0]);
                openWxModal.show();
            })
            .catch(function (err) {
                console.error('复制失败:', err);
            });
    });
    //  点赞按钮点击
    $('#agree-btn').on('click', function () {
        var cid = $(this).attr('data-cid');
        var url = $(this).attr('data-url');
        $(this).prop('disabled', true);

        $.ajax({
            type: 'post',
            url: url,
            data: { agree: cid },
            async: true,
            timeout: 30000,
            cache: false,
            success: function (data) {
                $('#agree-btn').prop('disabled', true);
                var re = /\d/;  //  匹配数字的正则表达式
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
