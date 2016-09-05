<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Learnningprogram */

$this->title = 'Create Learnning Program';
$this->params['breadcrumbs'][] = ['label' => 'Learnning programs', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="learnningprogram-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
