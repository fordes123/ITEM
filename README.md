<!-- This README Template See: https://github.com/othneildrew/Best-README-Template -->
<div align="center">
  <p>
    <a href="https://github.com/fordes123/ITEM">
      <img src="https://github.com/user-attachments/assets/56136bb1-fc1d-4dee-a3db-6fb8f5c64aa6" alt="Logo" width="80" height="80"></a>
    <h3 align="center">ITEM</h3></p>
  <p>网址导航仪表盘型的 Typecho 主题</p>
  <p>
    <img src="https://img.shields.io/github/v/release/fordes123/ITEM?style=flat-square" alt="last releases" />
    <img src="https://img.shields.io/github/actions/workflow/status/fordes123/ITEM/build.yaml?style=flat-square" alt="build status" />
    <img src="https://img.shields.io/github/license/fordes123/ITEM?style=flat-square" alt="license" />
    <img src="https://img.shields.io/github/contributors/fordes123/ITEM.svg?style=flat-square" alt="contributors" />
    <img src="https://img.shields.io/github/forks/fordes123/ITEM?style=flat-square" alt="forks" />
    <img src="https://img.shields.io/github/stars/fordes123/ITEM?style=flat-square" alt="stars" />
    <img src="https://img.shields.io/github/issues/fordes123/ITEM?style=flat-square" alt="open issues" /></p>
  <h4>
    <a href="#a">项目说明</a>
    <span>·</span>
    <a href="#b">快速开始</a>
    <span>·</span>
    <a href="#c">配置说明</a>
    <span>·</span>
    <a href="#d">交流反馈</a></h4>
</div>

<h2 id='a'>🎉 项目说明</h2>

![screenshot][screenshot]

> ✨ Hugo 版现已推出：[hugo-theme-item][hugo-theme-item]

在编程语言中，"item" 这个单词常用来代表一个元素、一个选项  
所以我们以此来命名这个网址导航主题，希望它能够承载更多的 "item"，链接每一个选项~

---

<h2 id='b'>🛠️ 快速开始</h2>

### 本地部署

1. 下载 [正式版][last-releases] 或者从工作流中获取即时构建的 [开发版][build]
2. 将解压后的主题文件重名为 <code>ITEM</code> 并移动至 Typecho 根目录<code>usr/themes</code> 文件夹中
3. 在 Typecho 管理面板中选择更换外观并启用主题

> [!WARNING]
> 必须使用 MySQL，不支持 SQLite 以及 PostgreSQL  
> 推荐 `Typecho 1.2+`、 `PHP 7.4+`、 `MySQL 8+` 低于这些版本不保证兼容性

### Vercel 部署

<a href="https://vercel.com/new/clone?project-name=ITEM&repository-name=ITEM&repository-url=https://github.com/fordes123/ITEM/tree/vercel&from=templates&integration-ids=oac_coKBVWCXNjJnCEth1zzKoF1j"><img src="https://vercel.com/button"></a>

点击上方 `Deploy` 按钮 或者 Fork 本仓库 [vercel][item-vercel] 分支并手动导入 Vercel。

> [!TIP]
> 通过 Vercel 托管需要添加一个 MySQL 集成，如 [TiDB][tidb]、[PlanetScale][planetscale]，参考: [Vercel 托管 Typecho][typecho-vercel-post]

### 本地开发

1. 安装 Docker 以及 Docker Compose 后，在项目根目录下执行以下命令：
   ```shell
   cd .docker
   docker compose up -d
   ```
2. 在项目根目录执行以下命令：
   ```shell
   yarn
   yarn watch
   ```

完成以上步骤后，浏览器打开 `http://localhost:80` 即可查看前台页面（账号: `dev`，密码: `12345678`）  
此时对源码的任何修改都将实时生效

---

<h2 id='c'>📄️ 配置说明</h2>

### 文章

在本主题中，我们将文章分为以下 3 类

- **网址导航**（默认）：点击图标前往文章详情页，点击其他位置直接跳转至对应 url
- **站内文章**：顾名思义，与网址导航对应，点击会直接前往文章详情页
- **微信小程序**：作为网址导航的分支，点击会直接前往文章详情页

### 分类

分类略缩名表示对应图标名称，可用图标可在 [FontAwesome 5][fontawesome-free] 图标库中浏览；  
(例: FontAwesome 图标类名为 `<i class="fas fa-vihara"></i>` 那么对应略缩名应为 `vihara`)

### 搜索引擎

配置格式为 JSON，其中 icon 为 [FontAwesome 5][fontawesome-free] 图标， 需要使用 **完整类名**。
示例如下：

（站内搜索 url 请指向站点 `/search/` 路径）

```json
[
    {
        "name": "站内",
        "url": "/search/",
        "icon": "fas fa-search-location"
    },
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

### 工具直达

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

### 时间线

请在后台 `管理` > `独立页面` > `新增`，将其模板设置为 `目录/时间线`，文章类型设置为 站内文章

---

<h2 id='d'>💬 交流反馈</h2>

请在 [issues][issues] 和 [discussions][discussions] 发表和交换意见，同时也欢迎贡献代码帮助我们完善项目

---

<h2 id='4'>📃 开源许可</h2>

基于 [GNU General Public License v3.0][license-url] 协议开源.

<!-- MARKDOWN LINKS & IMAGES -->
[screenshot]: https://github.com/user-attachments/assets/aa9dd5d5-1a19-478f-b147-d346d19d1df4
[hugo-theme-item]: https://github.com/fordes123/hugo-theme-item/
[last-releases]: https://github.com/fordes123/ITEM/releases/latest/download/ITEM.zip
[build]: https://github.com/fordes123/ITEM/actions/workflows/build.yaml
[item-vercel]: https://github.com/fordes123/ITEM/tree/vercel
[tidb]: https://tidbcloud.com/
[planetscale]: https://planetscale.com/
[typecho-vercel-post]: https://www.fordes.dev/posts/tutorials/typecho-vercel/
[fontawesome-free]: https://fontawesome.com/v5/search?o=r&m=free
[issues]: https://github.com/fordes123/ITEM/issues
[discussions]: https://github.com/fordes123/ITEM/discussions
[issues-url]: https://github.com/fordes123/ITEM/issues
[license-url]: https://github.com/fordes123/ITEM/blob/master/LICENSE.txt
