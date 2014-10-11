yii2-SyncSocial
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