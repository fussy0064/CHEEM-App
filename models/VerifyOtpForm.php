<?php

namespace app\models;

use yii\base\Model;

class VerifyOtpForm extends Model
{
    public $code;

    public function rules()
    {
        return [
            [['code'], 'required'],
            [['code'], 'string', 'length' => 6],
        ];
    }
}
