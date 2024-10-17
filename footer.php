<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<footer class="site-footer">
    <div class="container">
        <div class="copyright text-xs text-muted text-center">
            <span class="d-inline-block">© <?php echo date("Y"); ?></span>
            <a class="text-muted" href="<?php $this->options->siteUrl(); ?>" rel="home"><?php $this->options->title(); ?></a>
            <?php if (!empty($this->options->icp)) : ?>
                <span class="d-inline-block">&nbsp;|&nbsp;</span>
                <a href="https://beian.miit.gov.cn" target="_blank" rel="nofollow" class="text-muted"><?php $this->options->icp(); ?></a>
            <?php endif; ?>
        </div>
    </div>
</footer>
</div>
<ul class="site-fixedmenu">
    <li id="scrollToTOP"> <a href="#" class="btn btn-start btn-icon btn-rounded"><span><i class="fas fa-arrow-up"></i></span></a></li>
</ul>
<script src="//lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/bootstrap/5.1.3/js/bootstrap.min.js" type="application/javascript"></script>
<script src="<?php $this->options->themeUrl('./assets/js/lazyload.min.js'); ?>" type="application/javascript"></script>
<script src="<?php $this->options->themeUrl('./assets/js/script.min.js'); ?>" type="text/javascript"></script>
</body>

</html>