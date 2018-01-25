<?php

namespace frontend\widgets;

use Yii;
use common\models\News;



class NewsWidget extends \yii\base\Widget
{
    public function run()
    {
        $news = News::find()->active()->joinWith('info')->limit(2)->all();
        //   var_dump($menu);die();
//var_dump(Yii::$app->user->identity->parent_id);
        return $this->render('news/view.twig', [
            'news'        => $news,
        ]);
    }
}