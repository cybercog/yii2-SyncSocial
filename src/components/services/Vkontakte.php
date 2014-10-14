<?php

namespace xifrin\SyncSocial\components\services;

use Yii;
use VK\VK;
use yii\base\Object;
use xifrin\SyncSocial\iNetwork;

/**
 * Class Vkontakte
 * @package xifrin\SyncSocial\components\networks
 */
class Vkontakte extends Object implements INetwork {

    /**
     * @var VK
     */
    protected $provider;

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @param array $settings
     */
    public function __construct( array $settings = array() ) {

        $connection = isset($settings['connection']) ? $settings['connection'] : [];

        $this->provider = new VK(
            isset( $connection['client_id'] ) ? $connection['client_id'] : null,
            isset( $connection['client_secret'] ) ? $connection['client_secret'] : null,
            isset( $connection['client_token'] ) ? $connection['client_token'] : null
        );

        $this->settings = $settings;
    }

    /**
     * @return string
     */
    public function getAuthorizeURL() {
        return $this->provider->getAuthorizeURL(
            isset( $this->settings['permissions'] ) ? $this->settings['permissions'] : null
        );
    }

    /**
     *
     * @return array
     * @throws \VK\VKException
     */
    public function getAccessToken() {

        // @TODO: check this
        $code = $_REQUEST['code'];

        $response = $this->provider->getAccessToken( $code );

        return isset($response['access_token'])
            ? $response['access_token']
            : null;

    }

    /**
     * @return mixed
     */
    public function getPosts($count = 1000) {

        $owner_id = isset($this->settings['network']['owner_id']) ? $this->settings['network']['owner_id'] : null;

        $posts = $this->provider->api('wall.get', array(
            'owner_id' => $owner_id,
            'count' => $count
        ));

        echo "<pre>";
            print_r($posts);
        echo "</pre>";
        die();
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

        $owner_id = isset($this->settings['network']['owner_id']) ? $this->settings['network']['owner_id'] : null;
        $from_group = isset($this->settings['network']['owner_id']) ? $this->settings['network']['owner_id'] : null;

        $response = $this->provider->api('wall.post', array(
            'owner_id' => $owner_id,
            'from_group' => $from_group,
            'message' => "test api"
        ));

        return isset($response['response']['post_id'])
            ? $response['response']['post_id']
            : -1;
    }

}