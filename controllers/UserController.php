<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\models\User;
use app\models\UserForm;
use app\models\ResetPasswordForm;

class UserController extends Controller
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
                        // Only Admin (superadmin) can manage users. Health Officers cannot.
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->isAdmin();
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'toggle-status' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $users = User::find()->orderBy(['created_at' => SORT_DESC])->all();
        return $this->render('index', ['users' => $users]);
    }

    /** Create a new user with any role (e.g. Health Officer) */
    public function actionCreate()
    {
        $model = new UserForm();

        if ($model->load(Yii::$app->request->post())) {
            $user = $model->createUser();
            if ($user) {
                Yii::$app->session->setFlash('success', ucfirst($user->role) . " account '{$user->username}' created.");
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /** Toggle active/disabled status. Can't disable your own account. */
    public function actionToggleStatus($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }
        if ($user->id === Yii::$app->user->id) {
            throw new ForbiddenHttpException('You cannot disable your own account.');
        }

        $user->status = $user->status === User::STATUS_ACTIVE ? User::STATUS_DISABLED : User::STATUS_ACTIVE;
        $user->save(false);

        return $this->redirect(['index']);
    }

    /** Superadmin resets another user's password directly - no old password needed */
    public function actionResetPassword($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        $model = new ResetPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->setPassword($model->password);
            $user->generateAuthKey(); // invalidates old sessions/remember-me tokens for security
            $user->save(false);

            Yii::$app->session->setFlash('success', "Password reset for '{$user->username}'.");
            return $this->redirect(['index']);
        }

        return $this->render('reset-password', ['model' => $model, 'targetUser' => $user]);
    }

    /** Delete a user. Can't delete your own account. */
    public function actionDelete($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }
        if ($user->id === Yii::$app->user->id) {
            throw new ForbiddenHttpException('You cannot delete your own account.');
        }

        $user->delete();
        return $this->redirect(['index']);
    }
}
