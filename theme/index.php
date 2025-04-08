<?php

/**
 * 在编程语言中，"item" 这个单词常用来代表一个元素
 * 希望这个主题能够承载更多的 "item"，链接每一个选项<br/>
 * 
 * <a href="https://github.com/fordes123/ITEM" target="_blank">Github</a> | <a href="https://item-typecho.vercel.app" target="_blank">Live Demo</a>
 * 
 * @package ITEM
 * @author fordes123
 * @version 1.1.4
 * @link https://fordes.dev
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
            <div class="list-number list-row list-bordered"><?php Utils::ranked(6) ?></div>
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
      <?php global $tree;
      foreach ($tree as $p):
        if ($this->options->subCategoryType == 0 || empty($p['children'])):
          !empty($p['children'])
            ? array_map(fn($c) => Utils::indexCard($c, $posts), $p['children'])
            : Utils::indexCard($p, $posts);
        else: ?>
          <?php Utils::indexCard($p, $posts, true); ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</main>
<?php $this->need('footer.php'); ?>