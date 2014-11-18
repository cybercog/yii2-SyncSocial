<?php

namespace ifrin\SyncSocial\components\services;

use ifrin\SyncSocial\SyncService;

/**
 * Class Facebook
 * @package ifrin\SyncSocial\components\services
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
     * @return array
     * @throws \OAuth\Common\Token\Exception\ExpiredTokenException
     */
    public function publishPost( $message, $url = null ) {

        $response = json_decode( $this->service->request( '/me/feed', 'POST', [
            'message' => $message
        ] ), true );

        if ( isset( $response['id'] ) ) {
            return [
                'service_id_author' => isset( $response['user']['id'] ) ? isset( $response['user']['id'] ) : null,
                'service_id_post'   => $response['id'],
                'time_created'      => isset( $response['created_at'] ) ? strtotime( $response['created_at'] ) : time()
            ];
        } else {
            return [ ];
        }

    }

    /**
     * @param int $limit
     *
     * @return array
     * @throws \OAuth\Common\Token\Exception\ExpiredTokenException
     */
    public function getPosts( $limit = 100 ) {

        $response = json_decode( $this->service->request( '/me/feed' ), true );
        $list     = [ ];

        if ( ! empty( $response['data'] ) ) {
            foreach ( $response['data'] as $item ) {
                if ( ! empty( $item['id'] ) && ! empty( $item['message'] ) ) {
                    $list[] = [
                        'service_id_author' => isset( $item['from']['id'] ) ? $item['from']['id'] : null,
                        'service_id_post'   => $item['id'],
                        'time_created'      => isset( $item['created_time'] ) ? strtotime( $item['created_time'] ) : null,
                        'content'           => $item['message']
                    ];
                }
            }
        }

        return $list;
    }
}