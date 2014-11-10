<?php

namespace tests\unit\components;

use Codeception\Util\Debug;
use Yii;
use yii\codeception\TestCase;

use \OAuth\Common\Consumer\Credentials;
use \OAuth\Common\Storage\Session;
use \OAuth\ServiceFactory;
use \xifrin\SyncSocial\SyncService;


/**
 * require Test service classes bypassing php autoloader
 */
require_once dirname( __DIR__ ) . '/models/TestOAuth1Service.php';
require_once dirname( __DIR__ ) . '/models/TestOAuth2Service.php';

/**
 * Class SynchronizerTest
 *
 * @package tests\unit\components
 */
class SyncServiceTest extends TestCase {

    public $appConfig = '@tests/unit/_config.php';

    /**
     * @return \OAuth\Common\Service\ServiceInterface
     */
    private function buildOAuth1Service() {
        $credentials = new Credentials( 'fakeKey', 'fakeSecret', 'fakeURL' );
        $storage     = new Session();

        $factory = new ServiceFactory;

        $service = $factory->createService( 'TestOAuth1Service', $credentials, $storage );

        return $service;
    }

    /**
     * @return \OAuth\Common\Service\ServiceInterface
     */
    private function buildOAuth2Service() {
        $credentials = new Credentials( 'fakeKey', 'fakeSecret', 'fakeURL' );
        $storage     = new Session();

        $factory = new ServiceFactory;

        $service = $factory->createService( 'TestOAuth2Service', $credentials, $storage );

        return $service;
    }


    public function testSyncServiceGetName() {

        $service = $this->buildOAuth2Service();
        $sync    = new SyncService( $service );
        $this->assertTrue( $service->service() === $sync->getName() );

        $service = $this->buildOAuth1Service();
        $sync    = new SyncService( $service );
        $this->assertTrue( $service->service() === $sync->getName() );

    }


    public function testGetAuthorizationUri() {

        $service = $this->buildOAuth2Service();
        $sync    = new SyncService( $service );
        $this->assertTrue( $service->getAuthorizationUri() == $sync->getAuthorizationUri() );

        $service = $this->buildOAuth1Service();
        $sync    = new SyncService( $service );
        $this->assertTrue( $service->getAuthorizationUri() == $sync->getAuthorizationUri() );
    }


    public function testHasConnectionExtraParameters() {


        $service = $this->buildOAuth2Service();
        $sync    = new SyncService( $service );
        $this->assertTrue( $sync->hasConnectionExtraParameters( [ ] ) == false );
        $this->assertTrue( $sync->hasConnectionExtraParameters( [ 'user_id' => 1 ] ) == true );

    }

    public function testNoConnectionOAuth2() {

        $this->setExpectedException( 'yii\base\Exception', 'Code must be specified' );
        $service = $this->buildOAuth2Service();
        $sync    = new SyncService( $service );
        $sync->connect();

        $this->assertTrue( true );
    }


    public function testNoConnectionOAuth1() {

        $this->setExpectedException( 'yii\base\Exception', 'OAuth token must be specified' );
        $service = $this->buildOAuth1Service();
        $sync    = new SyncService( $service );
        $sync->connect();

        $this->assertTrue( true );
    }


    public function testReturnType() {

        $service = $this->buildOAuth1Service();
        $sync    = new SyncService( $service );

        $this->assertTrue( is_array( $sync->getPosts() ) );
        $this->assertTrue( is_array( $sync->publishPost('message') ) );
    }


}