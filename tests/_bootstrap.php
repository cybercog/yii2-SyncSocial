<?php

date_default_timezone_set( 'Asia/Krasnoyarsk' );

error_reporting( E_ALL );
ini_set( 'display_errors', '1' );
ini_set( 'display_startup_errors', 1 );

defined( 'YII_DEBUG' ) or define( 'YII_DEBUG', true );
defined( 'YII_ENABLE_ERROR_HANDLER' ) or define( 'YII_ENABLE_ERROR_HANDLER', false );
defined('YII_ENV') or define('YII_ENV', 'dev');

require_once( __DIR__ . '/../vendor/autoload.php' );
require_once( __DIR__ . '/../vendor/yiisoft/yii2/Yii.php' );

$_SERVER['SERVER_NAME'] = 'localhost';

Yii::setAlias( '@tests', __DIR__  );
