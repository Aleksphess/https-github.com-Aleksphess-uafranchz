<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "court_info".
 *
 * @property integer $record_id
 * @property string $lang
 * @property string $title
 * @property string $short_description
 * @property string $text
 *
 * @property Court $record
 */
class CourtInfo extends \common\components\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'court_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_id', 'lang', 'title', 'short_description', 'text'], 'required'],
            [['record_id'], 'integer'],
            [['short_description', 'text'], 'string'],
            [['lang'], 'string', 'max' => 3],
            [['title'], 'string', 'max' => 250],
            [['record_id'], 'exist', 'skipOnError' => true, 'targetClass' => Court::className(), 'targetAttribute' => ['record_id' => 'id']],
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
            'short_description' => Yii::t('app', 'Short Description'),
            'text' => Yii::t('app', 'Text'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(Court::className(), ['id' => 'record_id']);
    }
    public static function find()
    {
        return new \common\models\Queries\NewsInfoQuery(get_called_class());
    }
}
