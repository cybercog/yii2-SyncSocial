<?php

namespace xifrin\SyncSocial\commands;

use yii\base\ErrorException;
use yii\console\Controller;

/**
 * Class PublishController
 * @package xifrin\SyncSocial\commands
 */
class PublishController extends Controller {

    public function actionIndex( $services = '*' ) {

        if ( $services === '*' ) {
            $services = Yii::$app->synchronizer->getServiceList();
        } else {
            $services = explode( ',', $services );
        }

        if ( empty( $services ) ) {
            throw new ErrorException( Yii::t( '@SyncSocial', 'Service list is empty!' ) );
        }

        foreach ( $services as $service ) {
            $this->publishService( $service );
        }
    }

    protected function publishService( $service ) {
        $result = Yii::$app->synchronizer->publish($service);
    }
}