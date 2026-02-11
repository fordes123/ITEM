<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;

final class ThemeHelper
{

    /*
     * 文章是否被密码保护，已通过验证视为无密码
     */
    public static function hasPasswd($post)
    {
        if (!isset($post->password) || $post->password === '') {
            return false;
        }
        $cookiePassword = Typecho_Cookie::get('protectPassword_' . $post->cid);
        if ($cookiePassword === null || $cookiePassword !== $post->password) {
            return true;
        }
        return false;
    }


    /**
     * 规范化文章数据
     */
    public static function normalizePost($post): array
    {
        return array(
            'cid' => $post->cid,
            'title' => $post->title,
            'permalink' => $post->permalink,
            'url' => $post->permalink . ($post->fields->navigation == '1' && !$post->hidden ? '?go' : ''),
            'text' => $post->password ? '验证后可查看内容' : $post->fields->text,
            'logo' => self::favicon($post),
            'hidden' => $post->hidden,
            'modified' => $post->modified,
        );
    }

    /**
     * 格式化时间距离
     */
    public static function formatTimeAgo(int $timestamp): string
    {
        $diff = time() - $timestamp;

        if ($diff < 60) {
            return '刚刚';
        }

        $units = [
            '年前' => 31536000,
            '个月前' => 2592000,
            '天前' => 86400,
            '小时前' => 3600,
            '分钟前' => 60,
            '秒前' => 1,
        ];

        foreach ($units as $unit => $value) {
            if ($diff >= $value) {
                $time = floor($diff / $value);
                return $time . $unit;
            }
        }

        return '未知';
    }

    /**
     * 格式化数字
     */
    public static function formatNumber($number)
    {
        if (!is_numeric($number)) {
            return $number;
        }

        $number = (float) $number;
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        }

        if ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }

        return (int) $number;
    }

    /**
     * 获取favicon
     */
    public static function favicon($posts)
    {
        if ($logo = $posts->fields->logo) {
            return $logo;
        }

        $url = $posts->fields->url;
        $hostname = $url ? parse_url($url, PHP_URL_HOST) : null;
        if (!$hostname) {
            return '';
        }

        $options = Helper::options();
        $template = ($options->faviconApiSelect === 'custom' ? $options->faviconApi : $options->faviconApiSelect)
            ?? ThemeConfig::DEFAULT_FAVICON_API;

        return strtr($template, ['{hostname}' => urlencode($hostname)]);
    }

    /**
     * 根据email获取头像
     */
    public static function avatar($email)
    {
        $email ??= '';
        $options = Helper::options();

        $template = ($options->avatarApiSelect === 'custom' ? $options->avatarApi : $options->avatarApiSelect)
            ?? ThemeConfig::DEFAULT_GRAVATAR_API;

        return strtr($template, ['{hash}' => hash('sha256', $email)]);
    }

    /**
     * 过滤关键词，包含截断和转义
     */
    public static function filterKeywords($text, $length = 10)
    {
        $text = trim($text);
        if (mb_strlen($text, 'utf-8') > $length) {
            return mb_substr($text, 0, $length, 'utf-8');
        }

        return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function isBlank($str)
    {
        return !is_string($str)
            || $str === ''
            || !preg_match('/[^\s\p{C}]/u', $str);
    }

    public static function isPositive($value)
    {
        return ctype_digit((string) $value) && (int) $value > 0;
    }
}
