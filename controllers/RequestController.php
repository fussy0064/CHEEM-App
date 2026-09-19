<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\models\ServiceRequest;

class RequestController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['create', 'my'],
                        'allow' => true,
                        'roles' => ['@'], // any logged-in user
                    ],
                    [
                        'actions' => ['manage', 'update-status', 'view-location'],
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

    /** Field worker: submit a new request */
    public function actionCreate()
    {
        $model = new ServiceRequest();
        $model->user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->id;
            $model->uploadPhoto();
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Request submitted. Reference: ' . $model->reference_number);
                return $this->redirect(['my']);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /** Field worker: view own requests */
    public function actionMy()
    {
        $requests = ServiceRequest::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('my', ['requests' => $requests]);
    }

    /** Health Officer / Admin: Kanban-style list of all requests */
    public function actionManage()
    {
        $pending = ServiceRequest::find()->where(['status' => 'pending'])->orderBy(['urgency' => SORT_DESC])->all();
        $inProgress = ServiceRequest::find()->where(['status' => 'in_progress'])->all();
        $resolved = ServiceRequest::find()->where(['status' => 'resolved'])->orderBy(['updated_at' => SORT_DESC])->limit(20)->all();

        return $this->render('manage', [
            'pending' => $pending,
            'inProgress' => $inProgress,
            'resolved' => $resolved,
        ]);
    }

    /** Health Officer / Admin: change request status */
    public function actionUpdateStatus($id)
    {
        $model = ServiceRequest::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Request not found.');
        }

        $status = Yii::$app->request->post('status');
        if (!in_array($status, ['pending', 'in_progress', 'resolved'], true)) {
            throw new \yii\web\BadRequestHttpException('Invalid status.');
        }

        $model->status = $status;
        $model->assigned_to = Yii::$app->user->id;
        $model->save(false);

        return $this->redirect(['manage']);
    }

    /** Health Officer / Admin: map view with route to the client + status update controls */
    public function actionViewLocation($id)
    {
        $model = ServiceRequest::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Request not found.');
        }
        if (!$model->hasLocation()) {
            throw new NotFoundHttpException('This request has no pinned location.');
        }

        return $this->render('view-location', ['model' => $model]);
    }
}
