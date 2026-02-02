<?php if (!defined('__TYPECHO_ROOT_DIR__'))
    exit; ?>
<footer class="site-footer">
    <div class="container">
        <div class="copyright text-xs text-muted text-center">
            <span class="d-inline-block">© <?php echo date("Y"); ?></span>
            <a class="text-muted" href="<?php $this->options->siteUrl(); ?>"
                rel="home"><?php $this->options->title(); ?></a>
            <?php if (!ThemeHelper::isBlank($this->options->icp)): ?>
                <span class="d-inline-block">&nbsp;|&nbsp;</span>
                <a href="https://beian.miit.gov.cn" target="_blank" rel="nofollow"
                    class="text-muted"><?php $this->options->icp(); ?></a>
            <?php endif; ?>
        </div>
    </div>
</footer>
</div>
<ul class="site-fixedmenu">
    <li id="scrollToTOP"> <a href="#" class="btn btn-start btn-icon btn-rounded">
            <span><i class="fas fa-arrow-up"></i></span></a>
    </li>
</ul>

<?php $this->need('template.php'); ?>

<script>
    window.config = {
        siteUrl: "<?php $this->options->siteUrl(); ?>",
        <?php if ($this->is('index')): ?>
                        weatherApiKey: "<?php $this->options->weatherApiKey(); ?>",
            weatherNode: "<?php $this->options->weatherNode(); ?>",
        <?php endif; ?>
    }
</script>
<script src="<?php $this->options->themeUrl('./assets/js/main.min.js'); ?>" type="text/javascript"></script>
<?php if ($this->is('page') || $this->is('post')): ?>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('./assets/css/prismjs.min.css'); ?>">
    <script defer src="<?php $this->options->themeUrl('./assets/js/prismjs.min.js'); ?>"></script>
<?php endif; ?>
<?php $this->options->customFooter(); ?>
<?php $this->footer(); ?>
</body>

</html>