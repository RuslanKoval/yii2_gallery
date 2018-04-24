<?php

namespace app\controllers;

use app\models\Gallery;
use app\models\Images;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class GalleryController extends Controller
{

    public function actionIndex()
    {
        $query = Gallery::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index',[
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $model = new Gallery();
        $file = new Images();

        $request = \Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $files = Images::find()->where(['id' => $model->files])->all();
            $save = $model->save();

            if ($save)
            {
                if ($files) {
                    foreach ($files as $item) {
                        $item->gallery_id = $model->id;
                        $item->save();
                    }
                }
            }

            return [
                'success' => $save,
                'url' => Url::to(['gallery/index'])
            ];
        }

        return $this->render('create', [
            'model' => $model,
            'file' => $file
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $file = new Images();
        $attachedFiles = $model->images;

        $request = \Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $files = Images::find()->where(['id' => $model->files])->all();
            $save = $model->save();

            if ($save)
            {
                if ($files) {
                    foreach ($files as $item) {
                        $item->gallery_id = $model->id;
                        $item->save();
                    }
                }
            }

            return [
                'success' => $save,
                'url' => Url::to(['gallery/index'])
            ];
        }


        return $this->render('update', [
            'model' => $model,
            'file' => $file,
            'attachedFiles' => $attachedFiles
        ]);

    }


    public function actionValidate()
    {
        $model = new Gallery();
        $request = \Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    public function actionDelete($id)
    {
        $request = \Yii::$app->getRequest();
        $delete = false;
        if ($request->isPost) {
            $model = $this->findModel($id);
            $attachedFiles = $model->images;
            foreach ($attachedFiles as $attachedFile) {
                $attachedFile->delete();
            }

            $delete = $model->delete();
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return ['success' => $delete];

    }


    protected function findModel($id)
    {
        $query = Gallery::find()->where(['id' => $id])->one();


        if (($model = $query) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}