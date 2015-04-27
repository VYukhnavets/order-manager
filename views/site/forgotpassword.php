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
        <?php if(!$linkSent) : ?>
            <?php $form = ActiveForm::begin([
                'id' => 'restorepwd-form',
                'options' => [],
                'fieldConfig' => [
                    'template' => "<div class=\"form-group\">{label}:\n{input}{error}</div>",
                    'labelOptions' => [],
                ],
            ]); ?>
            <?php if($model->getErrors()) : 
                echo $form->errorSummary($model, ['class'=>'alert alert-danger']);
            else : ?>
                <div class="alert alert-info">Enter the email address associated with your account. CircleShout will send you an email with a link to reset your password.</div>
            <?php endif; ?>
            <?= $form->field($model, 'email') ?>
            <br>
            <p><?= Html::submitButton('Submit', ['class' => 'btn btn-default', 'name' => 'submit-button']) ?></p>
            <?php ActiveForm::end(); ?>
        <?php else : ?>
            <div class="alert alert-info">CircleShout has sent an email to <a href="mailto:<?=$model->email;?>"><?=$model->email;?></a>. Please click on that link in the email to reset your password.</div>
            <br>
            <p><a href="<?=yii\helpers\Url::home();?>" class="btn btn-default">OK</a></p>
        <?php endif;?>
</div>