<?php
use yii\helpers\Html;
?>
<nav class="navbar navbar-default">
        <div class="container">
                <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                        </button>
                </div>
                <div class="collapse navbar-collapse" id="main-navbar">
                        <ul class="nav navbar-nav">
                            <li<?=(Yii::$app->controller->id == 'default' || !empty(Yii::$app->controller->module->id) ? ' class="active"' : '');?>><a href="<?=  \yii\helpers\Url::to(['/default/index'])?>">Order Manager</a></li>
                            <li<?=(Yii::$app->controller->id == 'csemployee' ? ' class="active"' : '');?>><a href="<?=  \yii\helpers\Url::to(['/csemployees'])?>">Employees</a></li>
                        </ul>
                </div>
        </div>
</nav>