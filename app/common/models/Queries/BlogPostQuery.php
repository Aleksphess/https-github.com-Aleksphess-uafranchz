<?php

namespace common\models\Queries;

/**
 * This is the ActiveQuery class for [[\common\models\BlogPost]].
 *
 * @see \common\models\BlogPost
 */
class BlogPostQuery extends \common\components\BaseActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\BlogPost[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\BlogPost|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    public function active()
    {
        return $this->andWhere([\common\models\BlogPost::tableName().'.active' => 1]);
    }
}
