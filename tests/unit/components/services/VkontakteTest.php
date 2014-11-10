<?php

namespace tests\unit\components\services;

use Codeception\Util\Debug;
use OAuth\OAuth2\Token\StdOAuth2Token;
use xifrin\SyncSocial\components\services\Vkontakte;
use Yii;
use yii\codeception\TestCase;
use Mockery;

use \OAuth\Common\Consumer\Credentials;
use \OAuth\Common\Storage\Session;
use \OAuth\ServiceFactory;

/**
 * Class VkontakteTest
 *
 * @package tests\unit\components\services
 */
class VKontakteTest extends TestCase {

    public $appConfig = '@tests/unit/_config.php';

    protected $storage;

    /**
     * @return array
     */
    protected function getWallResponseSuccess() {
        return [
            'response' => [
                [
                    'id'      => 1,
                    'from_id' => 2,
                    'text'    => 'test',
                    'date'    => time()
                ]
            ]
        ];
    }

    public function _before() {
        $token = new StdOAuth2Token();
        $token->setAccessToken( 'access_token' );

        $this->storage = new Session( false );
        $this->storage->storeAccessToken( 'VKontakte', $token );
    }

    /**
     * @return \OAuth\Common\Service\ServiceInterface
     */
    protected function buildVKontakteServiceWithResponseSuccess() {

        $credentials = new Credentials( 'fakeKey', 'fakeSecret', 'fakeURL' );

        $factory = new ServiceFactory;
        $service = $factory->createService( 'Vkontakte', $credentials, $this->storage );

        $mock = Mockery::mock( $service );
        $mock->shouldReceive( 'request' )
             ->andReturnUsing( function ( $url, $method = 'GET' ) {

                 if ( strpos($url, 'wall.get') == 0 and $method == 'GET' ) {
                     return json_encode( $this->getWallResponseSuccess() );
                 }
             } );


        return $mock;
    }


    /**
     * @return \OAuth\Common\Service\ServiceInterface
     */
    protected function buildVKontakteServiceWithResponseEmpty() {

        $credentials = new Credentials( 'fakeKey', 'fakeSecret', 'fakeURL' );

        $factory = new ServiceFactory;
        $service = $factory->createService( 'Vkontakte', $credentials, $this->storage );

        $mock = Mockery::mock( $service );
        $mock->shouldReceive( 'request' )
             ->andReturnUsing( function ( $url, $method = 'GET' ) {
                 return json_encode( [ ] );
             } );

        return $mock;
    }


    public function testGetPostsSuccess() {

        $service = $this->buildVKontakteServiceWithResponseSuccess();
        $sync    = new Vkontakte( $service );

        $response = $this->getWallResponseSuccess();
        $result   = $sync->getPosts();

        $this->assertTrue( $result[0]['service_id_post'] == $response['response'][0]['id'] );
        $this->assertTrue( $result[0]['service_id_author'] == $response['response'][0]['from_id'] );
        $this->assertTrue( $result[0]['content'] == $response['response'][0]['text'] );
        $this->assertTrue( $result[0]['time_created'] == $response['response'][0]['date'] );

    }


    public function testGetPostsEmpty() {

        $service = $this->buildVKontakteServiceWithResponseEmpty();
        $sync    = new VKontakte( $service );
        $result  = $sync->getPosts();

        $this->assertTrue( $result === [ ] );

    }

}