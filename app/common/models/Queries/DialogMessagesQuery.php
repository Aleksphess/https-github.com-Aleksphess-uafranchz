<?php

namespace common\models\Queries;

/**
 * This is the ActiveQuery class for [[\common\models\DialogMessages]].
 *
 * @see \common\models\DialogMessages
 */
class DialogMessagesQuery extends \common\components\BaseActiveQuery
{
    /**
     * @inheritdoc
     * @return \common\models\DialogMessages[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\DialogMessages|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    // прочитанные сообщения
    public function isRead()
    {
        return $this->andWhere([\common\models\DialogMessages::tableName().'.is_read' => 1]);
    }
    
    // не прочитанные сообщения
    public function notRead()
    {
        return $this->andWhere([\common\models\DialogMessages::tableName().'.is_read' => 0]);
    }
    
    // показанные сообщения
    public function showed()
    {
        return $this->andWhere([\common\models\DialogMessages::tableName().'.is_showed' => 1]);
    }
    
    // не показанные сообщения
    public function notShowed()
    {
        return $this->andWhere([\common\models\DialogMessages::tableName().'.is_showed' => 0]);
    }
}