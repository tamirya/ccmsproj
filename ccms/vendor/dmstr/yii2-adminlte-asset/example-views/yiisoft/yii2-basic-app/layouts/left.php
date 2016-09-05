<?php
use yii\bootstrap\Nav;
use app\models\Learnningprogram;

$pagetitle = $this->title;
$Learnningprograms = Learnningprogram::find()->asArray()->orderBy('learnningProgram_name')->all();
?>

<aside class="left-side sidebar-offcanvas">

<section class="sidebar">

<?php if (!Yii::$app->user->isGuest) : ?>
    <div class="user-panel">
        <div class="pull-left image">
            <img src="<?= $directoryAsset ?>/img/avatar5.png" class="img-circle" alt="User Image"/>
        </div>
        <div class="pull-left info">
            <p>Hello, <?= @Yii::$app->user->identity->username ?></p>
            <i class="fa fa-circle text-success"></i> Online
        </div>
    </div>
<?php endif ?>


<?php if( $pagetitle == 'Curriculums'  ){ ?>
    <ul class="sidebar-menu">

        <li class="treeview">
            <a href="#">
                <i class="ion-cube"></i>
                <span>Learning Program</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>learnningprogram"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>picklearning"><i class="fa fa-angle-double-right"></i>Set Time Of Learning Program</a></li>
            </ul>
        </li>
        <li class="treeview" id="courses-menu">
            <a href="<?= $directoryAsset ?>/#">
                <i class="fa fa-book"></i>
                <span>Courses</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>course"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>connectocourses"><i class="fa fa-angle-double-right"></i>Connect Courses To Years</a></li>

            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Teachers</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>teacher"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>connecteachertocourses"><i class="fa fa-angle-double-right"></i>Match Teachers To Courses</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>teacherconstrains"><i class="fa fa-angle-double-right"></i>Set Teachers Constrains</a></li>
            </ul>
        </li>

        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Curriculums</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <?php
                foreach( $Learnningprograms as $Learnningprogram )
                { ?>
                    <li><a href="<?= Yii::$app->homeUrl; ?>curriculums?id=<?= $Learnningprogram['learnningProgram_id'] ?>"><i class="fa fa-angle-double-right"></i><?= $Learnningprogram['learnningProgram_name'] ?></a></li>

                <?php } ?>
            </ul>
        </li>
    </ul>
<?php } ?>

<?php if( $pagetitle == 'CCMS Project'  ){ ?>
    <ul class="sidebar-menu">

        <li class="treeview">
            <a href="#">
                <i class="ion-cube"></i>
                <span>Learning Program</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>learnningprogram"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>picklearning"><i class="fa fa-angle-double-right"></i>Set Time Of Learning Program</a></li>
            </ul>
        </li>
        <li class="treeview" id="courses-menu">
            <a href="<?= $directoryAsset ?>/#">
                <i class="fa fa-book"></i>
                <span>Courses</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>course"><i class="fa fa-angle-double-right"></i>Management</a></li>
				<li><a href="<?= Yii::$app->homeUrl; ?>connectocourses"><i class="fa fa-angle-double-right"></i>Connect Courses To Years</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Teachers</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>teacher"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>connecteachertocourses"><i class="fa fa-angle-double-right"></i>Match Teachers To Courses</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>teacherconstrains"><i class="fa fa-angle-double-right"></i>Set Teachers Constrains</a></li>
            </ul>
        </li>

        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Curriculums</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <?php
                foreach( $Learnningprograms as $Learnningprogram )
                { ?>
                    <li><a href="<?= Yii::$app->homeUrl; ?>curriculums?id=<?= $Learnningprogram['learnningProgram_id'] ?>"><i class="fa fa-angle-double-right"></i><?= $Learnningprogram['learnningProgram_name'] ?></a></li>

                <?php } ?>
            </ul>
        </li>
    </ul>
<?php } ?>


<?php if( $pagetitle == 'Match Teachers To Courses'  ){ ?>
    <ul class="sidebar-menu">

        <li class="treeview">
            <a href="#">
                <i class="ion-cube"></i>
                <span>Learning Program</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>learnningprogram"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>picklearning"><i class="fa fa-angle-double-right"></i>Set Time Of Learning Program</a></li>
            </ul>
        </li>
        <li class="treeview" id="courses-menu">
            <a href="<?= $directoryAsset ?>/#">
                <i class="fa fa-book"></i>
                <span>Courses</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>course"><i class="fa fa-angle-double-right"></i>Management</a></li>
				<li><a href="<?= Yii::$app->homeUrl; ?>connectocourses"><i class="fa fa-angle-double-right"></i>Connect Courses To Years</a></li>
            </ul>
        </li>
        <li class="treeview active">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Teachers</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>teacher"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li class="active"><a href="<?= Yii::$app->homeUrl; ?>connecteachertocourses"><i class="fa fa-angle-double-right"></i>Match Teachers To Courses</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>teacherconstrains"><i class="fa fa-angle-double-right"></i>Set Teachers Constrains</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Curriculums</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <?php
                foreach( $Learnningprograms as $Learnningprogram )
                { ?>
                    <li><a href="<?= Yii::$app->homeUrl; ?>curriculums?id=<?= $Learnningprogram['learnningProgram_id'] ?>"><i class="fa fa-angle-double-right"></i><?= $Learnningprogram['learnningProgram_name'] ?></a></li>
          <?php } ?>
            </ul>
        </li>
    </ul>
<?php } ?>

<?php if( $pagetitle == 'Teachers'  ){ ?>
    <ul class="sidebar-menu">

        <li class="treeview">
            <a href="#">
                <i class="ion-cube"></i>
                <span>Learning Program</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>learnningprogram"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>picklearning"><i class="fa fa-angle-double-right"></i>Set Time Of Learning Program</a></li>
            </ul>
        </li>
        <li class="treeview" id="courses-menu">
            <a href="<?= $directoryAsset ?>/#">
                <i class="fa fa-book"></i>
                <span>Courses</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>course"><i class="fa fa-angle-double-right"></i>Management</a></li>
				<li><a href="<?= Yii::$app->homeUrl; ?>connectocourses"><i class="fa fa-angle-double-right"></i>Connect Courses To Years</a></li>
            </ul>
        </li>
        <li class="treeview active">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Teachers</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li class="active"><a href="<?= Yii::$app->homeUrl; ?>teacher"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>connecteachertocourses"><i class="fa fa-angle-double-right"></i>Match Teachers To Courses</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>teacherconstrains"><i class="fa fa-angle-double-right"></i>Set Teachers Constrains</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Curriculums</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <?php
                foreach( $Learnningprograms as $Learnningprogram )
                { ?>
                    <li><a href="<?= Yii::$app->homeUrl; ?>curriculums?id=<?= $Learnningprogram['learnningProgram_id'] ?>"><i class="fa fa-angle-double-right"></i><?= $Learnningprogram['learnningProgram_name'] ?></a></li>

                <?php } ?>
            </ul>
        </li>
    </ul>
<?php } ?>

<?php if( $pagetitle == 'Learning Program'  ){ ?>
    <ul class="sidebar-menu">

        <li class="treeview active">
            <a href="#">
                <i class="ion-cube"></i>
                <span>Learning Program</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>learnningprogram"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li class="active"><a href="<?= Yii::$app->homeUrl; ?>picklearning"><i class="fa fa-angle-double-right"></i>Set Time Of Learning Program</a></li>
            </ul>
        </li>
        <li class="treeview" id="courses-menu">
            <a href="<?= $directoryAsset ?>/#">
                <i class="fa fa-book"></i>
                <span>Courses</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>course"><i class="fa fa-angle-double-right"></i>Management</a></li>
				<li><a href="<?= Yii::$app->homeUrl; ?>connectocourses"><i class="fa fa-angle-double-right"></i>Connect Courses To Years</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Teachers</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>teacher"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>connecteachertocourses"><i class="fa fa-angle-double-right"></i>Match Teachers To Courses</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>teacherconstrains"><i class="fa fa-angle-double-right"></i>Set Teachers Constrains</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Curriculums</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <?php
                foreach( $Learnningprograms as $Learnningprogram )
                { ?>
                    <li><a href="<?= Yii::$app->homeUrl; ?>curriculums?id=<?= $Learnningprogram['learnningProgram_id'] ?>"><i class="fa fa-angle-double-right"></i><?= $Learnningprogram['learnningProgram_name'] ?></a></li>

                <?php } ?>
            </ul>
        </li>
    </ul>
<?php } ?>

<?php if( $pagetitle == 'Learnning Programs'  ){ ?>
    <ul class="sidebar-menu">

        <li class="treeview active">
            <a href="#">
                <i class="ion-cube"></i>
                <span>Learning Program</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li class="active"><a href="<?= Yii::$app->homeUrl; ?>learnningprogram"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>picklearning"><i class="fa fa-angle-double-right"></i>Set Time Of Learning Program</a></li>
            </ul>
        </li>
        <li class="treeview" id="courses-menu">
            <a href="<?= $directoryAsset ?>/#">
                <i class="fa fa-book"></i>
                <span>Courses</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>course"><i class="fa fa-angle-double-right"></i>Management</a></li>
				<li><a href="<?= Yii::$app->homeUrl; ?>connectocourses"><i class="fa fa-angle-double-right"></i>Connect Courses To Years</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Teachers</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>teacher"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>connecteachertocourses"><i class="fa fa-angle-double-right"></i>Match Teachers To Courses</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>teacherconstrains"><i class="fa fa-angle-double-right"></i>Set Teachers Constrains</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Curriculums</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <?php
                foreach( $Learnningprograms as $Learnningprogram )
                { ?>
                    <li><a href="<?= Yii::$app->homeUrl; ?>curriculums?id=<?= $Learnningprogram['learnningProgram_id'] ?>"><i class="fa fa-angle-double-right"></i><?= $Learnningprogram['learnningProgram_name'] ?></a></li>

                <?php } ?>
            </ul>
        </li>
    </ul>
<?php } ?>


<?php if( $pagetitle == 'Connect Courses To Years'  ){ ?>
    <ul class="sidebar-menu">

        <li class="treeview">
            <a href="#">
                <i class="ion-cube"></i>
                <span>Learning Program</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>learnningprogram"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>picklearning"><i class="fa fa-angle-double-right"></i>Set Time Of Learning Program</a></li>
            </ul>
        </li>
        <li class="treeview active" id="courses-menu">
            <a href="<?= $directoryAsset ?>/#">
                <i class="fa fa-book"></i>
                <span>Courses</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>course"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li class="active"><a href="<?= Yii::$app->homeUrl; ?>connectocourses"><i class="fa fa-angle-double-right"></i>Connect Courses</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Teachers</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>teacher"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>connecteachertocourses"><i class="fa fa-angle-double-right"></i>Match Teachers To Courses</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>teacherconstrains"><i class="fa fa-angle-double-right"></i>Set Teachers Constrains</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Curriculums</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <?php
                foreach( $Learnningprograms as $Learnningprogram )
                { ?>
                    <li><a href="<?= Yii::$app->homeUrl; ?>curriculums?id=<?= $Learnningprogram['learnningProgram_id'] ?>"><i class="fa fa-angle-double-right"></i><?= $Learnningprogram['learnningProgram_name'] ?></a></li>

                <?php } ?>
            </ul>
        </li>
    </ul>
<?php } ?>

<?php
if( $pagetitle == 'Courses'  ){?>
    <ul class="sidebar-menu">

        <li class="treeview">
            <a href="#">
                <i class="ion-cube"></i>
                <span>Learning Program</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>learnningprogram"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>picklearning"><i class="fa fa-angle-double-right"></i>Set Time Of Learning Program</a></li>
            </ul>
        </li>
        <li class="treeview active" id="courses-menu">
            <a href="<?= $directoryAsset ?>/#">
                <i class="fa fa-book"></i>
                <span>Courses</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li class="active" ><a href="<?= Yii::$app->homeUrl; ?>course"><i class="fa fa-angle-double-right"></i>Management</a></li>
				<li><a href="<?= Yii::$app->homeUrl; ?>connectocourses"><i class="fa fa-angle-double-right"></i>Connect Courses To Years</a></li>
            </ul>
        </li>

        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Teachers</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>teacher"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>connecteachertocourses"><i class="fa fa-angle-double-right"></i>Match Teachers To Courses</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>teacherconstrains"><i class="fa fa-angle-double-right"></i>Set Teachers Constrains</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Curriculums</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <?php
                foreach( $Learnningprograms as $Learnningprogram )
                { ?>
                    <li><a href="<?= Yii::$app->homeUrl; ?>curriculums?id=<?= $Learnningprogram['learnningProgram_id'] ?>"><i class="fa fa-angle-double-right"></i><?= $Learnningprogram['learnningProgram_name'] ?></a></li>

                <?php } ?>
            </ul>
        </li>
    </ul>
<?php } ?>

<?php if( $pagetitle == 'Teachers Constrains' ){?>
    <ul class="sidebar-menu">
        <li class="treeview">
            <a href="#">
                <i class="ion-cube"></i>
                <span>Learning Program</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>learnningprogram"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>picklearning"><i class="fa fa-angle-double-right"></i>Set Time Of Learning Program</a></li>
            </ul>
        </li>
        <li class="treeview" id="courses-menu">
            <a href="<?= $directoryAsset ?>/#">
                <i class="fa fa-book"></i>
                <span>Courses</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>course"><i class="fa fa-angle-double-right"></i>Management</a></li>
				<li><a href="<?= Yii::$app->homeUrl; ?>connectocourses"><i class="fa fa-angle-double-right"></i>Connect Courses To Years</a></li>
            </ul>
        </li>
        <li class="treeview active">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Teachers</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?= Yii::$app->homeUrl; ?>teacher"><i class="fa fa-angle-double-right"></i>Management</a></li>
                <li><a href="<?= Yii::$app->homeUrl; ?>connecteachertocourses"><i class="fa fa-angle-double-right"></i>Match Teachers To Courses</a></li>
                <li class="active"><a href="<?= Yii::$app->homeUrl; ?>teacherconstrains"><i class="fa fa-angle-double-right"></i>Set Teachers Constrains</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-graduation-cap"></i>
                <span>Curriculums</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <?php
                foreach( $Learnningprograms as $Learnningprogram )
                { ?>
                    <li><a href="<?= Yii::$app->homeUrl; ?>curriculums?id=<?= $Learnningprogram['learnningProgram_id'] ?>"><i class="fa fa-angle-double-right"></i><?= $Learnningprogram['learnningProgram_name'] ?></a></li>

                <?php } ?>
            </ul>
        </li>
    </ul>
<?php } ?>

</section>

</aside>
