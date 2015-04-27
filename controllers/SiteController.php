<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login', 'forgotpassword', 'resetpassword'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $this->redirect(\yii\helpers\Url::to(['merchant/index']));
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm(['scenario' => 'login']);
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionForgotpassword()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $linkSent = false;
        $model = new LoginForm(['scenario' => 'forgotpassword']);
        if($model->load(Yii::$app->request->post()) && $model->resetPwd()){
            $linkSent = true;
        }
        return $this->render('forgotpassword', [
            'model' => $model,
            'linkSent' => $linkSent,
        ]);
    }

    public function actionResetpassword()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm(['scenario' => 'changepassword']);
        $key_valid = $model->checkActivationKey();
        if($key_valid){
            if($model->load(Yii::$app->request->post()) && $model->newPwd()){
                $model->login();
                $this->redirect(\yii\helpers\Url::home());
            }
        }
        return $this->render('changepassword', [
            'model' => $model,
            'key_valid' => $key_valid,
        ]);
    }

    public function actionSwitchpassword()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm(['scenario' => 'switchpassword']);
        if($model->load(Yii::$app->request->post()) && $model->newPwd()){
            return $this->render('passwordchanged', [
                'model' => $model,
            ]);
        }
        return $this->render('switchpassword', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
