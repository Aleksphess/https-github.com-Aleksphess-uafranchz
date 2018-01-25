<?php

namespace common\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\MainSliderInfo]].
 *
 * @see \app\models\MainSliderInfo
 */
class MainSliderInfoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\MainSliderInfo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\MainSliderInfo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
