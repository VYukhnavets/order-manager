<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Log In';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => [],
            'fieldConfig' => [
                'template' => "<div class=\"form-group\">{label}:\n{input}{error}</div>",
                'labelOptions' => [],
            ],
        ]); ?>
        <?php if($model->getErrors()) : 
            echo $form->errorSummary($model, ['class'=>'alert alert-danger']);
        endif; ?>
        <?= $form->field($model, 'username') ?>
        
        <?= $form->field($model, 'password')->passwordInput() ?>
        
        <?= $form->field($model, 'rememberMe', [
                'template' => "<div class=\"checkbox\"><label>{input}</label></div>",
            ])->checkbox() ?>
        <br>
        <p><?= Html::submitButton('Log In', ['class' => 'btn btn-default', 'name' => 'login-button']) ?></p>
        <br>
        <?=Html::a('Forgot your password?', ['site/forgotpassword']);?>
        <?php ActiveForm::end(); ?>
</div>