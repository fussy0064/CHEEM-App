<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;

/** @var \app\models\SignupForm $model */

$this->title = 'Sign Up';
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="text-center mb-4">Create Account</h3>

                <?php $form = ActiveForm::begin(['id' => 'signup-form']); ?>

                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'phone_number')->textInput(['placeholder' => '0712345678'])->hint('We\'ll text you a code to verify this number.') ?>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <?= Html::activePasswordInput($model, 'password', ['class' => 'form-control', 'id' => 'signup-password']) ?>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="signup-password"><i class="fas fa-eye"></i></button>
                    </div>
                    <?= Html::error($model, 'password', ['class' => 'invalid-feedback d-block']) ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <?= Html::activePasswordInput($model, 'password_repeat', ['class' => 'form-control', 'id' => 'signup-password-repeat']) ?>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="signup-password-repeat"><i class="fas fa-eye"></i></button>
                    </div>
                    <?= Html::error($model, 'password_repeat', ['class' => 'invalid-feedback d-block']) ?>
                    <div id="password-match-msg" class="small mt-1"></div>
                </div>

                <div class="d-grid">
                    <?= Html::submitButton('Sign Up', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var pass = document.getElementById('signup-password');
    var repeat = document.getElementById('signup-password-repeat');
    var msg = document.getElementById('password-match-msg');

    function checkMatch() {
        if (!repeat.value) {
            msg.textContent = '';
            return;
        }
        if (pass.value === repeat.value) {
            msg.innerHTML = '<i class="fas fa-check text-success"></i> Passwords match';
            msg.className = 'small mt-1 text-success';
        } else {
            msg.innerHTML = '<i class="fas fa-xmark text-danger"></i> Passwords do not match';
            msg.className = 'small mt-1 text-danger';
        }
    }

    pass.addEventListener('input', checkMatch);
    repeat.addEventListener('input', checkMatch);
});
</script>
