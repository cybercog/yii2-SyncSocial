<?php

namespace xifrin\SyncSocial\components;

use Closure;
use OAuth\Common\Consumer\Credentials;
use OAuth\ServiceFactory;
use yii\base\Component;
use yii\base\ErrorException;

\Yii::setAlias( '@SyncSocial', dirname( dirname( __DIR__ ) ) );

/**
 * Class Synchronizer
 * @package xifrin\SyncSocial\components
 */
class Synchronizer extends Component {

    /**
     * Value of timeout
     */
    const DEFAULT_TIMEOUT = 18000;

    /**
     * @var array
     */
    protected $services = array();

    /**
     * @var \OAuth\ServiceFactory
     */
    protected $factory;

    /**
     * @var \OAuth\Common\Storage\TokenStorageInterface
     */
    protected $storage;

    /**
     * @var string
     */
    public $storageClass = '\OAuth\Common\Storage\Session';

    /**
     * @var array
     */
    public $settings = array();

    /**
     * @var Closure
     */
    public $connectUrl;

    /**
     * @var Closure
     */
    public $disconnectUrl;

    /**
     * @var string
     */
    public $model;

    /**
     * @throws ErrorException
     */
    public function init() {
        $this->factory = new ServiceFactory();
        $this->storage = new $this->storageClass();
    }

    /**
     * @return array
     */
    public function getServiceList() {
        return array_keys( $this->settings );
    }

    /**
     * @param $serviceName
     *
     * @return mixed
     */
    public function getConnectUrl( $serviceName ) {
        $callbackUrl = null;
        $function    = $this->connectUrl;
        if ( is_callable( $function ) && ( $function instanceof Closure ) ) {
            return $function( $serviceName );
        }
    }

    /**
     * @param $serviceName
     *
     * @return mixed
     */
    public function getDisconnectUrl( $serviceName ) {
        $callbackUrl = null;
        $function    = $this->disconnectUrl;
        if ( is_callable( $function ) && ( $function instanceof Closure ) ) {
            return $function( $serviceName );
        }
    }

    /**
     * Create service synchronizer wrapper class
     *
     * @param $serviceName
     *
     * @return \xifrin\SyncSocial\SyncService
     */
    protected function factorySynchronizer( $serviceName ) {

        $class = 'xifrin\\SyncSocial\\components\\services\\' . ucfirst( $serviceName );
        if ( class_exists( $class ) ) {
            $settings   = isset( $this->settings[ $serviceName ] ) ? $this->settings[ $serviceName ] : [ ];
            $connection = isset( $settings['connection'] ) ? $settings['connection'] : [ ];

            $credentials = new Credentials(
                isset( $connection['key'] ) ? $connection['key'] : null,
                isset( $connection['secret'] ) ? $connection['secret'] : null,
                $this->getConnectUrl( $serviceName ),
                isset( $connection['scopes'] ) ? $connection['scopes'] : null
            );

            return new $class(
                $this->factory->createService( $serviceName, $credentials, $this->storage )
            );
        }
    }

    /**
     * Get service
     *
     * @param $serviceName
     *
     * @return null|\OAuth\Common\Service\ServiceInterface
     */
    /**
     * @param $serviceName
     *
     * @return null|\xifrin\SyncSocial\SyncService
     */
    public function getService( $serviceName ) {
        if ( ! isset( $this->services[ $serviceName ] ) ) {
            $service = $this->factorySynchronizer( $serviceName );
            if ( ! empty( $service ) ) {
                $this->services[ $serviceName ] = $service;
            }
        }

        return isset( $this->services[ $serviceName ] )
            ? $this->services[ $serviceName ]
            : null;
    }

    /**
     * @param null $serviceName
     *
     * @return \OAuth\Common\Http\Uri\UriInterface
     */
    public function getAuthorizationUri( $serviceName = null ) {
        $service = $this->getService( $serviceName );
        if ( ! empty( $service ) ) {
            return $service->getAuthorizationUri();
        }
    }

    /**
     * @param null $serviceName
     *
     * @return mixed
     */
    public function connect( $serviceName = null ) {
        $service = $this->getService( $serviceName );
        if ( ! empty( $service ) ) {
            return $service->connect();
        }
    }

    /**
     * Check if service is connected
     *
     * @param null $serviceName
     *
     * @return bool
     */
    public function isConnected( $serviceName = null ) {
        $service = $this->getService( $serviceName );
        if ( ! empty( $service ) ) {
            return $service->isConnected();
        }
    }

    /**
     * @param null $serviceName
     *
     * @return bool
     */
    public function disconnect( $serviceName = null ) {
        $service = $this->getService( $serviceName );
        if ( ! empty( $service ) ) {
            $service->disconnect();
        }

        return !$service->isConnected();
    }

    /**
     * @param null $serviceName
     *
     * @return mixed
     */
    public function publishServicePost( $serviceName = null ) {
        $service = $this->getService( $serviceName );
        if ( ! empty( $service ) ) {
            return $service->publishPost();
        }
    }

}