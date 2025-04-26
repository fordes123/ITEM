<aside class="site-aside">
    <div class="aside-wrapper">
        <a href="<?php $this->options->siteUrl(); ?>" class="aside-brand d-none d-xl-flex" rel="home">
            <img src="<?php empty($this->options->biglogo) ? $this->options->themeUrl('/assets/image/head.png') : $this->options->biglogo(); ?>" class="logo nc-no-lazy" alt="<?php $this->options->title(); ?>">
            <img src="<?php empty($this->options->smalllogo) ? $this->options->themeUrl('/assets/image/favicon.ico') : $this->options->smalllogo(); ?>" class="logo-sm nc-no-lazy" alt="<?php $this->options->title(); ?>">
        </a>
        <div class="aside-scroll scrollable hover">
            <ul class="aside-menu">
                <?php
                $db = Typecho_Db::get();
                $cats = $db->fetchAll($db->select('mid,name,slug,parent')
                    ->from('table.metas')
                    ->where('type = ? AND (count > 0 OR parent = 0)', 'category')
                    ->order('parent', Typecho_Db::SORT_ASC)
                    ->order('order', Typecho_Db::SORT_ASC));

                global $tree;
                $tree = array();
                foreach ($cats as $c) {
                    if ($c['parent'] == 0) {
                        $tree[$c['mid']] = $c;
                        $tree[$c['mid']]['children'] = array();
                    } else {
                        if (isset($tree[$c['parent']])) {
                            $tree[$c['parent']]['children'][$c['mid']] = $c;
                        }
                    }
                }

                foreach ($tree as $p):
                    $subMenu = $this->options->subCategoryType != 1;
                    if (!$subMenu || empty($p['children'])): ?>
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category">
                            <a role="button" data-target="<?php echo $p['slug']; ?>" data-index="<?php echo $this->is('index'); ?>" aria-current="page">
                                <span class="menu-icon"><i class="fas fa-<?php echo $p['slug']; ?> fa-sm"></i></span>
                                <span class="menu-text"><?php echo $p['name'] ?></span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children">
                            <a role="button">
                                <span class="menu-icon"><i class="fas fa-<?php echo $p['slug']; ?> fa-sm"></i></span>
                                <span class="menu-text"><?php echo $p['name'] ?></span>
                                <span class="menu-sign fas fa-arrow-right fa-sm"></span>
                            </a>
                            <ul class="sub-menu" role="menu">
                                <?php foreach ($p['children'] as $c): ?>
                                    <li class="menu-item menu-item-type-taxonomy menu-item-object-category">
                                        <a role="button" data-target="<?php echo $c['slug']; ?>" data-index="<?php echo $this->is('index'); ?>" aria-current="page">
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