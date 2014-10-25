<?php

namespace xifrin\SyncSocial\components\services;

use xifrin\SyncSocial\SyncService;

/**
 * Class Facebook
 * @package xifrin\SyncSocial\components\services
 */
class Facebook extends SyncService {

    /**
     * @var \OAuth\OAuth2\Service\Facebook
     */
    protected $service;

    /**
     * @param $message
     * @param null $url
     *
     * @return array|bool
     * @throws \OAuth\Common\Token\Exception\ExpiredTokenException
     */
    public function publishPost( $message, $url = null ) {

        $response = json_decode( $this->service->request( '/me/feed' , 'POST', [
            'message' => $message
        ] ));

        if ( isset( $response->id ) ) {
            return [
                'service_id_author' => $response->user->id,
                'service_id_post'   => $response->id,
                'time_created'      => strtotime( $response->created_at )
            ];
        }

        return $list;
    }

    /**
     * @return mixed|void
     */
    public function getPosts( $limit = 100 ) {

        $response = json_decode( $this->service->request( '/me/feed' ), true );
        $list     = [ ];

        if ( ! empty( $response->data ) ) {
            foreach ( $response->data as $item ) {
                if ( is_object( $item ) ) {
                    $list[] = [
                        'service_id_author' => $item->from->id,
                        'service_id_post'   => $item->id,
                        'time_created'      => strtotime( $item->created_time ),
                        'content'           => $item->message
                    ];
                }
            }
        }

        return $list;
    }
}