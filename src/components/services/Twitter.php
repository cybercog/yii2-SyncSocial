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
     * @return mixed
     */
    public function getPosts($limit = 200 ) {

        $response = $this->service->request('statuses/home_timeline.json');
        $result = json_decode($response);

    }
}