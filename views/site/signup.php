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

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <?= Html::activePasswordInput($model, 'password', ['class' => 'form-control', 'id' => 'signup-password']) ?>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="signup-password">👁</button>
                    </div>
                    <?= Html::error($model, 'password', ['class' => 'invalid-feedback d-block']) ?>
                </div>

                <?= $form->field($model, 'role')->dropDownList([
                    User::ROLE_FIELD_WORKER => 'Field Worker / Site Manager',
                    User::ROLE_HEALTH_OFFICER => 'Health Officer',
                ], ['prompt' => 'I am a...']) ?>

                <div class="d-grid">
                    <?= Html::submitButton('Sign Up', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
