<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;
?>
<aside class="site-aside offcanvas-xl offcanvas-start border-0" id="siteAside" tabindex="-1" aria-labelledby="siteAsideLabel">
    <div class="offcanvas-header" rel="home" id="siteAsideLabel"></div>
    <div class="offcanvas-body p-0">
        <div class="aside-wrapper d-flex flex-column h-100">
            <a href="<?php $this->options->siteUrl(); ?>" class="aside-brand d-none d-xl-flex px-4 py-3 justify-content-center align-items-center" rel="home">
                <img src="<?php ThemeHelper::isBlank($this->options->smalllogo) ? $this->options->themeUrl('/assets/image/favicon.ico') : $this->options->smalllogo(); ?>"
                    class="logo-sm img-fluid mx-auto nc-no-lazy" alt="<?php $this->options->title(); ?>">
                <img src="<?php ThemeHelper::isBlank($this->options->biglogo) ? $this->options->themeUrl('/assets/image/head.png') : $this->options->biglogo(); ?>"
                    class="logo img-fluid d-block mx-auto nc-no-lazy" alt="<?php $this->options->title(); ?>">
            </a>
            <div class="aside-scroll scrollable hover flex-grow-1">
                <ul class="aside-menu py-1 px-3" id="asideMenu">
                    <?php global $data;
                    $uid = ThemeHelper::getUid();
                    $data = ThemeRepository::categoryTree($this->is('index'), $uid);
                    foreach ($data as $item):
                        $subMenu = $this->options->subCategoryType != 1;
                        if (!$subMenu || empty($item['children'])): ?>
                            <li class="menu-item my-2">
                                <a role="button" class="d-flex align-items-center gap-1 py-1 px-3" data-target="<?php echo $item['slug']; ?>"
                                    data-index="<?php echo $this->is('index'); ?>" aria-current="page">
                                    <span class="menu-icon"><i class="fa-solid fa-<?php echo $item['slug']; ?> fa-sm"></i></span>
                                    <span class="menu-text"><?php echo $item['name'] ?></span>
                                </a>
                            </li>
                        <?php else: ?>
                            <?php $submenuId = 'submenu-' . $item['slug']; ?>
                            <li class="menu-item menu-item-has-children my-2">
                                <a role="button" class="d-flex align-items-center gap-1 py-1 px-3" data-bs-toggle="collapse" href="#<?php echo $submenuId; ?>"
                                    aria-expanded="false"
                                    aria-controls="<?php echo $submenuId; ?>">
                                    <span class="menu-icon"><i class="fa-solid fa-<?php echo $item['slug']; ?> fa-sm"></i></span>
                                    <span class="menu-text"><?php echo $item['name'] ?></span>
                                    <span class="menu-sign fa-solid fa-angle-right fa-sm ms-auto"></span>
                                </a>
                                <ul class="sub-menu collapse ps-0" id="<?php echo $submenuId; ?>" role="menu" data-bs-parent="#asideMenu">
                                    <?php foreach ($item['children'] as $c): ?>
                                        <li class="menu-item">
                                            <a role="button" class="d-flex align-items-center gap-1 py-1 px-3" data-target="<?php echo $c['slug']; ?>"
                                                data-index="<?php echo $this->is('index'); ?>" aria-current="page">
                                                <span class="menu-icon invisible"><i class="fa-solid fa-circle fa-sm"></i></span>
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
    </div>
</aside>
