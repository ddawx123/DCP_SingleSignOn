<?php
require_once(dirname(__FILE__).'/applist.inc.php'); //引用applist导入站点列表
$appid = AppType::readReferer(urldecode(@$_REQUEST['returnUrl']));
if(isset($appid)) {
    if($appid == 9999) {
        require_once(dirname(__FILE__).'/../template/disallow_service.php');
        exit();
        //$appname = '其他应用';
    }
    else {
        $appname = $IdsName[$appid];
    }
}
else {
    $appname = '用户中心';
}

class AppType {

    /**
     * 读取来路域名信息
     * @author alone◎浅忆
     * @copyright 2016-2017 All Rights Reserved
     * @param string $url
     * @return integer $appid
     */
    public static function readReferer($url) {
        global $siteIds;
        if($url == '') {
            return null;
        }
        $url_data = parse_url($url);
        $url_domain = @$url_data['host'];
        if(isset($siteIds[$url_domain])) {
            return $siteIds[$url_domain];
        }
        else {
            return 9999;
        }
    }

    /**
     * 通过Id传入输出站点介绍
     * @author alone◎浅忆
     * @copyright 2016-2017 All Rights Reserved
     * @param integer $siteId
     * @return string
     */
    public static function outputIntroduce($siteId) {
        return $IdsName[$siteId];
    }
}