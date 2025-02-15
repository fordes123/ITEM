<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;

if ($this->fields->url) : ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="0;url=<?php $this->fields->url() ?>">
    <script>
        window.location.href = "<?php $this->fields->url(); ?>";
    </script>
</head>

</html>
<?php exit(); endif;
$this->need('header.php');
$this->need('sidebar.php');
$this->need('topbar.php');
?>

<main class="site-main">
    <div class="container">
        <div class="container">
            <div class="row gx-3 gx-md-4">
                <div class="post card card-md mb-3 mb-md-4">
                    <div class="post-other-style">
                        <div class="post-heading text-center pt-5 pt-md-5 pb-3 pb-xl-4">
                            <h1 class="post-title"> <?php $this->title(); ?></h1>
                            <div class="post-meta d-flex flex-fill justify-content-center align-items-center text-base mt-3 mt-md-3">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="post-content">
                                <div class="post-excerpt">
                                    <?php if ($this->fields->text): ?>
                                        <i class="excerpt-icon"></i>
                                        <h4><?php echo $this->fields->text; ?></h4>
                                    <?php endif; ?>
                                </div>
                                <div class="text-wrap text-break fs-6 mx-3">
                                    <?php $this->content(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $this->need('footer.php'); ?>