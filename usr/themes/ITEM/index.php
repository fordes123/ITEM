<?php

/**
 * 在编程语言中，"item" 这个单词常用来代表一个元素
 * 希望这个主题能够承载更多的 "item"，链接每一个选项<br/>
 * 
 * <a href="https://github.com/fordes123/ITEM" target="_blank">Github</a> | <a href="https://item-typecho.vercel.app" target="_blank">Live Demo</a>
 * 
 * @package ITEM
 * @author fordes123
 * @version 1.3.0
 * @link https://fordes.dev
 */
if (!defined('__TYPECHO_ROOT_DIR__'))
  exit;

$this->need('header.php');
$this->need('sidebar.php');
$this->need('navbar.php'); ?>
<main class="site-main" data-id="index">
  <div class="container">
    <div class="row g-3 g-xl-4 d-flex">
      <div class="d-none d-sm-block col-12 col-md-7 col-xxxl-6 d-flex">
        <div class="card card-xl flex-fill h-100">
          <div class="card-header d-flex flex-nowrap text-nowrap gap-2 align-items-center">
            <div class="h4"> <i class="fa-solid fa-sm fa-flag"></i>&nbsp;热门站点</div>
          </div>
          <div class="card-body row g-2 d-flex align-items-center">
            <div id="card__popular" class="col list-number list-row list-bordered d-none d-sm-block">
              <div class="list-item">
                <div class="list-content w-100">
                  <div class="list-body placeholder-glow">
                    <div class="list-title h-1x">
                      <span class="placeholder col-9 placeholder-sm me-2"></span>
                      <span class="placeholder col-2 placeholder-sm"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-content w-100">
                  <div class="list-body placeholder-glow">
                    <div class="list-title h-1x">
                      <span class="placeholder col-5 placeholder-sm me-2"></span>
                      <span class="placeholder col-6 placeholder-sm"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-content w-100">
                  <div class="list-body placeholder-glow">
                    <div class="list-title h-1x">
                      <span class="placeholder col-10 placeholder-sm me-2"></span>
                      <span class="placeholder col-1 placeholder-sm"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-content w-100">
                  <div class="list-body placeholder-glow">
                    <div class="list-title h-1x">
                      <span class="placeholder col-7 placeholder-sm me-2"></span>
                      <span class="placeholder col-4 placeholder-sm"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-content w-100">
                  <div class="list-body placeholder-glow">
                    <div class="list-title h-1x">
                      <span class="placeholder col-6 placeholder-sm me-2"></span>
                      <span class="placeholder col-5 placeholder-sm"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id='card__weather' class="col d-none d-sm-block">
              <?php ThemeView::loading(); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-5 col-xxxl-6 d-flex">
        <div class="card card-xl flex-fill">
          <div class="card-header d-flex flex-nowrap text-nowrap gap-2 align-items-center">
            <div class="h4"><i class="fa-solid fa-sm fa-paperclip"></i>&nbsp;工具直达</div>
          </div>
          <div class="card-body index-sudoku row list text-center gx-2 gy-4 overflow-y-scroll scrollable">
            <?php $tool = json_decode($this->options->toolConfig, true);
            if (is_array($tool) && count($tool) > 0):
              foreach ($tool as $item): ?>
                <div class='col-3 col-sm-2 col-md-4 col-lg-3 col-xxxl-2 mb-3'>
                  <div class='list-item'>
                    <div style='background: <?php echo $item['background'] ?>'
                      class='btn btn-link btn-icon btn-md btn-rounded mx-auto mb-2'>
                      <span><i class='<?php echo $item['icon'] ?>'></i></span>
                    </div>
                    <div class='text-sm text-muted text-truncate'>
                      <?php echo $item['name'] ?>
                    </div>
                    <a href='<?php echo $item['url'] ?>' target='_blank' class='list-goto'></a>
                  </div>
                </div>
            <?php endforeach;
            else: ThemeView::empty();
            endif; ?>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div id="search" class="search-block card card-xl">
          <div class="card-body">
            <div class="search-tab">
              <?php $search = json_decode($this->options->searchConfig, true);
              $search = is_array($search) && count($search) > 0 ? $search : ThemeConfig::DEFAULT_SEARCH;
              foreach ($search as $index => $item): ?>
                <a href='javascript:;' data-url='<?php echo $item['url']; ?>' class='btn btn-link btn-sm btn-rounded
                <?php echo $index === 0 ? 'active' : ''; ?>'><i class='<?php echo $item['icon']; ?>'
                    aria-hidden='true'></i>&nbsp;
                  <?php echo $item['name']; ?>
                </a>
              <?php endforeach; ?>
            </div>
            <form> <input type="text" class="form-control" placeholder="请输入搜索关键词并按回车键…"></form>
          </div>
        </div>
      </div>
      <?php
      global $data;
      $collapse = $this->options->subCategoryType == 1;
      foreach ($data as $item) {
        if ($collapse) {
          ThemeView::navblock($item, true);
        } elseif (empty($item['children'])) {
          ThemeView::navblock($item);
        } else {
          foreach ($item['children'] as $child) {
            ThemeView::navblock($child);
          }
        }
      }
      ?>
    </div>
  </div>
</main>
<?php $this->need('footer.php'); ?>
