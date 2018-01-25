<?php

namespace common\models;

use Yii;
use app\components\behavior\ImgBehavior;

/**
 * This is the model class for table "main_slider".
 *
 * @property integer $id
 * @property string $href
 * @property integer $sort
 * @property integer $creation_time
 * @property integer $update_time
 */
class MainSlider extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'main_slider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['href', 'creation_time'], 'required'],
            [['href'], 'string'],
            [['sort', 'creation_time', 'update_time'], 'integer'],
        ];
    }

    public function behaviors()
    {
        return [
            'thumb' => [
                'class' => \common\components\behavior\ImgBehavior::className()
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'href' => Yii::t('app', 'Href'),
            'sort' => Yii::t('app', 'Sort'),
            'creation_time' => Yii::t('app', 'Creation Time'),
            'update_time' => Yii::t('app', 'Update Time'),
        ];
    }
	 public function getInfos()
    {
        return $this->hasMany(MainSliderInfo::className(), ['record_id' => 'id'])->where([MainSliderInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }
    public function getLogos()
    {
        $path_png = __DIR__ . "/../../../userfiles/png/".$this->logo;


        if(is_file($path_png))
        {
            return '/userfiles/png/'.$this->logo;
        }

        else
        {
            return '/images/no-img.png';
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfo()
    {
        return $this->hasOne(MainSliderInfo::className(), ['record_id' => 'id'])->where([MainSliderInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }
    /**
     * @inheritdoc
     * @return \app\models\Queries\MainSliderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\Queries\MainSliderQuery(get_called_class());
    }
}
