<?php

namespace ifrin\SyncSocial\components\services;

use ifrin\SyncSocial\SyncService;

/**
 * Class Vkontakte
 * @package ifrin\SyncSocial\components\services
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
     * @param integer $limit
     *
     * @return string
     */
    protected function getWallGetRequest( $limit ) {

        $parameters = [
            'owner_id'   => isset( $this->options['owner_id'] ) ? $this->options['owner_id'] : null,
            'from_group' => isset( $this->options['from_group'] ) ? $this->options['from_group'] : null,
            'limit'      => $limit
        ];

        $query = http_build_query( $parameters );

        return 'wall.get' . ! empty( $query ) ? "?" . $query : null;
    }

    /**
     * @return array
     */
    public function getPosts( $limit = 100 ) {

        $response = $this->service->request( $this->getWallGetRequest( $limit ), 'GET' );

        $list     = [ ];
        $response = json_decode( $response, true );

        if ( ! empty( $response['response'] ) ) {
            foreach ( $response['response'] as $item ) {
                if ( ! empty( $item['id'] ) && ! empty( $item['text'] ) ) {
                    $list[] = [
                        'service_id_author' => isset( $item['from_id'] ) ? $item['from_id'] : null,
                        'service_id_post'   => $item['id'],
                        'time_created'      => isset( $item['date'] ) ? $item['date'] : null,
                        'content'           => $item['text']
                    ];
                }
            }
        }

        return $list;
    }

}