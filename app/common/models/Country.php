<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property integer $id
 * @property integer $sort
 * @property integer $creation_time
 * @property integer $update_time
 *
 * @property CountryInfo[] $countryInfos
 */
class Country extends \common\components\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort', 'creation_time', 'update_time'], 'required'],
            [['sort', 'creation_time', 'update_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sort' => Yii::t('app', 'Sort'),
            'creation_time' => Yii::t('app', 'Creation Time'),
            'update_time' => Yii::t('app', 'Update Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountryInfos()
    {
        return $this->hasMany(CountryInfo::className(), ['record_id' => 'id']);
    }
    public function getInfo()
    {
        return $this->hasOne(CountryInfo::className(), ['record_id' => 'id'])
            ->andWhere([CountryInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }
    public static function getAllCountry()
    {
        return self::find()
            ->joinWith(['info'], true)


            ->all();
    }
}
