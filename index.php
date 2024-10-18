<?php

/**
 * 在编程语言中，"item" 这个单词常用来代表一个元素
 * 希望这个主题能够承载更多的 "item"，链接每一个选项<br/>
 * 
 * <a href="https://github.com/fordes123/ITEM" target="_blank">Github</a> | <a href="https://item.ink" target="_blank">Live Demo</a>
 * 
 * @package ITEM
 * @author fordes123
 * @version 1.0.5
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
            <div class="list-number list-row list-bordered"><?php ranked(6) ?></div>
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
              <?php $tool = json_decode($this->options->toolConfig, true);
              if (is_array($tool)) :
                foreach ($tool as $item) : ?>
                  <div class='col-4 col-md-3 col-md-2 col-lg-2'>
                    <div class='list-item'>
                      <div style='background: <?php echo $item['background'] ?>' class='btn btn-link btn-icon btn-md btn-rounded mx-auto mb-2'>
                        <span><i class='<?php echo $item['icon'] ?>'></i></span>
                      </div>
                      <div class='text-sm text-muted'><?php echo $item['name'] ?></div>
                      <a href='<?php echo $item['url'] ?>' target='_blank' class='list-goto'></a>
                    </div>
                  </div>
              <?php endforeach;
              endif; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div id="search" class="search-block card card-xl">
          <div class="card-body">
            <div class="search-tab">
              <?php $search = json_decode($this->options->searchConfig, true);
              if (is_array($search) && count($search) > 0) :
                foreach ($search as $index => $item) : ?>
                  <a href='javascript:;' data-url='<?php echo $item['url']; ?>' class='btn btn-link btn-sm btn-rounded <?php echo $index === 0 ? 'active' : ''; ?>'><i class='<?php echo $item['icon']; ?>' aria-hidden='true'></i>&nbsp;<?php echo $item['name']; ?></a>
                <?php endforeach;
              else : ?>
                <a href='javascript:;' data-url='https://www.google.com/search?q=' class='btn btn-link btn-sm btn-rounded active'><i class='fab fa-google'></i>&nbsp;谷歌</a>
              <?php endif; ?>
            </div>
            <form> <input type="text" class="form-control" placeholder="请输入搜索关键词并按回车键…"></form>
          </div>
        </div>
      </div>
      <?php global $category;
      foreach ($category as $item) :
        $this->widget("Widget_Archive@category-" . $item['mid'], "pageSize=10000&type=category", "mid=" . $item['mid'])->to($posts); ?>
        <div class="col-12">
          <div class="card" id="<?php echo $item['slug']; ?>">
            <div class="card-header">
              <div class="d-flex align-items-center">
                <i class="fas fa-sm fa-<?php echo $item['slug']; ?>"></i>
                <div class="h4"> <?php echo $item['name']; ?></div>
              </div>
            </div>
            <div class="card-body">
              <div class="row g-2 g-md-3 list-grid list-grid-padding">
                <?php while ($posts->next()) :
                  if (!is_null($posts->fields->navigation)) : ?>
                    <div class="col-6 col-lg-3">
                      <div class="list-item block">
                        <div href="<?php $posts->permalink() ?>" title="点击进入详情" class="media w-36 rounded-circle">
                          <img src="<?php $this->options->themeUrl('/assets/image/default.gif'); ?>"
                          data-src="<?php echo getSiteFavicon($posts); ?>"
                          class="media-content lazyload" />
                        </div>
                        <div href="<?php
                                    if ($posts->fields->navigation == '1') {
                                        echo $posts->fields->url();
                                    } else {
                                        echo $posts->permalink();
                                    }
                                    ?>" target="_blank" cid="<?php $posts->cid(); ?>" title="<?php $posts->fields->text(); ?>" class="list-content">
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
      <?php endforeach; ?>
    </div>
  </div>
</main>
<?php $this->need('footer.php'); ?>