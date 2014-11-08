<?php

namespace xifrin\SyncSocial\components;

use Closure;
use OAuth\Common\Consumer\Credentials;
use OAuth\ServiceFactory;
use Yii;
use yii\base\Component;
use yii\base\ErrorException;
use yii\base\Exception;
use xifrin\SyncSocial\models\SyncModel;

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
     * @var Closure
     */
    public $syncUrl;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $model;

    /**
     * @var string
     */
    public $attribute = 'content';

    /**
     * @var Closure
     */
    public $absolutePostUrl = null;

    /**
     * @throws ErrorException
     */
    public function init() {

        $className = $this->model;

        if ( ! class_exists( $className ) ) {
            throw new Exception( Yii::t( 'SyncSocial', 'Set model class to synchronization' ) );
        }

        if ( ! in_array( $this->attribute, $className::getTableSchema()->columnNames ) ) {
            throw new Exception( Yii::t( 'SyncSocial', 'Set model attribute to synchronization' ) );
        }

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
     * @return string
     */
    public function getConnectUrl( $serviceName ) {
        $function = $this->connectUrl;
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
        $function = $this->disconnectUrl;
        if ( is_callable( $function ) && ( $function instanceof Closure ) ) {
            return $function( $serviceName );
        }
    }

    /**
     * @param $serviceName
     *
     * @return mixed
     */
    public function getSyncUrl( $serviceName ) {
        $function = $this->syncUrl;
        if ( is_callable( $function ) && ( $function instanceof Closure ) ) {
            return $function( $serviceName );
        }
    }

    /**
     * Create service synchronizer wrapper class
     *
     * @param $serviceName
     *
     * @return \xifrin\SyncSocial\SyncService|null
     */
    protected function factorySynchronizer( $serviceName ) {

        $class = 'xifrin\\SyncSocial\\components\\services\\' . ucfirst( $serviceName );
        if ( class_exists( $class ) ) {

            $settings   = isset( $this->settings[ $serviceName ] ) ? $this->settings[ $serviceName ] : [ ];
            $connection = isset( $settings['connection'] ) ? $settings['connection'] : [ ];

            $key    = isset( $connection['key'] ) ? $connection['key'] : null;
            $secret = isset( $connection['secret'] ) ? $connection['secret'] : null;
            $url    = $this->getConnectUrl( $serviceName );
            $scopes = isset( $connection['scopes'] ) ? $connection['scopes'] : null;

            $credentials = new Credentials( $key, $secret, $url, $scopes );

            $service = $this->factory->createService( $serviceName, $credentials, $this->storage );
            $options = isset( $settings['options'] ) ? $settings['options'] : [ ];

            return new $class( $service, $options );
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
     * @return boolean|null
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
     * @return boolean|null
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

        return ! $service->isConnected();
    }

    /**
     * @param null $serviceName
     *
     * @return bool
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function syncService( $serviceName = null ) {

        $service = $this->getService( $serviceName );

        if ( ! empty( $service ) ) {
            $posts = $service->getPosts();
            foreach ( $posts as $post ) {

                $findOne = SyncModel::findOne( [
                    'service_id_author' => $post['service_id_author'],
                    'service_id_post'   => $post['service_id_post']
                ] );

                if ( ! empty( $findOne ) ) {
                    continue;
                }

                $post_model                     = new $this->model;
                $post_model->scenario           = 'sync';
                $post_model->{$this->attribute} = $post['content'];

                if ( $post_model->save() ) {

                    $sync_model = new SyncModel();

                    $sync_model->model_id          = $post_model->getPrimaryKey();
                    $sync_model->service_name      = $service->getName();
                    $sync_model->service_id_author = $post['service_id_author'];
                    $sync_model->service_id_post   = $post['service_id_post'];
                    $sync_model->time_created      = $post['time_created'];

                    if ( ! $sync_model->save() ) {
                        $post_model->delete();
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param \yii\db\ActiveRecord $post
     *
     * @return bool
     */
    public function syncPostAllService( $post ) {

        $serviceNames = $this->getServiceList();
        $result       = [ ];

        foreach ( $serviceNames as $serviceName ) {
            $result[ $serviceName ] = $this->syncPost( $serviceName, $post );
        }

        return $result;
    }

    /**
     * @param null $serviceName
     * @param \yii\db\ActiveRecord $post
     *
     * @return bool
     */
    public function syncPost( $serviceName = null, $post ) {

        $service = $this->getService( $serviceName );

        if ( $service->isConnected() ) {
            $message  = $post->{$this->attribute};
            $function = $this->absolutePostUrl;

            $url = null;
            if ( is_callable( $function ) && ( $function instanceof Closure ) ) {
                $url = $function( $serviceName, $post->getPrimaryKey() );
            }

            return $service->publishPost( $message, $url );
        }
    }

}