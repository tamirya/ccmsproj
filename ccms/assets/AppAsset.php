<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/ionicons.min.css',
        'css/fullcalendar.css',
        'css/select2.css',
        'css/select2-bootstrap.css',
        'css/jquery.dataTables.css',// work with ext
        //'css/dataTables.min.css',
        'css/jquery.dataTables.yadcf.css',
        'css/site.css',
    ];
    public $js = [
        'js/countUp.min.js',
        'js/core.js',
        'js/moment.min.js',
        'js/jquery-ui.custom.min.js',
        'js/fullcalendar.js',
        'js/select2.min.js',
        'js/select2_locale_he.js',
        //'js/jquery.tablesorter.min.js',
        'js/jquery.dataTables.min.js',// work with ext
        //'js/dataTables.min.js',
        'js/jquery.dataTables.yadcf.js',


    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
