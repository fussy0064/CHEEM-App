<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \app\models\ResetPasswordOtpForm $model */
/** @var string $phone */

$this->title = 'Reset Password';
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="text-center mb-3">Reset Password</h3>
                <p class="text-center text-muted small">Enter the code sent to <?= Html::encode($phone) ?> and choose a new password.</p>

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'code')->textInput([
                    'maxlength' => 6,
                    'placeholder' => '123456',
                    'class' => 'form-control text-center fs-3',
                    'autocomplete' => 'one-time-code',
                    'inputmode' => 'numeric',
                ]) ?>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <div class="input-group">
                        <?= Html::activePasswordInput($model, 'password', ['class' => 'form-control', 'id' => 'reset-otp-password']) ?>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="reset-otp-password">👁</button>
                    </div>
                    <?= Html::error($model, 'password', ['class' => 'invalid-feedback d-block']) ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <?= Html::activePasswordInput($model, 'password_repeat', ['class' => 'form-control', 'id' => 'reset-otp-password-repeat']) ?>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="reset-otp-password-repeat">👁</button>
                    </div>
                    <?= Html::error($model, 'password_repeat', ['class' => 'invalid-feedback d-block']) ?>
                </div>

                <div class="d-grid mb-3">
                    <?= Html::submitButton('Reset Password', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <?= Html::beginForm(['site/resend-reset-otp'], 'post') ?>
                    <div class="d-grid">
                        <?= Html::submitButton('Resend Code', ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                    </div>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
</div>
