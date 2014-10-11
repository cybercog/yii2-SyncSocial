<?php

namespace xifrin\SyncSocial\components;

use Yii;
use yii\base\Component;
use yii\base\ErrorException;

Yii::setAlias('@SyncSocial', dirname(dirname(__DIR__)));

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
     * @var
     */
    public $timeout;

    /**
     * @var array
     */
    public $services = array();

    /**
     * @var string
     */
    public $model;

    /**
     * @throws ErrorException
     */
    public function init()
    {
        if (empty($this->model)) {
            throw new ErrorException( Yii::t( 'SyncSocial', 'Model name must be specified' ) );
        }

        if (!class_exists($this->model)) {
            throw new ErrorException( Yii::t( 'SyncSocial', 'Model class is not exists' ) );
        }

        $this->timeout = $this->timeout === null ? DEFAULT_TIMEOUT : $this->timeout;
    }

    /**
     * @return array
     */
    public function getServiceList()
    {
        return array_keys($this->services);
    }

    /**
     * Has token
     *
     * @param null $networkName
     *
     * @return bool
     */
    public function hasToken($networkName = null) {
        return Yii::$app->cache->exists( 'social.' . $networkName . '.token' );
    }

    /**
     * @param null $networkName
     *
     * @return bool
     */
    public function isExpired($networkName = null)
    {
        $lastTime = (int)Yii::$app->cache->get( 'social.' . $networkName . '.lastTime');
        return (time() - $lastTime) > $this->timeout;
    }
}