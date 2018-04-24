<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Галеея';
$this->params['breadcrumbs'][] = $this->title;

?>
<?= Html::a('Create gallery', ['create'], ['class' => 'btn btn-success']) ?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemOptions' => ['class' => 'col-md-2 gallery__container', 'tag' =>'div'],
    'options' => [
        'tag' => 'div',
        'class' => 'gallery row',
    ],
    'summary' => false,
    'itemView' => function ($model) {
        return $this->render('gallery-item', [
            'model' => $model,
        ]);
    },

]) ?>