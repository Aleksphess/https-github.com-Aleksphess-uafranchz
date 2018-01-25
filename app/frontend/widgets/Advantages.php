<?php

namespace frontend\widgets;

use Yii;




class Advantages extends \yii\base\Widget
{
    public function run()
    {

        return $this->render('advantages/view.twig');
    }
}