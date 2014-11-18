<?php

namespace ifrin\SyncSocial\components\services;

use ifrin\SyncSocial\SyncService;

/**
 * Class Twitter
 * @package ifrin\SyncSocial\components\services
 */
class Twitter extends SyncService {

    /**
     * @var \OAuth\OAuth1\Service\Twitter
     */
    protected $service;

    /**
     * @return \OAuth\Common\Http\Uri\UriInterface
     */
    public function getAuthorizationUri() {
        $token = $this->service->requestRequestToken();

        return $this->service->getAuthorizationUri( array(
            'oauth_token' => $token->getRequestToken()
        ) );
    }


    /**
     * @TODO: warning maxixum 140 length for message
     * @TODO: check if similar message was posted
     *
     * @param $message
     * @param null $url
     *
     * @return array
     */
    public function publishPost( $message, $url = null ) {

        $response = json_decode( $this->service->request( 'statuses/update.json', 'POST', [
            'status' => $message
        ] ), true );

        if ( isset( $response['id'] ) ) {
            return [
                'service_id_author' => isset($response['user']['id']) ? $response['user']['id'] : null,
                'service_id_post'   => $response['id'],
                'time_created'      => isset($response['created_at']) ? strtotime( $response['created_at'] ) : time()
            ];
        } else {
            return [ ];
        }

    }

    /**
     * @return array
     */
    public function getPosts( $limit = 200 ) {

        $response = json_decode( $this->service->request( 'statuses/user_timeline.json' ), true );
        $list     = [ ];

        if ( ! empty( $response ) ) {
            foreach ( $response as $item ) {
                if ( ! empty( $item['id'] ) && ! empty( $item['text'] ) ) {
                    $list[] = [
                        'service_id_author' => isset($item['user']['id']) ? $item['user']['id'] : null,
                        'service_id_post'   => $item['id'],
                        'time_created'      => isset($item['created_at']) ? strtotime( $item['created_at'] ) : null ,
                        'content'           => $item['text']
                    ];
                }
            }
        }

        return $list;
    }
}