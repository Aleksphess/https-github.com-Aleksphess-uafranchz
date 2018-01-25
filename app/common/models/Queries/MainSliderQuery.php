<?php

namespace common\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\MainSlider]].
 *
 * @see \app\models\MainSlider
 */
class MainSliderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\MainSlider[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\MainSlider|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
