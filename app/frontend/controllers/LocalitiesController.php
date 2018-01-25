<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use common\models\Localities;

class LocalitiesController extends \common\components\BaseController
{
    
    public function behaviors()
    {
        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only'  => ['localities-autocomplete'],
//                'rules' => [
//                    [
//                        'actions'   => ['localities-autocomplete'],
//                        'allow'     => true,
//                        'roles'     => ['?'],
//                    ],
//                ],
//            ],
            'verbs' => [
                'class'     => VerbFilter::className(),
                'actions'   => [
                    'index'                     => ['get'],
                    'localities-autocomplete'   => ['get'],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionLocalitiesAutocomplete()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new BadRequestHttpException("Wrong request", 400);
        }
        $criteria = strip_tags(trim($request->get('city')));
        return Localities::getAutocompleteArray($criteria);
    }

}
