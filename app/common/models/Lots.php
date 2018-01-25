<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "lots".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property integer $category_id
 * @property integer $is_need
 * @property string $text
 * @property integer $locality_id
 * @property string $address
 * @property integer $status_id
 * @property integer $views_count
 * @property string $begin_date
 * @property string $end_date
 * @property double $longitude
 * @property double $latitude
 * @property integer $suggestions_count
 * @property integer $owner_id
 * @property integer $is_premium
 * @property string $last_ip
 * @property integer $creation_time
 * @property integer $update_time
 *
 * @property CatalogCategories $category
 * @property User $owner
 * @property LotStatuses $status
 */
class Lots extends \common\components\BaseActiveRecord
{
    const IN_PUBLIC     = 1;
    const HIDDEN        = 2;
    const SUCCESSED     = 3;
    const REMOVED       = 4;
    
    const IMG_COUNT     = 3; // количество обрабатываемых картинок для лота

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lots';
    }

    /**
     * @inheritdoc
     */
   /* public function rules()
    {
        return [
            [['name', 'alias', 'category_id', 'text', 'begin_date', 'end_date', 'owner_id'], 'required'],
            [['category_id', 'views_count', 'suggestions_count', 'owner_id', 'is_premium', 'creation_time', 'update_time'], 'integer'],
            [['text'], 'string'],
            [['begin_date', 'end_date'], 'safe'],
           
            [['name', 'address'], 'string', 'max' => 250],
            [['alias'], 'string', 'max' => 260],
            [['last_ip'], 'string', 'max' => 45],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogCategories::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => LotStatuses::className(), 'targetAttribute' => ['status_id' => 'id']],
        ];
    }*/

    /**
     * @inheritdoc
     */
    /*public function attributeLabels()
    {
        return [
            'id'                => Yii::t('app', 'ID'),
            'name'              => Yii::t('app', 'Name'),
            'alias'             => Yii::t('app', 'Alias'),
            'category_id'       => Yii::t('app', 'Category ID'),
           // 'is_need'           => Yii::t('app', 'Спрос или предложение. 0 - предложение. 1 - спрос'),
            'text'              => Yii::t('app', 'Text'),
            'locality_id'       => Yii::t('app', 'Locality'),
            'address'           => Yii::t('app', 'Address'),
            'status_id'         => Yii::t('app', 'статус лота'),
            'views_count'       => Yii::t('app', 'количество просмотров'),
            'begin_date'        => Yii::t('app', 'дата начала'),
            'end_date'          => Yii::t('app', 'лот активен до этой даты'),
            'longitude'         => Yii::t('app', 'долгота'),
            'latitude'          => Yii::t('app', 'широта'),
            'suggestions_count' => Yii::t('app', 'количество предложений (диалогов привязанных к лоту)'),
            'owner_id'          => Yii::t('app', 'владелец лота'),
            'is_premium'        => Yii::t('app', 'находится ли лот в топе'),
            'last_ip'           => Yii::t('app', 'ip адрес человека, который смотрел лот последним'),
            'creation_time'     => Yii::t('app', 'Creation Time'),
            'update_time'       => Yii::t('app', 'Update Time'),
        ];
    }*/

    public function behaviors() 
    {
        return [
            'timestamps' => [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'creation_time',
                'updatedAtAttribute' => 'update_time',
            ],
            'thumb' => [
                'class' => \common\components\behavior\ImgBehavior::className()
            ],
//            'translit' => [
//                'class' => \common\components\behavior\TranslitBehavior::className()
//            ],
        ];
    }
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CatalogCategories::className(), ['id' => 'category_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocality()
    {
        return $this->hasOne(Localities::className(), ['id' => 'locality_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDialogs()
    {
        return $this->hasMany(Dialogs::className(), ['lot_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserDialogs()
    {
        return $this->hasMany(Dialogs::className(), ['lot_id' => 'id'])
                ->where([Dialogs::tableName().'.lot_owner' => \Yii::$app->user->identity->id])
                ->orWhere([Dialogs::tableName().'.interlocutor_id' => \Yii::$app->user->identity->id]);
    }
    public function getPhotos() {
        $i = 1;
        $res = [];

         // var_dump();die();
        $files=scandir( $_SERVER['DOCUMENT_ROOT'].'app/frontend/web/server/php/files/user/'.$this->id.'/',1);
        foreach ($files as $file)
        {
           // var_dump(1)
            if(is_file($_SERVER['DOCUMENT_ROOT'].'app/frontend/web/server/php/files/user/'.$this->id.'/'.$file))
            {

                $res[] = [
                    'bimg'=>'/app/frontend/web/server/php/files/user/'.$this->id.'/'.$file,
                    'simg'=>'/app/frontend/web/server/php/files/user/'.$this->id.'/thumbnail/'.$file,
                ];
            }
        }


      //      var_dump($res);die();


        return $res;
    }
    /**
     * @inheritdoc
     * @return \common\models\Queries\LotsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\Queries\LotsQuery(get_called_class());
    }
    public function getLogos()
    {
        $path_png = __DIR__ . "/../../../userfiles/png/".$this->logo;


        if(is_file($path_png))
        {
            return '/userfiles/png/'.$this->logo;
        }

        else
        {
            return '/images/no-img.png';
        }
    }
    public function getObl()
    {
        $path_png = __DIR__ . "/../../../userfiles/jpg/".$this->ph;


        if(is_file($path_png))
        {
            return '/userfiles/jpg/'.$this->ph;
        }

        else
        {
            return '/images/no-img.png';
        }
    }
    public function getMinilogo()
    {
        $path_png = __DIR__ . "/../../../images/lots/logo.".$this->id.'.png';


        if(is_file($path_png))
        {
            return "/images/lots/logo.".$this->id.'.png';
        }
        else
        {
            return '/';
        }
    }

    public function getUrl()
    {
        return Url::to(['/lot/'.$this->alias], true);
    }
    public function getUserUrl()
    {
        return Url::to(['/user/lot/'.$this->alias], true);
    }


    public function getEditUrl()
    {
        return Url::to(['/lots/edit/'.$this->alias], true);
    }
    
    public function getHumanCreationDate()
    {
        return date("H:i d F Y", $this->creation_time);
    }
    
    // цвета маркеров на карте
    public static function colors()
    {
        return ["green", "purple", "red", "blue", "yellow", "grey"];
    }
    
    public static function colorsCount()
    {
        return count(self::colors());
    }

    // генерим массив для фронта, чтобы отображать лоты на карте
    // из контроллера выдаем в json 

    
}