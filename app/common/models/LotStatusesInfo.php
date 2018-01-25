<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lot_statuses_info".
 *
 * @property integer $record_id
 * @property integer $lang
 * @property string $name
 *
 * @property LotStatuses $record
 */
class LotStatusesInfo extends \common\components\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lot_statuses_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_id', 'lang', 'name'], 'required'],
            [['record_id', 'lang'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['record_id'], 'exist', 'skipOnError' => true, 'targetClass' => LotStatuses::className(), 'targetAttribute' => ['record_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'record_id' => Yii::t('app', 'Record ID'),
            'lang' => Yii::t('app', 'Lang'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(LotStatuses::className(), ['id' => 'record_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\Queries\LotStatusesInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\Queries\LotStatusesInfoQuery(get_called_class());
    }
}
