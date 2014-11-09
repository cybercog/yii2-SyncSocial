<?php

namespace xifrin\SyncSocial\actions;

use Yii;

/**
 * Class RunAction
 * @package xifrin\SyncSocial\actions
 */
class SyncAction extends ActionSynchronize {

    /**
     * @param $service
     */
    public function run( $service ) {

        $flagConnect = $this->synchronizer->isConnected( $service );

        if ( $flagConnect ) {
            $flagSync = $this->synchronizer->syncService( $service );
            $this->redirectWithMessages(
                $flagSync,
                Yii::t( 'SyncSocial', 'Service was successfully synchronized' ),
                Yii::t( 'SyncSocial', 'There is a error in service synchronization' )
            );
        } else {
            Yii::$app->session->setFlash( 'warning', Yii::t( 'SyncSocial', 'Service is not connected' ) );
            $this->controller->redirect( $this->failedUrl );
        }

    }
}