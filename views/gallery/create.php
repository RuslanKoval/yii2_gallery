<?php

use app\widgets\fileUpload\FileUpload;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \app\models\Gallery */
/* @var $file \app\models\Images */

$this->title = 'Создать галерею';
$this->params['breadcrumbs'][] = ['label' => 'Галерея', 'url' => ['gallery/index']];
$this->params['breadcrumbs'][] = $this->title;


$this->registerJs('

    $(document).on("beforeSubmit", "#gallery-form", function (form) {
       var data = $(this).serialize();
       jQuery.ajax({
            url: "'.  Url::to(['gallery/create']) .'",
            type: "POST",
            dataType: "json",
            data: data,
            success: function(response) {
                if (response.success) {
                    alert("success");
                    window.location.href = response.url;
                }
                
            },
            error: function(response) {
              
            }
        });
        return false;

    });
    
');


$form = \yii\widgets\ActiveForm::begin([
    'id' => 'gallery-form',
    'action' => Url::to(['gallery/create']),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['gallery/validate']),
    'options' => ['enctype' => 'multipart/form-data']
]); ?>

<?= $form->field($model, 'title')->textInput(); ?>



<?= FileUpload::widget([
    'model' => $file,
    'attribute' => 'file',
    'url' => ['images/upload', 'id' => $file->id],
    'gallery' => false,
    'load' => true,
    'fieldOptions' => [
        'accept' => 'image/*'
    ],
    'clientOptions' => [
        'maxFileSize' => 2000000
    ],

    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
        'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
    ],
]); ?>

<?= Html::submitButton('Submit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
<?php $form->end(); ?>