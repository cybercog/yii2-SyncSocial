<?php
namespace unit\components;

use yii\codeception\TestCase;
use xifrin\SyncSocial\components\Synchronizer;

/**
 * Class SynchronizerTest
 * @package unit\components
 */
class SynchronizerTest extends TestCase {

    public $appConfig = '@tests/functional/_config.php';

    public function testEmptyConfiguration() {

        $this->setExpectedException( 'yii\base\Exception', 'Set model class to synchronization' );
        new Synchronizer();

        $this->setExpectedException( 'yii\base\Exception', 'Set model attribute to synchronization' );
        new Synchronizer( [
            'model' => '\fixtures\models\Record'
        ] );

        $this->assertTrue( true );
    }

}