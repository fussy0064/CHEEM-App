<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Service;
use app\models\NewsPost;

class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
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

    // ---- Services ----

    public function actionServices()
    {
        $services = Service::find()->orderBy(['created_at' => SORT_DESC])->all();
        return $this->render('services', ['services' => $services]);
    }

    public function actionServiceCreate()
    {
        $model = new Service();
        $model->created_by = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Service added.');
            return $this->redirect(['services']);
        }

        return $this->render('service-form', ['model' => $model]);
    }

    public function actionServiceUpdate($id)
    {
        $model = Service::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Service not found.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Service updated.');
            return $this->redirect(['services']);
        }

        return $this->render('service-form', ['model' => $model]);
    }

    public function actionServiceDelete($id)
    {
        $model = Service::findOne($id);
        if ($model) {
            $model->delete();
        }
        return $this->redirect(['services']);
    }

    // ---- News ----

    public function actionNews()
    {
        $posts = NewsPost::find()->orderBy(['created_at' => SORT_DESC])->all();
        return $this->render('news', ['posts' => $posts]);
    }

    public function actionNewsCreate()
    {
        $model = new NewsPost();
        $model->created_by = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post())) {
            $model->uploadImage();
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'News post added.');
                return $this->redirect(['news']);
            }
        }

        return $this->render('news-form', ['model' => $model]);
    }

    public function actionNewsUpdate($id)
    {
        $model = NewsPost::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('News post not found.');
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->uploadImage();
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'News post updated.');
                return $this->redirect(['news']);
            }
        }

        return $this->render('news-form', ['model' => $model]);
    }

    public function actionNewsDelete($id)
    {
        $model = NewsPost::findOne($id);
        if ($model) {
            $model->delete();
        }
        return $this->redirect(['news']);
    }
}
