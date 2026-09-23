<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var \app\models\VerifyOtpForm $model */
/** @var string $phone */

$this->title = 'Verify Phone Number';
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="text-center mb-3">Verify Your Phone</h3>
                <p class="text-center text-muted">We sent a 6-digit code to <?= Html::encode($phone) ?></p>

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'code')->textInput([
                    'maxlength' => 6,
                    'placeholder' => '123456',
                    'class' => 'form-control text-center fs-3',
                    'autocomplete' => 'one-time-code',
                    'inputmode' => 'numeric',
                ])->label(false) ?>

                <div class="d-grid mb-3">
                    <?= Html::submitButton('Verify', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <?= Html::beginForm(['site/resend-otp'], 'post') ?>
                    <div class="d-grid">
                        <?= Html::submitButton('Resend Code', ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                    </div>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
</div>
