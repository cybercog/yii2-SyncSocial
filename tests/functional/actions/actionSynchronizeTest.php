<?php

namespace tests\functional\components;

use tests\fixtures\RecordFixture;
use yii\codeception\TestCase;

/**
 * Class SynchronizerTest
 * @package unit\components
 */
class actionSynchronizeTest extends TestCase {

    /**
     * @var string
     */
    public $appConfig = '@tests/functional/_config.php';

    /**
     * @return array
     */
    public function fixtures()
    {
        return [
            'records' => RecordFixture::className(),
        ];
    }


    public function testEmptyConfiguration() {

        $this->assertTrue( true );
    }
}