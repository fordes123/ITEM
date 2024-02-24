<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<footer class="site-footer">
    <div class="container">
        <div class="copyright text-xs text-muted text-center">
            <span class="d-inline-block">Copyright © 2019-<?php echo date("Y"); ?>
                <a class="text-muted" href="#" title="<?php $this->options->title(); ?>" rel="home"> <?php $this->options->title(); ?></a>
                . All rights reserved.
                <?php if ($this->options->icp != null && $this->options->icp != '') : ?>
                    <a href="https://beian.miit.gov.cn" target="_blank" rel="nofollow" class="text-muted"><?php $this->options->icp(); ?></a>
                <?php endif; ?>
            </span>
        </div>
    </div>
</footer>
</div>
<ul class="site-fixedmenu">
    <li id="scrollToTOP"> <a href="#" class="btn btn-start btn-icon btn-rounded"><span><i class="fas fa-arrow-up"></i></span></a></li>
</ul>
<script src="<?php $this->options->themeUrl('./assets/js/lazyload.min.js'); ?>" type="application/javascript"></script>
<script src="<?php $this->options->themeUrl('./assets/js/script.min.js'); ?>" type="text/javascript"></script>
</body>

</html>