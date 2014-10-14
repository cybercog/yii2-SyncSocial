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
     * @return mixed
     */
    public function publishPost();

}