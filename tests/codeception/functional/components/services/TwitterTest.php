<?php

namespace tests\codeception\unit\components;

use Yii;
use yii\base\ErrorException;
use yii\codeception\TestCase;
use xifrin\SyncSocial\components\Synchronizer;
use xifrin\SyncSocial\components\services\Twitter;

/**
 * @inheritdoc
 */
class TwitterTest extends TestCase {
    public $appConfig = '@tests/codeception/config/functional.php';

    public function setUp() {

    }

    public function testInitialComponent() {
        $synchronizer = new Synchronizer( [
                'settings' => [
                    'twitter' => [
                        'connection' => [
                            'key'    => '',
                            'secret' => ''
                        ]
                    ]
                ]
            ]
        );

        $service = $synchronizer->getService( 'twitter' );
        
        // @TODO: mock class
        // $url     = $service->getAuthorizationUri();
        // $this->assertTrue( $url != '' );

    }
}