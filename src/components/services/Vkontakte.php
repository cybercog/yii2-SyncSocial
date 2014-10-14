<?php

namespace xifrin\SyncSocial\components\services;

use xifrin\SyncSocial\SyncService;

/**
 * Class Twitter
 * @package xifrin\SyncSocial\components\services
 */
class Vkontakte extends SyncService {

    /**
     * @var \OAuth\OAuth2\Service\Vontakte
     */
    protected $service;

    /**
     * @return mixed|void
     */
    public function getPosts() {
        $response = $this->service->request('wall.get', [
            'owner_id'   => '-55351290',
            'from_group' => '1'
        ]);
    }


    /**
     * @return mixed
     */
    public function publishPost() {

    }

}