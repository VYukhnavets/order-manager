<?php

namespace app\components;
	 
use yii\base\Widget;
use yii\helpers\Html;

class topMenuWidget extends Widget{
    public function init() {
        parent::init();
    }
    
    public function run() {
        if(!\Yii::$app->user->isGuest){
            echo $this->render('topMenu');
        }
    }
}