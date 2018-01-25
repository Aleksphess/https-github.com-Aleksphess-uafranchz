<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
/**
 * This is the model class for table "pages".
 *
 * @property string $icon
 * @property string $route
 * @property integer $nomenu
 * @property string $text
 * @property integer $parent_id
 * @property integer $sort
 * @property integer $creation_time
 * @property integer $update_time
 *
 * @property PagesInfo[] $pagesInfos
 */
class Pages extends \common\components\BaseActiveRecord
{
    
    const IMG_COUNT = 45;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
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

    public function img_count_const()
    {
        return self::IMG_COUNT;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'nomenu', 'parent_id', 'sort'], 'required'],
            [['icon'], 'string'],
            [['nomenu', 'parent_id', 'sort', 'creation_time', 'update_time'], 'integer'],
            [['alias'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                    => Yii::t('app', 'ID'),
            'alias'                 => Yii::t('app', 'Alias'),
            'name'                  => Yii::t('app', 'Name'),
            'icon'                  => Yii::t('app', 'Icon'),
            'text'                  => Yii::t('app', 'Text'),
            'parent_id'             => Yii::t('app', 'Parent ID'),
            'sort'                  => Yii::t('app', 'Sort'),
            'creation_time'         => Yii::t('app', 'Creation Time'),
            'update_time'           => Yii::t('app', 'Update Time'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfos()
    {
        return $this->hasMany(PagesInfo::className(), ['record_id' => 'id'])
                ->where([PagesInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfo()
    {
        return $this->hasOne(PagesInfo::className(), ['record_id'=>'id'])
                ->where([PagesInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }

    /**
     * @inheritdoc
     * @return \frontend\models\Queries\Pages the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\Queries\Pages(get_called_class());
    }

    public function getParent()
    {
        return $this::find()->byId($this->parent_id)->one();
    }

    public function getUrl()
    {
        if ($this->alias == 'main')
        {
            return Yii::$app->getHomeUrl();
        }
        elseif($this->alias != '')
        {
            return Url::to(['/' . $this->alias], true);
        }
        else
        {
            return '';
        }
    }

    public function hasChildren()
    {
        return self::find()->select('id')->childPages($this->id)->count();
    }

}