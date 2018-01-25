<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "blog_post".
 *
 * @property integer $id
 * @property string $alias
 * @property integer $active
 * @property integer $sort
 * @property string $custom_date
 * @property integer $creation_time
 * @property integer $update_time
 *
 * @property BlogPostInfo[] $blogPostInfos
 */
class BlogPost extends \common\components\BaseActiveRecord
{
    const IMG_COUNT = 1;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blog_post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'alias', 'active', 'custom_date', 'creation_time'], 'required'],
            [['id', 'active', 'sort', 'creation_time', 'update_time'], 'integer'],
            [['alias', 'custom_date'], 'string'],
            [['alias'], 'unique'],
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
            'active'            => Yii::t('app', 'Active'),
            'sort'              => Yii::t('app', 'Sort'),
            'custom_date'       => Yii::t('app', 'Custom Date'),
            'creation_time'     => Yii::t('app', 'Creation Time'),
            'update_time'       => Yii::t('app', 'Update Time'),
        ];
    }

    public function behaviors() {
        return [
            'thumb' => [
                'class' => \common\components\behavior\ImgBehavior::className()
            ],
            'timestamp' => [
                'class'              => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'creation_time',
                'updatedAtAttribute' => 'update_time',
            ],
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfos()
    {
        return $this->hasMany(BlogPostInfo::className(), ['record_id' => 'id'])
                ->where([BlogPostInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfo()
    {
        return $this->hasOne(BlogPostInfo::className(), ['record_id' => 'id'])
                ->where([BlogPostInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }

    /**
     * @inheritdoc
     * @return \common\models\Queries\BlogPostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\Queries\BlogPostQuery(get_called_class());
    }
    
    public function getHumanDate()
    {
        if(isset($this->custom_date) && !empty($this->custom_date))
        {
            return $this->custom_date;
        }
        else
        {
            return date("d.m.Y", $this->creation_time);    
        }
    }
    
    public function getUrl()
    {
        return Url::to(['/post/'.$this->alias], true);
    }
}