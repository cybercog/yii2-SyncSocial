<?php

namespace xifrin\SyncSocial\components\services;

use Yii;

use OAuth\OAuth1\Service\Twitter;

\OAuth\ServiceFactory

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
        $twitterService = $serviceFactory->createService('twitter', $credentials, $storage);


        $this->settings = $settings;
    }

    /**
     *
     * @return array
     * @throws \VK\VKException
     */
    public function getAccessToken() {

        // @TODO: check this
        $verifier = $_REQUEST['oauth_verifier'];

        return $this->provider->getAccessToken($verifier);
    }


    /**
     * @return \a|mixed
     */
    public function getAuthorizeURL() {


        $connection = isset( $this->settings['connection'] ) ? $this->settings['connection'] : [ ];

        $callback_url =
        $credentials = $this->provider->getRequestToken( $callback_url );

        if ( isset( $credentials['oauth_token'] ) ) {
            return $this->provider->getAuthorizeUrl( $credentials );
        }
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