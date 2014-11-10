<?php

namespace xifrin\SyncSocial\components\services;

use xifrin\SyncSocial\SyncService;

/**
 * Class Vkontakte
 * @package xifrin\SyncSocial\components\services
 */
class Vkontakte extends SyncService {

    /*
     * VKontakte not support clear post method, only via manual saved token
     * or when application is allowed to direction authorization
     */
    const SUPPORT_POST = false;

    /**
     * @var \OAuth\OAuth2\Service\Vontakte
     */
    protected $service;

    /**
     * @return array
     */
    public function getPosts( $limit = 100 ) {

        $parameters = [
            'owner_id'   => isset( $this->options['owner_id'] ) ? $this->options['owner_id'] : null,
            'from_group' => isset( $this->options['from_group'] ) ? $this->options['from_group'] : null,
            'limit'      => $limit
        ];

        $query = http_build_query( $parameters );

        $response = $this->service->request( 'wall.get' . ( ! empty( $query ) ? "?" . $query : null ), 'GET' );

        $list     = [ ];
        $response = json_decode( $response );

        if ( ! empty( $response->response ) ) {
            foreach ( $response->response as $item ) {
                if ( is_object( $item ) ) {
                    $list[] = [
                        'service_id_author' => $item->from_id,
                        'service_id_post'   => $item->id,
                        'time_created'      => $item->date,
                        'content'           => $item->text
                    ];
                }
            }
        }

        return $list;
    }

}