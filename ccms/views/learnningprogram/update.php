<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Learnningprogram */

$this->title = 'Update Learnningprogram: ' . ' ' . $model->learnningProgram_id;
$this->params['breadcrumbs'][] = ['label' => 'Learnningprograms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->learnningProgram_id, 'url' => ['view', 'id' => $model->learnningProgram_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="learnningprogram-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
