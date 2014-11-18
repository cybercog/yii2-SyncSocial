<?php

namespace tests\models;

use yii\db\ActiveRecord;
use ifrin\SyncSocial\behaviors\SynchronizerBehavior;

/**
 * This is the model class for table "record".
 *
 * @property integer $id_record
 * @property string $content
 */

class Record extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%posts}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            SynchronizerBehavior::className()
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            'default' => ['content']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSyncModel()
    {
        return $this->hasOne('\ifrin\SyncSocial\models\SyncModel', ['model_id' => 'id_sign']);
    }
}