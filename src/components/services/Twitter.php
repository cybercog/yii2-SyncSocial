<?php

namespace xifrin\SyncSocial\components\services;

use OAuth\Common\Exception\Exception;
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
     * @return mixed|\OAuth\Common\Http\Uri\UriInterface
     */
    public function getAuthorizationUri() {
        $token = $this->service->requestRequestToken();

        return $this->service->getAuthorizationUri( array(
            'oauth_token' => $token->getRequestToken()
        ) );
    }


    /**
     * @param $message
     * @param null $url
     *
     * @return bool|mixed
     */
    public function publishPost( $message, $url = null ) {

        // @TODO: warning maxixum 140 length for message

        $response = json_decode( $this->service->request( 'statuses/update.json', 'POST', [
            'status' => $message
        ] ) );

        if ( isset( $response->id ) ) {
            return [
                'service_id_author' => $response->user->id,
                'service_id_post'   => $response->id,
                'time_created'      => strtotime( $response->created_at )
            ];
        }

    }

    /**
     * @return mixed
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