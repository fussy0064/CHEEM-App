<?php
// One-time script to insert an admin user. Delete this file after running it once.

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';
new yii\console\Application($config);

$username = 'fussy';
$email = 'fussy@cheem.ac.tz';
$password = 'Cheem@001'; // change this
$role = 'admin';

$hash = Yii::$app->security->generatePasswordHash($password);
$authKey = Yii::$app->security->generateRandomString();
$now = time();

Yii::$app->db->createCommand()->delete('user', ['username' => $username])->execute();

Yii::$app->db->createCommand()->insert('user', [
    'username' => $username,
    'email' => $email,
    'password_hash' => $hash,
    'auth_key' => $authKey,
    'role' => $role,
    'status' => 1,
    'created_at' => $now,
    'updated_at' => $now,
])->execute();

echo "Admin user '$username' created successfully.\n";
