<?php

namespace tests\codeception\unit\components;

use Yii;
use yii\base\ErrorException;
use yii\codeception\TestCase;
use xifrin\SyncSocial\components\Synchronizer;

/**
 * @inheritdoc
 */
class SynchonizerTest extends \yii\codeception\TestCase
{
    public $appConfig = '@tests/codeception/config/unit.php';


    public function setUp(){

    }
    public function testInitialComponent()
    {
        $settings = [
            'social_1' => [],
            'social_2' => [],
            'social_3' => [],
            'social_4' => [],
        ];

        $synchronizer = new Synchronizer( array(
            'settings' => $settings
        ) );

        $list = $synchronizer->getServiceList();
        $this->assertTrue(array_keys($settings) === $list);
    }
}