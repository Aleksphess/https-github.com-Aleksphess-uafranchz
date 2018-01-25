<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id'                    => 'app-frontend',
    'name'                  => 'Dashboard',
    'basePath'              => dirname(__DIR__),
    'bootstrap'             => ['log'],
    'controllerNamespace'   => 'frontend\controllers',
    'components' => [
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
        ],

        'request' => [
            'csrfParam'     => 'digital_force',
            'baseUrl'       => '/',
            'class'               => 'common\components\LangRequest', // for multiLang
            'cookieValidationKey' => 'NtC8TLEAnI8DYwWjrjdaauocn_HQfZ-p',
        ],
        'liqpay' => [
            'class' => 'common\components\LiqPay',
        ],
        'user'   => [
            'identityClass'     => 'common\models\User',
            'enableAutoLogin'   => false,
            'identityCookie'    => ['name' => '_identity-frontend', 'httpOnly' => false],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel'    => YII_DEBUG ? 3 : 0,
            'targets'       => [
                [
                    'class'     => 'yii\log\FileTarget',
                    'levels'    => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class'               => 'common\components\LangUrlManager', // for multiLang
            'enablePrettyUrl'     => true,
            'showScriptName'      => false,
            'enableStrictParsing' => false,
            'rules' => [
                [
                    'pattern'   => 'find-franchise',
                    'route'     => 'content/find-franchise',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => 'sell-franchise',
                    'route'     => 'content/sell-franchise',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => 'forms/callback',
                    'route'     => 'forms/callback',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => 'reset/<reset>',
                    'route'     => 'content/reset-password',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => 'reset',
                    'route'     => 'content/reset',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => 'search/result',
                    'route'     => 'search/result',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => 'search/request',
                    'route'     => 'search/index',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => 'about',
                    'route'     => 'content/about',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => 'membership',
                    'route'     => 'content/membership',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '',
                    'route'     => 'content/index',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => 'signin',
                    'route'     => 'auth/login',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => 'login',
                    'route'     => 'content/login',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => 'logout',
                    'route'     => 'auth/logout',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => 'user/lot/<alias>',
                    'route'     => 'user/lot',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => 'user/lots',
                    'route'     => 'user/lots',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/lot/<alias>',
                    'route'     => 'lots/index',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/lots/edit/<alias>',
                    'route'     => 'lots/edit',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/filter',
                    'route'     => 'catalog/filter',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/category/filter',
                    'route'     => 'catalog/category',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/category/<alias>/filter',
                    'route'     => 'catalog/category',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/category/<alias>',
                    'route'     => 'catalog/category',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/category/<alias>/page/<page>',
                    'route'     => 'catalog/category',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/category',
                    'route'     => 'catalog/category',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/category/page/<page>',
                    'route'     => 'catalog/category',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/court/<alias>',
                    'route'     => 'court/single',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/court/page/<page>',
                    'route'     => 'court/index',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/court',
                    'route'     => 'court/index',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/news/<alias>',
                    'route'     => 'news/single',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/news/page/<page>',
                    'route'     => 'news/index',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/user/create/lot',
                    'route'     => 'lots/create',
                    'suffix'    => '',
                ],
                [
                    'pattern'   => '/lots/edit-basic/<alias>',
                    'route'     => 'lots/edit-basic',
                    'suffix'    => '',
                ],
                [
                    'pattern'   => '/lots/edit-cost/<alias>',
                    'route'     => 'lots/edit-cost',
                    'suffix'    => '',
                ],
                [
                    'pattern'   => '/lots/edit-extra/<alias>',
                    'route'     => 'lots/edit-extra',
                    'suffix'    => '',
                ],
                [
                    'pattern'   => '/lots/edit-contact/<alias>',
                    'route'     => 'lots/edit-contact',
                    'suffix'    => '',
                ],
                [
                    'pattern'   => '/lots/edit-media/<alias>',
                    'route'     => 'lots/edit-media',
                    'suffix'    => '',
                ],
                [
                    'pattern'   => '/lots/delete-img',
                    'route'     => 'lots/delete-img',
                    'suffix'    => '',
                ],
                [
                    'pattern'   => '/user/edit/lot/<alias>',
                    'route'     => 'lots/edit',
                    'suffix'    => '',
                ],
                [
                    'pattern'   => '/news',
                    'route'     => 'news/index',
                    'suffix'    => ''
                ],
//                [
//                    'pattern'   => '/category/<type>/<alias>',
//                    'route'     => 'catalog/category-with-lots-types',
//                    'suffix'    => ''
//                ],
                [
                    'pattern'   => '/dialog/<id:\d+>',
                    'route'     => 'dialogs/dialog',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/dialogs/check-messages/<dialog_id:\d+>',
                    'route'     => 'dialogs/check-messages',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/dialogs/read-messages/<dialog_id:\d+>',
                    'route'     => 'dialogs/read-messages',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/dialogs/check-contacts/<dialog_id:\d+>',
                    'route'     => 'dialogs/check-contacts',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/dialogs/display-contacts/<dialog_id:\d+>/<is_show:\d>',
                    'route'     => 'dialogs/display-contacts',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/user/payment',
                    'route'     => '/user/payment-static',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/user/bookmarks',
                    'route'     => '/user-bookmarks/index',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => '/user-bookmarks/bookmark/<lot_id:\d+>',
                    'route'     => '/user-bookmarks/bookmark',
                    'suffix'    => ''
                ],
                [
                    'pattern' => '/blog/index/page/<page:\d+>',
                    'route' => 'blog/index',
                    'suffix' => ''
                ],
                [
                    'pattern' => '/post/<alias>',
                    'route' => 'blog/single',
                    'suffix' => ''
                ],
                [
                    'pattern' => '/contacts', // for static pages
                    'route' => 'content/contacts',
                    'suffix' => ''
                ],
                [
                    'pattern' => '/<alias>', // for static pages
                    'route' => 'content/static',
                    'suffix' => ''
                ],
                [
                    'pattern' => 'auth/reset',
                    'route' => 'auth/reset',
                    'suffix' => ''
                ],
                [
                    'pattern' => 'auth/sign-double',
                    'route' => 'auth/sign-double',
                    'suffix' => ''
                ],
                [
                    'pattern' => 'auth/sign-up',
                    'route' => 'auth/sign-up',
                    'suffix' => ''
                ],
                [
                    'pattern'   => 'autt/<auth_key>',
                    'route'     => 'auth/registration',
                    'suffix'    => ''
                ],
                [
                    'pattern'   => 'site/login',
                    'route'     => 'content/login',
                    'suffix'    => ''
                ],
//                [
//                    'pattern' => '/auth/login/<service>',
//                    'route' => 'auth/login',
//                    'suffix' => ''
//                ],
//                [
//                    'pattern' => '/actions/post/<id:\d+>',
//                    'route' => 'shares/single',
//                    'suffix' => ''
//                ],
                [
                    'pattern'   => '<_c>/<_a>',
                    'route'     => '<_c>/<_a>',
                    'suffix'    => '',
                ],
            ],
        ],
        'language'     => 'ru-RU',
        'i18n'         => [
            'translations' => [
                '*' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
            ],
        ],
        // выключаем bootstap
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css'   => [],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js'    =>[]
                ],
            ],
        ],
    ],
    'params' => $params,
];
