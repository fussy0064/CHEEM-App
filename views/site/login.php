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
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="d-grid">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <p class="text-center mt-3">
                    No account? <?= Html::a('Sign up', ['site/signup']) ?>
                </p>
            </div>
        </div>
    </div>
</div>
