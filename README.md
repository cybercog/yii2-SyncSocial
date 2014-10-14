yii2-SyncSocial [alpha]
===============


Extension supports these social networks:

* [facebook](https://facebook.com)
* [twitter](https://twitter.com)
* [vkontakte](https://vk.com)

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
                'facebook'   => [
                    'connection' => [
                        'key'     => 'YOUR_FACEBOOK_APP_KEY',
                        'secret'  => 'YOUR_FACEBOOK_APP_SECRET'
                    ]
                ],
                'vkontakte' => [
                    'connection'  => [
                        'key'     => 'YOUR_VKONTAKTE_APP_KEY',
                        'secret'  => 'YOUR_VKONTAKTE_APP_SECRET',
                    ]
                ],
                'twitter'   => [
                    'connection' => [
                        'key'     => 'YOUR_TWITTER_APP_KEY',
                        'secret'  => 'YOUR_TWITTER_APP_SECRET'
                    ]
                ],
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