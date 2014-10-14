<?php

namespace xifrin\SyncSocial\components\services;

use OAuth\Common\Exception\Exception;
use xifrin\SyncSocial\SyncService;

/**
 * Class Twitter
 * @package xifrin\SyncSocial\components\services
 */
class Twitter extends SyncService {

    /**
     * @var \OAuth\OAuth1\Service\Twitter
     */
    protected $service;

    /**
     * @return mixed|\OAuth\Common\Http\Uri\UriInterface
     */
    public function getAuthorizationUri() {
        $token = $this->service->requestRequestToken();

        return $this->service->getAuthorizationUri( array(
            'oauth_token' => $token->getRequestToken()
        ) );
    }

    /**
     * @return mixed|\OAuth\Common\Token\TokenInterface|\OAuth\OAuth1\Token\TokenInterface|string
     * @throws Exception
     */
    public function getAccessToken() {
        if ( empty( $_GET['oauth_token'] ) || empty( $_GET['oauth_verifier'] ) ) {
            throw new Exception( "Oauth token must be specified" );
        }

        $storage = $this->service->getStorage();
        $token   = $storage->retrieveAccessToken( $this->service->service() );

        return $this->service->requestAccessToken(
            $_GET['oauth_token'],
            $_GET['oauth_verifier'],
            $token->getRequestTokenSecret()
        );
    }

    /**
     * @return mixed
     */
    public function getPosts($limit = 200 ) {

        $response = $this->service->request('statuses/home_timeline');


    }
}