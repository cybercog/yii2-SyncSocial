<?php

namespace xifrin\SyncSocial\components\networks;

use Yii;
use TwitterOAuth;
use yii\base\Object;

/**
 * Class Twitter
 * @package xifrin\SyncSocial\components\networks
 */
class Twitter extends Object implements iNetwork {

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

        $this->settings = $settings;

        $this->provider = new TwitterOAuth(
            isset( $settings['client_id'] ) ? $settings['client_id'] : null,
            isset( $settings['client_secret'] ) ? $settings['client_secret'] : null
        );
    }

    /**
     * @return \a
     */
    public function getAuthorizeURL() {

        $callback_url = isset( $settings['callback_url'] ) ? $settings['callback_url'] : null;
        $credentials = $this->provider->getRequestToken($callback_url);

        return $this->provider->getAuthorizeUrl($credentials);
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