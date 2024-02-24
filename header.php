<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE html>
<html lang="zh">
<head>
  <meta charset="<?php $this->options->charset(); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
  <meta name="author" content="fordes123" />
  <meta name="referrer" content="origin">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title><?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
          ), '', ' - '); ?><?php $this->options->title(); ?><?php if ($this->is('index')) : ?> - <?php $this->options->description(); ?><?php endif; ?></title>
	<link rel="icon" href="<?php empty($this->options->favicon)? $this->options->themeUrl('/assets/image/favicon.ico') : $this->options->favicon(); ?>" type="image/x-icon" />
  <link rel="shortcut icon" href="<?php empty($this->options->favicon)? $this->options->themeUrl('/assets/image/favicon.ico') : $this->options->favicon(); ?>" type="image/x-icon" >
  <link rel="apple-touch-icon" href="<?php $this->options->themeUrl('/assets/image/apple-touch-icon.png')?>" sizes="180x180" />
  <link rel="stylesheet" href="//lf9-cdn-tos.bytecdntp.com/cdn/expire-1-M/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php $this->options->themeUrl('./assets/css/style.min.css'); ?>">
  <link rel="stylesheet" href="//lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/font-awesome/5.15.4/css/all.min.css">
  <script src="//lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/jquery/3.6.0/jquery.min.js" type="application/javascript"></script>
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
