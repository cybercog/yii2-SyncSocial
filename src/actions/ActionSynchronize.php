<?php

namespace xifrin\SyncSocial\actions;

use Yii;
use yii\base\Action;
use yii\base\Exception;

/**
 * Class RunAction
 * @package xifrin\SyncSocial\actions
 */
class ActionSynchronize extends Action {
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
     * @var \xifrin\SyncSocial\components\Synchronizer
     */
    protected $synchronizer;

    /**
     * @var \yii\web\Controller
     */
    public $controller;

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
     * @param $successMessage
     * @param $failedMessage
     */
    protected function redirectWithMessages( $flag, $successMessage, $failedMessage ) {
        if ( $flag ) {
            Yii::$app->session->setFlash( 'success', $successMessage );
            $this->controller->redirect( $this->successUrl );
        } else {
            Yii::$app->session->setFlash( 'warning', $failedMessage );
            $this->controller->redirect( $this->failedUrl );
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function beforeRun() {

        $this->initialRedirectUrl();

        $components = Yii::$app->getComponents();

        if ( ! isset( $components[ $this->componentName ] ) ) {
            throw new Exception( Yii::t( 'SyncSocial', 'Component is not configured!' ) );
        } else {
            $this->synchronizer = Yii::$app->{$this->componentName};
        }

        return parent::beforeRun();
    }
}