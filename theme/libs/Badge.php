<style>
    @font-face {
        font-family: 'Luckiest Guy';
        src: url(data:application/font-woff2;charset=utf-8;base64,d09GMgABAAAAAAQcAAoAAAAAC2gAAAPSAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAABmAAgmIKgVyBRwsSAAE2AiQDIAQgBY44BzIbjAoRlZx/yH4c5Gaji84fQzf90hrJ+V4OnufX/f/aR3fmSUjvy6hUA5sQBScoUxMAfq8687Iz7H90gbNT+kSvuxCKOZd2ovnf32spdDPzhYKZQ8EIByMezXtpVUup1qv35QAt9vHtuEChLSA7a7S2gAvOqwDeDiItwU/AqIOyBgt2lk0ASfFPiz7JOPmqsA5QmG3wIQYAAGjds30HWpFDP7vt3zYIPZWfrKcxP20NkIMBNKCBDASAYWgEyMDQCojAQ2HIoT2d6U6/ICD19eBtcC44HZwIDiEYmolDXc3/qQEs4AAghPFSD4AYwABlwGiAdBdpF/bKWBPpzX38uN+ofpGDeE94eJSbNXfurFFkeFjYiGF8eObwqXOL65Jvd9TQFuN32OH+sdDwvYn+/LLExnLfX/tzv50e8dxMa3yVoOwOCr4tcu/77E7EdmPsk4P2yulr8bR1d2bu39ySlXcipf0Eib+1MfoM7dtv8veWhptLh8HPkX/YjQ5/BgACCG0+vO+1emR0s29euAfAjcYXBuBJ+e2HuOCNa24PAj4KAIFxL+n6Qq7XkHuyBuYzvUY/oUKNpglW1JpBuGOPWKTWZBgo/m7IkAEIALAPyBKR0nNQhDgFTRYXYUjgFSyRfIUjXgQh6iQBHnHSgXxhEMJJkykUISxDJFWy5RlRlMltNGJ8kBHynYCQovtBEaWnQdNMz4GhRB+FJU2/gKNI/0SIcSYLHgVmCfnCHoTTYG5ShPAfkQyzdUdR9LOrvjzUy6VdvLkfP7DunpAQLXKWeeQrkgd6nTbaYjnKlGUNp73s78Yure6S0dnKz0yZ9ml2LeQj7VJpLvlKIZ4CGjDUWP9kKxubKsVXI9KEGAn+5LKlhfNlBLsEtoWNWAwvj8e6Vb1QGL78zQOdf2V0jKNF9lTsAGvY6uM1jaTGWKCO+LKzuQb1/oDsAKanJ00l+NJqSMF4RKB/HzSQhtfXndErhYPxpE/donzUHJ0tHO3iNXwJcXygU7PKMaHc71rkLKm6SfJAr9NGWyxHmbKs4bSX/d3YJbTTFB1gk5TF+GQKV3OCnnpVnKpjanDJT5wy7NlQadM06wkbcba05V6TuzFqtOY2W/4Gwc1cJ1WjdirqKCB5iQSzcbe/2PZbrbBRAy0Zqjq76ISfbjG8PDYmuqSqfqmBDWbH30DZOvirzcDNaZHDNXwe+DsPphYucrRuoq3gI54QhiVtaiTYMvy8JCj+J4GRgZG5oUbnoJouPukiC/DZ2aSw8mki7EdRs+9Ikb3+vUnyO58BCMJK1lc4XyuiS5qo9Qs=) format('woff2');
    }

    .col-tb-offset-2 {
        margin-left: 0 !important;
    }

    sup {
        font-family: 'Helvetica Neue', Helvetica, Arial, -apple-system, system-ui, sans-serif;
        font-size: 1rem;
        letter-spacing: normal;
        top: -1.75em;
        border-radius: .75rem;
        padding: .25rem .5rem;
        margin-left: .5rem;
        background-color: cornflowerblue;
        color: white;
    }

    hr {
        margin: 2em 0;
    }

    #start-link {
        margin-bottom: 2em;
        border-bottom: 1px solid #ECECEC;
        list-style: none;
    }
</style>
<script>
    function checkUpdate() {
        fetch('https://api.github.com/repos/fordes123/ITEM/releases/latest')
            .then(response => response.json())
            .then(data => {
                const latestVersion = data.tag_name.replace('v', '');
                const currentVersion = document.getElementById('currentVersion').textContent;
                if (latestVersion !== currentVersion) {
                    document.getElementById('versionDesc').innerHTML = '🎉 发现新版本 <em>' + latestVersion + '</em>，赶快去更新吧~';
                } else {
                    document.getElementById('versionDesc').innerHTML = '🎉 当前版本是 <em>' + currentVersion + '</em>，已经是最新版本啦~';
                }
            })
            .catch(error => {
                console.error('检查更新失败:', error);
                document.getElementById('versionDesc').innerHTML = '🥲 检查更新失败，请稍后再试~';
            });
    }
</script>
<div class="col-mb-12 ">
    <div style="display: flex;align-items: center;gap: 10px">
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAMAAABg3Am1AAAAe1BMVEUAAAAyMjIxMTEyMjIyMjIyMjIyMjIyMjIyMjIzMzMyMjI2NjYuLi4yMjIyMjIyMjIyMjIyMjIxMTEyMjIyMjIyMjIyMjIyMjIvLy8yMjIzMzMyMjIyMjIyMjIyMjIyMjI0NDQ1NTUyMjI0NDQzMzMyMjI3NzcqKiozMzMJ+N1mAAAAKHRSTlMA/YCFfI2Kj4jv+AgRzeKucGUj69ebl5EKwralUUlBNCsY8l1UGRcM7NjVKwAAAX1JREFUSMeVk9lywjAUQxUHQgJlCTuUtav//wt7TZmxXA01OQ90OtVJZUfgAaVDJ0pfuE75oqy7GKUvgep5w1ne6Hv3bJ969BJYeRd+PNEf7fCG9/YxzvWxPKY+Ms2dt7bPCQmz/P3gsyChyvU3jpQvCpd5vtEjYdxnQ/sb6Zk38j40jwUJLb1z7f/L3BNzALQr7W98UH6BQKWGi3msSdgBvCveT/xlTEIP4P+h/Y3vNxJOYIP3EzlTfngFGXyfREvCEhE7R7wfZkXCK4jKO+mPf6dq70Ofj68hCRckhF25cKHMifLN9q/Qgxlx8TJVJAxCPhh9EDzVgeYD7v6t0qm+J3l/z3MrnWraH2ocKD95lOdzlCTs0/4RPseOhFr6s3H/c0PCUZ6vrS6UL67SX41Wz4xNzOs51jrVjfTn213yVLWPGp44y3mVKpmq5JUZT1XvX9mTsNL+yrJpGitz46B5ZWQ4v96OApn+sqtMf9lV5vmyEumfMySfMx7lfwDzUkrxqQ+ZbwAAAABJRU5ErkJggg==" alt="ITEM">
        <h1 style="font-family: 'Luckiest Guy', sans-serif;font-size: 48px;letter-spacing: .075em;margin: 0;">ITEM<sup id="currentVersion"><?php echo Utils::THEME_VERSION ?></sup></h1>
    </div>
    <p><span id="versionDesc">🎉 欢迎使用 ITEM 主题 ~</span></p>
    <ul id="start-link">
        <li><a href="https://github.com/fordes123/ITEM">项目主页</a></li>
        <li><a href="https://github.com/fordes123/ITEM/issues">问题反馈</a></li>
        <li><a onclick="checkUpdate()">检查更新</a></li>
    </ul>
</div>
