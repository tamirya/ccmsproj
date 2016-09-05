<?php

namespace app\models;
use yii\base\Model;
use app\models\Course;
use app\models\Coursetoyear;
use app\models\Yearandsemester;
use app\models\Learnningprogram;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use Yii;
?>

<?php
$this->title ='Connect Courses To Years';
$courses     =Course::find()->asArray()->orderBy('course_name')->all();
$yearandsemesters=Yearandsemester::find()->asArray()->orderBy('yearAndSemester_name')->all();
$Learnningprograms = Learnningprogram::find()->asArray()->orderBy('learnningProgram_name')->all();
$firstLearnningprogramsobject = empty($Learnningprograms) ? -1 :reset($Learnningprograms);

if( isset($_GET['learnningProgramid']) ){
    $learnningProgramidbyget = $_GET['learnningProgramid'];
    $coursetoyears = Coursetoyear::find()->where(['user_id'=>Yii::$app->getUser()->id,'learnningProgram_id'=>$learnningProgramidbyget])->asArray()->all();
}else{
    $coursetoyears = Coursetoyear::find()->where(['user_id'=>Yii::$app->getUser()->id,'learnningProgram_id'=>$firstLearnningprogramsobject['learnningProgram_id']])->asArray()->all();
}

$courseselected = array();

foreach($coursetoyears as $id=>$coursetoyear){

    $courseidtodelete=0;
    $coursetogetdata=array();
    $toDelete = false;
    foreach( $courses as $key=>$course ){
        if( $course['course_id'] == $coursetoyear['course_id'] ){
            $courseidtodelete = $key;
            $coursetogetdata = $course;
            $toDelete= true;
        }
    }
    if( !empty($coursetogetdata)  ){
        $courseselected[$coursetogetdata['course_id']]=$coursetogetdata;

        foreach( $yearandsemesters as $key=>$yearandsemester ){
            if( $yearandsemester['yearAndSemester_id'] == $coursetoyear['yearAndSemester_id'] )
            {
                $courseselected[$coursetogetdata['course_id']][$yearandsemester['yearAndSemester_id']] = $yearandsemester;
            }
        }
     }
     if($toDelete){
        unset($courses[$courseidtodelete]);    
     }
    
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
        <h4>Courses</h4>
<select class="form-control" id="select-course" >
    <?php foreach($courses as $course){?>
        <option value="<?= $course['course_id'] ?>"><?= $course['course_name'] ?></option>
    <?php } ?>
</select>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <h4>Year</h4>
<select class="form-control" id="select-yearandsemester">
    <?php foreach($yearandsemesters as $yearandsemester){?>
        <option value="<?= $yearandsemester['yearAndSemester_id'] ?>"><?= $yearandsemester['yearAndSemester_name'] ?></option>
    <?php } ?>
</select>
</div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="margin-top: 38px;">
<button class="btn btn-default" id="attache-btn" >Attache</button>
</div>
</div>
<div class="table-responsive" style="margin-top: 20px;">
    <table class="table table-bordered table-hover" id="course-year-tbl">
        <thead>
        <tr>
            <th>Course</th>
            <th>Year And Semester</th>
            <th style="text-align: center">Action</th>
        </tr>
        </thead>
        <tbody id="course-year-tbl-body">
        <?php
        foreach( $courseselected as $courseselecte )
        {
            $coursename = $courseselecte['course_name'];
            $courseid = $courseselecte['course_id'];
            $yearandsemesterobj = array_slice($courseselecte, 3, 2, true);

            $yearandsemesterobj = reset($yearandsemesterobj);

            $yearandsemesterid = $yearandsemesterobj['yearAndSemester_id'];
            $yearandsemestername = $yearandsemesterobj['yearAndSemester_name'];
            echo '<tr><td><div class=\"course-year-tbl-row\" data-item-id='.$courseid.'>'.$coursename.'</div></td>';
            echo '<td><div class=\"course-year-tbl-row\" data-item-id='.$yearandsemesterid.'>'.$yearandsemestername.'</div></td>';
            echo '<td align="center"><a href="#" title="Delete" class="delete-row-btn"><span class="glyphicon glyphicon-trash"></span></a></td>';
            echo '</tr>';
           }?>
        </tbody>
    </table>
</div>

<?php
$inlineScript = 'var baseurl = ' . Yii::$app->homeUrl . ';';
$this->registerJs($inlineScript, View::POS_HEAD);

$this->registerJs("
    dataTable = $('#course-year-tbl').dataTable( {'bPaginate': false}).yadcf([
	    {column_number : 0, column_data_type: 'html', html_data_type: 'text', filter_default_label: 'Select Course'},
	    {column_number : 1, column_data_type: 'html', html_data_type: 'text', filter_default_label: 'Select Year'}
            
    ]);
    //select2
    $('#select-yearandsemester').select2();
    $('#select-course').select2();
    $('#select-learningprogram').select2();

    $('#select-learningprogram').on('change', function()
    {
        var	learnningProgramid = $(this).val();
        window.location.href = baseurl+'site/connectocourses?learnningProgramid='+learnningProgramid;

    });


    $('body').on('click','#attache-btn',function(){
        // get data from select
        var courseName       = $('#select-course').find(':selected').text();
        var courseId         = $('#select-course').val();
        var yearSemesterName = $('#select-yearandsemester').find(':selected').text();
        var yearSemesterId   = $('#select-yearandsemester').val();
        var learningprogramId  =  $('#select-learningprogram').val();

         //insert values to course-year-tbl
        dataTable.fnAddData( [
            '<div data-item-id='+courseId+' class=course-year-tbl-row>'+courseName+'</div>',
            '<div data-item-id='+yearSemesterId+' class=course-year-tbl-row>'+yearSemesterName+'</div>',
            '<a href=# title=Delete class=delete-row-btn><span class= \"glyphicon glyphicon-trash\"></span></a>' ]
          );
        // remove from select
        $('#select-course option[value='+courseId+']').remove();
        $('#select-course').select2();
        //save to DB by ajax
        var data = {
            'courseId'      :courseId,
            'yearSemesterId':yearSemesterId,
            'learningprogramId':learningprogramId,
            'action'        :1
        };

          $.ajax({
          url: baseurl+'site/managecoursetoyear',
          data:data,
          type:'post',
          success: function(result){
                console.log(result);
          }});
    });

    $('body').on('click','.delete-row-btn',function(){

        if(!confirm('Delete this item?')){return;}

        //$(this).parent().parent().children().eq(0).find('div')
        var row = $(this).parent().parent();
        var rowData = $(this).parent().parent().children();
        //course
        var course = rowData.eq(0).find('div');
        var courseId = course.data('item-id');
        var courseName = course.text();
        //yearsemester
        var yearsemester = rowData.eq(1).find('div');
        var yearsemesterId = yearsemester.data('item-id');
        var learningprogramId  =  $('#select-learningprogram').val();
        //insert to course select
        var courseOption ='<option value='+courseId+'>'+courseName+'</option>';
        $('#select-course').append(courseOption);
         var options = $('#select-course option');
         sort_select_by_name(options);
         $('#select-course').select2();
        // row deleted
        //delete row
        dataTable.fnDeleteRow(dataTable.fnGetPosition($(this).closest('tr').get(0)));
        //delete form DB
         var data = {
            'courseId'      :courseId,
            'yearSemesterId':yearsemesterId,
            'learningprogramId':learningprogramId,
            'action'        :2
        };

          $.ajax({
          url: baseurl+'site/managecoursetoyear',
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