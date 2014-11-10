<?php
namespace tests\functional\components;

use Codeception\Util\Debug;
use Yii;
use yii\codeception\TestCase;

use xifrin\SyncSocial\components\Synchronizer;

/**
 * Class SynchronizerTest
 *
 * @package tests\functional\components
 */
class SynchronizerTest extends TestCase {

    public $appConfig = '@tests/functional/_config.php';


    public function testEmptyClassModelInit() {
        $this->setExpectedException( 'yii\base\Exception', 'Set model class to synchronization' );
        new Synchronizer();
        $this->assertTrue( true );
    }


    public function testNonExistsAttributeInit() {

        $this->setExpectedException( 'yii\base\Exception', 'Set model attribute to synchronization' );
        new Synchronizer( [
            'model'     => '\tests\models\Record',
            'attribute' => 'non_exists_attribute'
        ] );
        $this->assertTrue( true );
    }


    public function testGetServiceList() {

        $synchronizer = new Synchronizer( [
            'model'    => '\tests\models\Record',
            'settings' => [
                'provider_1' => [ ],
                'provider_2' => [ ],
                'provider_3' => [ ]
            ]
        ] );

        $serviceListByConfig = array_keys( $synchronizer->settings );
        $serviceListByMethod = $synchronizer->getServiceList();
        $this->assertTrue( $serviceListByConfig === $serviceListByMethod );
    }


    public function testSynchronizerUrlClosure() {

        $synchronizer = new Synchronizer( [
            'model'         => '\tests\models\Record',
            'settings'      => [
                'provider' => [ ]
            ],
            'connectUrl'    => function ( $service ) {
                return Yii::$app->urlManager->createUrl( [ 'action/connect', 'service' => $service ] );
            },
            'disconnectUrl' => function ( $service ) {
                return Yii::$app->urlManager->createUrl( [ 'action/disconnect', 'service' => $service ] );
            },
            'syncUrl'       => function ( $service ) {
                return Yii::$app->urlManager->createUrl( [ 'action/sync', 'service' => $service ] );
            }
        ] );

        $this->assertTrue( $synchronizer->getConnectUrl( 'provider' ) == './action/connect?service=provider' );
        $this->assertTrue( $synchronizer->getDisconnectUrl( 'provider' ) == './action/disconnect?service=provider' );
        $this->assertTrue( $synchronizer->getSyncUrl( 'provider' ) == './action/sync?service=provider' );
    }


    public function testSynchronizerUrlEmpty() {

        $synchronizer = new Synchronizer( [
            'model' => '\tests\models\Record'
        ] );

        $this->assertTrue( $synchronizer->getConnectUrl( 'provider' ) == null );
        $this->assertTrue( $synchronizer->getDisconnectUrl( 'provider' ) == null );
        $this->assertTrue( $synchronizer->getSyncUrl( 'provider' ) == null );
    }


    public function testGetNonExistsService() {

        $synchronizer = new Synchronizer( [
            'model'    => '\tests\models\Record',
            'settings' => [
                'provider' => [ ]
            ]
        ] );

        $this->assertTrue( $synchronizer->getService( 'non_exists_provider' ) == null );
    }


    public function testGetExistsService() {

        $synchronizer = new Synchronizer( [
            'model'    => '\tests\models\Record',
            'settings' => [
                'facebook' => [ ]
            ]
        ] );

        $service = $synchronizer->getService( 'facebook' );

        $this->assertTrue( $service !== null );
    }

}