<?php

namespace app\models;
use yii\base\Model;
use app\models\Teacher;
use app\models\Teachertocourse;
use app\models\Learnningprogram;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use Yii;
use yii\web\Controller;

$this->title ='Curriculums';
$LearnningprogramId = isset( $_GET['id'] ) ? $_GET['id'] : -1;
$Learnningprograms = Learnningprogram::find()->asArray()->orderBy('learnningProgram_name')->all();
$Learnningprogram = Learnningprogram::find()->where(['learnningProgram_id' => $LearnningprogramId])->one();
//### Year 1
$yearName = 'Year 1';
$yearSql = getSqlData($LearnningprogramId,$yearName);
$yearsqlCommand = Yii::$app->db->createCommand($yearSql);
$yearCommanDataReader = $yearsqlCommand->queryAll();
$algo = executeAlgo($LearnningprogramId,$yearName);
//###ENd Year 1

//### Year 2
$year2Name = 'Year 2';
$year2Sql = getSqlData($LearnningprogramId,$year2Name);
$year2sqlCommand = Yii::$app->db->createCommand($year2Sql);
$year2CommanDataReader = $year2sqlCommand->queryAll();
$algo2 = executeAlgo($LearnningprogramId,$year2Name);
//###ENd Year 2

//### Year 3
$year3Name = 'Year 3';
$year3Sql = getSqlData($LearnningprogramId,$year3Name);
$year3sqlCommand = Yii::$app->db->createCommand($year3Sql);
$year3CommanDataReader = $year3sqlCommand->queryAll();
$algo3 = executeAlgo($LearnningprogramId,$year3Name);
//###ENd Year 3

//### Year 4
$year4Name = 'Year 4';
$year4Sql = getSqlData($LearnningprogramId,$year4Name);
$year4sqlCommand = Yii::$app->db->createCommand($year4Sql);
$year4CommanDataReader = $year4sqlCommand->queryAll();
$algo4 = executeAlgo($LearnningprogramId,$year4Name);
//###ENd Year 4

function executeAlgo($LearnningprogramId,$yearName)
{
    $algo = new Algorithm($LearnningprogramId,$yearName);
    $algo->execute();
    do{
        $restart = $algo->fillCurriculum();
    }while( $restart > 0 );

    $algo->writeToJsonFile();   
    return $algo;
}

function getSqlData($LearnningprogramId,$yearName)
{
    $sql ="SELECT ttc.`learnningProgram_id`,
               ttc.teacher_id,
               te.teacher_name,
               ttc.course_id,
               co.course_name,
               co.course_duration,
               ye.yearAndSemester_id
        FROM `teachertocourse` ttc
            join `coursetoyear` cty on ttc.`learnningProgram_id` = cty.`learnningProgram_id`
            join `yearandsemester` ye on ye.yearAndSemester_id = cty.yearAndSemester_id
            join `teacher` te on te.`teacher_id` = ttc.`teacher_id`
            join `course` co on co.`course_id` = cty.`course_id`
        WHERE ttc.`learnningProgram_id`=".$LearnningprogramId." 
        and ye.`yearAndSemester_name` = '".$yearName."'
	  and ttc.`course_id`= cty.`course_id`";
    return $sql;
}

$yearId=0;
$year2Id=0;
$year3Id=0;
$year4Id=0;
?>
<section class="content">
    <h3 style="margin:0" ><?= $Learnningprogram->learnningProgram_name ?></h3>
    <?php if( !empty($yearCommanDataReader) ){
            $yearId = reset($yearCommanDataReader)['yearAndSemester_id'];
        ?>
    <!--- Year 1 -->
    <h3>Year 1</h3>
    <div class="table-responsive" style="margin-top: 20px;">
        <table class="table table-bordered table-hover" id="course-year-tbl">
            <thead>
                <tr>
                    <th>Teacher</th>
                    <th>Course</th>
                    <th>Course Duration</th>
                </tr>
            </thead>
            <tbody id="course-year-tbl-body">

        <?php
            foreach( $yearCommanDataReader as $row )
            {
                echo  '<tr>';
                    echo  '<td><div>'.$row['teacher_name'].'<div></td>';
                    echo  '<td><div>'.$row['course_name'].'<div></td>';
                    echo  '<td><div>'.$row['course_duration'].'<div></td>';
                echo '</tr>';
            }
        ?>
            </tbody>
        </table>
    </div>
        
        <div class="row" id="divcalendar" >
            <div class="col-md-2">
                <div id='courses-11' class="inner-courses">
                    <h4>Courses</h4>
                    <?php
                        foreach($yearCommanDataReader as $row)
                        {
                            $isCourseInCurriculum = $algo->isCourseInCurriculum($row['course_id']);

                            if( $isCourseInCurriculum == false )
                            {
                                echo '<div class="fc-event" style="background:red" >'.$row['course_name'].'</div>';
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="col-md-10">
                <div id='calendar<?= $yearId ?>'></div>
            </div>
        </div>
    <?php }#####END YEAR 1 ?>
    <!--- Year 2 --->
    <?php if( !empty($year2CommanDataReader) ){
            $year2Id = reset($year2CommanDataReader)['yearAndSemester_id'];
        ?>
    <!--- Year 2 -->
    <h3>Year 2</h3>
    <div class="table-responsive" style="margin-top: 20px;">
        <table class="table table-bordered table-hover" id="course-year-tbl2">
            <thead>
                <tr>
                    <th>Teacher</th>
                    <th>Course</th>
                    <th>Course Duration</th>
                </tr>
            </thead>
            <tbody id="course-year-tbl-body">

        <?php
            foreach( $year2CommanDataReader as $row )
            {
                echo  '<tr>';
                    echo  '<td><div>'.$row['teacher_name'].'<div></td>';
                    echo  '<td><div>'.$row['course_name'].'<div></td>';
                    echo  '<td><div>'.$row['course_duration'].'<div></td>';
                echo '</tr>';
            }
        ?>
            </tbody>
        </table>
    </div>
        
        <div class="row" id="divcalendar" >
            <div class="col-md-2">
                <div id='courses-11' class="inner-courses">
                    <h4>Courses</h4>
                    <?php
                        foreach($year2CommanDataReader as $row)
                        {
                            $isCourseInCurriculum = $algo2->isCourseInCurriculum($row['course_id']);

                            if( $isCourseInCurriculum == false )
                            {
                                echo '<div class="fc-event" style="background:red" >'.$row['course_name'].'</div>';
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="col-md-10">
                <div id='calendar<?= $year2Id ?>'></div>
            </div>
        </div>
    <?php } ### YEAR 2 ?>
    <!--- Year 3 --->
    <?php if( !empty($year3CommanDataReader) ){
            $year3Id = reset($year3CommanDataReader)['yearAndSemester_id'];
        ?>
    <!--- Year 3 -->
    <h3>Year 3</h3>
    <div class="table-responsive" style="margin-top: 20px;">
        <table class="table table-bordered table-hover" id="course-year-tbl3">
            <thead>
                <tr>
                    <th>Teacher</th>
                    <th>Course</th>
                    <th>Course Duration</th>
                </tr>
            </thead>
            <tbody id="course-year-tbl-body">

        <?php
            foreach( $year3CommanDataReader as $row )
            {
                echo  '<tr>';
                    echo  '<td><div>'.$row['teacher_name'].'<div></td>';
                    echo  '<td><div>'.$row['course_name'].'<div></td>';
                    echo  '<td><div>'.$row['course_duration'].'<div></td>';
                echo '</tr>';
            }
        ?>
            </tbody>
        </table>
    </div>
        
        <div class="row" id="divcalendar" >
            <div class="col-md-2">
                <div id='courses-11' class="inner-courses">
                    <h4>Courses</h4>
                    <?php
                        foreach($year3CommanDataReader as $row)
                        {
                            $isCourseInCurriculum = $algo3->isCourseInCurriculum($row['course_id']);

                            if( $isCourseInCurriculum == false )
                            {
                                echo '<div class="fc-event" style="background:red" >'.$row['course_name'].'</div>';
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="col-md-10">
                <div id='calendar<?= $year3Id ?>'></div>
            </div>
        </div>
    <?php } ### YEAR 3 ?>
    
    <!--- Year 4 --->
    <?php if( !empty($year4CommanDataReader) ){
            $year4Id = reset($year4CommanDataReader)['yearAndSemester_id'];
        ?>
    <!--- Year 4 -->
    <h3>Year 4</h3>
    <div class="table-responsive" style="margin-top: 20px;">
        <table class="table table-bordered table-hover" id="course-year-tbl4">
            <thead>
                <tr>
                    <th>Teacher</th>
                    <th>Course</th>
                    <th>Course Duration</th>
                </tr>
            </thead>
            <tbody id="course-year-tbl-body">

        <?php
            foreach( $year4CommanDataReader as $row )
            {
                echo  '<tr>';
                    echo  '<td><div>'.$row['teacher_name'].'<div></td>';
                    echo  '<td><div>'.$row['course_name'].'<div></td>';
                    echo  '<td><div>'.$row['course_duration'].'<div></td>';
                echo '</tr>';
            }
        ?>
            </tbody>
        </table>
    </div>
        
        <div class="row" id="divcalendar" >
            <div class="col-md-2">
                <div id='courses-11' class="inner-courses">
                    <h4>Courses</h4>
                    <?php
                        foreach($year4CommanDataReader as $row)
                        {
                            $isCourseInCurriculum = $algo4->isCourseInCurriculum($row['course_id']);

                            if( $isCourseInCurriculum == false )
                            {
                                echo '<div class="fc-event" style="background:red" >'.$row['course_name'].'</div>';
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="col-md-10">
                <div id='calendar<?= $year4Id ?>'></div>
            </div>
        </div>
    <?php } ### YEAR 4 ?>
</section>

<?php
$inlineScript = 'var baseurl = ' . Yii::$app->homeUrl . ';';
$this->registerJs($inlineScript, View::POS_HEAD);
$this->registerJs("
 learningprogramId = '$LearnningprogramId';
 $('#course-year-tbl').DataTable({'bPaginate': false}).yadcf([
	    {column_number : 0, column_data_type: 'html', html_data_type: 'text', filter_default_label: 'Select Teacher'},
	    {column_number : 1, column_data_type: 'html', html_data_type: 'text', filter_default_label: 'Select Course'},
	    ]);
$('#course-year-tbl2').DataTable({'bPaginate': false}).yadcf([
        {column_number : 0, column_data_type: 'html', html_data_type: 'text', filter_default_label: 'Select Teacher'},
        {column_number : 1, column_data_type: 'html', html_data_type: 'text', filter_default_label: 'Select Course'},
        ]);
$('#course-year-tbl3').DataTable({'bPaginate': false}).yadcf([
        {column_number : 0, column_data_type: 'html', html_data_type: 'text', filter_default_label: 'Select Teacher'},
        {column_number : 1, column_data_type: 'html', html_data_type: 'text', filter_default_label: 'Select Course'},
        ]);
$('#course-year-tbl4').DataTable({'bPaginate': false}).yadcf([
        {column_number : 0, column_data_type: 'html', html_data_type: 'text', filter_default_label: 'Select Teacher'},
        {column_number : 1, column_data_type: 'html', html_data_type: 'text', filter_default_label: 'Select Course'},
        ]);
/* initialize the external events
-----------------------------------------------------------------*/
$('.fc-event').each(function() {

    // store data so the calendar knows to render an event upon drop
    $(this).data('event', {
        title: $.trim($(this).text()), // use the element's text as the event title
        stick: true // maintain when user navigates (see docs on the renderEvent method)
    });

    // make the event draggable using jQuery UI
    $(this).draggable({
        zIndex: 999,
        revert: true,      // will cause the event to go back to its
        revertDuration: 0  //  original position after the drag
    });
});

// Year 1
fetch_all_events('Year 1',".$yearId.");
getNotAllowedEvents(".$yearId.");
//###END Year 1 

// Year 2
fetch_all_events('Year 2',".$year2Id.");
getNotAllowedEvents(".$year2Id.");
//###END Year 2

// Year 3
fetch_all_events('Year 3',".$year3Id.");
getNotAllowedEvents(".$year3Id.");
//###END Year 3

// Year 4
fetch_all_events('Year 4',".$year4Id.");
getNotAllowedEvents(".$year4Id.");
//###END Year 4

function getNotAllowedEvents(yearAndSemesterId)
{
        if(yearAndSemesterId == 0 ){
            return;
        }
        var data = {
            'learningprogramId':learningprogramId,
            'yearAndSemesterId':yearAndSemesterId
        };

        $.ajax({
         url: baseurl+'site/notallowedevents',
         data: data,
         type: 'POST',

         success: function(response){
            var dataArray = $.parseJSON(response);
            if(typeof dataArray =='object')
            {
                  $.each(dataArray, function(idx, obj) {
                      id = idx;
                      start = dataArray[idx].start;
                      end = dataArray[idx].end;
                      title = dataArray[idx].title;
                      addCalanderEvent(id, start, end, title,yearAndSemesterId);
                  });
            }

         },
         error: function(e){
           console.log(e.responseText);
         }
       });
}

function addCalanderEvent(id, start, end, title,yearAndSemesterId)
{
    if(yearAndSemesterId == 0 ){
                return;
            }

    var eventObject = {
        title: title,
        start: start,
        end: end,
        id: id,
        backgroundColor:'orange',
        editable:false
    };
   
    $('#calendar'+yearAndSemesterId).fullCalendar('renderEvent', eventObject, true);
    
    
}

function fetch_all_events(yearAndSemesterName,yearAndSemesterId)
{
        if(yearAndSemesterId == 0 ){
            return;
        }
        
       /* initialize the calendar
		-----------------------------------------------------------------*/

		$('#calendar'+yearAndSemesterId).fullCalendar({
			defaultView: 'agendaWeek',
			allDaySlot: false,
			defaultDate: '2015-02-12',
			minTime:  '08:00',
			maxTime:  '22:00',
			editable: true,
			editable: true,
			droppable: true, // this allows things to be dropped onto the calendar
			hiddenDays: [ 6 ],
			timeFormat: {
                    agenda: 'H:mm' //h:mm{ - h:mm}'
            },
			eventClick: function(calEvent, jsEvent, view)
			{
				var r=confirm('Delete ' + calEvent.title);
				if (r===true)
				  {
                    if(calEvent.title != 'Free')
					  $('#calendar').fullCalendar('removeEvents', calEvent._id);
					  //deleteEvent(calEvent.id);
				  }
			},
			eventReceive: function(event)
			{
			    $('#calendar').fullCalendar('updateEvent',event);
			     //handleEventoDb(event,'new');
			},
            eventResize: function(event, delta, revertFunc)
            {
                   //handleEventoDb(event,'update');
            },
            drop: function() {
                    $(this).remove();       
            },
            eventDrop: function(event, delta, revertFunc)
            {
                  // handleEventoDb(event,'update');
            },
            events: {
                url: baseurl+'site/getevents',
                type: 'POST',
                data: {
                    'learnningprogramId':learningprogramId,
                    'yearAndSemesterName' :yearAndSemesterName
                },
                error: function() {
                    alert('there was an error while fetching events!');
                }
            }
		});
}
", View::POS_READY);
?>