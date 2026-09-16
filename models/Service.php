<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * @property int $id
 * @property string $name
 * @property string $category
 * @property string $description
 * @property int $active
 * @property int $created_by
 */
class Service extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%service}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            [['name', 'category'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['category'], 'in', 'range' => ['water', 'waste', 'pest', 'safety', 'risk', 'economic']],
            [['description'], 'string'],
            [['active'], 'boolean'],
            [['active'], 'default', 'value' => 1],
        ];
    }
}
