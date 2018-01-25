<?php

namespace common\components;

use Yii;
//use common\models\Pages;
use common\models\Lang;
use common\models\UserBookmarks;
use common\models\Dialogs;
use common\models\DialogMessages;
use common\models\Localities;
use common\models\Slovar;
//use app\models\Redirect;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class BaseController extends \yii\web\Controller
{
    public $default_content;

    protected function url_origin($use_forwarded_host = false )
    {
        $s        = $_SERVER;
        $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
        $sp       = strtolower( $s['SERVER_PROTOCOL'] );
        $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
        $port     = $s['SERVER_PORT'];
        $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
        $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
        $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }

    protected function full_url( $s, $use_forwarded_host = false )
    {
        return $this->url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
    }
    
    public function init()
    {
        parent::init();
       /* foreach (Yii::$app->user->identity->getBookmarks()->all() as $item) {
            var_dump($item->lot_id);
        }*/

        // редирект из таблицы редиректов. если находим в базе совпадение - редиректим
//        $absolute_url = $this->full_url();
//        $need_redirect = Redirect::find()->where(['redirect.redirect_from' => $absolute_url])->one();
//        
//        if(!is_null($need_redirect)) {
//            return $this->redirect($need_redirect->redirect_to, 301)->send();
//        }
        
        $this->layout = 'main.twig';
        
        $session = Yii::$app->session;
        $session->open();

        $lang                               = Lang::getCurrent();
        $this->view->params['lang']         = $lang;
        $this->view->params['lang_sh']      = mb_substr(($lang->name),0,3, 'utf-8');
        $langs                              = Lang::find()->all();
        $this->view->params['langs']        = $langs;
        $current_url                        = Yii::$app->request->pathinfo;

        $slovar = Slovar::find()
                        ->leftJoin('slovar_info', '`record_id`=`id`')
                        ->select(['slovar.alias', 'slovar_info.value'])
                        ->where(['lang' => Lang::getCurrentId()])
                        ->asArray()
                        ->all();
        $slovar = ArrayHelper::map($slovar, 'alias', 'value');
//        
        $this->view->params = array_merge($this->view->params, $slovar);
        
        if($lang->by_default)
        {
            $this->view->params['lang_url']     = '';
            Yii::$app->homeUrl                  = $this->view->params['home_url']='/';
            $this->view->params['current_url']  = $current_url ? "/{$current_url}": '/';
        }
        else
        {
            $this->view->params['lang_url']     = "/{$lang->url}";
            Yii::$app->homeUrl                  = $this->view->params['home_url']="/{$lang->url}/";
            $this->view->params['current_url']  = "/{$lang->url}/{$current_url}";
        }
        //var_dump(Yii::$app->user->identity->getImg());die();
        // найдем закладки лотов для залогиненного юзера
        $ids = [];
        if(!Yii::$app->user->isGuest)
        {
            $bookmarks = UserBookmarks::find()->select(['lot_id'])
                ->where([UserBookmarks::tableName().'.user_id' => Yii::$app->user->identity->id])
                ->asArray()->all();
            if(!is_null($bookmarks))
            {
                foreach ($bookmarks as $bmk)
                {
                    array_push($ids, $bmk['lot_id']);
                }
                $this->view->params['bookmarksIds'] = $ids;
            }
           /* $unread_msgs_count = DialogMessages::countUnreadMessages();
            $this->view->params['unread_msgs_count']= $unread_msgs_count;
            if($unread_msgs_count > 0)
            {
                $this->view->params['dialogs'] = Dialogs::dialogsWithUnreadMessages();
            }*/
        }
        if(strstr($_SERVER['REQUEST_URI'],'/user/edit/lot/')!==false)
        {
            $this->view->params['edit_script']=1;
        }
        
        $this->view->params['default_locality']= $default_locality;
        
        if(isset($_GET['page']) && !empty($_GET['page']) && (int)$_GET['page'] > 1)
        {
            Yii::$app->view->registerMetaTag([
                'name'    => 'robots',
                'content' => 'NOINDEX, NOFOLLOW'
            ]);
        }
        if(strstr($_SERVER['REQUEST_URI'],'/search/')!==false)
        {
            Yii::$app->view->registerMetaTag([
                'name'    => 'robots',
                'content' => 'NOINDEX, NOFOLLOW'
            ]);
        }
        if(strstr($_SERVER['REQUEST_URI'],'/login')!==false or strstr($_SERVER['REQUEST_URI'],'/user/')!==false or strstr($_SERVER['REQUEST_URI'],'/reset')!==false or strstr($_SERVER['REQUEST_URI'],'/dialogs/')!==false)
        {
            Yii::$app->view->registerMetaTag([
                'name'    => 'robots',
                'content' => 'NOINDEX, NOFOLLOW'
            ]);
        }



    }
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => '@frontend/views/content/404.twig',
            ],
        ];
    }
} 