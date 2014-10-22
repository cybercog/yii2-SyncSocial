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
    public function getPosts($limit = 100) {

        $response = $this->service->request('wall.get', [
            'owner_id'   => '-55351290',
            'from_group' => '1',
            'limit' => $limit
        ]);

        $list = [];
        $response = json_decode($response);
        if (!empty($response->response))
            foreach($response->response as $item){
                $list[] = [
                    'service_id_author' =>$item->from_id,
                    'service_id_post' => $item->id,
                    'time_created' => $item->date,
                    'content' => $item->text
                ];
            }

        return $list;
    }


    /**
     * @return mixed
     */
    public function publishPost() {

    }

}