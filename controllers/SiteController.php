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
use app\models\VerifyOtpForm;
use app\models\ForgotPasswordForm;
use app\models\ResetPasswordOtpForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'signup', 'error', 'verify-phone', 'resend-otp', 'forgot-password', 'reset-password-otp', 'resend-reset-otp'],
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
                    'resend-otp' => ['post'],
                    'resend-reset-otp' => ['post'],
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
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = $model->getUser();

            if (!$user->phone_verified) {
                Yii::$app->session->set('unverified_user_id', $user->id);
                $code = $user->generateOtp();
                Yii::$app->sms->send($user->phone_number, "Your CHEEM verification code is: $code");
                Yii::$app->session->setFlash('success', 'Please verify your phone number. A new code was sent.');
                return $this->redirect(['verify-phone']);
            }

            Yii::$app->user->login($user, $model->rememberMe ? 3600 * 24 * 30 : 0);
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
                $code = $user->generateOtp();
                Yii::$app->sms->send($user->phone_number, "Your CHEEM verification code is: $code");
                Yii::$app->session->set('unverified_user_id', $user->id);
                Yii::$app->session->setFlash('success', 'Account created. Enter the code sent to your phone.');
                return $this->redirect(['verify-phone']);
            }
        }

        return $this->render('signup', ['model' => $model]);
    }

    public function actionVerifyPhone()
    {
        $userId = Yii::$app->session->get('unverified_user_id');
        if (!$userId) {
            return $this->redirect(['login']);
        }
        $user = User::findOne($userId);
        if (!$user) {
            Yii::$app->session->remove('unverified_user_id');
            return $this->redirect(['signup']);
        }

        $model = new VerifyOtpForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($user->verifyOtp($model->code)) {
                Yii::$app->session->remove('unverified_user_id');
                Yii::$app->user->login($user);
                Yii::$app->session->setFlash('success', 'Phone verified! Welcome.');
                return $this->goHome();
            }
            $model->addError('code', 'Invalid or expired code.');
        }

        return $this->render('verify-phone', ['model' => $model, 'phone' => $user->phone_number]);
    }

    public function actionResendOtp()
    {
        $userId = Yii::$app->session->get('unverified_user_id');
        if (!$userId) {
            return $this->redirect(['login']);
        }
        $user = User::findOne($userId);
        if ($user) {
            $code = $user->generateOtp();
            Yii::$app->sms->send($user->phone_number, "Your CHEEM verification code is: $code");
            Yii::$app->session->setFlash('success', 'A new code has been sent.');
        }
        return $this->redirect(['verify-phone']);
    }

    // ---- Forgot password (via phone OTP) ----

    public function actionForgotPassword()
    {
        $model = new ForgotPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = $model->getUser();
            if (!$user) {
                $model->addError('phone_number', 'No account found with this phone number.');
            } else {
                $code = $user->generateOtp();
                Yii::$app->sms->send($user->phone_number, "Your CHEEM password reset code is: $code");
                Yii::$app->session->set('password_reset_user_id', $user->id);
                Yii::$app->session->setFlash('success', 'A reset code has been sent to your phone.');
                return $this->redirect(['reset-password-otp']);
            }
        }

        return $this->render('forgot-password', ['model' => $model]);
    }

    public function actionResetPasswordOtp()
    {
        $userId = Yii::$app->session->get('password_reset_user_id');
        if (!$userId) {
            return $this->redirect(['forgot-password']);
        }
        $user = User::findOne($userId);
        if (!$user) {
            Yii::$app->session->remove('password_reset_user_id');
            return $this->redirect(['forgot-password']);
        }

        $model = new ResetPasswordOtpForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($user->verifyOtp($model->code)) {
                $user->setPassword($model->password);
                $user->generateAuthKey(); // invalidate old sessions
                $user->save(false);

                Yii::$app->session->remove('password_reset_user_id');
                Yii::$app->session->setFlash('success', 'Password reset! You can now log in.');
                return $this->redirect(['login']);
            }
            $model->addError('code', 'Invalid or expired code.');
        }

        return $this->render('reset-password-otp', ['model' => $model, 'phone' => $user->phone_number]);
    }

    public function actionResendResetOtp()
    {
        $userId = Yii::$app->session->get('password_reset_user_id');
        if (!$userId) {
            return $this->redirect(['forgot-password']);
        }
        $user = User::findOne($userId);
        if ($user) {
            $code = $user->generateOtp();
            Yii::$app->sms->send($user->phone_number, "Your CHEEM password reset code is: $code");
            Yii::$app->session->setFlash('success', 'A new code has been sent.');
        }
        return $this->redirect(['reset-password-otp']);
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
