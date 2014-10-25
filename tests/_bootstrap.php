<?php

date_default_timezone_set( 'Asia/Krasnoyarsk' );

defined( 'YII_DEBUG' ) or define( 'YII_DEBUG', true );
defined( 'YII_ENABLE_ERROR_HANDLER' ) or define( 'YII_ENABLE_ERROR_HANDLER', false );

require_once( __DIR__ . '/../vendor/autoload.php' );
require_once( __DIR__ . '/../vendor/yiisoft/yii2/Yii.php' );

$_SERVER['SERVER_NAME'] = 'localhost';

Yii::setAlias( '@tests', __DIR__  );
