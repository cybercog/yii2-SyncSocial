<?php

namespace ifrin\SyncSocial;

use Yii;
use yii\base\Exception;
use yii\base\Object;
use OAuth\OAuth1\Service\ServiceInterface as OAuth1Interface;
use OAuth\OAuth2\Service\ServiceInterface as OAuth2Interface;

/**
 * Class SyncService
 * @package ifrin\SyncSocial
 */
class SyncService extends Object implements ISyncService {

    /**
     * @var \OAuth\Common\Service\AbstractService
     */
    protected $service;

    /**
     * @var
     */
    protected $options = [ ];

    /**
     * @param \OAuth\Common\Service\AbstractService $service
     * @param array $options
     */
    public function __construct( $service, $options = [ ] ) {
        $this->service = $service;
        $this->options = $options;
    }

    /**
     * @return \OAuth\Common\Http\Uri\UriInterface
     */
    public function getAuthorizationUri() {
        return $this->service->getAuthorizationUri();
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->service->service();
    }

    /**
     * @return boolean|null
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

        /**
         * @var \OAuth\OAuth1\Service\AbstractService $service
         */

        if ( empty( $_GET['oauth_token'] ) || empty( $_GET['oauth_verifier'] ) ) {
            throw new Exception( Yii::t( 'SyncSocial', 'OAuth token must be specified' ) );
        }

        $service = $this->service;
        $storage = $service->getStorage();
        $token   = $storage->retrieveAccessToken( $service->service() );

        $service->requestAccessToken(
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
        /**
         * @var \OAuth\OAuth2\Service\AbstractService $service
         */

        if ( empty( $_GET['code'] ) ) {
            throw new Exception( Yii::t( 'SyncSocial', 'Code must be specified' ) );
        }

        $service = $this->service;
        $service->requestAccessToken( $_GET['code'] );
    }

    /**
     * @param $parameters
     *
     * @return bool
     */
    public function hasConnectionExtraParameters( $parameters ) {
        return isset( $parameters['user_id'] );
    }

    /**
     * @return boolean|null
     */
    public function isConnected() {
        $storage        = $this->service->getStorage();
        $serviceName    = $this->service->service();
        $hasAccessToken = $storage->hasAccessToken( $serviceName );

        if ( $hasAccessToken ) {
            $token      = $storage->retrieveAccessToken( $serviceName );
            $parameters = $token->getExtraParams();

            return ! $token->isExpired() && $this->hasConnectionExtraParameters( $parameters );
        }
    }

    /**
     * @return boolean
     */
    public function disconnect() {
        $storage = $this->service->getStorage();
        $storage->clearToken( $this->service->service() );

        return $storage->hasAccessToken( $this->service->service() );
    }

    /**
     *  Example of return response:
     *      return [
     *          [
     *              'service_id_author' => '2000',
     *              'service_id_post'   => '1000',
     *              'time_created'      => 12345
     *          ]
     *     ];
     *
     * @return array
     */
    public function getPosts() {
        return [ ];
    }

    /**
     * @param $message
     * @param null $url
     *
     *  Example of return response:
     *      return [
     *          'service_id_author' => '2000',
     *          'service_id_post'   => '1000',
     *          'service_language'  => 'ru',
     *          'time_created'      => strtotime( 'Thu Oct 23 07:00:00 +0000 2014' ),
     *      ];
     *
     * @return array
     */
    public function publishPost( $message, $url = null ) {
        return [ ];
    }

}