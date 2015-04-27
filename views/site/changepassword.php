<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Forgot your password?';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if($key_valid) : ?>
        <?php $form = ActiveForm::begin([
            'id' => 'changepwd-form',
            'options' => [],
            'fieldConfig' => [
                'template' => "<div class=\"form-group\">{label}:\n{input}{error}</div>",
                'labelOptions' => [],
            ],
        ]); ?>
        <?php if($model->getErrors()) : 
            echo $form->errorSummary($model, ['class'=>'alert alert-danger']);
        else : ?>
            <div class="alert alert-info">Please reset your password by entering and confirming a new password.</div>
        <?php endif; ?>
        <?= $form->field($model, 'new_password')->passwordInput(); ?>
        <?= $form->field($model, 'confirm_new_password')->passwordInput(); ?>
        <br>
        <p><?= Html::submitButton('Reset Password', ['class' => 'btn btn-default', 'name' => 'submit-button']) ?></p>
        <?php ActiveForm::end(); ?>
    <?php else : ?>
        <?=Html::errorSummary($model, ['class'=>'alert alert-danger']);?>
    <?php endif; ?>
</div>