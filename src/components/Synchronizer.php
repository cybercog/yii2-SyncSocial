<?php

namespace xifrin\SyncSocial\components;

use Yii;
use yii\base\Component;
use yii\base\ErrorException;

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
     * @var callable
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
     * @return mixed
     */
    public function getService( $serviceName, array $settings = array() ) {
        if ( ! isset( $this->objects[ $serviceName ] ) ) {

            $class = 'xifrin\\SyncSocial\\components\\networks\\' . ucfirst( $serviceName );

            if ( class_exists( $class ) ) {
                $this->objects[ $serviceName ] = new $class( array_merge(
                    isset( $this->services[ $serviceName ] ) ? $this->services[ $serviceName ] : [ ],
                    $settings
                ) );
            }
        }

        return $this->objects[ $serviceName ];
    }

    /**
     * @param null $serviceName
     */
    public function getConnectUrl( $serviceName = null ) {

        $callbackUrl = null;
        if ( is_callable( $this->callbackUrl ) && $this->callbackUrl instanceof Closure ) {
            $callbackUrl = $this->callbackUrl( $serviceName );
        }

        $service = $this->getService( $serviceName, [
            'callback_url' => $callbackUrl
        ] );

        return $service->getAuthorizeURL();
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