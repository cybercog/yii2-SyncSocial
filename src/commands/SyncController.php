<?php

namespace ifrin\SyncSocial\commands;

use Yii;
use yii\base\ErrorException;
use yii\console\Controller;

/**
 * Class PublishController
 * @package ifrin\SyncSocial\commands
 */
class SyncController extends Controller
{
    /**
     * @var string
     */
    public $componentName = 'synchronizer';

    /**
     * @param string $services
     *
     * @throws ErrorException
     */
    public function actionIndex( $services = '*' ) {

        $synchronizer =  Yii::$app->{$this->componentName};

        if ( $services === '*' ) {
            $services = $synchronizer->getServiceList();
        } else {
            $services = explode( ',', $services );
        }

        if ( empty( $services ) ) {
            throw new ErrorException( Yii::t( 'error', 'Service list is empty!' ) );
        }

        foreach ( $services as $service ) {
            $this->publishService( $service );
        }
    }

    protected function publishService( $service ) {

        $synchronizer =  Yii::$app->{$this->componentName};

        $result = $synchronizer->publish( $service );
    }
}