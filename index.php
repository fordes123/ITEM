<?php

/**
 * 在编程语言中，"item" 这个单词常用来代表一个元素
 * 希望这个主题能够承载更多的 "item"，链接每一个选项<br/>
 * 
 * <a href="https://github.com/fordes123/ITEM" target="_blank">Github</a> | <a href="https://item.ink" target="_blank">Live Demo</a>
 * 
 * @package ITEM
 * @author fordes123
 * @version 2.0
 * @link https://fordes.top
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$this->need('header.php');
$this->need('sidebar.php');
$this->need('topbar.php'); ?>
<main class="site-main">
  <div class="container">
    <div class="row g-3 g-xl-4">
      <div class="col-12 col-lg-4 d-lg-flex hot-rank">
        <div class="card card-xl flex-fill">
          <div class="card-header">
            <div class="d-flex align-items-center"><i class="fas fa-sm fa-flag"></i>
              <div class="h4">热门站点</div>
            </div>
          </div>
          <div class="card-body">
            <div class="list-number list-row list-bordered">
              <?php theMostViewed(6) ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-8 d-lg-flex tool-direct">
        <div class="card card-xl flex-fill">
          <div class="card-header">
            <div class="d-flex align-items-center"> <i class="fas fa-sm fa-paperclip"></i>
              <div class="h4">工具直达</div>
            </div>
          </div>
          <div class="card-body">
            <div class="index-sudoku row list text-center g-2 g-md-3 g-lg-4">
              <?php
              $toolConfig = json_decode($this->options->toolConfig, true);
              if (is_array($toolConfig)) {
                foreach ($toolConfig as $item) {
                  echo <<<HTML
            <div class='col-4 col-md-2 col-lg-2'>
                <div class='list-item'>
                    <div style='background: {$item['background']}' class='btn btn-link btn-icon btn-md btn-rounded mx-auto mb-2'>
                        <span><i class='{$item['icon']}'></i></span>
                    </div>
                    <div class='text-sm text-muted'>{$item['name']}</div>
                    <a href='{$item['url']}' target='_blank' class='list-goto'></a>
                </div>
            </div>
        HTML;
                }
              } ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div id="search" class="search-block card card-xl">
          <div class="card-body">
            <div class="search-tab">
              <?php $searchConfig = json_decode($this->options->searchConfig, true);
              if (is_array($searchConfig) && count($searchConfig) > 0) {
                foreach ($searchConfig as $index => $item) {
                  $active = $index === 0 ? 'active' : '';
                  echo "<a href='javascript:;' data-url='{$item['url']}' class='btn btn-link btn-sm btn-rounded {$active}'><i class='{$item['icon']}' aria-hidden='true'></i> {$item['name']}</a>";
                }
              } else {
                echo "<a href='javascript:;' data-url='https://www.google.com/search?q=' class='btn btn-link btn-sm btn-rounded active'><i class='fab fa-google'></i> 谷歌</a>";
              }
              ?>
            </div>
            <form> <input type="text" class="form-control" placeholder="请输入搜索关键词并按回车键…"></form>
          </div>
        </div>
      </div>
      <?php global $categories;
      while ($categories->next()) :
        if (count($categories->children) === 0) :
          $this->widget('Widget_Archive@' . $categories->mid, 'pageSize=10000&type=category', 'mid=' . $categories->mid)->to($posts); ?>
          <div class="col-12">
            <div class="card" id="<?php $categories->slug(); ?>">
              <div class="card-header">
                <div class="d-flex align-items-center"> <i class="fas fa-sm fa-<?php $categories->slug(); ?>"></i>
                  <div class="h4"> <?php $categories->name(); ?></div>
                </div>
              </div>
              <div class="card-body">
                <div class="row g-2 g-md-3 list-grid list-grid-padding">
                  <?php while ($posts->next()) :
                    if (!is_null($posts->fields->navigation)) : ?>
                      <div class="col-6 col-lg-3">
                        <div class="list-item block">
                          <div href="<?php $posts->permalink() ?>" title="点击进入详情" class="media w-36 rounded-circle">
                            <img src="<?php $this->options->themeUrl('/assets/image/default.gif'); ?>" data-src="<?php $posts->fields->logo(); ?>" class="media-content lazyload" />
                          </div>
                          <div href="<?php $posts->fields->navigation ? $posts->fields->url() : $posts->permalink() ?>" target='_blank' cid="<?php $posts->cid(); ?>" title="<?php $posts->fields->text(); ?>" class="list-content">
                            <div class="list-body">
                              <div class="list-title text-md h-1x">
                                <?php $posts->title(); ?>
                              </div>
                              <div class="list-desc text-xx text-muted mt-1">
                                <div class="h-1x"><?php $posts->fields->text(); ?></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  <?php endif;
                  endwhile; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endwhile; ?>
    </div>
  </div>
</main>

<?php $this->need('footer.php'); ?>