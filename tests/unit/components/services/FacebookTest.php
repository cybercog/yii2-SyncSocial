<?php

namespace tests\unit\components\services;

use Codeception\Util\Debug;
use OAuth\OAuth2\Token\StdOAuth2Token;
use xifrin\SyncSocial\components\services\Facebook;
use Yii;
use yii\codeception\TestCase;
use Mockery;

use \OAuth\Common\Consumer\Credentials;
use \OAuth\Common\Storage\Session;
use \OAuth\ServiceFactory;

/**
 * Class SynchronizerTest
 *
 * @package tests\unit\components
 */
class FacebookTest extends TestCase {

    public $appConfig = '@tests/unit/_config.php';

    protected $storage;

    /**
     * @return array
     */
    protected function getFeedResponseSuccess() {
        return [
            'data' => [
                [
                    'id'           => 1,
                    'from'         => [
                        'id' => 2
                    ],
                    'message'      => 'test',
                    'created_time' => 'Thu Oct 23 07:00:00 +0000 2014'
                ]
            ]
        ];
    }


    /**
     * @return array
     */
    protected function postFeedResponseSuccess() {
        return [
            'id'         => 1,
            'user'       => [
                'id' => 2
            ],
            'created_at' => 'Thu Oct 23 07:00:00 +0000 2014'
        ];
    }

    public function _before() {
        if ( ! isset( $_SESSION ) ) {
            $token = new StdOAuth2Token();
            $token->setAccessToken( 'access_token' );

            $this->storage = new Session( false );
            $this->storage->storeAccessToken( 'Facebook', $token );
        }
    }

    /**
     * @return \OAuth\Common\Service\ServiceInterface
     */
    protected function buildFacebookServiceWithResponseSuccess() {

        $credentials = new Credentials( 'fakeKey', 'fakeSecret', 'fakeURL' );

        $factory = new ServiceFactory;
        $service = $factory->createService( 'Facebook', $credentials, $this->storage );

        $mock = Mockery::mock( $service );
        $mock->shouldReceive( 'request' )
             ->andReturnUsing( function ( $url, $method = 'GET' ) {

                 if ( $url == '/me/feed' and $method == 'POST' ) {
                     return json_encode( $this->postFeedResponseSuccess() );
                 } elseif ( $url == '/me/feed' and $method == 'GET' ) {
                     return json_encode( $this->getFeedResponseSuccess() );
                 }

             } );

        return $mock;
    }


    /**
     * @return \OAuth\Common\Service\ServiceInterface
     */
    protected function buildFacebookServiceWithResponseEmpty() {

        $credentials = new Credentials( 'fakeKey', 'fakeSecret', 'fakeURL' );

        $factory = new ServiceFactory;
        $service = $factory->createService( 'Facebook', $credentials, $this->storage );

        $mock = Mockery::mock( $service );
        $mock->shouldReceive( 'request' )
             ->andReturnUsing( function ( $url, $method = 'GET' ) {
                 return json_encode( [ ] );
             } );

        return $mock;
    }

    public function testPublishPostSuccess() {

        $service = $this->buildFacebookServiceWithResponseSuccess();
        $sync    = new Facebook( $service );

        $response = $this->postFeedResponseSuccess();
        $result   = $sync->publishPost( 'message' );

        $this->assertTrue( $result['service_id_post'] == $response['id'] );
        $this->assertTrue( $result['time_created'] == strtotime( $response['created_at'] ) );
        $this->assertTrue( $result['service_id_author'] == $response['user']['id'] );

    }


    public function testPublishPostEmpty() {

        $service = $this->buildFacebookServiceWithResponseEmpty();
        $sync    = new Facebook( $service );
        $result  = $sync->publishPost( 'message' );

        $this->assertTrue( $result === [ ] );

    }


    public function testGetPostsSuccess() {

        $service = $this->buildFacebookServiceWithResponseSuccess();
        $sync    = new Facebook( $service );

        $response = $this->getFeedResponseSuccess();
        $result   = $sync->getPosts();

        $this->assertTrue( $result[0]['service_id_post'] == $response['data'][0]['id'] );
        $this->assertTrue( $result[0]['service_id_author'] == $response['data'][0]['from']['id'] );
        $this->assertTrue( $result[0]['content'] == $response['data'][0]['message'] );
        $this->assertTrue( $result[0]['time_created'] == strtotime( $response['data'][0]['created_time'] ) );

    }


    public function testGetPostsEmpty() {

        $service = $this->buildFacebookServiceWithResponseEmpty();
        $sync    = new Facebook( $service );
        $result  = $sync->getPosts();

        $this->assertTrue( $result === [ ] );

    }


}