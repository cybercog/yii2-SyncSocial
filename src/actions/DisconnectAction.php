<?php

namespace ifrin\SyncSocial\actions;

use Yii;

/**
 * Class ConnectAction
 * @package ifrin\SyncSocial\actions
 */
class DisconnectAction extends ActionSynchronize {

    /**
     * @param $service
     */
    public function run( $service ) {

        $flagDisconnect = $this->synchronizer->disconnect( $service );

        $this->redirectWithMessages(
            $flagDisconnect,
            Yii::t( 'SyncSocial', 'Service was successfully disconnected' ),
            Yii::t( 'SyncSocial', 'There is error in disconnection' )
        );
    }

}