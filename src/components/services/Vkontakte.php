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
     * @return mixed
     */
    public function publishPost( $message, $url = null ) {

        $response = $this->service->request( 'wall.post', 'POST', [
            'owner_id'   => isset( $this->options['owner_id'] ) ? $this->options['owner_id'] : null,
            'from_group' => isset( $this->options['from_group'] ) ? $this->options['from_group'] : null,
            'message'    => $message
        ] );

        if ( isset( $response->post_id ) ) {
            return [
                'service_id_author' => $response->user->id,
                'service_id_post'   => $response->post_id,
                'time_created'      => time(),
            ];
        }
    }

    /**
     * @return mixed|void
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