<?php

namespace app\models;

use yii\base\Model;
use Yii;
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
use app\models\Teacher;
use app\models\Course;
use app\models\Learnningprogram;
use yii\web\View;
?>

<aside class="right-side">
    <section class="content-header">
        <h1>
            <?php $this->params['breadcrumbs'][] = $this->title ?>
            <?php
            if ($this->title !== null) {
                echo $this->title;
            } else {
                echo \yii\helpers\Inflector::camel2words(\yii\helpers\Inflector::id2camel($this->context->module->id));
                echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
            } ?>
        </h1>
        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>

    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>


<?php
            $controller = Yii::$app->controller;
            $default_controller = Yii::$app->defaultRoute;
            $isHome = (($controller->id === $default_controller) && ($controller->action->id === $controller->defaultAction)) ? true : false;
            if($isHome){

        $courses           = Course::find()->asArray()->all();
        $teachers          = Teacher::find()->asArray()->all();
        $Learnningprograms = Learnningprogram::find()->asArray()->all();
        $courses_num =count($courses);
        $teachers_num =count($teachers);
        $Learnningprograms_num =count($Learnningprograms);

        ?> <div class="row">
            <div class="col-lg-4 col-xs-4 col-md-4">

                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3 id="learningprogram-count"></h3>
                        <p>
                            Curriculums
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion-cube"></i>
                    </div>
                    <div  class="small-box-footer" style="min-height: 26px;" >

                    </div>
                </div>
            </div>

                <div class="col-lg-4 col-xs-4 col-md-4">

                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3 id="teacher-count"></h3>
                            <p>
                                Teachers
                            </p>
                        </div>
                        <div class="icon">
                            <i class="ion-university"></i>
                        </div>
                        <div  class="small-box-footer" style="min-height: 26px;" >

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-4 col-md-4">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3 id="course-count"></h3>
                            <p>
                                Courses
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-book"></i>
                        </div>
                        <div  class="small-box-footer" style="min-height: 26px;" >

                        </div>
                    </div>
                </div>
            </div>
        </section>

<?php
    $inlineScript = 'var coursecount = ' . $courses_num . ';';
    $inlineScript .='var teachercount = ' . $teachers_num . ';';
    $inlineScript .='var Learnningprogramscount = ' . $Learnningprograms_num . ';';
    $this->registerJs($inlineScript, View::POS_HEAD);
    $this->registerJs("
        var options = {
      useEasing : true,
      useGrouping : true,
      separator : ',',
      decimal : '.',
      prefix : '',
      suffix : ''
    };
        new CountUp('course-count', 0, coursecount, 0, 2.5, options).start();
        new CountUp('teacher-count', 0, teachercount, 0, 2.5, options).start();
        new CountUp('learningprogram-count', 0, Learnningprogramscount, 0, 2.5, options).start();
    ", View::POS_READY);


    }?>
        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; CCMS System <?= date('Y') ?></p>
        </div>
    </footer>

</aside>
 