<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use common\components\SeoComponent;
use common\models\ContactForm;

class FeedbackController extends \common\components\BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions'   => ['create-contact-request', 
                                        'index', 
                                        ],
                        'allow'     => true,
                        'roles'     => ['@', '?'],
                    ],
                ],
            ],
            'verbs' => [
                'class'     => VerbFilter::className(),
                'actions'   => [
                    'create-contact-request'         => ['post'],
                ],
            ],
        ];
    }
    
    public function beforeAction($action)
    {
        if (in_array($action->id, ['create-contact-request'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    
    public function actionCreateContactRequest()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new BadRequestHttpException("Wrong request", 400);
        }
        $post                   = $request->post();
        $model                  = new ContactForm();
        $model->name            = isset($post['name']) ? strip_tags($post['name']) : '';
        $model->email           = isset($post['email']) ? strip_tags($post['email']) : '';
        $model->phone           = isset($post['phone']) ? strip_tags($post['phone']) : '';
        $model->text            = isset($post['text']) ? strip_tags($post['text']) : '';
        $model->is_processed    = 0;
        if($model->save())
        {
            return ['status' => true, 'msg' => ''];
        } else {
            return ['status' => false, 'msg' => serialize($model->getErrors())];
        }
    }

}