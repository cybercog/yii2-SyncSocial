<?php
namespace tests\functional\components;

use Yii;
use yii\codeception\TestCase;

/**
 * Class SynchronizerTest
 * @package functional\components
 *
 *
 * @var Yii::$app->synchronizer
 */
class SynchronizerTest extends TestCase {

    /**
     * @var \xifrin\SyncSocial\components\Synchronizer
     */
    protected $synchronizer;


    public function _before(){
        $this->synchronizer = Yii::$app->synchronizer;
    }

    public function testGetServiceList() {
        $serviceListByConfig = array_keys( $this->synchronizer->settings );
        $serviceListByMethod = $this->synchronizer->getServiceList();
        $this->assertTrue( $serviceListByConfig === $serviceListByMethod );
    }

}