<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

/**
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $image_path
 * @property int $active
 * @property int $created_by
 */
class NewsPost extends ActiveRecord
{
    public $imageFile;

    public static function tableName()
    {
        return '{{%news_post}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['content'], 'string'],
            [['active'], 'boolean'],
            [['active'], 'default', 'value' => 1],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 5 * 1024 * 1024],
        ];
    }

    public function uploadImage()
    {
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
        if ($this->imageFile) {
            $dir = Yii::getAlias('@webroot/uploads/news');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $filename = Yii::$app->security->generateRandomString(16) . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($dir . '/' . $filename);
            $this->image_path = 'uploads/news/' . $filename;
            $this->imageFile = null; // clear so re-validation on save() doesn't check the now-moved temp file
        }
        return true;
    }
}
