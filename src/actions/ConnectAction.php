<?php

namespace xifrin\SyncSocial\actions;

use Yii;

/**
 * Class ConnectAction
 * @package xifrin\SyncSocial\actions
 */
class ConnectAction extends ActionSynchronize {

    /**
     * @param $service
     */
    public function run( $service ) {

        $flagConnect = $this->synchronizer->connect( $service );

        if ( $flagConnect) {
            Yii::$app->session->setFlash('success', Yii::t('SyncSocial', 'Service was successfully connected'));
            $this->controller->redirect( $this->successUrl );
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('SyncSocial', 'Service could not be connected'));
            $this->controller->redirect( $this->failedUrl );
        }
    }
}