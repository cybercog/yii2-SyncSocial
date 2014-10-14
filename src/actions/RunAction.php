<?php

namespace xifrin\SyncSocial\actions;

use Yii;
use yii\base\Action;

/**
 * Class RunAction
 * @package xifrin\SyncSocial\actions
 */
class RunAction extends Action
{
    /**
     * @var string
     */
    public $componentName = 'synchronizer';

    /**
     * @param $service
     */
    public function run($service)
    {
        $synchronizer = Yii::$app->{$this->componentName};

        if ($synchronizer->isConnected($service)){

            $service = $synchronizer->getService($service);
            $service->getPosts();
        }

    }
}