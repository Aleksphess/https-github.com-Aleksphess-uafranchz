<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/main.css',
        /*'css/jquery.auto-complete.css',
        'css/jquery.arcticmodal-0.3.css',
        'css/arctic-simple.css',
        'css/custom.css',*/
    ];
    public $js = [
        'js/vendor.js',
        'js/main.js',
        'js/my.js',
     /*   'js/leaflet.markercluster.js',
        'js/verge.js',
        'js/jquery-ui.min.js',
        'js/bootstrap.min.js',
        'js/jquery.selectric.min.js',
        'js/jquery.auto-complete.min.js',
        'js/slick.min.js',
        'js/jquery.arcticmodal-0.3.min.js',
        'js/jquery.dotdotdot.min.js',
        'js/jquery.matchheight-min.js',
        'js/app.js',
        'js/custom.js',*/
    ];
    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
