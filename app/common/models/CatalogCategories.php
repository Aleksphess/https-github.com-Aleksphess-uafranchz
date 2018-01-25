<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "catalog_categories".
 *
 * @property integer $id
 * @property string $alias
 * @property integer $parent_id
 * @property integer $active
 * @property integer $sort
 * @property integer $creation_time
 * @property integer $update_time
 *
 * @property CatalogCategoriesInfo[] $catalogCategoriesInfos
 */
class CatalogCategories extends \common\components\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'active', 'creation_time', 'update_time'], 'required'],
            [['parent_id', 'active', 'sort', 'creation_time', 'update_time'], 'integer'],
            [['alias'], 'string', 'max' => 100],
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
//            'thumb' => [
//                'class' => \common\components\behavior\ImgBehavior::className()
//            ],
            'translit' => [
                'class' => \common\components\behavior\TranslitBehavior::className()
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('app', 'ID'),
            'alias'         => Yii::t('app', 'Alias'),
            'parent_id'     => Yii::t('app', 'Parent ID'),
            'active'        => Yii::t('app', 'Active'),
            'sort'          => Yii::t('app', 'Sort'),
            'creation_time' => Yii::t('app', 'Creation Time'),
            'update_time'   => Yii::t('app', 'Update Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogCategoriesInfos()
    {
        return $this->hasMany(CatalogCategoriesInfo::className(), ['record_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfo()
    {
        return $this->hasOne(CatalogCategoriesInfo::className(), ['record_id' => 'id'])
                ->andWhere([CatalogCategoriesInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveLots()
    {
        return $this->hasMany(Lots::className(), ['category_id' => 'id'])
                ->where([Lots::tableName().'.status_id' => Lots::IN_PUBLIC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSellingLots()
    {
        return $this->hasMany(Lots::className(), ['category_id' => 'id'])
                ->where([Lots::tableName().'.is_need' => 0])
                ->andWhere([Lots::tableName().'.status_id' => Lots::IN_PUBLIC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuyingLots()
    {
        return $this->hasMany(Lots::className(), ['category_id' => 'id'])
                ->where([Lots::tableName().'.is_need' => 1])
                ->andWhere([Lots::tableName().'.status_id' => Lots::IN_PUBLIC]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLots()
    {
        return $this->hasMany(Lots::className(), ['category_id' => 'id']);
    }
    
    /**
     * @inheritdoc
     * @return \common\models\Queries\CatalogCategoriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\Queries\CatalogCategoriesQuery(get_called_class());
    }
    
    public function getChilds()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'id'])->orderBy("sort DESC");
    }
    
    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }
    
//    public function getParent()
//    {
//        return self::find()->joinWith(['info'], true)
//                ->andWhere([self::tableName().'.id' => $this->parent_id])->limit(1)->one();
//    }
    
    public function getUrl()
    {
        return Url::to(['/category/'.$this->alias], true);
    }
    
    public function getSellUrl()
    {
        return Url::to(['/category/sell/'.$this->alias], true);
    }
    
    public function getBuyUrl()
    {
        return Url::to(['/category/buy/'.$this->alias], true);
    }
    
    public function getActiveLotsCount()
    {
        return Lots::find()->andWhere([
            Lots::tableName().'.category_id' => $this->id,
            Lots::tableName().'.status_id' => Lots::IN_PUBLIC
        ])->count();
    }
    
    public function getActiveSellingLotsCount()
    {
        return Lots::find()->andWhere([
            Lots::tableName().'.category_id' => $this->id,
            Lots::tableName().'.status_id' => Lots::IN_PUBLIC,
            Lots::tableName().'.is_need' => 0
        ])->count();
    }
    
    public function getActiveBuyingLotsCount()
    {
        return Lots::find()->andWhere([
            Lots::tableName().'.category_id' => $this->id,
            Lots::tableName().'.status_id' => Lots::IN_PUBLIC,
            Lots::tableName().'.is_need' => 1
        ])->count();
    }
    
    public static function getAllActiveCategories()
    {
        return self::find()
            ->joinWith(['info'], true)
            ->with(['childs'])
            ->andWhere([self::tableName().'.parent_id' => -1])
            ->active()
            ->all();
    }
}