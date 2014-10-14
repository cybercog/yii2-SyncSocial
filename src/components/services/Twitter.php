<?php

namespace xifrin\SyncSocial\components\services;

use Yii;

use OAuth\ServiceFactory;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

use yii\base\Object;
use xifrin\SyncSocial\iNetwork;

/**
 * Class Twitter
 * @package xifrin\SyncSocial\components\networks
 */
class Twitter extends Object implements INetwork {

    /**
     * @var VK
     */
    protected $service;

    /**
     * @var Session
     */
    protected $storage;

    /**
     * @var array
     */
    public $settings = array();

    /**
     * @param array $settings
     */
    public function __construct( array $settings = array() ) {

        $connection = isset($settings['connection']) ? $settings['connection'] : [];

        $credentials = new Credentials(
            isset( $connection['key'] ) ? $connection['key'] : null,
            isset( $connection['secret'] ) ? $connection['secret'] : null,
            isset( $connection['callback_url'] ) ? $connection['callback_url'] : null
        );

        $serviceFactory = new ServiceFactory();

        $this->storage = new Session();
        $this->service = $serviceFactory->createService('twitter', $credentials, $this->storage);
        $this->settings = $settings;
    }

    /**
     * @return mixed
     * @throws \OAuth\Common\Storage\Exception\TokenNotFoundException
     */
    public function getAccessToken() {

        $token = $this->storage->retrieveAccessToken('Twitter');

        // This was a callback request from twitter, get the token
        $this->service->requestAccessToken(
            $_GET['oauth_token'],
            $_GET['oauth_verifier'],
            $token->getRequestTokenSecret()
        );

        // Send a request now that we have access token
        $result = json_decode($this->service->request('account/verify_credentials.json'));

        print_r($result);

        die();
    }


    /**
     * @return mixed|\OAuth\Common\Http\Uri\UriInterface
     */
    public function getAuthorizeURL() {
        $token = $this->service->requestRequestToken();
        return $this->service->getAuthorizationUri(array(
            'oauth_token' => $token->getRequestToken()
        ));
    }

    /**
     * @return mixed
     */
    public function getPosts() {

    }

    /**
     * @return mixed
     */
    public function getPost() {

    }

    /**
     * @return mixed
     */
    public function publishPost() {

    }

    /**
     * @return mixed
     */
    public function deletePost() {

    }
}