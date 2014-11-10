<?php
namespace tests\functional\components;

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
 * @package tests\functional\components
 */
class SyncServiceTest extends TestCase {

    public $appConfig = '@tests/functional/_config.php';

    /**
     * @return \OAuth\Common\Service\ServiceInterface
     */
    private function buildOAuth1Service() {
        $credentials = new Credentials( 'fakeKey', 'fakeSecret', 'fakeURL' );
        $storage     = new Session();

        $factory = new ServiceFactory;

        return $factory->createService( 'TestOAuth1Service', $credentials, $storage );
    }

    /**
     * @return \OAuth\Common\Service\ServiceInterface
     */
    private function buildOAuth2Service() {
        $credentials = new Credentials( 'fakeKey', 'fakeSecret', 'fakeURL' );
        $storage     = new Session();

        $factory = new ServiceFactory;

        return $factory->createService( 'TestOAuth2Service', $credentials, $storage );
    }

    public function testSyncServiceGetName() {

        $service = $this->buildOAuth2Service();
        $sync  = new SyncService($service);
        $this->assertTrue( $service->service() === $sync->getName() );

        $service = $this->buildOAuth1Service();
        $sync  = new SyncService($service);
        $this->assertTrue( $service->service() === $sync->getName() );

    }


    public function testGetAuthorizationUri(){

        $service = $this->buildOAuth2Service();
        $sync  = new SyncService($service);
        $this->assertTrue( $service->getAuthorizationUri() == $sync->getAuthorizationUri() );

        $service = $this->buildOAuth1Service();
        $sync  = new SyncService($service);
        $this->assertTrue( $service->getAuthorizationUri() == $sync->getAuthorizationUri() );
    }

}