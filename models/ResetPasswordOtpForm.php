<?php

namespace app\models;

use yii\base\Model;

class ResetPasswordOtpForm extends Model
{
    public $code;
    public $password;
    public $password_repeat;

    public function rules()
    {
        return [
            [['code', 'password', 'password_repeat'], 'required'],
            [['code'], 'string', 'length' => 6],
            ['password', 'string', 'min' => 8],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match."],
        ];
    }
}
