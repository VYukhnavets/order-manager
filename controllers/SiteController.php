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
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm(['scenario' => 'login']);
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
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
}
