<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

use app\components\topMenuWidget;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="/css/extra.css" />
    <script src="/js/scripts.js"></script>
    <script async src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.min.js"></script>
    <!--[if lt IE 9]>
            <script src="https://cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php $this->beginBody() ?>
<div class="page-wrap">
	<header>
		<div class="container">
			<div class="row">
				<div class="col-sm-9">
					<span class="header-logo"><a href="/"><img src="/img/logo.png" alt="" /></a></span>
					<span class="header-title">Order Manager</span>
				</div>
				<div class="col-sm-3 text-right">
                                    <?php if(!\Yii::$app->user->isGuest): ?>
                                    <div class="header-account">
                                            <span class="header-account-greeting">Hello,</span>
                                            <div class="btn-group header-account-name text-left">
                                                    <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                                                        <?php echo (\Yii::$app->user->identity->username);?> &nbsp;<span class="caret"></span></button>
                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                        <li><?=Html::a('Sign Out', '/site/logout', ['data-method' => 'post']);?></li>
                                                        <li><a href="/site/switchpassword">Change Password</a></li>
                                                    </ul>
                                            </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
			</div>
		</div>
	</header>

            <?=topMenuWidget::widget();?>

	<main>
            <?= $content ?>
	</main>
    
<!-- Modals: BEGIN -->
<div class="modal fade" id="modal-form-global">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-form-global-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-form-global-sm">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
                            
			</div>
			<div class="modal-footer">
                            
			</div>
		</div>
	</div>
</div>

<!-- Modals: END -->
</div>
 <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
