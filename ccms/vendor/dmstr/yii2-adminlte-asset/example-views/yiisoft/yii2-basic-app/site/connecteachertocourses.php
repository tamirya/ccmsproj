<?php

namespace app\models;
use yii\base\Model;
use app\models\Yearandsemester;
use app\models\Learnningprogram;
use app\models\Course;
use app\models\Teacher;
use app\models\Teachertocourse;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use Yii;

$this->title ='Match Teachers To Courses';
$Learnningprograms = Learnningprogram::find()->asArray()->orderBy('learnningProgram_name')->all();

if( isset($_GET['learnningProgramid']) ){
    $learnningProgramidbyget = $_GET['learnningProgramid'];
}else{
    $learnningProgramidbyget = $Learnningprograms[0]['learnningProgram_id'];
}
/************************/
/*       GET DATA       */
/************************/
$allCourses = Course::find()->asArray()->orderBy('course_name')->all();
$allTeacher = Teacher::find()->asArray()->orderBy('teacher_name')->all();

$selectedDataSql = 'SELECT  ttc.`teacher_id` , te.teacher_name,ttc.`course_id` , co.course_name ,co.course_duration
		FROM `teachertocourse` ttc
        join `course` co on co.course_id = ttc.`course_id`
		join `teacher` te on te.teacher_id = ttc.`teacher_id`
WHERE ttc.`learnningProgram_id`='.$learnningProgramidbyget;
//var_dump($selectedDataSql);exit;
$selectedData = Yii::$app->db->createCommand($selectedDataSql);
$selectedDataConnectedReader = $selectedData->queryAll();

/********************************/
/*   Insert To Array And Filter */
/********************************/
// array's
$coursesArr = array();
$coursesSelectedArr = array();

if( !empty($selectedDataConnectedReader)  )
{
    foreach( $selectedDataConnectedReader as $course )
    {
        $coursesSelectedArr[] = $course['course_id'];
    }

    foreach( $allCourses as $row )
    {
       if(  !in_array($row['course_id'],$coursesSelectedArr) )
       {
           $coursesArr[] = array(
               'course_id'=>$row['course_id'],
               'course_name'=>$row['course_name'],
           );
       }
    }
}else{
    $coursesArr = $allCourses;
}
?>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <h4>Learning Program</h4>
            <select class="form-control" id="select-learningprogram">
                <?php
                if( isset($_GET['learnningProgramid']) ){ ?>

                    <?php foreach($Learnningprograms as $Learnningprogram){
                        if( $learnningProgramidbyget == $Learnningprogram['learnningProgram_id'] )
                        {
                            echo '<option value="'.$Learnningprogram['learnningProgram_id'].'" selected>'.$Learnningprogram['learnningProgram_name'].'</option>';
                        }else{
                            echo '<option value="'.$Learnningprogram['learnningProgram_id'].'">'.$Learnningprogram['learnningProgram_name'].'</option>';
                        }
                        ?>
                    <?php } ?>

                <?php  }else{?>
                    <?php foreach($Learnningprograms as $Learnningprogram){?>
                        <option value="<?= $Learnningprogram['learnningProgram_id'] ?>"><?= $Learnningprogram['learnningProgram_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <h4>Teachers</h4>
            <select class="form-control" id="select-teacher">
                <?php
                foreach($allTeacher as $row){ ?>
                        <option value="<?= $row['teacher_id'] ?>"><?= $row['teacher_name'] ?></option>
                    <?php }?>
            </select>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <h4>Courses</h4>
            <select class="form-control" id="select-course" >
                <?php
                foreach($coursesArr as $row){ ?>
                    <option value="<?= $row['course_id'] ?>"><?= $row['course_name'] ?></option>
                <?php }?>
            </select>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="margin-top: 33px;">
            <button class="btn btn-default<?= empty($coursesArr) ? ' disabled':''  ?>" id="attache-btn" >Attache</button>
        </div>
    </div>
    <div class="table-responsive" style="margin-top: 20px;">
        <table class="table table-bordered table-hover" id="course-year-tbl">
            <thead>
            <tr>
                <th>Teacher</th>
                <th>Course</th>
               <!--  <th>Course Duration</th>  -->
                <th style="text-align: center">Action</th>
            </tr>
            </thead>
            <tbody id="course-year-tbl-body">
            <?php
            foreach( $selectedDataConnectedReader as $data )
            {
                $coursename = $data['course_name'];
                $courseid = $data['course_id'];
                //$courseDuration = $data['course_duration'];
                $teachername = $data['teacher_name'];
                $teacherid = $data['teacher_id'];

                echo '<tr><td><div class=\"course-year-tbl-row\" data-item-id='.$teacherid.'>'.$teachername.'</div></td>';
                echo '<td><div class=\"course-year-tbl-row\" data-item-id='.$courseid.'>'.$coursename.'</div></td>';
                //echo '<td><div class=\"course-year-tbl-row\" data-item-id='.$courseid.'>'.$courseDuration.'</div></td>';
                echo '<td><a href="#" title="Delete" class="delete-row-btn"><span class="glyphicon glyphicon-trash"></span></a></td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
<?php
$inlineScript = 'var baseurl = ' . Yii::$app->homeUrl . ';';
$this->registerJs($inlineScript, View::POS_HEAD);

$this->registerJs("

      dataTable = $('#course-year-tbl').DataTable({'bPaginate': false}).yadcf([
	    {column_number : 0, column_data_type: 'html', html_data_type: 'text', filter_default_label: 'Select Teacher'},
	    ]);
    //select2
    $('#select-teacher').select2();
    $('#select-course').select2();
    $('#select-learningprogram').select2();

    // on change learningprogram
    $('#select-learningprogram').on('change', function()
    {
        var	learnningProgramid = $(this).val();
        window.location.href = baseurl+'site/connecteachertocourses?learnningProgramid='+learnningProgramid;
    });


    $('body').on('click','#attache-btn',function(){
        // get data from select
        var courseName       = $('#select-course').find(':selected').text();
        var courseId         = $('#select-course').val();
        var learningprogramId  =  $('#select-learningprogram').val();
        var teacherId = $('#select-teacher').val();
        var teacherName = $('#select-teacher').find(':selected').text();

        //insert values to course-year-tbl
        dataTable.fnAddData( [
            '<div data-item-id='+teacherId+' class=course-year-tbl-row>'+teacherName+'</div>',
            '<div data-item-id='+courseId+' class=course-year-tbl-row>'+courseName+'</div>',
            '<a href=# title=Delete class=delete-row-btn><span class= \"glyphicon glyphicon-trash\"></span></a>' ]
          );

        $('#select-course option[value='+courseId+']').remove();
        $('#select-course').select2();

        var courseOption =  $('#select-course option').size(); 
        if( courseOption==0  )
        {
            $('#attache-btn').addClass('disabled');
        }
        //save to DB by ajax
        var data = {
            'courseId'      :courseId,
            'teacherId':teacherId,
            'learningprogramId':learningprogramId,
            'action'        :1
        };

          $.ajax({
          url: baseurl+'site/manageteachertocourse',
          data:data,
          type:'post',
          success: function(result){
                console.log(result);
          }});

    });

    $('body').on('click','.delete-row-btn',function(){

        if(!confirm('Delete this item?')){return;}

        var row = $(this).parent().parent();
        var rowData = $(this).parent().parent().children();
        //teacher
        var teacher = rowData.eq(0).find('div');
        var teacherId = teacher.data('item-id');
        //course
        var course = rowData.eq(1).find('div');
        var courseId = course.data('item-id');
        var courseName = course.text();
        var learningprogramId  =  $('#select-learningprogram').val();

        //insert to course select
        var courseOption ='<option value='+courseId+'>'+courseName+'</option>';
        $('#select-course').append(courseOption);
        var options = $('#select-course option');
        sort_select_by_name(options);
        $('#select-course').select2();

        //delete row
        dataTable.fnDeleteRow(dataTable.fnGetPosition($(this).closest('tr').get(0)));
        // remove disabled from btn
        $('#attache-btn').removeClass('disabled');
        //delete form DB

         var data = {
            'courseId'      :courseId,
            'teacherId'     :teacherId,
            'learningprogramId':learningprogramId,
            'action'        :2
        };

      $.ajax({
          url: baseurl+'site/manageteachertocourse',
          data:data,
          type:'post',
          success: function(result){
                console.log(result);
                }});


    });

   function sort_select_by_name(options){
       var arr = options.map(function(_, o) {
            return {
                t: $(o).text(),
                v: o.value
            };
        }).get();
        arr.sort(function(o1, o2) {
            return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0;
        });
        options.each(function(i, o) {
            //console.log(i);
            o.value = arr[i].v;
            $(o).text(arr[i].t);
        });
   }


", View::POS_READY);

?>