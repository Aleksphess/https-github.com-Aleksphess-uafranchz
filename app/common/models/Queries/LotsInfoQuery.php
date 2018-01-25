<?php

namespace common\models\Queries;

/**
 * This is the ActiveQuery class for [[\common\models\CatalogCategoriesInfo]].
 *
 * @see \common\models\CatalogCategoriesInfo
 */
class LotsInfoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\CatalogCategoriesInfo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\CatalogCategoriesInfo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
