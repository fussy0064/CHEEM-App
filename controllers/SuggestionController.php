<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Suggestion;

class SuggestionController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'], // any logged-in user
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            $u = Yii::$app->user->identity;
                            return $u->isAdmin() || $u->isHealthOfficer();
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $model = new Suggestion();
        $model->user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Thanks for your suggestion.');
                return $this->redirect(['/site/index']);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionIndex()
    {
        $suggestions = Suggestion::find()->orderBy(['created_at' => SORT_DESC])->all();
        return $this->render('index', ['suggestions' => $suggestions]);
    }
}
