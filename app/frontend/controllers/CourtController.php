<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use yii\helpers\Url;
use common\components\SeoComponent;
use common\models\Court;

class CourtController extends \common\components\BaseController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
//                'only'  => ['index', 'category', 'category-by-type'],
                'rules' => [
                    [
                        'actions'   => ['index', 'single'],
                        'allow'     => true,
                        'roles'     => ['?', '@'],
                    ],
                ],
            ],
            'verbs' => [
                'class'     => VerbFilter::className(),
                'actions'   => [
                    'index'                     => ['get'],
                    'single'                     => ['get'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $posts = Court::find()->joinWith(['info'], true)->active();
        $posts_count_query = clone $posts;
        $pages = new Pagination(['totalCount' => $posts_count_query->count(), 'pageSize' => 1]);

        $posts = $posts->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('sort DESC, custom_date DESC')
            ->all();

        /*        SeoComponent::setByTemplate('default', [
                    'name' => 'новости',
                ]);*/

        /*$breadcrumbs = [];
        $breadcrumbs[] = ['label' => "<span itemprop=\"title\">Блог</span>" ];*/
        return $this->render('index.twig', [
            'posts'         => $posts,
            'pages'         => $pages,

        ]);
    }

    public function actionSingle($alias)
    {
        $post = Court::find()
            ->joinWith(['info'], true)->active()
            ->where([Court::tableName().'.alias' => $alias])
            ->limit(1)
            ->one();

        if(is_null($post))
        {
            throw new NotFoundHttpException('Not Found!', 404);
        }

        /*$breadcrumbs = [];
        $breadcrumbs[] = ['label' => "<span itemprop=\"title\">Блог</span>",
            "url" => Url::to(['/blog/index', false]), 'itemprop' => 'url', 'class' => 'b-crumb__link' ];
        $breadcrumbs[] = ['label' => "<span itemprop=\"title\">".$post->info->title."</span>" ];

        SeoComponent::setByTemplate('default', [
            'name' => $post->info->title,
        ]);*/

        return $this->render('single.twig', [
            'post'          => $post,
            /*  'breadcrumbs'   => $breadcrumbs*/
        ]);
    }
}