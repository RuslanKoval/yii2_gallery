<?php
use yii\helpers\Html;
?>

<div class="gallery__item">
    <img src="<?= $model->getPreview()?>" class="img-responsive" alt="">
    <?= $model->title ?>
    <div class="gallery__buttons">
        <?= Html::a('Edit', ['update', 'id' => $model->id ], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id ], ['class' => 'btn btn-danger delete-gallery']) ?>
    </div>
</div>
