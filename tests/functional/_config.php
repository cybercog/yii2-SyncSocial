<?php

return [
    'id'         => 'Test application',
    'basePath'   => dirname( dirname( dirname( __DIR__ ) ) ),
    'components' => [
        'db'           => array(
            'class' => '\yii\db\Connection',
            'dsn' => 'sqlite:' . dirname( __FILE__ ) . '/../_data/database.sqlite',
        ),
        'i18n'         => [
            'translations' => [
                'SyncSocial' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@SyncSocial/messages',
                ],
            ],
        ],
        'synchronizer' => [
            'class'           => '\xifrin\SyncSocial\components\Synchronizer',
            'model'           => '\tests\models\Record',
            'settings'        => [
                'facebook'  => [
                    'connection' => [
                        'key'    => 'YOUR_FACEBOOK_APP_KEY',
                        'secret' => 'YOUR_FACEBOOK_APP_SECRET'
                    ],
                ],
                'vkontakte' => [
                    'connection' => [
                        'key'    => 'YOUR_VKONTAKTE_APP_KEY',
                        'secret' => 'YOUR_VKONTAKTE_APP_SECRET'
                    ]
                ],
                'twitter'   => [
                    'connection' => [
                        'key'    => 'YOUR_TWITTER_APP_KEY',
                        'secret' => 'YOUR_TWITTER_APP_SECRET'
                    ]
                ]
            ],
            'absolutePostUrl' => function ( $service, $id_post ) {
                return Yii::$app->urlManager->createAbsoluteUrl( [
                    'default/post/view',
                    'id' => $id_post
                ] );
            },
            'connectUrl'      => function ( $service ) {
                return Yii::$app->urlManager->createUrl( [ 'admin/sync/connect', 'service' => $service ] );
            },
            'disconnectUrl'   => function ( $service ) {
                return Yii::$app->urlManager->createUrl( [ 'admin/sync/disconnect', 'service' => $service ] );
            },
            'syncUrl'         => function ( $service ) {
                return Yii::$app->urlManager->createUrl( [ 'admin/sync/sync', 'service' => $service ] );
            }
        ]
    ]
];