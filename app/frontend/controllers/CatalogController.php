<?php

namespace frontend\controllers;

use Yii;
use yii\data\Pagination;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\components\SeoComponent;
use common\models\CatalogCategories;
use common\models\Lots;

class CatalogController extends \common\components\BaseController
{
    
       public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
//                'only'  => ['index', 'category', 'category-by-type'],
                'rules' => [
                    [
                        'actions'   => ['index', 'category', 'category-with-lots-types', 'filter'],
                        'allow'     => true,
                        'roles'     => ['?', '@'],
                    ],
                ],
            ],
            'verbs' => [
                'class'     => VerbFilter::className(),
                'actions'   => [
                    'index'                     => ['get'],
                    'category'                  => ['get'],
                    'category-with-lots-types'  => ['get'],
                    'filter'                    => ['get']
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
        return $this->render('index');
    }
    
    public function actionCategory( $alias=NULL)
    {
		//var_dump(1);die();
        if ($alias != null) {
            $category = CatalogCategories::find()->joinWith('info')
                ->byAlias($alias, CatalogCategories::tableName())
                ->active()
                ->limit(1)
                ->one();
            if(is_null($category))
            {
                throw new NotFoundHttpException('Not Found!', 404);
            }
        }
        $country=\common\models\Country::find()->all();
        $invest=\common\models\Invest::find()->all();
        //var_dump($country,$invest);die();
        //die();
        //$lots = Lots::find()->joinWith('info')->where(['category_id'=>$category->id])->all();
        if ($alias != null)
        {
            $query=Lots::find()->where(['lots.active'=>1])->andWhere(['>=','lots.date_active' ,date('Y-m-d')])->andWhere(['category_id'=>$category->id]);

        }
        else
        {
            $query=Lots::find()->where(['lots.active'=>1])->andWhere(['>=','lots.date_active' ,date('Y-m-d')]);
        }
        $query_count=$query->count();
        $pages = new Pagination(['totalCount' =>$query_count , 'pageSize' => 3]);
        $lots=$query->offset($pages->offset)->limit($pages->limit)->orderBy('views_count desc')->all();
        /*   SeoComponent::setByTemplate('default', [
            'name' => $category->info->name,
        ]);
	    */          
        $breadcrumbs = [];


            $breadcrumbs[] = ['label' => "<span itemprop=\"title\">".$category->info->name."</span>" ];
            return $this->render('category.twig', [
                'category'  => $category,
                'lots'      => $lots,
                'pages'     => $pages,
                'breadcrumbs'   => $breadcrumbs,
                'country'       => $country,
                'invest'        => $invest
            ]);

    }
    public function actionFilter( )
    {
        $post=Yii::$app->request->get();
        $investments = explode('-',$post['investments']);
        $alias=$post['category'];
        $filter=[
            'invest_min'=>$investments[0],
            'invest_max'=>$investments[1],
            'country_id'=>$post['region'],
            'sort'      =>$post['sort_by'],
        ];

        if ($alias != null) {
            $category = CatalogCategories::find()->joinWith('info')
                ->byAlias($alias, CatalogCategories::tableName())
                ->active()
                ->limit(1)
                ->one();
            if(is_null($category))
            {
                throw new NotFoundHttpException('Not Found!', 404);
            }
        }
        $country=\common\models\Country::find()->all();
        $invest=\common\models\Invest::find()->all();
        //var_dump($country,$invest);die();
        //die();
        //$lots = Lots::find()->joinWith('info')->where(['category_id'=>$category->id])->all();
        if ($alias != null)
        {
            $query=Lots::find()->where(['lots.active'=>1])->andWhere(['>=','lots.date_active' ,date('Y-m-d')])->andWhere(['category_id'=>$category->id])
                ->andWhere(['>=','invest_min',$investments[0]])
                ->andWhere(['<=','invest_max',$investments[1]])
                ->andWhere(['country_id'=>$post['region']])
                ->orderBy('invest_min '.$post['sort_by'].', invest_max '.$post['sort_by']);

        }
        else
        {
            $query=Lots::find()->where(['lots.active'=>1])->andWhere(['>=','lots.date_active' ,date('Y-m-d')])
                ->andWhere(['>=','invest_min',$investments[0]])
                ->andWhere(['<=','invest_max',$investments[1]])
                ->andWhere(['country_id'=>$post['region']])
                ->orderBy('invest_min '.$post['sort_by'].', invest_max '.$post['sort_by']);
        }
        $query_count=$query->count();
        $pages = new Pagination(['totalCount' =>$query_count , 'pageSize' => 3]);
        $lots=$query->offset($pages->offset)->limit($pages->limit)->orderBy('views_count desc')->all();
        /*   SeoComponent::setByTemplate('default', [
            'name' => $category->info->name,
        ]);
	    */
        $breadcrumbs = [];


        $breadcrumbs[] = ['label' => "<span itemprop=\"title\">".$category->info->name."</span>" ];
        return $this->render('category.twig', [
            'category'  => $category,
            'lots'      => $lots,
            'pages'     => $pages,
            'breadcrumbs'   => $breadcrumbs,
            'country'       => $country,
            'invest'        => $invest,
            'filter'           => $filter
        ]);

    }
    
//    public function actionCategoryWithLotsTypes(string $type, string $alias)
//    {
//        switch($type)
//        {
//            case "sell":
//                $lots_rel = 'sellingLots';
//                break;
//            case "buy":
//                $lots_rel = 'buyingLots';
//                break;
//            default:
//                $lots_rel = 'activeLots';
//        }
//
//        $category = CatalogCategories::find()->byAlias($alias, CatalogCategories::tableName())
//                            ->innerJoinWith(['info', 'lots'], true)
//                            ->andWhere([\common\models\Lots::tableName().'.is_need' => 1])
//                            ->active()
//                            ->limit(1)
//                            ->one();
////        var_dump($lots_rel);
////        var_dump($category->buyingLots);
////        die();
//        if(is_null($category))
//        {
//            throw new NotFoundHttpException('Not Found!', 404);
//        }
//        
//        $breadcrumbs = [];
//
//        if($category->parent_id < 1)
//        {
//            $breadcrumbs[] = ['label' => "<span itemprop=\"title\">".$category->info->name."</span>" ];
//            return $this->render('subcategories.twig', [
//                'category'      => $category,
//                'breadcrumbs'   => $breadcrumbs
//            ]);
//        }
//        
//        $breadcrumbs[] = ['label' => "<span itemprop=\"title\">".$category->parent->info->name."</span>",
//        "url" => $category->parent->url, 'itemprop' => 'url', 'class' => 'b-crumb__link' ];
//        $breadcrumbs[] = ['label' => "<span itemprop=\"title\">".$category->info->name."</span>" ];
//        
//        SeoComponent::setByTemplate('default', [
//            'name' => $category->info->name,
//        ]);
//        
//        return $this->render('category.twig', [
//            'category'      => $category,
//            'breadcrumbs'   => $breadcrumbs
//        ]);
//    }
}