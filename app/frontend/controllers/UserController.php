<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\SeoComponent;
use common\models\Lots;
use common\models\User;
use yii\imagine\Image;
use common\models\Payment;

class UserController extends \common\components\BaseController
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class'     => AccessControl::className(),
//                'only'      => ['index'],
                'rules'     => [
                    [
                        'actions'   => ['index', 'settings', 'change-settings','payment-static','lot','lots'],
                        'allow'     => true,
                        'roles'     => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index'                 => ['get'],
                    'settings'              => ['get'],
                    'change-settings'       => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    
    public function actionIndex()
    {
        /*$lots = Lots::find()
                ->innerJoinWith(['status'], true)
                ->with(['dialogs', 'dialogs.interlocutor'])
                ->andWhere(['lots.owner_id' => Yii::$app->user->identity->id])
                ->andWhere(['NOT', ['lots.status_id' => Lots::REMOVED]])
                ->orderBy('lots.status_id ASC')
                ->all();
        */
       
        
        return $this->render('old_index.twig', [
										   'user' => Yii::$app->user->identity->parent_id ]);
    }
    
    public function actionSettings()
    {
        SeoComponent::setByTemplate('default', [
            'name' => Yii::$app->user->identity->username,
        ]);
        $user = User::findIdentity(Yii::$app->user->identity->id);
        return $this->render('settings.twig', [
            'user'  => $user
        ]);
    }
    
    public function actionChangeSettings()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();

            $current_user = User::findIdentity(Yii::$app->user->identity->id);
            $current_user->username=trim(strip_tags($post['username']));
            $current_user->email=trim(strip_tags($post['email']));
            $current_user->phone=trim(strip_tags($post['phone']));
            $current_user->birth_day=trim(strip_tags($post['date']));
            $current_user->parent_id=trim(strip_tags($post['user_category']));
            $current_user->gender=trim(strip_tags($post['gender']));
            $current_user->site=trim(strip_tags($post['site']));
            $current_user->skype=trim(strip_tags($post['skype']));
            $current_user->city=trim(strip_tags($post['city']));
             $current_user->about=trim(strip_tags($post['about']));
          //  var_dump($current_user);die();
       // var_dump($_FILES['file-11']['error']);die();
        if($_FILES['file-11']['error'] != 4) // ошибка 4 - если нет файла. (см. документацию)
        {
            $count_files = count($_FILES['file-11']['name']);
            //var_dump($_FILES['file-11']['type']);die();

                $uploaddir = '../../../images/'.'user'.'/';

                $uploadfile = $uploaddir . 'user'.'.'.Yii::$app->user->identity->id.'.'.'1'.'.jpg';

                $mime = $_FILES['file-11']['type'];
              //  var_dump($mime);die();
                if($mime == 'image/jpeg'  || $mime == 'image/pjpeg' || $mime =='image/png')
                {

                    	//var_dump(move_uploaded_file($_FILES['file-11']['tmp_name'], $uploadfile));die();
                    if(move_uploaded_file($_FILES['file-11']['tmp_name'], $uploadfile)){
                        if($mime == 'image/jpeg'  || $mime == 'image/pjpeg')
                        {
                            $sImg = $uploaddir . Yii::$app->user->identity->id .'.'.(1).'.s.jpg';
                            $bImg = $uploaddir . Yii::$app->user->identity->id .'.'.(1).'.b.jpg';
                            unlink($_SERVER['DOCUMENT_ROOT'].'/images/user/'.Yii::$app->user->identity->id.".1.b.png");
                            unlink($_SERVER['DOCUMENT_ROOT'].'/images/user/'.Yii::$app->user->identity->id.".1.s.png");
                        }
                        else
                        {
                            $sImg = $uploaddir . Yii::$app->user->identity->id .'.'.(1).'.s.png';
                            $bImg = $uploaddir . Yii::$app->user->identity->id .'.'.(1).'.b.png';
                            unlink($_SERVER['DOCUMENT_ROOT'].'/images/user/'.Yii::$app->user->identity->id.".1.b.jpg");
                            unlink($_SERVER['DOCUMENT_ROOT'].'/images/user/'.Yii::$app->user->identity->id.".1.s.jpg");
                        }

                        //создаем маленькую картинку
                        Image::thumbnail($uploadfile, 180, 137)
                            ->save($sImg, ['quality' => 80]);
                        // создаем большую картинку
                        Image::thumbnail($uploadfile, 190, 119)
                            ->save($bImg, ['quality' => 80]);
                        // удаляем оригинал
                        unlink($uploadfile);
                    } else {
                        throw new \yii\base\Exception('Не удалось переместить файл!');
                    }
                } else {
                   return 'Недопустимый тип файла!';
                }


        }
           // $current_user->setAttributes($post);
            if( $current_user->save())
            {
            //    return $this->redirect(Url::to('/user/index'));
                return 'success';
            }
            return ['status' => false];


    }
    public function actionPaymentStatic()
    {
        $payment=Payment::findOne(['id'=>1]);
        $current_user=' пользователя '.Yii::$app->user->identity->username.' ID('.Yii::$app->user->identity->id.')';
        $date_30 = date_create(Yii::$app->user->identity->date_active);
        date_add($date_30, date_interval_create_from_date_string('30 days'));
        //var_dump($date);die();
        $liqpay_params_30 = [
            'currency'      => 'UAH',
            'amount'        => $payment->payment_30,
            'action'        => 'pay',
            'description'   => 'Продления членства на 30 дней до '.date_format($date_30, 'Y-m-d').$current_user
        ];
        $date_90 = date_create(Yii::$app->user->identity->date_active);
        date_add($date_90, date_interval_create_from_date_string('90 days'));
        //var_dump($date);die();
        $liqpay_params_90 = [
            'currency'      => 'UAH',
            'amount'        => $payment->payment_90,
            'action'        => 'pay',
            'description'   => 'Продления членства на 90 дней до '.date_format($date_90, 'Y-m-d').$current_user
        ];
        $date_180 = date_create(Yii::$app->user->identity->date_active);
        date_add($date_180, date_interval_create_from_date_string('180 days'));
        //var_dump($date);die();
        $liqpay_params_180 = [
            'currency'      => 'UAH',
            'amount'        => $payment->payment_180,
            'action'        => 'pay',
            'description'   => 'Продления членства на 180 дней до '.date_format($date_180, 'Y-m-d').$current_user
        ];
        $date_360 = date_create(Yii::$app->user->identity->date_active);
        date_add($date_360, date_interval_create_from_date_string('360 days'));
        //var_dump($date);die();
        $liqpay_params_360 = [
            'currency'      => 'UAH',
            'amount'        => $payment->payment_360,
            'action'        => 'pay',
            'description'   => 'Продления членства на 360 дней до '.date_format($date_360, 'Y-m-d').$current_user
        ];
        //var_dump($liqpay_params_1);die();
        $liqpay_button_30      = Yii::$app->liqpay->cnb_form($liqpay_params_30);
        $liqpay_button_90      = Yii::$app->liqpay->cnb_form($liqpay_params_90);
        $liqpay_button_180      = Yii::$app->liqpay->cnb_form($liqpay_params_180);
        $liqpay_button_360      = Yii::$app->liqpay->cnb_form($liqpay_params_360);
        $payment_date=
        [
            'date_30' => date_format($date_30, 'Y-m-d'),
            'date_90' => date_format($date_90, 'Y-m-d'),
            'date_180' => date_format($date_180, 'Y-m-d'),
            'date_360' => date_format($date_360, 'Y-m-d'),

        ];

        return $this->render('payment.twig', [
            'liqpay_button_30'=>$liqpay_button_30,
            'liqpay_button_90'=>$liqpay_button_90,
            'liqpay_button_180'=>$liqpay_button_180,
            'liqpay_button_360'=>$liqpay_button_360,
            'payment_date'  =>  $payment_date,
            'payment'       => $payment,
            ]);
    }
    public function actionLot($alias)
    {

        $lot = Lots::find()->byAlias($alias, Lots::tableName())->byUser()
            ->where([Lots::tableName().'.status_id' => Lots::IN_PUBLIC])
            ->where([Lots::tableName().'.alias' => $alias])
            ->limit(1)->one();
        if($lot->owner_id!=Yii::$app->user->identity->id)
        {
            throw new NotFoundHttpException('Not Found!', 404);

        }
        //var_dump($lot);
        return $this->render('lot.twig',[
            'lot'   =>  $lot,
        ]);
    }
    public function actionLots()
    {

        $lots = Lots::find()->byUser()
            ->all();
        //var_dump($lot);
        return $this->render('lots.twig',[
            'lots'   =>  $lots,
        ]);
    }
}