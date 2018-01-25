<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\base\ErrorException;
use common\components\SeoComponent;
use common\models\CatalogCategories;
use common\models\Lots;
use common\models\Dialogs;
use common\models\Localities;
use common\models\Country;
use yii\imagine\Image;
use yii\web\User;

class LotsController extends \common\components\BaseController
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'edit', 'close-lot'],
                'rules' => [
                    [
                        'actions'   => ['create', 'edit', 'close-lot', 'test'],
                        'allow'     => true,
                        'roles'     => ['@'],
                    ],
                    [ 
                        'actions'   => ['index'],
                        'allow'     => true,
                        'roles'     => ['?','@'],
                    ],
                ],
            ],
            'verbs' => [
                'class'     => VerbFilter::className(),
                'actions'   => [
                    'index'             => ['get'],
                    'create'            => ['get'],
                    'edit'              => ['get'],
                    'save-lot'          => ['post'],
                    'lots-json'         => ['get'],
                    'close-lot'         => ['post'],
                ],
            ],
            'translit' => [
                'class'     => \common\components\behavior\TranslitBehavior::className()
            ],
        ];
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    
    public function actionIndex($alias)
    {
        $lot = Lots::find()
                ->byAlias($alias, Lots::tableName())
                ->where([Lots::tableName().'.status_id' => Lots::IN_PUBLIC])
                ->where([Lots::tableName().'.alias' => $alias])
                ->andWhere([Lots::tableName().'.active' => 1])
                ->andWhere(['>=','lots.date_active' ,date('Y-m-d')])
            ->limit(1)->one();
        //var_dump($lot);
       // $lot->getPhotos();
     //   var_dump();die();
        if(is_null($lot))
        {
            throw new NotFoundHttpException('Not Found! ', 404);
        }
        

        
        if(Yii::$app->getRequest()->getUserIP() != $lot->last_ip)
        {
            $lot->views_count = $lot->views_count + 1;    
        }
        $lot->last_ip = Yii::$app->getRequest()->getUserIP();
        $lot->date_active=\common\models\User::findIdentity($lot->owner_id)->date_active;
        $lot->update(false);
        
        $country=Country::find()->joinWith('info')->where(['id'=>$lot->country_id])->one();
        return $this->render('index.twig', [
            'lot'           => $lot,
            'country'       => $country,
           
            'is_guest'      => Yii::$app->user->isGuest
        ]);
    }
    
    public function actionCreate()
    {
       
        
        return $this->render('create.twig', [
            'categories' => CatalogCategories::getAllActiveCategories(),
            'country' => Country::getAllcountry(),
        ]);
    }
    public static function  translit($q)
    {
        $q = trim($q);

        $q = str_replace("А","A",$q);
        $q = str_replace("Б","B",$q);
        $q = str_replace("В","V",$q);
        $q = str_replace("Г","G",$q);
        $q = str_replace("Д","D",$q);
        $q = str_replace("Е","E",$q);
        $q = str_replace("Є","E",$q);
        $q = str_replace("Ж","Zh",$q);
        $q = str_replace("З","Z",$q);
        $q = str_replace("И","I",$q);
        $q = str_replace("Й","J",$q);
        $q = str_replace("І","I",$q);
        $q = str_replace("Ї","I",$q);
        $q = str_replace("К","K",$q);
        $q = str_replace("Л","L",$q);
        $q = str_replace("М","M",$q);
        $q = str_replace("Н","N",$q);
        $q = str_replace("О","O",$q);
        $q = str_replace("П","P",$q);
        $q = str_replace("Р","R",$q);
        $q = str_replace("С","S",$q);
        $q = str_replace("Т","T",$q);
        $q = str_replace("У","U",$q);
        $q = str_replace("Ф","F",$q);
        $q = str_replace("Х","H",$q);
        $q = str_replace("Ц","C",$q);
        $q = str_replace("Ч","Ch",$q);
        $q = str_replace("Ш","Sh",$q);
        $q = str_replace("Щ","Sch",$q);
        $q = str_replace("Э","E",$q);
        $q = str_replace("Ю","U",$q);
        $q = str_replace("Я","Ya",$q);
        $q = str_replace("Ь","",$q);
        $q = str_replace("Ъ","",$q);
        $q = str_replace("Ы","u",$q);

        $q = str_replace("а","a",$q);
        $q = str_replace("б","b",$q);
        $q = str_replace("в","v",$q);
        $q = str_replace("г","g",$q);
        $q = str_replace("д","d",$q);
        $q = str_replace("е","e",$q);
        $q = str_replace("є","e",$q);
        $q = str_replace("ж","zh",$q);
        $q = str_replace("з","z",$q);
        $q = str_replace("и","i",$q);
        $q = str_replace("й","j",$q);
        $q = str_replace("і","i",$q);
        $q = str_replace("ї","i",$q);
        $q = str_replace("к","k",$q);
        $q = str_replace("л","l",$q);
        $q = str_replace("м","m",$q);
        $q = str_replace("н","n",$q);
        $q = str_replace("о","o",$q);
        $q = str_replace("п","p",$q);
        $q = str_replace("р","r",$q);
        $q = str_replace("с","s",$q);
        $q = str_replace("т","t",$q);
        $q = str_replace("у","u",$q);
        $q = str_replace("ф","f",$q);
        $q = str_replace("х","h",$q);
        $q = str_replace("ц","c",$q);
        $q = str_replace("ч","ch",$q);
        $q = str_replace("ш","sh",$q);
        $q = str_replace("щ","sch",$q);
        $q = str_replace("э","e",$q);
        $q = str_replace("ю","u",$q);
        $q = str_replace("я","ya",$q);
        $q = str_replace("ь","",$q);
        $q = str_replace("ъ","",$q);
        $q = str_replace("ы","u",$q);

        $q = str_replace("("," ",$q);
        $q = str_replace(")"," ",$q);
        //$q = str_replace("."," ",$q);
        $q = str_replace(","," ",$q);
        $q = str_replace(":"," ",$q);
        $q = str_replace(";"," ",$q);
        $q = str_replace("@"," ",$q);
        $q = str_replace("!"," ",$q);
        $q = str_replace("`"," ",$q);
        $q = str_replace("'"," ",$q);
        $q = str_replace("\""," ",$q);
        $q = str_replace("#"," ",$q);
        $q = str_replace("$"," ",$q);
        $q = str_replace("%"," ",$q);
        $q = str_replace("^"," ",$q);
        $q = str_replace("&"," ",$q);
        $q = str_replace("*"," ",$q);
        $q = str_replace("_"," ",$q);
        $q = str_replace("="," ",$q);
        $q = str_replace("+"," ",$q);
        $q = str_replace("<"," ",$q);
        $q = str_replace(">"," ",$q);
        $q = str_replace("?"," ",$q);
        $q = str_replace("/"," ",$q);

        $q = trim($q);

        $q = str_replace("  "," ",$q);
        $q = str_replace(" ","-",$q);
        return strtolower($q);
    }
    public function actionSaveLot()
    {
        if(Yii::$app->user->isGuest)
        {
            throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        $post = Yii::$app->request->post();
       // var_dump($post);die();
       /*// var_dump(self::translit($post['name']));die();
        if(isset($post['alias']) && !empty($post['alias']))
        {
            $alias  = trim(strip_tags($post['alias']));
            $lot    = Lots::find()->byAlias($alias)->limit(1)->one();
        }
        
        if(is_null($lot) || !isset($lot))
        {

			
        }*/
        $lot                    = new Lots();
       // $lot->status_id         = Lots::IN_PUBLIC;
        $lot->alias             = self::translit($post['name']);
       // var_dump($post);die();
        
        $lot->name              = trim(strip_tags($post['name']));
        $lot->category_id       = (int)trim(strip_tags($post['business']));
        $lot->owner_id          = Yii::$app->user->identity->id;
        $lot->text              = trim(strip_tags($post['description']));
        $lot->country_id      = trim(strip_tags($post['country']));
        $lot->rus      = trim(strip_tags($post['rus']));
        $lot->ukr      = trim(strip_tags($post['ukr']));
        $lot->kz      = trim(strip_tags($post['kaz']));
        $lot->bel      = trim(strip_tags($post['blr']));
        $lot->mol      = trim(strip_tags($post['mda']));
        $lot->advantages_1      = trim(strip_tags($post['advantage_1']));
        $lot->advantages_2      = trim(strip_tags($post['advantage_2']));
        $lot->advantages_3      = trim(strip_tags($post['advantage_3']));
        $lot->advantages_4      = trim(strip_tags($post['advantage_4']));
        $lot->country_from      = trim(strip_tags($post['country']));
        $lot->itself_point      = trim(strip_tags($post['own_business']));
        $lot->franchise_point      = trim(strip_tags($post['franchise_business']));
        $lot->country_for_use   = trim(strip_tags($post['countries']));
        $lot->personal_mins      = trim(strip_tags($post['worker_min']));
        $lot->personal_max      = trim(strip_tags($post['worker_max']));
        $lot->square_min = trim(strip_tags($post['area_min']));
        $lot->square_max = trim(strip_tags($post['area_max']));
        $lot->requirements_room = trim(strip_tags($post['requirements']));
        $lot->country_for_use      = trim(strip_tags($post['countries']));
        $lot->last_ip           = Yii::$app->getRequest()->getUserIP();
        $lot->suggestions_count = 0;
        $lot->views_count       = 0;
        $lot->begin_date        = date('Y-m-d', date('U'));
        $lot->end_date          = date('Y-m-d', date('U') + 604800); // добавляем 7 дней к текущей дате. пока так
        $lot->date_active=\common\models\User::findIdentity($lot->owner_id)->date_active;
        
        if($lot->save())
        {
            /*if($_FILES['files']['error'][0] != 4) // ошибка 4 - если нет файла. (см. документацию)
            {
                $count_files = count($_FILES['files']['name']);
                for($i = 0; $i < $count_files; $i++){
                    $uploaddir = '../../../images/'.Lots::tableName().'/';
                    $uploadfile = $uploaddir . Lots::tableName().'.'.$lot->id.'.'.$i.'.jpg';
                    $mime = $_FILES['files']['type'][$i];

                    if($mime == 'image/jpeg'  || $mime == 'image/pjpeg' )
                    {
						
					//	var_dump(move_uploaded_file($_FILES['files']['tmp_name'][$i], $uploadfile));die();
                        if(move_uploaded_file($_FILES['files']['tmp_name'][$i], $uploadfile)){
                            $sImg = $uploaddir . $lot->id .'.'.($i+1).'.s.jpg';
                            $bImg = $uploaddir . $lot->id .'.'.($i+1).'.b.jpg';
                            //создаем маленькую картинку
                            Image::thumbnail($uploadfile, 180, 137)
                                  ->save($sImg, ['quality' => 80]);
                            // создаем большую картинку
                            Image::thumbnail($uploadfile, 730, 497)
                                ->save($bImg, ['quality' => 80]);
                            // удаляем оригинал
                            unlink($uploadfile);
                        } else {
                            throw new \yii\base\Exception('Не удалось переместить файл!');
                        }
                    } else {
                        throw new \yii\base\Exception('Недопустимый тип файла!');
                    }

                }
            }*/
            $this->view->title = 'Лот успешно сохранен!';
           // var_dump($this->view->title);die();
            $headers  = "Content-type: text/html; charset=UTF-8 \r\n";
            $headers .= "From:franch.ua \r\n";
            if(mail('aleksphesspro@gmail.com','Новая франшиза на franch.ua','У вас появился новая франшиза'.$lot->name, $headers))
            {
                return $this->redirect(Url::to('/user/edit/lot/'.$lot->alias));
            }
        }
        else
        {
            $this->view->title = 'Лот не сохранен!';
            return $this->render('lot_not_saved.twig', ['error' => $lot->getErrors()]);
        }
    }
    
    public function actionEdit($alias = '')
    {
        $lot = Lots::find()->byAlias($alias)->limit(1)->one();
        //  $category = CatalogCategories::find()->joinWith('info')->all();
        if(is_null($lot))
        {
            throw new NotFoundHttpException('Not Found! ', 404);
        }

        if($lot->owner_id != Yii::$app->user->identity->id)
        {
            throw new ErrorException('You are not an owner of this lot!', 403);
        }

        SeoComponent::setByTemplate('default', [
            'name' => $lot->name,
        ]);

        setcookie("lot_id", $lot->id,time()+3600 * 24 * 7, '/');

     //   var_dump($_COOKIE);


		

        return $this->render('edit.twig', [
            'lot'           => $lot,
            'categories'    => CatalogCategories::getAllActiveCategories(),
            'country' => Country::getAllcountry(),
        ]);

    }
    public function actionEditBasic()
    {
        $post = Yii::$app->request->post();
        $lot = Lots::find()->byAlias($post['alias'])->limit(1)->one();
        //  $category = CatalogCategories::find()->joinWith('info')->all();
        //var_dump($post);die();
        if(is_null($lot))
        {
            throw new NotFoundHttpException('Not Found! ', 404);
        }

        if($lot->owner_id != Yii::$app->user->identity->id)
        {
            throw new ErrorException('You are not an owner of this lot!', 403);
        }

        SeoComponent::setByTemplate('default', [
            'name' => $lot->name,
        ]);

        $post = Yii::$app->request->post();
        //var_dump($post);die();

        $lot->name              = trim(strip_tags($post['name']));
        $lot->category_id       = (int)trim(strip_tags($post['business']));
        $lot->owner_id          = Yii::$app->user->identity->id;
        $lot->text              = trim(strip_tags($post['description']));
        $lot->rus      = trim(strip_tags($post['rus']));
        $lot->ukr      = trim(strip_tags($post['ukr']));
        $lot->kz      = trim(strip_tags($post['kaz']));
        $lot->bel      = trim(strip_tags($post['blr']));
        $lot->mol      = trim(strip_tags($post['mda']));
        $lot->country_id      = trim(strip_tags($post['country']));
        $lot->advantages_1      = trim(strip_tags($post['advantage_1']));
        $lot->advantages_2      = trim(strip_tags($post['advantage_2']));
        $lot->advantages_3      = trim(strip_tags($post['advantage_3']));
        $lot->advantages_4      = trim(strip_tags($post['advantage_4']));
        $lot->country_from      = trim(strip_tags($post['country']));
        $lot->itself_point      = trim(strip_tags($post['own_business']));
        $lot->franchise_point      = trim(strip_tags($post['franchise_business']));
        $lot->country_for_use   = trim(strip_tags($post['countries']));
        $lot->personal_mins      = trim(strip_tags($post['worker_min']));
        $lot->personal_max      = trim(strip_tags($post['worker_max']));
        $lot->square_min = trim(strip_tags($post['area_min']));
        $lot->square_max = trim(strip_tags($post['area_max']));
        $lot->requirements_room = trim(strip_tags($post['requirements']));
        $lot->country_for_use      = trim(strip_tags($post['countries']));
        if ($lot->save())
        {
            //$this->redirect('/user/edit/lot/krutaya-franshiza#basic');
            return 'success';
        }


        /*return $this->render('edit.twig', [
            'lot'           => $lot,
            'categories'    => CatalogCategories::getAllActiveCategories()
        ]);*/
    }
    public function actionEditCost()
    {
        $post = Yii::$app->request->post();
        $lot = Lots::find()->byAlias($post['alias'])->limit(1)->one();
        //  $category = CatalogCategories::find()->joinWith('info')->all();
        if(is_null($lot))
        {
            throw new NotFoundHttpException('Not Found! ', 404);
        }

        if($lot->owner_id != Yii::$app->user->identity->id)
        {
            throw new ErrorException('You are not an owner of this lot!', 403);
        }

        SeoComponent::setByTemplate('default', [
            'name' => $lot->name,
        ]);


        //var_dump($post);die();

        $lot->contribution_min       = trim(strip_tags($post['fee_min']));
        $lot->contribution_max       = (int)trim(strip_tags($post['fee_max']));

        $lot->contribution_is_empty  = trim(strip_tags($post['fee']));
        $lot->contribution_text      = trim(strip_tags($post['fee_desc']));
        $lot->invest_min      = trim(strip_tags($post['investment_min']));
        $lot->invest_max      = trim(strip_tags($post['investment_max']));
        $lot->invest_text      = trim(strip_tags($post['investment_desc']));
        $lot->occupation_min      = trim(strip_tags($post['return_period_min']));
        $lot->occupation_max      = trim(strip_tags($post['return_period_max']));
        $lot->profit_min      = trim(strip_tags($post['return_min']));
        $lot->profit_max   = trim(strip_tags($post['return_max']));
        $lot->dop_settings      = trim(strip_tags($post['franchise_extras']));
        /*$lot->personal_max      = trim(strip_tags($post['worker_max']));
        $lot->square_min = trim(strip_tags($post['area_min']));
        $lot->square_max = trim(strip_tags($post['area_max']));
        $lot->requirements_room = trim(strip_tags($post['requirements']));
        $lot->country_for_use      = trim(strip_tags($post['country']));*/
        if ($lot->save())
        {
            return 'success';
           // $this->redirect('/user/edit/lot/krutaya-franshiza#cost');
        }


        /*return $this->render('edit.twig', [
            'lot'           => $lot,
            'categories'    => CatalogCategories::getAllActiveCategories()
        ]);*/
    }
    public function actionEditExtra()
    {
        $post = Yii::$app->request->post();
        $lot = Lots::find()->byAlias($post['alias'])->limit(1)->one();
        //  $category = CatalogCategories::find()->joinWith('info')->all();
        if(is_null($lot))
        {
            throw new NotFoundHttpException('Not Found! ', 404);
        }

        if($lot->owner_id != Yii::$app->user->identity->id)
        {
            throw new ErrorException('You are not an owner of this lot!', 403);
        }

        SeoComponent::setByTemplate('default', [
            'name' => $lot->name,
        ]);
        $current_user =  \common\models\User::findIdentity(Yii::$app->user->identity->id);
        // var_dump($post);die();
        $current_user->work_year     = trim(strip_tags($post['work_year']));
        $current_user->franchise_year     = trim(strip_tags($post['franchise_year']));

        $lot->city_in_Russia       = trim(strip_tags($post['rus_cities']));
        $lot->city_in_Ukraine       = trim(strip_tags($post['ukr_cities']));

        $lot->other_city  = trim(strip_tags($post['other_cities']));
        $lot->avg_price      = trim(strip_tags($post['services_cost']));
        $lot->basic_group      = trim(strip_tags($post['services_desc']));
        $lot->format_franchise      = trim(strip_tags($post['franchise_formats']));
        $lot->else_settings      = trim(strip_tags($post['franchise_conditions']));

        if ($lot->save() && $current_user->save())
        {
            return 'success';
            //$this->redirect('/user/edit/lot/krutaya-franshiza#extra');
        }


        /*return $this->render('edit.twig', [
            'lot'           => $lot,
            'categories'    => CatalogCategories::getAllActiveCategories()
        ]);*/
    }
    public function actionEditContact()
    {
        $post = Yii::$app->request->post();

        $lot = Lots::find()->byAlias($post['alias'])->limit(1)->one();
        //  $category = CatalogCategories::find()->joinWith('info')->all();
        if(is_null($lot))
        {
            throw new NotFoundHttpException('Not Found! ', 404);
        }

        if($lot->owner_id != Yii::$app->user->identity->id)
        {
            throw new ErrorException('You are not an owner of this lot!', 403);
        }

        SeoComponent::setByTemplate('default', [
            'name' => $lot->name,
        ]);
        $current_user =  \common\models\User::findIdentity(Yii::$app->user->identity->id);
        // var_dump($post);die();
        $current_user->skype     = trim(strip_tags($post['messanger']));
        $current_user->phone     = trim(strip_tags($post['phone']));
        $current_user->username     = trim(strip_tags($post['owner_name']));
        $current_user->email     = trim(strip_tags($post['email']));



        if ( $current_user->save())
        {
            return 'success';
            //$this->redirect('/user/edit/lot/krutaya-franshiza#extra');
        }


        /*return $this->render('edit.twig', [
            'lot'           => $lot,
            'categories'    => CatalogCategories::getAllActiveCategories()
        ]);*/
    }
    public function actionEditMedia()
    {
        $post = Yii::$app->request->post();
        //var_dump($_FILES);die();
        $lot = Lots::find()->byAlias($post['alias-3'])->limit(1)->one();
        if(is_null($lot))
        {
            throw new NotFoundHttpException('Not Found! ', 404);
        }

        if($lot->owner_id != Yii::$app->user->identity->id)
        {
            throw new ErrorException('You are not an owner of this lot!', 403);
        }

        SeoComponent::setByTemplate('default', [
            'name' => $lot->name,
        ]);


        if($_FILES['logotip']['error'] != 4) // ошибка 4 - если нет файла. (см. документацию)
        {


                $uploaddir = '../../../images/'.Lots::tableName().'/';
                $uploadfile = $uploaddir . Lots::tableName().'.logo.'.$lot->id.'.png';
                $mime = $_FILES['logotip']['type'];

                if($mime =='image/png' || $mime == 'image/jpeg'  || $mime == 'image/pjpeg'  )
                {
                       // var_dump($uploadfile);die();
                    if(move_uploaded_file($_FILES['logotip']['tmp_name'], $uploadfile)){
                        if($mime == 'image/jpeg'  || $mime == 'image/pjpeg')
                        {
                            $sImg = $uploaddir . $lot->id .'.'.(1).'.s.jpg';
                            $bImg = $uploaddir . $lot->id .'.'.(1).'.b.jpg';
                            unlink($_SERVER['DOCUMENT_ROOT'].'/images/lots/'.$lot->id.".1.b.png");
                            unlink($_SERVER['DOCUMENT_ROOT'].'/images/lots/'.$lot->id.".1.s.png");
                        }
                        else
                        {
                            $sImg = $uploaddir . $lot->id .'.'.(1).'.s.png';
                            $bImg = $uploaddir . $lot->id .'.'.(1).'.b.png';
                            unlink($_SERVER['DOCUMENT_ROOT'].'/images/lots/'.$lot->id.".1.b.jpg");
                            unlink($_SERVER['DOCUMENT_ROOT'].'/images/lots/'.$lot->id.".1.s.jpg");
                        }

                        //создаем маленькую картинку
                        Image::thumbnail($uploadfile, 321, 352)
                            ->save($sImg, ['quality' => 80]);
                        // создаем большую картинку
                        Image::thumbnail($uploadfile, 509, 368)
                            ->save($bImg, ['quality' => 80]);
                        // удаляем оригинал
                        unlink($uploadfile);
                        return 'success';
                    } else {
                        throw new \yii\base\Exception('Не удалось переместить файл!');
                    }
                } else {
                    return 'Недопустимый тип файла!';
                }

        }
    }
    public function actionLotsJson()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new BadRequestHttpException('Wrong request!', 400);
        }
        $get    = $request->get();
        $lots   = Lots::find()->innerJoinWith(['locality'], true)
                    ->active()
                    ->where([Lots::tableName().'.is_need' => (int)$get['sellOrBuy']])
                    ->andFilterWhere([Lots::tableName().'.category_id' => strip_tags(trim($get['rubric']))])
                    ->andFilterWhere(['like', Lots::tableName().'.name', strip_tags(trim($get['search']))]);
        $dateRange = isset($get['dateRange']) ? (int)$get['dateRange'] : 0;
        if(isset($get['radius']) && !empty($get['radius']))
        {
            // фильтр по расстоянию
            $locality = Localities::findOne((int)$get['cityId']);
            if(!is_null($locality))
            {
                $radius = ((int)$get['radius'] + $locality->radius_correction) * 1000;
                $coordinates_range = Yii::$app->geo->calculateCoordinatesRange($locality->latitude, $locality->longitude, $radius);                            
                $lots = $lots->andWhere(['between', Lots::tableName().'.latitude', $coordinates_range['latitude_from'], $coordinates_range['latitude_to'] ])
                             ->andWhere(['between', Lots::tableName().'.longitude', $coordinates_range['longitude_from'], $coordinates_range['longitude_to'] ]);
            }
        } else {
            $lots = $lots->andFilterWhere([Lots::tableName().'.locality_id' => (int)$get['cityId']]);
        }
        
        switch ((int)$dateRange)
        { // фильтр по дате
            case 1:
                $lots = $lots->andWhere('`begin_date` >= CURDATE()');
                break;
            case 2:
                $lots = $lots->andWhere('`begin_date` >= CURDATE() + INTERVAL -1 DAY');
                break;
            case 3:
                $lots = $lots->andWhere('`begin_date` >= CURDATE() + INTERVAL -2 DAY');
                break;
            default: 
        }
        $lots = $lots->all();
        
        return Lots::generateLotsMap($lots);
    }
    public function actionDeleteImg()
    {
        $post=Yii::$app->request->post();
        unlink($_SERVER['DOCUMENT_ROOT'].$post['img']);
        unlink($_SERVER['DOCUMENT_ROOT'].str_replace('b','s',$post['img']));

        return true;
    }
    public function actionCloseLot()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new BadRequestHttpException('Wrong request!', 400);
        }   
        
        $lot_id = (int)$request->post('lot_id');
        $lot = Lots::findOne($lot_id);
        if(is_null($lot))
        {
            throw new NotFoundHttpException('Lot wasnt found!', 404);
        }
        
        $lot->status_id = Lots::SUCCESSED;
        if($lot->save())
        {
            $dialogs = Dialogs::find()->where(['lot_id' => $lot->id])->all();
            if(!is_null($dialogs))
            {
                foreach ($dialogs as $dialog)
                {
                    $dialog->status = Dialogs::INACTIVE;
                    $dialog->save();
                }
            }
            return ['ans' => true, 'msg' => true];
        }
        else
        {
            return ['ans' => false, 'msg' => serialize($lot->getErrors())];
        }
    }
   
}