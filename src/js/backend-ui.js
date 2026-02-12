(function () {
  "use strict";
  document.getElementById('checkUpdate').addEventListener('click', () => {
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
        document.getElementById('versionDesc').innerHTML = '🥲 检查更新失败, 请稍后再试~';
      });
  })
})();
