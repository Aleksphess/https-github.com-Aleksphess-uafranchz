<?php

namespace common\models\Queries;
use Yii;
/**
 * This is the ActiveQuery class for [[\common\models\Lots]].
 *
 * @see \common\models\Lots
 */
class LotsQuery extends \common\components\BaseActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\Lots[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Lots|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    public function byUser()
    {
        return $this->andWhere(['`lots`.`owner_id`'=>Yii::$app->user->identity->id]);
    }
    public function suggestion()
    {
        return $this->andWhere(['`lots`.`is_need`' => 0]);
    }
    
    public function need()
    {
        return $this->andWhere(['`lots`.`is_need`' => 1]);
    }
    
    
   
    
}
