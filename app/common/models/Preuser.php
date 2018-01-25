<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "preuser".
 *
 * @property integer $id
 * @property string $name
 * @property string $password
 * @property string $email
 * @property string $phone
 * @property string $city
 * @property integer $parent_id
 */
class Preuser extends \common\components\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'preuser';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'password', 'email', 'phone', 'city', 'parent_id'], 'required'],
            [['name', 'password', 'email', 'phone', 'city'], 'string'],
            [['parent_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'password' => Yii::t('app', 'Password'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'city' => Yii::t('app', 'City'),
            'parent_id' => Yii::t('app', 'Parent ID'),
        ];
    }
}
