<aside class="site-aside bg-white">
    <div class="aside-wrapper">
        <a href="<?php $this->options->siteUrl(); ?>" class="aside-brand d-none d-xl-flex" rel="home">
            <img src="<?php empty($this->options->biglogo) ? $this->options->themeUrl('/assets/image/head.png') : $this->options->biglogo(); ?>" class="logo nc-no-lazy" alt="<?php $this->options->title(); ?>">
            <img src="<?php empty($this->options->smalllogo) ? $this->options->themeUrl('/assets/image/favicon.ico') : $this->options->smalllogo(); ?>" class="logo-sm nc-no-lazy" alt="<?php $this->options->title(); ?>">
        </a>
        <div class="aside-scroll scrollable hover">
            <ul class="aside-menu">
                <?php global $categories;
                $this->widget('Widget_Metas_Category_List')->to($categories);
                while ($categories->next()) :
                    if ($categories->levels === 0) :
                        $children = $categories->getAllChildren($categories->mid);
                        if (empty($children)) { ?>
                            <li class="menu-item menu-item-type-taxonomy menu-item-object-category">
                                <?php $onclick = $this->is('index') ? "document.getElementById('" . $categories->slug . "').scrollIntoView({behavior: 'smooth', block: 'center'})" : "window.location.href = '/'"; ?>
                                <a onclick="<?php echo $onclick; ?>" aria-current="page">
                                    <span class="menu-icon"><i class="fas fa-<?php $categories->slug(); ?> fa-sm"></i></span>
                                    <span class="menu-text"><?php $categories->name() ?></span>
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children">
                                <a>
                                    <span class="menu-icon"><i class="fas fa-<?php $categories->slug(); ?> fa-sm"></i></span>
                                    <span class="menu-text"><?php $categories->name(); ?></span>
                                    <span class="menu-sign fas fa-arrow-right fa-sm"></span>
                                </a>
                                <ul class="sub-menu" role="menu">
                                    <?php foreach ($children as $mid) { ?>
                                        <?php $child = $categories->getCategory($mid); ?>
                                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category">
                                            <?php $onclick = $this->is('index') ? "document.getElementById('" . $child['slug'] . "').scrollIntoView({behavior: 'smooth', block: 'center'})" : "window.location.href = '/'"; ?>
                                            <a onclick="<?php echo $onclick; ?>" aria-current="page">
                                                <span class="menu-text"><?php echo $child['name'] ?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                    <?php endif; ?>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</aside>