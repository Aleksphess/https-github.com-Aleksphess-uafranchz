<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "dialogs".
 * @property integer $id
 * @property string $subject
 * @property integer $lot_id
 * @property integer $lot_owner
 * @property integer $interlocutor_id
 * @property integer $status
 * @property integer $creation_time
 * @property DialogMessages[] $dialogMessages
 * @property Lots $lot
 * @property User $lotOwner
 * @property User $interlocutor
 */
class Dialogs extends \common\components\BaseActiveRecord
{
    const ACTIVE    = 1;
    const INACTIVE  = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dialogs';
    }

    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'              => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'creation_time',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */


    /**
     * @return \yii\db\ActiveQuery
     */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnreadMessages()
    {
        return $this->hasMany(DialogMessages::className(), ['dialog_id' => 'id'])
                ->andWhere([DialogMessages::tableName().'.is_read' => 0])
                ->andWhere([DialogMessages::tableName().'.user_to' => 41])
                ->orderBy(DialogMessages::tableName().'.creation_time DESC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */


    /**
     * @return \yii\db\ActiveQuery
     */


    /**
     * @return \yii\db\ActiveQuery
     */


    /**
     * Get count of unread messages of concrete dialog
     * @return type
     */
    public function getFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'user_from']);
    }
    public function getUnreadMessagesCountByDialog()
    {
        return DialogMessages::find()
                ->andWhere(['dialog_id' => $this->id, 'user_to' => \Yii::$app->user->identity->id])
                ->notRead()
                ->count();
    }

    /**
     * Get count of unread messages of user dialogs
     * @return type
     */
    public static function getUnreadMessagesCount()
    {
        return DialogMessages::find()
                ->where(['user_to' => \Yii::$app->user->identity->id])
                ->notRead()
                ->count();
    }

    public function getUrl()
    {
        return Url::to(['/dialog/'.$this->id], true);
    }

    public function getHumanCreationDate()
    {
        return date("H:i d F Y", $this->creation_time);
    }

    /**
     * @inheritdoc
     * @return \common\models\Queries\DialogsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\Queries\DialogsQuery(get_called_class());
    }

    public static function dialogsWithUnreadMessages()
    {
        $result  = [];
        $dialogs = self::find()
                ->joinWith(['unreadMessages', 'lot'], true)
                ->where([DialogMessages::tableName().'.user_to' => Yii::$app->user->identity->id])
                ->groupBy('`dialogs`.`id`')
                ->having('COUNT(*) > 0')
                ->all();

        if(!is_null($dialogs))
        {
            foreach ($dialogs as $dialog)
            {
                $messages = [];
                foreach($dialog->unreadMessages as $message)
                {
                    $messages[] = ['text' => $message->text, 'time' => $message->creation_time];
                }
                $result[] = [
                    'from'      => $dialog->interlocutor->username,
                    'title'     => $dialog->lot->name,
                    'url'       => Url::to(['/dialog/'.$dialog->id], true),
                    'count'     => $dialog->unreadMessagesCountByDialog,
                    'messages'  => $messages
                ];
            }
        }
        return $result;
    }
}