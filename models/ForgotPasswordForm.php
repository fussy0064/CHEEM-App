<?php

namespace app\models;

use yii\base\Model;

class ForgotPasswordForm extends Model
{
    public $phone_number;

    public function rules()
    {
        return [
            [['phone_number'], 'required'],
            [['phone_number'], 'match', 'pattern' => '/^(0|255)[67]\d{8}$/', 'message' => 'Enter a valid Tanzanian phone number.'],
        ];
    }

    public function getUser()
    {
        return User::findOne(['phone_number' => $this->phone_number]);
    }
}
