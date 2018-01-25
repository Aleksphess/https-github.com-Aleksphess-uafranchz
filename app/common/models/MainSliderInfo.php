<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "main_slider_info".
 *
 * @property integer $record_id
 * @property integer $lang
 * @property string $title
 * @property string $description
 * @property string $text
 *
 * @property MainSlider $record
 */
class MainSliderInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'main_slider_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_id', 'lang', 'title', 'description', 'text'], 'required'],
            [['record_id', 'lang'], 'integer'],
            [['title', 'description', 'text'], 'string'],
            [['record_id'], 'exist', 'skipOnError' => true, 'targetClass' => MainSlider::className(), 'targetAttribute' => ['record_id' => 'id']],
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
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'text' => Yii::t('app', 'Text'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(MainSlider::className(), ['id' => 'record_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\MainSliderInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\Queries\MainSliderInfoQuery(get_called_class());
    }
}
