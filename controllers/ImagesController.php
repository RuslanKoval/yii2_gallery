<?php

namespace app\controllers;


use app\models\Gallery;
use app\models\Images;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class ImagesController extends Controller
{

    public function actionUpload()
    {
        $model = new Images();

        $imageFile = UploadedFile::getInstance($model, 'file');

        $directory = Images::getFileDirectory();

        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory);
        }

        if ($imageFile) {
            $uid = uniqid(time(), true);
            $fileName = $uid . '.' . $imageFile->extension;
            $filePath = $directory . $fileName;

            if ($imageFile->saveAs($filePath)) {
                $path = Images::getFileViewDirectory() . $fileName;
                $thumbPatch =  Images::getFileViewDirectory() . Images::PREVIEW_PREFIX  . $fileName;

                Image::thumbnail($directory . $fileName, 200, 200)
                    ->save($directory . Images::PREVIEW_PREFIX . $fileName, ['quality' => 100]);


                $model->url = $path;
                $model->preview = $thumbPatch;
                $model->save();


                return Json::encode([
                    'files' => [
                        [
                            'name' => $fileName,
                            'size' => $imageFile->size,
                            'url' => $path,
                            'thumbnailUrl' => $thumbPatch,
                            'deleteUrl' => Url::to(['images/delete', 'id' => $model->id]),
                            'editUrl' => Url::to(['images/edit', 'id' => $model->id]),
                            'deleteType' => 'POST',
                            'inputName' => "Gallery[files][]",
                            'imageId' => $model->id

                        ],
                    ],
                ]);
            }
        }

        return '';
    }


    public function actionEdit($id)
    {
        $model = Images::find()->where(['id' => $id])->one();
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if(!$model) {

            return false;
        }



        return [
            'success' => true,
            'model' => [
                'description' => $model->description,
                'name' => $model->getName()
            ]
        ];

    }


    public function actionDelete($id)
    {
        $file = Images::find()->where(['id' => $id])->one();

        if ($file)
        {
            $delete = $file->delete();
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return ['success' => $delete];
    }

}