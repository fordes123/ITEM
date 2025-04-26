<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE html>
<html lang="zh" data-bs-theme=night>

<head>
  <script>
    let state = localStorage.getItem("data-bs-theme") || "default";
    switch (state) {
      case "dark":
        document.documentElement.setAttribute("data-bs-theme", "dark");
        break;
      case "light":
        document.documentElement.setAttribute("data-bs-theme", "light");
        break;
      default:
        localStorage.setItem("data-bs-theme", "default");
        state = window.matchMedia('(prefers-color-scheme: dark)').matches ? "dark" : "light";
        document.documentElement.setAttribute("data-bs-theme", state);
        break;
    }

    window.matchMedia("(prefers-color-scheme: dark)").addEventListener('change', event => {
      if (localStorage.getItem("data-bs-theme") === "default") {
        document.documentElement.setAttribute("data-bs-theme", event.matches ? "dark" : "light");
      }
    });
  </script>

  <meta charset="<?php $this->options->charset(); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
  <meta name="author" content="fordes123" />
  <meta name="referrer" content="strict-origin-when-cross-origin">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title><?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
          ), '', ' - '); ?><?php $this->options->title(); ?><?php if ($this->is('index')) : ?> - <?php $this->options->description(); ?><?php endif; ?></title>

  <?php if (empty($this->options->favicon)): ?>
    <link rel="icon" type="image/png" href="<?php $this->options->themeUrl('/assets/image/favicon-32x32.png'); ?>" sizes="32x32">
    <link rel="icon" type="image/png" href="<?php $this->options->themeUrl('/assets/image/favicon-16x16.png'); ?>" sizes="16x16">
    <link rel="shortcut icon" href="<?php $this->options->themeUrl('/assets/image/favicon.ico'); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php $this->options->themeUrl('/assets/image/apple-touch-icon.png') ?>">
  <?php else: ?>
    <link rel="icon" href="<?php $this->options->favicon(); ?>" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php $this->options->favicon(); ?>" type="image/x-icon">
  <?php endif; ?>
  <link rel="stylesheet" href="<?php $this->options->themeUrl('./assets/css/main.min.css'); ?>">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  <!-- 通过自有函数输出HTML头部信息 -->
  <?php $this->header(); ?>
</head>

<body class="home blog">
  <div class="site-wrapper">