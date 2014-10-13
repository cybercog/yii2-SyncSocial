<?php

namespace xifrin\SyncSocial\actions;


use Yii;
use yii\base\Action;

class ConnectAction extends Action {
    public $successUrl;
    public $failedUrl;

    public $componentName = 'synchronizer';


    /**
     * Set default redirect url
     */
    protected function initialRedirectUrl() {

        $defaultUrl = $this->controller->module->id
                      . "/" . $this->controller->id
                      . "/" . $this->controller->defaultAction;

        $defaultUrl = ltrim( $defaultUrl, "/" );

        if ( $this->successUrl !== null ) {
            $this->successUrl = $defaultUrl;
        }

        if ( $this->failedUrl !== null ) {
            $this->failedUrl = $defaultUrl;
        }
    }

    /**
     * @param $service
     */
    public function run( $service ) {

        $this->initialRedirectUrl();

        $synchronizer = Yii::$app->{$this->componentName};
        $serviceModel = $synchronizer->getService( $service, true );

        $token = $serviceModel->getAccessToken();

        if ( $synchronizer->setToken( $service, $token ) ) {
            $this->controller->redirect( $this->successUrl );
        } else {
            $this->controller->redirect( $this->$failedURL );
        }
    }
}