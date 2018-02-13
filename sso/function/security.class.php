<?php
/**
 * 安全增强组件类_v1内部版本
 * @package DingStudio_API_Interface
 * @subpackage API/Security 安全类库
 * @copyright DingStudio 2017 All Right Reserved
 */
require_once(dirname(__FILE__).'/api.class.php');

class Security {

    public function checkDomain($CustomDomain) {
        if ($CustomDomain) {
            $trust_domain = $CustomDomain;
        }
        else {
            $trust_domain = $_SERVER["HTTP_HOST"];
        }
        $current_domain = @parse_url($_SERVER['HTTP_REFERER'])[host];
        if ($trust_domain != $current_domain and $current_domain != '') {
            self::CSRF_Breaker();
        }
    }

    public function create_CSRFToken() {
        if (!isset($_COOKIE['csrf_token']) or $_COOKIE['csrf_token'] == '') {
            setcookie("csrf_token", rand(1000,9999), time() + 600,  "/", $_SERVER["HTTP_HOST"]);
        }
    }

    public function CSRF_Breaker() {
        $response = Response::getInstance('json');
        $response->make(403, 'Sorry, the system was protected by DingStudio CSRF Firewall.');
    }
}
