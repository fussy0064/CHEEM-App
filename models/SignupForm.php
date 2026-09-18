<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username is taken.'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email is taken.'],
            ['password', 'string', 'min' => 8],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        // Public signup is Field Worker/Site Manager only.
        // Health Officer and Admin accounts are created by the superadmin (User Management page).
        $user->role = User::ROLE_FIELD_WORKER;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}
