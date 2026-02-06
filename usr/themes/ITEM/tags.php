<?php
/**
 * 标签归档
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$this->need("header.php");
$this->need("sidebar.php");
$this->need("navbar.php");
?>

<main class="site-main">
    <div class="container">
        <div class="container">
            <div class="row gx-3 gx-md-4">
                <div class="post card card-md mb-3 mb-md-4">
                    <div class="post-other-style">
                        <?php
                        $this->widget('Widget_Metas_Tag_Cloud', 'ignoreZeroCount=1')->to($tags);
                        $tagItems = [];
                        if ($tags->have()) {
                            while ($tags->next()) {
                                $tagItems[] = [
                                    'name' => $tags->name,
                                    'permalink' => $tags->permalink,
                                    'count' => $tags->count
                                ];
                            }
                        }
                        $tagTotal = count($tagItems);
                        ?>
                        <div class="post-heading text-center pt-5 pt-md-5 pb-3 pb-xl-4">
                            <h1 class="post-title"><?php $this->title(); ?></h1>
                            <div class="post-meta d-flex flex-fill justify-content-center align-items-center text-base mt-3 mt-md-3">
                                <span class="text-muted">共 <?php echo $tagTotal; ?> 个标签</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="post-content">
                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    <?php if (!empty($tagItems)): ?>
                                        <?php foreach ($tagItems as $item): ?>
                                            <a href="<?php echo $item['permalink']; ?>" class="btn btn-outline-primary btn-sm rounded-pill">
                                                <?php echo $item['name']; ?>
                                                <span class="badge bg-primary ms-2"><?php echo $item['count']; ?></span>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php ThemeView::empty(); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="post-actions row g-2 mt-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $this->need('footer.php'); ?>
