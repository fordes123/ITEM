<!-- This README Template See: https://github.com/othneildrew/Best-README-Template -->
<a name="readme-top"></a>

<!-- PROJECT SHIELDS -->
[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]

<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/fordes123/ITEM">
    <img src="./assets/image/apple-touch-icon.png" alt="Logo" width="80" height="80">
  </a>

<h3 align="center">ITEM</h3>

  <p align="center">
    网址导航型的 Typecho 主题
    <br /><br />
    <a href="https://item.ink"><strong>演示站点 »</strong></a>
    <br />
    <br />
    <a href="#1">项目说明</a>
    ·
    <a href="#2">快速开始</a>
    ·
    <a href="#3">问题反馈</a>
  </p>
</div>


<!-- ABOUT THE PROJECT -->

<h2 id='1'>🎉 项目说明</h2>

[![Product Name Screen Shot][product-screenshot]](https://example.com)

在编程语言中，"item" 这个单词常用来代表一个元素、一个选项  
希望这个主题能够承载更多的 "item"，链接每一个选项~

---

<!-- GETTING STARTED -->

<h2 id='2'>🛠️ 快速开始</h2>

<a href="https://vercel.com/new/clone?project-name=ITEM-vercel&repository-name=ITEM-vercel&repository-url=https://github.com/fordes123/ITEM-vercel&from=templates&integration-ids=oac_coKBVWCXNjJnCEth1zzKoF1j"><img src="https://vercel.com/button"></a>
> 通过 Vercel 托管需要添加一个 MySQL 集成，如 [TiDB](https://tidbcloud.com/)、[PlanetScale](https://planetscale.com/)，参考: [Vercel 托管 Typecho](https://www.fordes.top/posts/tutorials/typecho-vercel/)

### 本地部署

这是一个 Typecho 主题，因此你必须要先安装 Typecho 才能使用它，同时还需要满足以下条件:

- php 7.4+
- MySQL 8+

1. 获取主题文件
   克隆仓库源码或下载最新 [Releases](https://github.com/fordes123/ITEM/releases)，
   ```shell
   git clone https://github.com/fordes123/ITEM.git
   ```
2. 将主题文件重名为 <code>ITEM</code> 并移动至 Typecho 根目录<code>usr/themes</code> 文件夹中
3. 在 Typecho 管理面板中选择更换外观并启用主题

---

### 配置说明

#### 文章配置

**文章类型** 为 **网址导航** 时，点击图标前往详情，点击其他位置直接跳转至对应url；**文章类型** 为 **普通文章** 时，
代表站内文章，点击会前往文章页，所以普通文章无需跳转链接

#### 分类配置

分类略缩名表示对应图标名称，可用图标可在 [FontAwesome 5](https://fontawesome.com/v5/search?o=r&m=free) 图标库中浏览；  
(例: FontAwesome 图标类名为 `<i class="fas fa-vihara"></i>` 那么对应略缩名应为 `vihara`)

#### 搜索引擎配置

配置格式为 JSON，其中 icon 为 [FontAwesome 5](https://fontawesome.com/v5/search?o=r&m=free) 图标， 需要使用 **完整类名**。
示例如下：

```json
[
  {
    "name": "谷歌",
    "url": "https://www.google.com/search?q=",
    "icon": "fab fa-google"
  },
  {
    "name": "Github",
    "url": "https://github.com/search?q=",
    "icon": "fab fa-github"
  }
]

```

#### 工具直达配置

配置格式为 JSON，结构类似 搜索引擎配置，增加了 `background` 控制背景色，填写 css 格式的颜色值即可。
示例如下：

```json
[
  {
    "name": "热榜速览",
    "url": "https://www.hsmy.fun",
    "icon": "fas fa-fire",
    "background": "linear-gradient(45deg, #97b3ff, #2f66ff)"
  },
  {
    "name": "地图",
    "url": "https://ditu.amap.com/",
    "icon": "fas fa-fire",
    "background": "red"
  },
  {
    "name": "微信文件助手",
    "url": "https://filehelper.weixin.qq.com",
    "icon": "fab fa-weixin",
    "background": "#1ba784"
  }
]
```  

---

<!-- CONTACT -->
<h2 id='3'>💬 问题反馈</h2>

Issues - [https://github.com/fordes123/ITEM/issues](https://github.com/fordes123/ITEM/issues)

博客 - [https://fordes.top](https://fordes.top)

---

<!-- LICENSE -->
<h2>📃 开源许可</h2>

基于 GNU General Public License v3.0 协议开源.

<!-- MARKDOWN LINKS & IMAGES -->

[contributors-shield]:https://img.shields.io/github/contributors/fordes123/ITEM.svg?style=for-the-badge

[contributors-url]:https://github.com/fordes123/ITEM/graphs/contributors

[forks-shield]:https://img.shields.io/github/forks/fordes123/ITEM.svg?style=for-the-badge

[forks-url]:https://github.com/fordes123/ITEM/network/members

[stars-shield]:https://img.shields.io/github/stars/fordes123/ITEM.svg?style=for-the-badge

[stars-url]:https://github.com/fordes123/ITEM/stargazers

[issues-shield]:https://img.shields.io/github/issues/fordes123/ITEM.svg?style=for-the-badge

[issues-url]:https://github.com/fordes123/ITEM/issues

[license-shield]:https://img.shields.io/github/license/fordes123/ITEM.svg?style=for-the-badge

[license-url]:https://github.com/fordes123/ITEM/blob/master/LICENSE.txt

[product-screenshot]:screenshot.png
