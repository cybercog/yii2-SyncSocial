<?php

namespace xifrin\SyncSocial\components\networks;

use Yii;
use TwitterOAuth;
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
    protected $provider;

    /**
     * @var array
     */
    public $settings = array();

    /**
     * @param array $settings
     */
    public function __construct( array $settings = array() ) {

        $connection = isset($settings['connection']) ? $settings['connection'] : [];

        $this->provider = new TwitterOAuth(
            isset( $connection['client_id'] ) ? $connection['client_id'] : null,
            isset( $connection['client_secret'] ) ? $connection['client_secret'] : null
        );

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

        $callback_url = isset( $connection['callback_url'] ) ? $connection['callback_url'] : null;
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