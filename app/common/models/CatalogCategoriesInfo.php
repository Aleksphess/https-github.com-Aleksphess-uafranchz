<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "catalog_categories_info".
 *
 * @property integer $record_id
 * @property integer $lang
 * @property string $name
 *
 * @property CatalogCategories $record
 */
class CatalogCategoriesInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_categories_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_id', 'lang', 'name'], 'required'],
            [['record_id', 'lang'], 'integer'],
            [['name'], 'string', 'max' => 150],
            [['text'], 'string'],
            [['record_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogCategories::className(), 'targetAttribute' => ['record_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'record_id' => Yii::t('app', 'Record ID'),
            'lang'      => Yii::t('app', 'Lang'),
            'name'      => Yii::t('app', 'Name'),
            'text'      => Yii::t('app', 'Text'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(CatalogCategories::className(), ['id' => 'record_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\Queries\CatalogCategoriesInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\Queries\CatalogCategoriesInfoQuery(get_called_class());
    }
}