<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "court".
 *
 * @property integer $id
 * @property integer $active
 * @property string $custom_date
 * @property string $alias
 * @property integer $sort
 * @property integer $creation_time
 * @property integer $update_time
 *
 * @property CourtInfo[] $courtInfos
 */
class Court extends \common\components\BaseActiveRecord
{
    const IMG_COUNT     = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'court';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active', 'custom_date', 'alias', 'sort', 'creation_time', 'update_time'], 'required'],
            [['active', 'sort', 'creation_time', 'update_time'], 'integer'],
            [['custom_date'], 'safe'],
            [['alias'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'active' => Yii::t('app', 'Active'),
            'custom_date' => Yii::t('app', 'Custom Date'),
            'alias' => Yii::t('app', 'Alias'),
            'sort' => Yii::t('app', 'Sort'),
            'creation_time' => Yii::t('app', 'Creation Time'),
            'update_time' => Yii::t('app', 'Update Time'),
        ];
    }
    public function behaviors()
    {
        return [
            'timestamps' => [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'creation_time',
                'updatedAtAttribute' => 'update_time',
            ],
            'thumb' => [
                'class' => \common\components\behavior\ImgBehavior::className()
            ],
//            'translit' => [
//                'class' => \common\components\behavior\TranslitBehavior::className()
//            ],
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourtInfos()
    {
        return $this->hasMany(CourtInfo::className(), ['record_id' => 'id']);
    }
    public function getInfo()
    {
        return $this->hasOne(CourtInfo::className(), ['record_id' => 'id'])
            ->andWhere([CourtInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }

    public function getUrl()
    {
        return Url::to(['/court/'.$this->alias], true);
    }
    public static function find()
    {
        return new \common\models\Queries\CourtQuery(get_called_class());
    }
}
