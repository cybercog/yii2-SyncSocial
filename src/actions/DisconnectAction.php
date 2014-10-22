<?php

namespace xifrin\SyncSocial\actions;

use Yii;

/**
 * Class ConnectAction
 * @package xifrin\SyncSocial\actions
 */
class DisconnectAction extends ActionSynchronize {

    /**
     * @param $service
     */
    public function run( $service ) {

        if ( $this->synchronizer->disconnect( $service ) ) {
            Yii::$app->session->setFlash( 'success', Yii::t( 'SyncSocial', 'Service was successfully disconnected' ) );
            $this->controller->redirect( $this->successUrl );
        } else {
            Yii::$app->session->setFlash( 'warning', Yii::t( 'SyncSocial', 'There is error in disconnection' ) );
            $this->controller->redirect( $this->failedUrl );
        }
    }
}