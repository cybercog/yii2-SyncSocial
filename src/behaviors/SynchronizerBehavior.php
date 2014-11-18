<?php

namespace ifrin\SyncSocial\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class SynchronizerBehavior
 * @package ifrin\SyncSocial\behaviors
 */
class SynchronizerBehavior extends Behavior {

    /**
     * @var string
     */
    public $componentName = 'synchronizer';

    /**
     * @var \ifrin\SyncSocial\components\Synchronizer
     */
    protected $synchronizer;

    /**
     * @return \ifrin\SyncSocial\components\Synchronizer
     * @throws Exception
     */
    protected function getSynchonizer() {

        if ( $this->synchronizer === null ) {
            $components = Yii::$app->getComponents();

            if ( ! isset( $components[ $this->componentName ] ) ) {
                throw new Exception( Yii::t( 'SyncSocial', 'Component is not configured!' ) );
            } else {
                $this->synchronizer = Yii::$app->{$this->componentName};
            }
        }

        return $this->synchronizer;
    }

    /**
     * @return array
     */
    public function events() {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert'
        ];
    }

    /**
     * @param \yii\base\Event $event
     * @var \yii\db\ActiveRecord $event->sender
     *
     * @throws Exception
     */
    public function afterInsert( $event ) {
        $synchronizer = $this->getSynchonizer();
        $synchronizer->syncPostAllService( $event->sender );
    }

}