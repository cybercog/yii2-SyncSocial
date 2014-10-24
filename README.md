yii2-SyncSocial [alpha]
=======================

[![Latest Stable Version](https://poser.pugx.org/xifrin/yii2-SyncSocial/v/stable.png)](https://packagist.org/packages/xifrin/yii2-SyncSocial)
[![Travis Status](https://travis-ci.org/xifrin/yii2-SyncSocial.svg?branch=master)](https://travis-ci.org/xifrin/yii2-SyncSocial)
[![Coverage Status](https://coveralls.io/repos/xifrin/yii2-SyncSocial/badge.png)](https://coveralls.io/r/xifrin/yii2-SyncSocial)

Extension synchronizes your Active Record model with social networks.
It helps you collect social networks' text posts and publish new post to all connected social networks.

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
                return Yii::$app->urlManager->createAbsoluteUrl( [
                    'admin/sync/connect',
                    'service' => $service
                ] );
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
                    'class' => 'xifrin\SyncSocial\actions\ConnectAction',
                    // 'successUrl' => 'YOUR_CUSTOM_SUCCESS_URL',
                    // 'failedUrl' => 'YOUR_CUSTOM_FAILED_URL'
                ],
                'disconnect' => [
                    'class' => 'xifrin\SyncSocial\actions\ConnectAction',
                    // 'successUrl' => 'YOUR_CUSTOM_SUCCESS_URL',
                    // 'failedUrl' => 'YOUR_CUSTOM_FAILED_URL'
                ],
                'run' => [
                    'class' => 'xifrin\SyncSocial\actions\RunAction',
                    // 'successUrl' => 'YOUR_CUSTOM_SUCCESS_URL',
                    // 'failedUrl' => 'YOUR_CUSTOM_FAILED_URL'
                ]
            ];
        }
```

Add the following in your model:


```php
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSyncSocial()
    {
        return $this->hasOne('xifrin\SyncSocial\models\SyncRecord', ['model_id' => 'id']);
    }
```

Run migration to create table for sync related model:

```bash
php app/yiic.php migrate --migrationPath='@vendor/xifrin/yii2-syncsocial/src/migrations'
```