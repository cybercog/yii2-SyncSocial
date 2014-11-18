<?php

namespace ifrin\SyncSocial;

/**
 * Interface ISyncService
 * @package ifrin\SyncSocial
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