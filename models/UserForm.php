<?php

namespace app\models;

use Yii;
use yii\base\Model;

class UserForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $role;

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'role'], 'required'],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username is taken.'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email is taken.'],
            ['password', 'string', 'min' => 8],
            // Superadmin CAN assign any role, including health_officer and admin.
            ['role', 'in', 'range' => [User::ROLE_FIELD_WORKER, User::ROLE_HEALTH_OFFICER, User::ROLE_ADMIN]],
        ];
    }

    public function createUser()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->role = $this->role;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}
