<?php

namespace xifrin\SyncSocial;

use Yii;
use yii\base\Object;

use OAuth\Common\Exception\Exception;
use OAuth\OAuth1\Service\ServiceInterface as OAuth1Interface;
use OAuth\OAuth2\Service\ServiceInterface as OAuth2Interface;

/**
 * Class SyncService
 * @package xifrin\SyncSocial
 */
class SyncService extends Object implements ISyncService {

    /**
     * @var \OAuth\Common\Service\ServiceInterface
     */
    protected $service;

    /**
     * @param array $service
     */
    public function __construct( $service ) {
        $this->service = $service;
    }

    /**
     * @return mixed|\OAuth\Common\Http\Uri\UriInterface
     */
    public function getAuthorizationUri() {
        return $this->service->getAuthorizationUri();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function connect() {
        if ( $this->service instanceof OAuth1Interface ) {
            $this->connectOAuth1();
        }

        if ( $this->service instanceof OAuth2Interface ) {
            $this->connectOAuth2();
        }

        return $this->isConnected();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function connectOAuth1() {

        if ( empty( $_GET['oauth_token'] ) || empty( $_GET['oauth_verifier'] ) ) {
            throw new Exception( Yii::t( 'SyncSocial', 'OAuth token must be specified' ) );
        }

        $storage = $this->service->getStorage();
        $token   = $storage->retrieveAccessToken( $this->service->service() );

        $this->service->requestAccessToken(
            $_GET['oauth_token'],
            $_GET['oauth_verifier'],
            $token->getRequestTokenSecret()
        );
    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function connectOAuth2() {
        if ( empty( $_GET['code'] ) ) {
            throw new Exception( Yii::t( 'SyncSocial', 'Code must be specified' ) );
        }

        $this->service->requestAccessToken( $_GET['code'] );
    }

    /**
     * @param $parameters
     *
     * @return bool
     */
    public function hasConnectionExtraParameters($parameters){
        return isset($parameters['user_id']);
    }

    /**
     * @return mixed
     */
    public function isConnected() {
        $storage        = $this->service->getStorage();
        $serviceName    = $this->service->service();
        $hasAccessToken = $storage->hasAccessToken( $serviceName );

        if ( $hasAccessToken ) {
            $token = $storage->retrieveAccessToken( $serviceName );
            $parameters = $token->getExtraParams();

            return ! $token->isExpired() && $this->hasConnectionExtraParameters($parameters);
        }
    }

    /**
     * @return mixed
     */
    public function disconnect() {
        $storage = $this->service->getStorage();
        $storage->clearToken( $this->service->service() );

        return $storage->hasAccessToken( $this->service->service() );
    }

    /**
     * @return mixed
     */
    public function getPosts() {
        return [ ];
    }

    /**
     * @return mixed
     */
    public function getPost() {
        return [ ];
    }

    /**
     * @return mixed
     */
    public function publishPost() {
        return [ ];
    }

}