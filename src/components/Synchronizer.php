<?php

namespace xifrin\SyncSocial\components;

use Closure;
use Yii;
use yii\base\Component;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;

Yii::setAlias( '@SyncSocial', dirname( dirname( __DIR__ ) ) );

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
    protected $objects = array();

    /**
     * @var
     */
    public $timeout;

    /**
     * @var array
     */
    public $services = array();

    /**
     * @var Closure
     */
    public $callbackUrl;

    /**
     * @var string
     */
    public $model;

    /**
     * @throws ErrorException
     */
    public function init() {
        $this->timeout = $this->timeout === null ? self::DEFAULT_TIMEOUT : $this->timeout;
    }

    /**
     * @return array
     */
    public function getServiceList() {
        return array_keys( $this->services );
    }

    /**
     * @param $serviceName
     *
     * @return array
     */
    protected function getServiceSettings( $serviceName = null, $flagNewConnection = false) {

        $callbackUrl = null;
        $function    = $this->callbackUrl;
        if ( is_callable( $function ) && ( $function instanceof Closure ) ) {
            $callbackUrl = $function( $serviceName );
        }

        return ArrayHelper::merge(
            isset( $this->services[ $serviceName ] ) ? $this->services[ $serviceName ] : [ ],
            [
                'connection' => [
                    'client_token' => $flagNewConnection ? null : $this->getToken( $serviceName ),
                    'callback_url' => $callbackUrl
                ]
            ]
        );
    }

    /**
     * @param $serviceName
     * @param bool $flagNewConnection is new connection
     *
     * @return mixed
     */
    public function getService( $serviceName, $flagNewConnection = false) {

        if ( ! isset( $this->objects[ $serviceName ] ) ) {
            $class = 'xifrin\\SyncSocial\\components\\networks\\' . ucfirst( $serviceName );
            if ( class_exists( $class ) ) {
                $this->objects[ $serviceName ] = new $class( $this->getServiceSettings( $serviceName, $flagNewConnection ) );
            }
        }

        return $this->objects[ $serviceName ];
    }

    /**
     * @param null $serviceName
     */
    public function getConnectUrl( $serviceName = null ) {
        $service = $this->getService( $serviceName );
        return $service->getAuthorizeURL();
    }

    /**
     * @param null $serviceName
     *
     * @return mixed
     */
    public function publishServicePost( $serviceName = null ) {
        $service = $this->getService( $serviceName );
        return $service->publishPost();
    }

    /**
     * Set token
     *
     * @param null $serviceName
     *
     * @return bool
     */
    public function setToken( $serviceName = null, $tokenValue = null ) {
        return  Yii::$app->cache->set( 'social.' . $serviceName . '.token', $tokenValue );
    }

    /**
     * Reset token
     *
     * @param null $serviceName
     *
     * @return bool
     */
    public function resetToken( $serviceName = null ) {
        return Yii::$app->cache->delete( 'social.' . $serviceName . '.token' );
    }

    /**
     * Get token
     *
     * @param null $serviceName
     *
     * @return bool
     */
    public function getToken( $serviceName = null ) {
        return Yii::$app->cache->get( 'social.' . $serviceName . '.token', null );
    }

    /**
     * Has token
     *
     * @param null $serviceName
     *
     * @return bool
     */
    public function isConnected( $serviceName = null ) {
        return Yii::$app->cache->exists( 'social.' . $serviceName . '.token' );
    }


    /**
     * @param null $serviceName
     *
     * @return bool
     */
    public function isExpired( $serviceName = null ) {
        $lastTime = (int) Yii::$app->cache->get( 'social.' . $serviceName . '.lastTime' );
        return ( time() - $lastTime ) > $this->timeout;
    }
}