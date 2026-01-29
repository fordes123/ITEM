<?php
require_once 'constants.php';
require_once 'utils.php';
require_once 'api.php';
class Bootstrap
{

    /**
     * API 入口路由分发
     */
    public static function dispatch()
    {

        $request = Typecho_Request::getInstance();
        $action = $request->get('action');
        if ($action) {
            // 简单的路由匹配
            switch ($action) {
                case 'category':
                    Theme_Utils::checkMethod('GET');
                    Theme_Utils::getParams([
                        'cid' => 'int',
                    ]);
                    break;
                case 'search':
                    Theme_Utils::checkMethod('GET');
                    break;

                case 'likes':
                    Theme_Utils::checkMethod('POST');
                    $params = Theme_Utils::getParams([
                        'cid' => ['type' => 'int', 'min' => 1]
                    ]);
                    self::likeArticle($params);

                    break;
                case 'views':
                    Theme_Utils::checkMethod('POST');
                    break;
                default:
                    Theme_Utils::error(404, 'Unknown Action');
            }

            exit;
        } else {
            self::init();
        }
    }

    private static function likeArticle($params)
    {

        $num = Theme_Api::updateAgree($params['cid']);
        Theme_Utils::success($num);
    }

    public static function init()
    {
        try {
            $db = Typecho_Db::get();
            $prefix = $db->getPrefix();

            // 获取当前版本
            $row = $db->fetchRow($db->select()->from('table.options')->where('name = ?', Theme_Constants::VERSION_OPTION));
            $version = $row ? $row['value'] : null;

            error_log("当前版本: " . $version);

            if ($version !== Theme_Constants::THEME_VERSION) {
                error_log('检测到主题更新, 重新初始化数据库...');

                // 浏览量字段
                if (empty($db->fetchAll($db->query('SHOW COLUMNS FROM `' . $prefix . 'contents` LIKE "views";')))) {
                    $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT UNSIGNED NOT NULL DEFAULT 0;');
                }

                // 点赞字段
                if (empty($db->fetchAll($db->query('SHOW COLUMNS FROM `' . $prefix . 'contents` LIKE "agree";')))) {
                    $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `agree` INT UNSIGNED NOT NULL DEFAULT 0;');
                }

                // 写入版本信息
                if ($version) {
                    $db->query($db->update('table.options')
                        ->rows(['value' => Theme_Constants::THEME_VERSION])
                        ->where('name = ?', Theme_Constants::VERSION_OPTION));
                } else {
                    $db->query($db->insert('table.options')->rows([
                        'name' => Theme_Constants::VERSION_OPTION,
                        'value' => Theme_Constants::THEME_VERSION,
                        'user' => 0
                    ]));
                }
                error_log('已从 ' . ($version ? $version : '未知版本') . ' 更新至 ' . Theme_Constants::THEME_VERSION);
            }
        } catch (Exception $e) {
            error_log('初始化数据库失败: ' . $e->getMessage());
            throw $e;
        }
    }
}
