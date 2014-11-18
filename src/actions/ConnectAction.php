<?php

namespace ifrin\SyncSocial\actions;

use Yii;

/**
 * Class ConnectAction
 * @package ifrin\SyncSocial\actions
 */
class ConnectAction extends ActionSynchronize {

    /**
     * @param $service
     */
    public function run( $service ) {

        $flagConnect = $this->synchronizer->connect( $service );

        $this->redirectWithMessages(
            $flagConnect,
            Yii::t( 'SyncSocial', 'Service was successfully connected' ),
            Yii::t( 'SyncSocial', 'Service could not be connected' )
        );

    }
}