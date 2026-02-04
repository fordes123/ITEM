<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;
?>
<aside class="site-aside">
    <div class="aside-wrapper">
        <a href="<?php $this->options->siteUrl(); ?>" class="aside-brand d-none d-xl-flex" rel="home">
            <img src="<?php ThemeHelper::isBlank($this->options->biglogo) ? $this->options->themeUrl('/assets/image/head.png') : $this->options->biglogo(); ?>"
                class="logo nc-no-lazy" alt="<?php $this->options->title(); ?>">
            <img src="<?php ThemeHelper::isBlank($this->options->smalllogo) ? $this->options->themeUrl('/assets/image/favicon.ico') : $this->options->smalllogo(); ?>"
                class="logo-sm nc-no-lazy" alt="<?php $this->options->title(); ?>">
        </a>
        <div class="aside-scroll scrollable hover">
            <ul class="aside-menu">
                <?php global $data;
                $data = ThemeRepository::categoryTree($this->is('index'));
                foreach ($data as $item):
                    $subMenu = $this->options->subCategoryType != 1;
                    if (!$subMenu || empty($item['children'])): ?>
                        <li class="menu-item">
                            <a role="button" data-target="<?php echo $item['slug']; ?>"
                                data-index="<?php echo $this->is('index'); ?>" aria-current="page">
                                <span class="menu-icon"><i class="fa-solid fa-<?php echo $item['slug']; ?> fa-sm"></i></span>
                                <span class="menu-text"><?php echo $item['name'] ?></span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children">
                            <a role="button">
                                <span class="menu-icon"><i class="fa-solid fa-<?php echo $item['slug']; ?> fa-sm"></i></span>
                                <span class="menu-text"><?php echo $item['name'] ?></span>
                                <span class="menu-sign fa-solid fa-arrow-right fa-sm"></span>
                            </a>
                            <ul class="sub-menu" role="menu">
                                <?php foreach ($item['children'] as $c): ?>
                                    <li class="menu-item">
                                        <a role="button" data-target="<?php echo $c['slug']; ?>"
                                            data-index="<?php echo $this->is('index'); ?>" aria-current="page">
                                            <span class="menu-text"><?php echo $c['name'] ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</aside>
