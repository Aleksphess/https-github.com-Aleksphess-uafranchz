<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "dialog_messages".
 *
 * @property integer $id
 * @property integer $dialog_id
 * @property string $text
 * @property integer $user_from
 * @property integer $user_to
 * @property integer $is_showed
 * @property integer $is_read
 * @property integer $creation_time
 *
 * @property Dialogs $dialog
 */
class DialogMessages extends \common\components\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dialog_messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'text', 'user_from', 'user_to'], 'required'],
            [[ 'user_from', 'user_to', 'is_read', 'is_showed','creation_time'], 'integer'],
            [['text'], 'string'],
            [['text'], 'trim'],
          //  [['dialog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dialogs::className(), 'targetAttribute' => ['dialog_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => Yii::t('app', 'ID'),

            'text'              => Yii::t('app', 'Text'),
            'user_from'         => Yii::t('app', 'User From'),
            'user_to'           => Yii::t('app', 'User To'),
            'is_showed'         => Yii::t('app', 'Is Showed'),
            'is_read'           => Yii::t('app', 'Is Read'),
            'creation_time'     => Yii::t('app', 'Creation Time'),
        ];
    }

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
     * @return \yii\db\ActiveQuery
     */
   /* public function getDialog()
    {
        return $this->hasOne(Dialogs::className(), ['id' => 'dialog_id']);
    }*/

    /**
     * @inheritdoc
     * @return \common\models\Queries\DialogMessagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\Queries\DialogMessagesQuery(get_called_class());
    }
    public function getFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'user_from']);
    }
    public function getHumanCreationDate()
    {
        return date("H:i d F Y", $this->creation_time);
    }
    
    public static function countUnreadMessages()
    {
        return self::find()->where([DialogMessages::tableName().'.user_to' => Yii::$app->user->identity->id])    
                           ->notRead()
                           ->notShowed()
                           ->count();
    }
    
    public static function dialogUnreadMessages($dialog_id = null)
    {
        return self::find()->where([DialogMessages::tableName().'.user_to' => Yii::$app->user->identity->id])    

//                           ->notShowed()
                           ->notRead()
                           ->all();
    }
}