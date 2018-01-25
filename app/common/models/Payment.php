<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property integer $id
 * @property string $name
 * @property string $payment_30
 * @property string $payment_90
 * @property string $payment_180
 * @property string $payment_360
 */
class Payment extends \common\components\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'payment_30', 'payment_90', 'payment_180', 'payment_360'], 'required'],
            [['name', 'payment_30', 'payment_90', 'payment_180', 'payment_360'], 'string', 'max' => 250],
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
            'payment_30' => Yii::t('app', 'Payment 30'),
            'payment_90' => Yii::t('app', 'Payment 90'),
            'payment_180' => Yii::t('app', 'Payment 180'),
            'payment_360' => Yii::t('app', 'Payment 360'),
        ];
    }
}
