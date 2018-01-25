<?php

namespace common\models\Queries;

/**
 * This is the ActiveQuery class for [[\common\models\Dialogs]].
 *
 * @see \common\models\Dialogs
 */
class DialogsQuery extends \common\components\BaseActiveQuery
{

    /**
     * @inheritdoc
     * @return \common\models\Dialogs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Dialogs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    public function byId($id)
    {
        return $this->andWhere([\common\models\Dialogs::tableName().'.id' => $id]);
    }
    
    public function active()
    {
        return $this->andWhere([\common\models\Dialogs::tableName().'.status' => 1]);
    }
}