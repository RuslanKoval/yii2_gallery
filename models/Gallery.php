<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%gallery}}".
 *
 * @property int $id
 * @property string $title
 *
 * @property Images[] $images
 */
class Gallery extends \yii\db\ActiveRecord
{

    public $files;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gallery}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['files'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Images::className(), ['gallery_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreview()
    {
        $result =  $this->hasOne(Images::className(), ['gallery_id' => 'id'])->orderBy(['created_at' => SORT_DESC])->one();

        if ($result) {
            return $result->preview;
        }

        return Images::DEFAULT_IMAGES;

    }
}
