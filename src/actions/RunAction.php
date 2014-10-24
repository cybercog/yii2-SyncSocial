<?php

namespace xifrin\SyncSocial\actions;

use Yii;

/**
 * Class RunAction
 * @package xifrin\SyncSocial\actions
 */
class RunAction extends ActionSynchronize {

    /**
     * @param $service
     */
    public function run($service)
    {
        if ($this->synchronizer->isConnected($service)){
            if ($this->synchronizer->syncService($service)){
                Yii::$app->session->setFlash('success', Yii::t('SyncSocial', 'Service was successfully synchronized'));
                $this->controller->redirect( $this->successUrl );
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('SyncSocial', 'There is a error in service synchronization'));
                $this->controller->redirect( $this->failedUrl );
            }
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('SyncSocial', 'Service is not connected'));
            $this->controller->redirect( $this->failedUrl );
        }

    }
}