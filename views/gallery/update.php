<?php

use app\widgets\fileUpload\FileUpload;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \app\models\Gallery */
/* @var $file \app\models\Images */


$this->registerJs('

    $(document).on("beforeSubmit", "#gallery-form", function (form) {
       var data = $(this).serialize();
       jQuery.ajax({
            url: "'.  Url::to(['gallery/update', 'id' => $model->id]) .'",
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
    'action' => Url::to(['gallery/update']),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['gallery/validate']),
    'options' => ['enctype' => 'multipart/form-data']
]); ?>

<?= $form->field($model, 'title')->textInput(); ?>


<?php if ($attachedFiles) { ?>

    <h3>Attached images</h3>
    <table role="presentation" class="table table-striped">
        <tbody class="files">

        <?php  foreach ($attachedFiles as $attachedFile) { ?>
            <tr class="fade in">
                <td>
            <span class="preview">
                    <a href="#" title="<?= $attachedFile->description?>" data-gallery=""><img src="<?= $attachedFile->preview ?>"></a>

            </span>
                </td>
                <td>
                    <p class="name">
                        <a href="#" title="<?= $attachedFile->description?>" data-gallery=""><?= $attachedFile->getFileName() ?></a>
                    </p>

                </td>
                <td>

                <a class="btn btn-danger delete delete-attached" href="<?= Url::to(['images/delete', 'id' => $attachedFile->id ])?>">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </a>

                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>

<?php  } ?>



<?= FileUpload::widget([
    'model' => $file,
    'attribute' => 'file',
    'url' => ['images/upload', 'id' => $file->id],
    'gallery' => false,
    'fieldOptions' => [
        'accept' => 'image/*'
    ],
    'clientOptions' => [
        'maxFileSize' => 2000000
    ],
    // ...
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