<?php

return [
    'id'         => 'Test application',
    'basePath'   => dirname( dirname( dirname( __DIR__ ) ) ),
    'components' => [
        'db'         => [
            'class'    => '\yii\db\Connection',
            'dsn'      => 'mysql:host=localhost;dbname=tests',
            'username' => 'travis',
            'password' => '',
        ],
        'i18n'       => [
            'translations' => [
                'SyncSocial' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@SyncSocial/messages',
                ],
            ],
        ],
        'urlManager' => [
            'class' => '\yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '/' => 'site/index'
            ]
        ]
    ]
];