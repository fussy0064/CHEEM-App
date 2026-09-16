<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

/**
 * @property int $id
 * @property int $user_id
 * @property string $category
 * @property string $location
 * @property string $description
 * @property string $urgency
 * @property string $status
 * @property string $photo_path
 * @property string $reference_number
 * @property int $assigned_to
 */
class ServiceRequest extends ActiveRecord
{
    const CATEGORY_WATER = 'water';
    const CATEGORY_WASTE = 'waste';
    const CATEGORY_PEST = 'pest';
    const CATEGORY_SAFETY = 'safety';
    const CATEGORY_RISK = 'risk';
    const CATEGORY_ECONOMIC = 'economic';

    public $photoFile;

    public static function tableName()
    {
        return '{{%service_request}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            [['category', 'location', 'description', 'urgency'], 'required'],
            [['description'], 'string'],
            [['location'], 'string', 'max' => 255],
            [['category'], 'in', 'range' => [
                self::CATEGORY_WATER, self::CATEGORY_WASTE, self::CATEGORY_PEST,
                self::CATEGORY_SAFETY, self::CATEGORY_RISK, self::CATEGORY_ECONOMIC,
            ]],
            [['urgency'], 'in', 'range' => ['low', 'medium', 'high']],
            [['photoFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 5 * 1024 * 1024],
        ];
    }

    public function beforeValidate()
    {
        if (empty($this->reference_number)) {
            $this->reference_number = 'CHEEM-' . strtoupper(substr(uniqid(), -8));
        }
        return parent::beforeValidate();
    }

    /**
     * Handles secure photo upload: random filename, validated extension only.
     */
    public function uploadPhoto()
    {
        $this->photoFile = UploadedFile::getInstance($this, 'photoFile');
        if ($this->photoFile) {
            $dir = Yii::getAlias('@webroot/uploads/requests');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $filename = Yii::$app->security->generateRandomString(16) . '.' . $this->photoFile->extension;
            $this->photoFile->saveAs($dir . '/' . $filename);
            $this->photo_path = 'uploads/requests/' . $filename;
        }
        return true;
    }

    public function getUrgencyLabel()
    {
        return ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'][$this->urgency] ?? $this->urgency;
    }

    public function getStatusLabel()
    {
        return ['pending' => 'Pending', 'in_progress' => 'In Progress', 'resolved' => 'Resolved'][$this->status] ?? $this->status;
    }
}
