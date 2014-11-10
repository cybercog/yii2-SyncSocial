<?php

namespace xifrin\SyncSocial\components\services;

use xifrin\SyncSocial\SyncService;

/**
 * Class Twitter
 * @package xifrin\SyncSocial\components\services
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
        ] ) );

        if ( isset( $response->id ) ) {
            return [
                'service_id_author' => $response->user->id,
                'service_id_post'   => $response->id,
                'time_created'      => strtotime( $response->created_at )
            ];
        } else {
            return [ ];
        }

    }

    /**
     * @return array
     */
    public function getPosts( $limit = 200 ) {

        $response = json_decode( $this->service->request( 'statuses/user_timeline.json' ) );
        $list     = [ ];

        if ( ! empty( $response ) ) {
            foreach ( $response as $item ) {
                if ( is_object( $item ) ) {
                    $list[] = [
                        'service_id_author' => $item->user->id,
                        'service_id_post'   => $item->id,
                        'time_created'      => strtotime( $item->created_at ),
                        'content'           => $item->text
                    ];
                }
            }
        }

        return $list;
    }
}