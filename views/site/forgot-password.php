<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var \app\models\ForgotPasswordForm $model */
$this->title = 'Forgot Password';
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="text-center mb-3">Forgot Password</h3>
                <p class="text-center text-muted small">Enter your registered phone number. We'll text you a reset code.</p>

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'phone_number')->textInput(['placeholder' => '0712345678']) ?>

                <div class="d-grid">
                    <?= Html::submitButton('Send Reset Code', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <p class="text-center mt-3">
                    <?= Html::a('Back to Login', ['site/login']) ?>
                </p>
            </div>
        </div>
    </div>
</div>
