<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property integer $active
 * @property string $custom_date
 * @property string $alias
 * @property integer $sort
 * @property integer $creation_time
 * @property integer $update_time
 *
 * @property NewsInfo[] $newsInfos
 */
class News extends \common\components\BaseActiveRecord
{
    const IMG_COUNT     = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
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
    public function getNewsInfos()
    {
        return $this->hasMany(NewsInfo::className(), ['record_id' => 'id']);
    }
    public function getInfo()
    {
        return $this->hasOne(NewsInfo::className(), ['record_id' => 'id'])
            ->andWhere([NewsInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }

     public function getUrl()
     {
        return Url::to(['/news/'.$this->alias], true);
     }
     public static function find()
    {
        return new \common\models\Queries\NewsQuery(get_called_class());
    }
}
