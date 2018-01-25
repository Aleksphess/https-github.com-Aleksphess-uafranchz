<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "blog_post_info".
 *
 * @property integer $record_id
 * @property integer $lang
 * @property string $title
 * @property string $short_description
 * @property string $text
 *
 * @property BlogPost $record
 */
class BlogPostInfo extends \common\components\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blog_post_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_id', 'lang', 'title', 'short_description', 'text'], 'required'],
            [['record_id', 'lang'], 'integer'],
            [['title', 'short_description', 'text'], 'string'],
            [['record_id'], 'exist', 'skipOnError' => true, 'targetClass' => BlogPost::className(), 'targetAttribute' => ['record_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'record_id'         => Yii::t('app', 'Record ID'),
            'lang'              => Yii::t('app', 'Lang'),
            'title'             => Yii::t('app', 'Title'),
            'short_description' => Yii::t('app', 'Short Description'),
            'text'              => Yii::t('app', 'Text'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(BlogPost::className(), ['id' => 'record_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\Queries\BlogPostInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\Queries\BlogPostInfoQuery(get_called_class());
    }
}
