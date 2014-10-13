yii2-SyncSocial [alpha]
===============


Extension supports these social networks:

* vkotakte
* twitter

### Configuration


Add the following in your config:

```php
'components' => array(
        // ..

        // add Synchronizer component
        'synchronizer' => [
            'class'       => 'xifrin\SyncSocial\components\Synchronizer',
            'model'       => '\app\models\Post',
            'services'    => [
                'vkontakte' => [
                    'connection'  => [
                        'client_secret' => 'YOUR_VKONTAKTE_APP_ID',
                        'client_id'     => 'YOUR_VKONTAKTE_APP_KEY',
                    ]
                ],
                'twitter'   => [
                    'connection' => [
                        'client_id'     => 'YOUR_TWITTER_APP_ID',
                        'client_secret' => 'YOUR_TWITTER_APP_KEY'
                    ]
                ]
            ],
            'callbackUrl' => function ( $service ) {
                return Yii::$app->urlManager->createAbsoluteUrl( [ 'admin/sync/connect', 'service' => $service ] );
            }
        ]

        // ..

        // add Synchronizer's messages
        'i18n' => array(
            'translations' => array(
                // ..
                'SyncSocial' => array(
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@SyncSocial/messages',
                ),
                // ..
            ),
        )

        // ..
    ),
```



Add the following in your controller:
```php
        public function actions() {
            return [
                'connect' => [
                    'class'      => 'xifrin\SyncSocial\actions\ConnectAction',
                    'successUrl' => 'admin/sync/index',
                    'failedUrl'  => 'admin/sync/index'
                ],
                'run'     => [
                    'class' => 'xifrin\SyncSocial\actions\RunAction'
                ]
            ];
        }
```