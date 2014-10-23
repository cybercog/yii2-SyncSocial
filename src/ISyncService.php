<?php

namespace xifrin\SyncSocial;

/**
 * Interface ISyncService
 * @package xifrin\SyncSocial
 */
interface ISyncService {

    /**
     * @return mixed
     */
    public function getPosts();

    /**
     * @param $message
     * @param null $url
     *
     * @return bool|mixed
     */
    public function publishPost($message, $url = null);

}