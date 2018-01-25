<?php

namespace frontend\widgets;

use Yii;
use common\models\CatalogCategories;



class HeadMenu extends \yii\base\Widget
{
    public function run()
    {
        $menu = CatalogCategories::find()->joinWith('info')
            ->all();
     //   var_dump($menu);die();
//var_dump(Yii::$app->user->identity->parent_id);
        return $this->render('head/menu.twig', [
            'menu'        => $menu,
             ]);
    }
}