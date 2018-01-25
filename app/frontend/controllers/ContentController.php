<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\components\SeoComponent;
use common\models\Pages;
use common\models\Lots;
use common\models\MainSlider;

class ContentController extends \common\components\BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'static', 'contacts','login','reset'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],

                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['static', 'contacts','login','reset'],
                        'allow' => true,
                        'roles' => ['@', '?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'index' => ['get'],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        $all_lots = Lots::find()
            ->where(['lots.active'=>1])->andWhere(['>=','lots.date_active' ,date('Y-m-d')])
            ->limit(6)
            ->orderBy('mark DESC')
            ->all();
        foreach ($all_lots as $lot)
        {
            $lot->date_active=\common\models\User::findIdentity($lot->owner_id)->date_active;
            $lot->update(false);
        }

        $lots = Lots::find()
                ->where(['lots.active'=>1])->andWhere(['>=','lots.date_active' ,date('Y-m-d')])
                ->limit(9)
                ->orderBy('mark DESC')
                ->all();


        $slids=MainSlider::find()->joinWith('info')->all();
        //var_dump($lots_slider);
    /*    $new_lots = Lots::find()->active()
                ->with(['locality', 'category', 'category.info', 'category.parent'])
                ->orderBy(Lots::tableName().'.creation_time DESC')
                ->limit(10)
                ->all();

        $blog_posts = BlogPost::find()
                ->innerJoinWith(['info'], true)
                ->active()
//                ->orderBy(BlogPost::tableName().'.sort DESC')
                ->limit(10)
                ->all();
        */
        SeoComponent::setByTemplate('default', [
            'name' => 'Франчайзинг',
        ]);
        
        return $this->render('index.twig', [
            'lots'          => $lots,
            'slids'       =>    $slids,
        ]);
    }
    
    public function actionStatic($alias)
    {
        $page = Pages::find()->innerJoinWith(['info'], true)
                ->andWhere([Pages::tableName().'.alias' => $alias])->limit(1)->one();
        
        if(is_null($page))
        {
            throw new NotFoundHttpException('404! Not found!');
        }
        else
        {
            SeoComponent::setByTemplate('default', [
                'name' => $page->info->name,
            ]);
            $breadcrumbs = [];
            $breadcrumbs[] = ['label' => "<span itemprop=\"title\">".$page->info->name."</span>" ];
            return $this->render('static.twig', [
                'breadcrumbs' => $breadcrumbs,
                'page'        => $page
            ]);
        }
    }
    public function actionAbout()
    {
      /*  SeoComponent::setByTemplate('default', [
            'name' => 'Контакты',
        ]);*/
        /*$breadcrumbs = [];
        $breadcrumbs[] = ['label' => "<span itemprop=\"title\">Контакты</span>" ];*/
        return $this->render('about.twig', [

        ]);
    }
    public function actionMembership()
    {
        /*  SeoComponent::setByTemplate('default', [
              'name' => 'Контакты',
          ]);*/
        /*$breadcrumbs = [];
        $breadcrumbs[] = ['label' => "<span itemprop=\"title\">Контакты</span>" ];*/
        return $this->render('membership.twig', [

        ]);
    }
    public function actionContacts()
    {
        SeoComponent::setByTemplate('default', [
            'name' => 'Контакты',
        ]);
        $breadcrumbs = [];
        $breadcrumbs[] = ['label' => "<span itemprop=\"title\">Контакты</span>" ];
        return $this->render('contacts.twig', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
    public function actionLogin()
    {
        return $this->render('signin.twig');
    }
    public function actionFindFranchise()
    {
        return $this->render('find-franchise.twig');
    }
    public function actionSellFranchise()
    {
        return $this->render('sell-franchise.twig');
    }
    public function actionReset()
    {
        return $this->render('reset.twig');
    }
    public function actionResetPassword($reset='')
    {
        $id=\common\models\User::find()->where(['password_reset_token'=>$reset])->limit(1)->one()->id;
        if (is_null($id))
        {
            throw new NotFoundHttpException('404! Not found!');
        }
        return $this->render('reset-password.twig',['id'=>$id]);
    }

}