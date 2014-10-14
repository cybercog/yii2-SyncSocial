<?php

namespace xifrin\SyncSocial;

use OAuth\Common\Exception\Exception;
use Yii;
use yii\base\Object;

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
    public function getAccessToken() {
        if ( empty( $_GET['code'] ) ) {
            throw new Exception( "Code must be specified" );
        }

        return $this->service->requestAccessToken( $_GET['code'] );
    }

    /**
     * @return mixed
     */
    public function isConnected() {
        $storage = $this->service->getStorage();

        return $storage->hasAccessToken( $this->service->service() );
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