<?php

namespace xifrin\SyncSocial;

/**
 * Interface INetwork
 * @package xifrin\SyncSocial
 */
interface INetwork {

    /**
     * @return mixed
     */
    public function getAuthorizeURL();

    /**
     * @return mixed
     */
    public function getPosts();

    /**
     * @return mixed
     */
    public function getPost();

    /**
     * @return mixed
     */
    public function publishPost();

}