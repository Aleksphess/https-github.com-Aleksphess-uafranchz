<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Question;
use common\models\Messaging;
use common\models\Callback;

class FormsController extends \common\components\BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions'   => ['callback','messaging','contact',
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
                    'callback'         => ['post'],
                    'messaging'         => ['post'],
                    'contact'           =>['post']
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (in_array($action->id, ['callback','messaging','contact'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionCallback()
    {
       // var_dump(1);die();
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new BadRequestHttpException("Wrong request", 400);
        }
        $post                   = $request->post();
        $model                  = new Callback();
        $model->name            = isset($post['name']) ? strip_tags($post['name']) : '';
        $model->phone           = isset($post['phone']) ? strip_tags($post['phone']) : '';
        if($model->save())
        {
            return 'Успешно';
        } else {
            foreach ($model->errors as $error)
            {
                return $error[0];
            }
        }
    }
    public function actionMessaging()
    {
        // var_dump(1);die();
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new BadRequestHttpException("Wrong request", 400);
        }
        $post                   = $request->post();
        $model                  = new Messaging();
        $model->email            = isset($post['email']) ? strip_tags($post['email']) : '';

        if($model->save())
        {
            return 'Успешно';
        } else {
            foreach ($model->errors as $error)
            {
                return $error[0];
            }
        }
    }
    public function actionContact()
    {
        // var_dump(1);die();
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new BadRequestHttpException("Wrong request", 400);
        }
        $post                   = $request->post();
        $model                  = new Question();
        $model->name            = isset($post['name']) ? strip_tags($post['name']) : '';
        $model->question           = isset($post['text']) ? strip_tags($post['text']) : '';
        $model->emai            = isset($post['email']) ? strip_tags($post['email']) : '';

        if($model->save())
        {
            return 'Успешно';
        } else {
            foreach ($model->errors as $error)
            {
                return $error[0];
            }
        }
    }

}