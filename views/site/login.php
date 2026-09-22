<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \app\models\LoginForm $model */

$this->title = 'Login';
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="text-center mb-4">CHEEM Login</h3>

                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username') ?>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <?= Html::activePasswordInput($model, 'password', ['class' => 'form-control', 'id' => 'login-password']) ?>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="login-password"><i class="fas fa-eye"></i></button>
                    </div>
                    <?= Html::error($model, 'password', ['class' => 'invalid-feedback d-block']) ?>
                </div>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="d-grid">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <p class="text-center mt-3">
                    No account? <?= Html::a('Sign up', ['site/signup']) ?>
                </p>
                <p class="text-center">
                    <?= Html::a('Forgot password?', ['site/forgot-password'], ['class' => 'small']) ?>
                </p>
            </div>
        </div>
    </div>
</div>
