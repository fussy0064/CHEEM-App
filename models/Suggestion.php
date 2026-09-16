<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * @property int $id
 * @property int $user_id
 * @property string $message
 * @property string $status
 */
class Suggestion extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%suggestion}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            [['message'], 'required'],
            [['message'], 'string', 'max' => 2000],
            [['status'], 'in', 'range' => ['new', 'reviewed']],
        ];
    }
}
