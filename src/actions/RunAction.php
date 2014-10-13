<?php

namespace xifrin\SyncSocial\actions;

use Yii;
use yii\base\Action;

class RunAction extends Action
{
    protected $synchronizer;

    public $componentName = 'synchronizer';

    public function run($service)
    {
        $this->synchronizer = Yii::$app->{$this->componentName};

        if ($this->synchronizer->isConnected($service)){
            $this->synchronizer->publishServicePost($service);
        }

    }
}