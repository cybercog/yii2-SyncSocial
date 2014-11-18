### Configuration

All commented lines is written to show full possible config. You could delete them.

Add the following in your config:

```php
'components' => array(
        // ..

        // add Synchronizer component
        'synchronizer' => [
            'class'       => '\ifrin\SyncSocial\components\Synchronizer',
            'model'       => '\app\models\Post',
            // 'attribute'   => 'content',
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
            /*
            'absolutePostUrl' => function ( $service, $id_post ) {
                return Yii::$app->urlManager->createAbsoluteUrl( [
                    'default/post/view',
                    'id' => $id_post
                ] );
            },
            'connectUrl' => function ( $service ) {
                return Yii::$app->urlManager->createUrl( [
                    'admin/sync/connect',
                    'service' => $service
                ] );
            },
            'disconnectUrl' => function ( $service ) {
                return Yii::$app->urlManager->createUrl( [
                    'admin/sync/disconnect',
                    'service' => $service
                ] );
            },
            'syncUrl' => function ( $service ) {
                return Yii::$app->urlManager->createUrl( [
                    'admin/sync/sync',
                    'service' => $service
                ] );
            }
            */
        ],

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
                    'class' => 'ifrin\SyncSocial\actions\ConnectAction',
                    // 'successUrl' => 'YOUR_CUSTOM_SUCCESS_URL',
                    // 'failedUrl' => 'YOUR_CUSTOM_FAILED_URL'
                ],
                'disconnect' => [
                    'class' => 'ifrin\SyncSocial\actions\ConnectAction',
                    // 'successUrl' => 'YOUR_CUSTOM_SUCCESS_URL',
                    // 'failedUrl' => 'YOUR_CUSTOM_FAILED_URL'
                ],
                'sync' => [
                    'class' => 'ifrin\SyncSocial\actions\SyncAction',
                    // 'successUrl' => 'YOUR_CUSTOM_SUCCESS_URL',
                    // 'failedUrl' => 'YOUR_CUSTOM_FAILED_URL'
                ]
            ];
        }
```

Add the following in your model:


```php
    //..

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSyncModel()
    {
        return $this->hasOne('\ifrin\SyncSocial\models\SyncModel', ['model_id' => 'id']);
    }

    //..


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            //..
            \ifrin\SyncSocial\behaviors\SynchronizerBehavior::className(),
            //..
        ];
    }

    //..
```

Run migration to create table for sync related model:

```bash
php app/yiic.php migrate --migrationPath='@vendor/ifrin/yii2-syncsocial/src/migrations'
```