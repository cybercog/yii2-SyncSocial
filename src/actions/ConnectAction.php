<?php

namespace xifrin\SyncSocial\actions;


use Yii;
use yii\base\Action;

/**
 * Class ConnectAction
 * @package xifrin\SyncSocial\actions
 */
class ConnectAction extends Action {

    /**
     * @var
     */
    public $successUrl;

    /**
     * @var
     */
    public $failedUrl;

    /**
     * @var string
     */
    public $componentName = 'synchronizer';

    /**
     * Set default redirect url
     */
    protected function initialRedirectUrl() {

        $defaultUrl = $this->controller->module->id
                      . "/" . $this->controller->id
                      . "/" . $this->controller->defaultAction;

        $defaultUrl = ltrim( $defaultUrl, "/" );

        if ( empty( $this->successUrl ) ) {
            $this->successUrl = $defaultUrl;
        }

        if ( empty( $this->failedUrl ) ) {
            $this->failedUrl = $defaultUrl;
        }

    }

    /**
     * @param $service
     */
    public function run( $service ) {

        $this->initialRedirectUrl();

        $synchronizer = Yii::$app->{$this->componentName};
        $flagConnect = $synchronizer->connect( $service );

        if ( $flagConnect) {
            Yii::$app->session->setFlash('success', Yii::t('SyncSocial', 'Service was successfully connected'));
            $this->controller->redirect( $this->successUrl );
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('SyncSocial', 'Service could not be connected'));
            $this->controller->redirect( $this->failedUrl );
        }
    }
}