<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $phone_number
 * @property int $phone_verified
 * @property string $otp_hash
 * @property int $otp_expires_at
 * @property string $password_hash
 * @property string $auth_key
 * @property string $role  field_worker | health_officer | admin
 * @property int $status   1 = active, 0 = disabled
 * @property int $created_at
 * @property int $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_FIELD_WORKER = 'field_worker';
    const ROLE_HEALTH_OFFICER = 'health_officer';
    const ROLE_ADMIN = 'admin';

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['username', 'email', 'password_hash', 'role'], 'required'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['phone_number'], 'string', 'max' => 20],
            [['role'], 'in', 'range' => [self::ROLE_FIELD_WORKER, self::ROLE_HEALTH_OFFICER, self::ROLE_ADMIN]],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DISABLED]],
        ];
    }

    // ---- IdentityInterface ----

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('Access token auth is not supported.');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    // ---- Auth helpers ----

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    // ---- Role helpers ----

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isHealthOfficer()
    {
        return $this->role === self::ROLE_HEALTH_OFFICER;
    }

    public function isFieldWorker()
    {
        return $this->role === self::ROLE_FIELD_WORKER;
    }

    // ---- OTP / phone verification ----

    /** Generates a 6-digit OTP, stores its hash + 10-min expiry, returns the plaintext code to send via SMS. */
    public function generateOtp()
    {
        $code = (string) random_int(100000, 999999);
        $this->otp_hash = Yii::$app->security->generatePasswordHash($code);
        $this->otp_expires_at = time() + 600; // 10 minutes
        $this->save(false);
        return $code;
    }

    public function verifyOtp($code)
    {
        if (empty($this->otp_hash) || empty($this->otp_expires_at)) {
            return false;
        }
        if (time() > $this->otp_expires_at) {
            return false; // expired
        }
        if (!Yii::$app->security->validatePassword($code, $this->otp_hash)) {
            return false;
        }

        $this->phone_verified = 1;
        $this->otp_hash = null;
        $this->otp_expires_at = null;
        $this->save(false);
        return true;
    }
}
