<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use app\models\NewsPost;
use app\models\Service;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'signup', 'error'],
                        'allow' => true,
                        'roles' => ['?'], // guests only
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'], // logged-in users only
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?', '@'], // public landing page + logged-in dashboard
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            $news = NewsPost::find()->where(['active' => 1])->orderBy(['created_at' => SORT_DESC])->limit(5)->all();
            $services = Service::find()->where(['active' => 1])->all();
            return $this->render('landing', ['news' => $news, 'services' => $services]);
        }

        $user = Yii::$app->user->identity;

        if ($user->isAdmin() || $user->isHealthOfficer()) {
            return $this->render('dashboard-officer', ['user' => $user]);
        }

        return $this->render('dashboard-worker', ['user' => $user]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', ['model' => $model]);
    }

    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            $user = $model->signup();
            if ($user) {
                Yii::$app->user->login($user);
                return $this->goHome();
            }
        }

        return $this->render('signup', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }
}
