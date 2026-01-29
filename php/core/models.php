<?php
/**
 * 文章统计数据缓存类
 */
class ArticleCache
{
    public $views;
    public $agree;

    public function __construct($views = 0, $agree = 0)
    {
        $this->views = $views;
        $this->agree = $agree;
    }
}