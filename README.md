yii2-SyncSocial [alpha]
===============


### Configuration

Add the following in your config:

```php
'components' => array(
        // ..
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