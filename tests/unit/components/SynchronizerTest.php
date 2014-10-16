<?php
namespace components;

use yii\codeception\TestCase;
use xifrin\SyncSocial\components\Synchronizer;

class SynchronizerTest extends TestCase
{
   /**
    * @var \UnitTester
    */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {

    }

    // tests
    public function testSimple()
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