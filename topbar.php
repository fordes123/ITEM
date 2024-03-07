<header class="site-header bg-white">
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a href="<?php $this->options->siteUrl(); ?>" class="navbar-brand d-xl-none" rel="home"><img src="<?php empty($this->options->biglogo) ? $this->options->themeUrl('/assets/image/head.png') : $this->options->biglogo(); ?>" class="logo nc-no-lazy" alt="<?php $this->options->title(); ?>"></a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav navbar-site me-auto ms-5 ms-xl-0">
          <?php
          $this->widget('Widget_Contents_Page_List')->to($pages);
          while ($pages->next()) : ?>
            <li class="menu-item menu-item-type-taxonomy menu-item-object-category">
              <a href="<?php echo $pages->fields->navigation ? $pages->fields->url : $pages->permalink; ?>" title="<?php echo $pages->fields->navigation ? $pages->fields->text : $pages->title; ?>"><?php $pages->title(); ?></a>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
      <div class="d-flex align-items-center flex-shrink-0 ms-auto me-lg-4">
        <a href="https://github.com/fordes123/ITEM" class="btn btn-link btn-icon btn-rounded ms-3 ms-md-4 "><span><i class="fab fa-github"></i></span></a>
        <a href="javascript:;" class="btn btn-link btn-icon btn-rounded ms-3 ms-md-4 " id="menuCollasped"><span><i class="fas fa-th-large"></i></span></a>
      </div>
    </div>
  </nav>
</header>