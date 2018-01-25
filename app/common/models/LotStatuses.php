<?php

namespace common\models;

use Yii;


/**
 * This is the model class for table "lot_statuses".
 *
 * @property integer $id
 * @property string $alias
 * @property integer $creation_time
 * @property integer $update_time
 *
 * @property LotStatusesInfo[] $lotStatusesInfos
 * @property Lots[] $lots
 */
class LotStatuses extends \common\components\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lot_statuses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'creation_time', 'update_time'], 'required'],
            [['creation_time', 'update_time'], 'integer'],
            [['alias'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => Yii::t('app', 'ID'),
            'alias'             => Yii::t('app', 'Alias'),
            'creation_time'     => Yii::t('app', 'Creation Time'),
            'update_time'       => Yii::t('app', 'Update Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotStatusesInfos()
    {
        return $this->hasMany(LotStatusesInfo::className(), ['record_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfo()
    {
        return $this->hasOne(LotStatusesInfo::className(), ['record_id' => 'id'])
                ->where([LotStatusesInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLots()
    {
        return $this->hasMany(Lots::className(), ['status_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\Queries\LotStatusesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\Queries\LotStatusesQuery(get_called_class());
    }
}
