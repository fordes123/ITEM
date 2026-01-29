<?php
/**
 * 主题工具类
 */
class Theme_Utils
{


    /**
     * 校验请求方式，若不匹配直接中断并返回错误
     * @param string $requiredMethod 期待的方式，如 'POST'
     */
    public static function checkMethod($requiredMethod = 'POST')
    {

        if (strtoupper($_SERVER['REQUEST_METHOD']) !== strtoupper($requiredMethod)) {
            self::error(405);
        }
    }


    /**
     * 校验请求参数
     * @param array $rules 规则数组，格式：['参数名' => '类型']
     * @return array 返回过滤后的安全参数数组
     */
    /**
     * 增强版参数校验
     * 支持类型: positive(>0), positiveOrZero(>=0), str, int, email
     */
    public static function getParams(array $config)
    {
        $request = Typecho_Request::getInstance();
        $results = [];

        foreach ($config as $name => $rules) {
            $val = $request->get($name);

            // 默认规则初始化
            $type = $rules['type'] ?? 'str';
            $required = $rules['required'] ?? true;
            $default = $rules['default'] ?? null;
            $min = $rules['min'] ?? null;
            $max = $rules['max'] ?? null;

            // 1. 处理空值与默认值
            if (is_null($val) || $val === '') {
                if ($required) {
                    self::error(400, "Parameter [{$name}] is required");
                }
                $results[$name] = $default;
                continue;
            }

            // 2. 类型校验与长度/范围校验
            if ($type === 'int') {
                $intVal = filter_var($val, FILTER_VALIDATE_INT);
                if ($intVal === false) {
                    self::error(400, "Parameter [{$name}] must be an integer");
                }
                // 默认正整数约束 (根据需求：int 默认正数)
                if ($intVal <= 0 && !isset($rules['min'])) {
                    self::error(400, "Parameter [{$name}] must be a positive integer");
                }
                // 上下限校验
                if (!is_null($min) && $intVal < $min)
                    self::error(400, "Parameter [{$name}] is too small (min: {$min})");
                if (!is_null($max) && $intVal > $max)
                    self::error(400, "Parameter [{$name}] is too large (max: {$max})");

                $results[$name] = $intVal;

            } elseif ($type === 'str') {
                $strVal = trim($request->filter('strip_tags')->get($name));
                // 默认非空白字符约束
                if ($strVal === '') {
                    self::error(400, "Parameter [{$name}] cannot be empty");
                }
                // 长度校验
                $len = mb_strlen($strVal);
                if (!is_null($min) && $len < $min)
                    self::error(400, "Parameter [{$name}] length is too short (min: {$min})");
                if (!is_null($max) && $len > $max)
                    self::error(400, "Parameter [{$name}] length is too long (max: {$max})");

                $results[$name] = $strVal;
            }
        }
        return $results;
    }

    /**
     * 生成安全 Token (用于前端请求时携带)
     */
    public static function getToken()
    {
        // 利用 Typecho 核心安全类生成 token
        return Typecho_Widget::widget('Widget_Security')->getToken(
            Typecho_Request::getInstance()->getReferer()
        );
    }

    /**
     * 校验 Token 安全性
     */
    public static function verifyToken()
    {
        $token = Typecho_Request::getInstance()->get('_'); // 约定参数名为 _
        if (!Typecho_Widget::widget('Widget_Security')->validateToken($token, Typecho_Request::getInstance()->getReferer())) {
            self::error(403, 'Invalid Security Token');
        }
    }

    /**
     * 发送规范的 JSON 响应
     * @param int $code HTTP 状态码
     * @param string $message 消息描述
     * @param mixed $data 数据载荷
     */
    public static function jsonResponse($code, $message, $data = null)
    {
        if (ob_get_length())
            ob_clean();

        // HTTP Status
        http_response_code($code);

        //Header
        header('Content-Type: application/json; charset=utf-8');
        header('x-powered-by: ');

        $response = [
            'msg' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        echo json_encode($response);
        exit;
    }

    /**
     * 快捷方法：成功响应 (200 OK)
     */
    public static function success($data = null, $message = 'Success')
    {
        self::jsonResponse(200, $message, $data);
    }

    /**
     * 快捷方法：错误响应
     * @param int $code 错误代码
     * @param string $message 错误详情
     */
    public static function error($code, $message = 'Error')
    {
        self::jsonResponse($code, $message);
    }

    /**
     * 时间友好化显示
     * @param int $timestamp
     * @return string
     */
    public static function timeago(int $timestamp): string
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
     * 输出评价星级
     * @param float|int $score 分数 (0-5)
     * @param string $color 星星颜色
     */
    public static function renderStars($score, string $color = '#FFD43B'): void
    {
        // 1. 约束分数范围
        $score = max(0, min(5, (float) $score));

        // 2. 循环生成星星
        for ($i = 1; $i <= 5; $i++) {
            // 判断当前位置该显示的图标类
            if ($score >= $i) {
                $icon = 'fas fa-star';          // 实星
            } elseif ($score >= $i - 0.5) {
                $icon = 'fas fa-star-half-alt'; // 半星
            } else {
                $icon = 'far fa-star';          // 空星
            }

            printf('<i class="%s" style="color: %s;"></i>', $icon, $color);
        }
    }

    public static function getLoadingIcon()
    {
        return Helper::options()->themeUrl(Theme_Constants::DEFAULT_LOADING_ICON);
    }

    public static function favicon($post, $class = '')
    {
        $options = Helper::options();
        ?>
        <img src="<?php echo $options->themeUrl(Theme_Constants::DEFAULT_LOADING_ICON); ?>"
            data-src="<?php echo self::getFavicon($post); ?>" class="<?php echo $class; ?> lazy" />
        <?php
    }

    public static function getFavicon($posts)
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
            ?? Theme_Constants::DEFAULT_FAVICON_API;

        return strtr($template, ['{hostname}' => urlencode($hostname)]);
    }

    public static function getAvatar($email)
    {
        $email ??= '';
        $options = Helper::options();

        $template = ($options->avatarApiSelect === 'custom' ? $options->avatarApi : $options->avatarApiSelect)
            ?? Theme_Constants::DEFAULT_GRAVATAR_API;

        return strtr($template, ['{hash}' => hash('sha256', $email)]);
    }

    /**
     * 格式化数字（大于1000转化为K，大于1000000转化为M）
     * @param mixed $number 要格式化的数字
     * @return string|int
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
     * 生成分页导航
     * @param string $baseUrl 基础URL
     * @param int $currentPage 当前页码
     * @param int $totalPages 总页数
     * @return string 分页HTML
     */
    public static function renderPage($baseUrl, $currentPage, $totalPages)
    {
        $html = '<nav class="navigation pagination" aria-label="Posts Navigation"><div class="nav-links">';
        if ($currentPage > 1) {
            $html .= '<a class="prev page-numbers" href="' . $baseUrl . ($currentPage - 1) . '">上一页</a>';
        }

        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                $html .= '<span aria-current="page" class="page-numbers current">' . $i . '</span>';
            } else {
                if ($i == 1 || $i == $totalPages || ($i >= $currentPage - 2 && $i <= $currentPage + 2)) {
                    $html .= '<a class="page-numbers" href="' . $baseUrl . $i . '">' . $i . '</a>';
                } elseif ($i == $currentPage - 3 || $i == $currentPage + 3) {
                    $html .= '<span class="page-numbers dots">...</span>';
                }
            }
        }

        if ($currentPage < $totalPages) {
            $html .= '<a class="next page-numbers" href="' . $baseUrl . ($currentPage + 1) . '">下一页</a>';
        }

        $html .= '</div></nav>';
        echo $html;
    }

    public static function createHtmlWidget(Typecho_Widget_Helper_Form $form, $content)
    {

        $html = new Typecho_Widget_Helper_Layout();
        $html->html(_t($content));
        return $html;
    }

    public static function isPostiveInt($value): int
    {
        return is_numeric($value) && (int) $value == $value && (int) $value > 0;
    }


}