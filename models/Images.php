<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%images}}".
 *
 * @property int $id
 * @property string $url
 * @property string $description
 * @property string $preview
 * @property int $gallery_id
 * @property int $created_at
 *
 * @property Gallery $gallery
 */
class Images extends \yii\db\ActiveRecord
{

    const PREVIEW_PREFIX = 'thumb_';
    const DEFAULT_IMAGES = '/image/no-image.svg';

    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%images}}';
    }


    public function behaviors()
    {
        return [
            'timestamp'  => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['description'], 'string'],
            [['gallery_id', 'created_at'], 'integer'],
            [['url', 'preview'], 'string', 'max' => 255],
            [['gallery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Gallery::className(), 'targetAttribute' => ['gallery_id' => 'id']],
            [['file'], 'file']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'description' => 'Description',
            'preview' => 'Preview',
            'gallery_id' => 'Gallery ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasOne(Gallery::className(), ['id' => 'gallery_id']);
    }

    public static function getFileDirectory()
    {
        return Yii::getAlias('@app/web/image/upload') . DIRECTORY_SEPARATOR;
    }

    public static function getFileViewDirectory()
    {
        return '/image/upload' . DIRECTORY_SEPARATOR ;
    }

    public function getFileName()
    {

        $url = $this->url;
        $name = explode("/image/upload\\", $url);
        return $name[1];
    }

    public function getThumbName()
    {
        return self::PREVIEW_PREFIX . $this->getFileName();
    }

    public function deleteImages()
    {
        $directory = $this->getFileDirectory();
        $mainImage = $directory . $this->getFileName();
        $preview = $directory . $this->getThumbName();

        if (is_file($mainImage)) {
            FileHelper::unlink($mainImage);
        }

        if (is_file($preview)) {
            FileHelper::unlink($preview);
        }

    }

    public function getName() {
        $filename = $this->getFileName();
        $p = strrpos($filename, '.');
        if ($p > 0) return substr($filename, 0, $p);
        else return $filename;
    }

    public function beforeDelete()
    {
        $this->deleteImages();
        return parent::beforeDelete();
    }
}
