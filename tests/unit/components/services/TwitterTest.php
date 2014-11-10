<?php

namespace tests\unit\components\services;

use Codeception\Util\Debug;
use OAuth\OAuth2\Token\StdOAuth2Token;
use xifrin\SyncSocial\components\services\Twitter;
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
class TwitterTest extends TestCase {

    public $appConfig = '@tests/unit/_config.php';

    protected $storage;

    /**
     * @return array
     */
    protected function getFeedResponseSuccess() {
        return [
            [
                'id'         => 1,
                'user'       => [
                    'id' => 2
                ],
                'text'       => 'test',
                'created_at' => 'Thu Oct 23 07:00:00 +0000 2014'
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
        $token = new StdOAuth2Token();
        $token->setAccessToken( 'access_token' );

        $this->storage = new Session( false );
        $this->storage->storeAccessToken( 'Twitter', $token );
    }

    /**
     * @return \OAuth\Common\Service\ServiceInterface
     */
    protected function buildTwitterServiceWithResponseSuccess() {

        $credentials = new Credentials( 'fakeKey', 'fakeSecret', 'fakeURL' );

        $factory = new ServiceFactory;
        $service = $factory->createService( 'Twitter', $credentials, $this->storage );

        $mock = Mockery::mock( $service );
        $mock->shouldReceive( 'request' )
             ->andReturnUsing( function ( $url, $method = 'GET' ) {

                 if ( $url == 'statuses/update.json' and $method == 'POST' ) {
                     return json_encode( $this->postFeedResponseSuccess() );
                 } elseif ( $url == 'statuses/user_timeline.json' and $method == 'GET' ) {
                     return json_encode( $this->getFeedResponseSuccess() );
                 }

             } );


        $mock->shouldReceive( 'requestRequestToken' )
             ->andReturnUsing( function () {
                 return $this->getMock('OAuth\OAuth1\Token\StdOAuth1Token', [
                     'getRequestToken' => 'requestTokenValue'
                 ]);
             } );

        return $mock;
    }


    /**
     * @return \OAuth\Common\Service\ServiceInterface
     */
    protected function buildTwitterServiceWithResponseEmpty() {

        $credentials = new Credentials( 'fakeKey', 'fakeSecret', 'fakeURL' );
        $storage     = new Session();

        $factory = new ServiceFactory;
        $service = $factory->createService( 'Twitter', $credentials, $storage );

        $mock = Mockery::mock( $service );
        $mock->shouldReceive( 'request' )
             ->andReturnUsing( function ( $url, $method = 'GET' ) {
                 return json_encode( [ ] );
             } );

        return $mock;
    }

    public function testPublishPostSuccess() {

        $service = $this->buildTwitterServiceWithResponseSuccess();
        $sync    = new Twitter( $service );

        $response = $this->postFeedResponseSuccess();
        $result   = $sync->publishPost( 'message' );

        $this->assertTrue( $result['service_id_post'] == $response['id'] );
        $this->assertTrue( $result['service_id_author'] == $response['user']['id'] );
        $this->assertTrue( $result['time_created'] == strtotime( $response['created_at'] ) );

    }


    public function testPublishPostEmpty() {

        $service = $this->buildTwitterServiceWithResponseEmpty();
        $sync    = new Twitter( $service );
        $result  = $sync->publishPost( 'message' );

        $this->assertTrue( $result === [ ] );

    }


    public function testGetPostsSuccess() {

        $service = $this->buildTwitterServiceWithResponseSuccess();
        $sync    = new Twitter( $service );

        $response = $this->getFeedResponseSuccess();
        $result   = $sync->getPosts();

        $this->assertTrue( $result[0]['service_id_post'] == $response[0]['id'] );
        $this->assertTrue( $result[0]['service_id_author'] == $response[0]['user']['id'] );
        $this->assertTrue( $result[0]['content'] == $response[0]['text'] );
        $this->assertTrue( $result[0]['time_created'] == strtotime( $response[0]['created_at'] ) );

    }


    public function testGetPostsEmpty() {

        $service = $this->buildTwitterServiceWithResponseEmpty();
        $sync    = new Twitter( $service );
        $result  = $sync->getPosts();

        $this->assertTrue( $result === [ ] );

    }


    public function testGetAuthorizationUri() {
        $service = $this->buildTwitterServiceWithResponseSuccess();
        $sync    = new Twitter( $service );

        $this->assertTrue( true );
    }

}