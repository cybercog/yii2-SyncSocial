<?php

namespace tests\codeception\unit\components;

use Yii;
use yii\base\ErrorException;
use yii\codeception\TestCase;
use xifrin\SyncSocial\components\services\Twitter;

/**
 * @inheritdoc
 */
class TwitterTest extends \yii\codeception\TestCase
{
    public $appConfig = '@tests/codeception/config/functional.php';


    public function setUp(){

    }
    public function testInitialComponent()
    {
        $service = new Twitter([
            'connection' => [
                'key' => '',
                'secret' => ''
            ]
        ]);

        echo $service->getAuthorizeURL();

    }
}