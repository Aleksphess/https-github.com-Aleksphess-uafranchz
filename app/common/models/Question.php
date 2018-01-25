<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property integer $id
 * @property string $name
 * @property string $emai
 * @property string $question
 * @property integer $sort
 * @property integer $creation_time
 * @property integer $update_time
 */
class Question extends \common\components\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'emai', 'question'], 'required'],
            [['sort', 'creation_time', 'update_time'], 'integer'],
            [['name', 'emai', 'question'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'emai' => Yii::t('app', 'Emai'),
            'question' => Yii::t('app', 'Question'),
            'sort' => Yii::t('app', 'Sort'),
            'creation_time' => Yii::t('app', 'Creation Time'),
            'update_time' => Yii::t('app', 'Update Time'),
        ];
    }
}
