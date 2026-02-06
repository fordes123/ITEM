<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;

final class ThemeApi
{
    /**
     * 请求分发
     */
    public static function dispatch($action): void
    {

        switch ($action) {
            case 'category':
                self::checkMethod('GET');
                $params = self::getParams([
                    'mid' => ['type' => 'int', 'min' => 1]
                ]);
                self::success(ThemeRepository::postsByCategory($params['mid']));
                break;

            case 'posts':
                self::checkMethod('GET');
                $options = Helper::options();
                $defaultSize = ThemeHelper::isPositive($options->pageSize) ? (int) $options->pageSize : 10;
                $params = self::getParams([
                    'page' => ['type' => 'int', 'min' => 1, 'required' => false, 'default' => 1],
                    'size' => ['type' => 'int', 'min' => 1, 'max' => 50, 'required' => false, 'default' => $defaultSize],
                ]);

                $user = Typecho_Widget::widget('Widget_User');
                $uid = $user->group == 'administrator' ? -1 : $user->uid;

                $result = ThemeRepository::posts($params['size'], $params['page'], null, $uid);
                $items = [];
                foreach ($result['data'] as $cid) {
                    $post = ThemeRepository::post($cid);
                    $post['date'] = date('m-d, Y', $post['modified']);
                    $items[] = $post;
                }
                $result['data'] = $items;
                self::success($result);
                break;

            case 'popular':
                self::checkMethod('GET');
                $params = self::getParams([
                    'size' => ['type' => 'int', 'min' => 1, 'max' => 20],
                ]);
                self::success(ThemeRepository::postsByViews($params['size']));
                break;

            case 'likes':
                self::checkMethod('POST');
                $params = self::getParams([
                    'cid' => ['type' => 'int', 'min' => 1]
                ]);

                $num = ThemeRepository::increaseAgree($params['cid']);
                self::success($num);
                break;

            default:
                self::error(404);
        }

        exit;
    }

    /**
     * 检查请求方法
     */
    private static function checkMethod($requiredMethod = 'POST')
    {

        if (strtoupper($_SERVER['REQUEST_METHOD']) !== strtoupper($requiredMethod)) {
            self::error(405);
        }
    }

    /**
     * 获取请求参数
     */
    private static function getParams(array $config)
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
                    self::error(400, "parameter [{$name}] is required");
                }
                $results[$name] = $default;
                continue;
            }

            // 2. 类型校验与长度/范围校验
            if ($type === 'int') {
                $intVal = filter_var($val, FILTER_VALIDATE_INT);
                if ($intVal === false) {
                    self::error(400, "parameter [{$name}] must be an integer");
                }
                // 默认正整数约束 (根据需求：int 默认正数)
                if ($intVal <= 0 && !isset($rules['min'])) {
                    self::error(400, "parameter [{$name}] must be a positive integer");
                }
                // 上下限校验
                if (!is_null($min) && $intVal < $min)
                    self::error(400, "parameter [{$name}] is too small (min: {$min})");
                if (!is_null($max) && $intVal > $max)
                    self::error(400, "parameter [{$name}] is too large (max: {$max})");

                $results[$name] = $intVal;
            } elseif ($type === 'str') {
                $strVal = trim($request->filter('strip_tags')->get($name));
                // 默认非空白字符约束
                if ($strVal === '') {
                    self::error(400, "parameter [{$name}] cannot be empty");
                }
                // 长度校验
                $len = mb_strlen($strVal);
                if (!is_null($min) && $len < $min)
                    self::error(400, "parameter [{$name}] length is too short (min: {$min})");
                if (!is_null($max) && $len > $max)
                    self::error(400, "parameter [{$name}] length is too long (max: {$max})");

                $results[$name] = $strVal;
            }
        }
        return $results;
    }

    /**
     * 获取token
     * TODO: 本函数未经测试
     */
    private static function getToken()
    {
        return Typecho_Widget::widget('Widget_Security')->getToken(
            Typecho_Request::getInstance()->getReferer()
        );
    }

    /**
     * 验证token
     * TODO: 本函数未经测试
     */
    private static function verifyToken()
    {
        $token = Typecho_Request::getInstance()->get('_');
        if (!Typecho_Widget::widget('Widget_Security')->validateToken($token, Typecho_Request::getInstance()->getReferer())) {
            self::error(403);
        }
    }

    /**
     * 返回json数据
     */
    private static function response($code, $message, $data = null)
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
     * 返回成功数据
     */
    private static function success($data = null, $message = '')
    {
        self::response(200, $message, $data);
    }

    /**
     * 返回错误数据
     */
    private static function error($code, $message = '')
    {
        self::response($code, $message);
    }
}
