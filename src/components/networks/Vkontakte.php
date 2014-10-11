<?php

namespace xifrin\SyncSocial\components\networks;

use Yii;
use VK\VK;
use yii\base\Object;

/**
 * Class Vkontakte
 * @package xifrin\SyncSocial\components\networks
 */
class Vkontakte extends Object implements iNetwork {

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

        $this->provider = new VK(
            isset( $settings['client_id'] ) ? $settings['client_id'] : null,
            isset( $settings['client_secret'] ) ? $settings['client_secret'] : null,
            isset( $settings['client_token'] ) ? $settings['client_token'] : null
        );
    }

    public function getAuthorizeURL() {

        return $this->provider->getAuthorizeURL(
            isset( $settings['permissions'] ) ? $settings['permissions'] : null,
            isset( $settings['callback_url'] ) ? $settings['callback_url'] : null
        );
    }

    /**
     * @param null $code
     *
     * @return array
     * @throws \VK\VKException
     */
    public function getToken($code = null) {
        return $this->provider->getAccessToken(
            $code,
            isset( $settings['callback_url'] ) ? $settings['callback_url'] : null
        );
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