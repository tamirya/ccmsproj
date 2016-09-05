<?php
namespace app\models;
use yii\base\Model;
use app\models\Teacher;
use app\models\Teachertocourse;
use app\models\Learnningprogram;
use app\models\Yearandsemester;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use Yii;

$this->title ='Teachers Constrains';
$teachers          = Teacher::find()->asArray()->orderBy('teacher_name')->all();
$Learnningprograms = Learnningprogram::find()->asArray()->orderBy('learnningProgram_name')->all();
$firstLearnningprogramsobject = empty($Learnningprograms) ? -1 :reset($Learnningprograms);

if( isset($_GET['learnningProgramid']) ){
    $learnningProgramidbyget = $_GET['learnningProgramid'];
    $teacherstocourses = getTeacherToCourses($learnningProgramidbyget);
    $firstTeacher = isset($_GET['teacherid']) ? Teacher::find()->where(['teacher_id'=>$_GET['teacherid']])->asArray()->orderBy('teacher_name')->one() : reset($teacherstocourses) ;
    
    $yearandsemesters=getYears($firstTeacher,$learnningProgramidbyget);
}else{
    $teacherstocourses = getTeacherToCourses($firstLearnningprogramsobject['learnningProgram_id']);
    $firstTeacher = isset($_GET['teacherid']) ? Teacher::find()->where(['teacher_id'=>$_GET['teacherid']])->asArray()->orderBy('teacher_name')->one() : reset($teacherstocourses) ;
    $yearandsemesters=getYears($firstTeacher,$firstLearnningprogramsobject['learnningProgram_id']);
}


function getTeacherToCourses($learnningProgramId)
{
    $sql ='SELECT  ttc.`teacher_id`,te.`teacher_name`
            FROM `teachertocourse` ttc
            join  `coursetoyear`   cty on cty.`learnningProgram_id` = ttc.`learnningProgram_id`
            join `teacher` te on te.`teacher_id`= ttc.`teacher_id`
            WHERE   ttc.`course_id`= cty.`course_id`
                            and ttc.`learnningProgram_id`='.$learnningProgramId.' 
            group by ttc.`teacher_id` order by te.`teacher_name`';
    
    $teacherToCoursesData = Yii::$app->db->createCommand($sql);
    $teacherToCoursesDataConnectedReader = $teacherToCoursesData->queryAll();
    
    return $teacherToCoursesDataConnectedReader;
    
}

function getYears($firstTeacher,$learnningProgramId)
{
    
    if( $firstTeacher == -1 )
    {
        return -1;
    }
    
    $sql='SELECT year.yearAndSemester_id ,year.yearAndSemester_name
		FROM `teachertocourse` ttc
        join  `coursetoyear`   cty on cty.`learnningProgram_id` = ttc.`learnningProgram_id`
        join   `yearandsemester` year on cty.yearAndSemester_id = year.yearAndSemester_id
        WHERE ttc.`course_id`= cty.`course_id`
        and ttc.`learnningProgram_id`='.$learnningProgramId.'
        and ttc.`teacher_id`='.$firstTeacher['teacher_id'].'
        group by year.yearAndSemester_name';
    
    $yearData = Yii::$app->db->createCommand($sql);
    $yearDataConnectedReader = $yearData->queryAll();
    return $yearDataConnectedReader;
}

?>
<section class="content">
    <div id="wrap-pickteacher">

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
        
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <h4>Pick Teacher</h4>
                <select id="select-pickteacher">
                    <?php   
                        foreach($teacherstocourses as $teacher){
                            if( $firstTeacher['teacher_id'] == $teacher['teacher_id'] ){
                                echo '<option value="'.$teacher['teacher_id'].'" selected>'.$teacher['teacher_name'].'</option>'; 
                            }else{
                             echo '<option value="'.$teacher['teacher_id'].'">'.$teacher['teacher_name'].'</option>';    
                            }
                            ?>
                    <?php }?>
                </select>
        </div>
        
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <h4>Pick Year</h4>
        <select class="form-control" id="yearAndSemester-select">
            <?php foreach($yearandsemesters as $yearandsemester ){ ?>
            <option value="<?= $yearandsemester['yearAndSemester_id'] ?>" ><?= $yearandsemester['yearAndSemester_name'] ?></option>
            <?php }?>
        </select>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="margin-top: 39px;">
            <button class="btn btn-default" id="pickteacher-btn">Pick</button>
        </div>
</div>
<div class="row">
    <div class="col-md-2"> <h6>Min teaching hours to pick:</h6> </div>
    <div class="col-md-9"><h5 id="calcteachinghours"></h5></div>
</div>
<div class="container" id="divcontainercalender" >
    <div class="row" id="divlearningprogramtitle">
        <h2 style="margin-top:15px;margin-left: 20px"></h2>
    </div>

    <div class="row" id="divcalendertitle">
        <h3 style="margin-top:15px;margin-bottom: 25px;margin-left: 20px" >Choose The Days And Hours</h3>
    </div>
    <div class="row" id="divcalendar" >
        <div class="col-md-2">
            <div id='external-events'>
                <h4>Events</h4>
                <div class='fc-event'>Study</div>
            </div>
        </div>
        <div class="col-md-10">
            <div id='calendar'></div>
        </div>
    </div>
</div>
</section>

<?php
$inlineScript = 'var baseurl = ' . Yii::$app->homeUrl . ';';
$this->registerJs($inlineScript, View::POS_HEAD);

$this->registerJs("
    teacherFullCalc =0;
    $('#select-pickteacher').select2();
    $('#select-learningprogram').select2();
    $('#yearAndSemester-select').select2();
    
    $('#select-pickteacher').on('change', function()
        {
            var	teacherid = $(this).val();
            var	learnningProgramid = $('#select-learningprogram').val();
            window.location.href = baseurl+'teacherconstrains?learnningProgramid='+learnningProgramid+'&teacherid='+teacherid;

        });
    $('#select-learningprogram').on('change', function()
        {
            var	learnningProgramid = $(this).val();
            window.location.href = baseurl+'teacherconstrains?learnningProgramid='+learnningProgramid;

        });

    /* initialize the external events
		-----------------------------------------------------------------*/

		$('#external-events .fc-event').each(function() {

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

    $('#pickteacher-btn').click(function(){
        var learningprogramId  =  $('#select-learningprogram').val();
        var yearAndSemesterid = $('#yearAndSemester-select').val();
        getNotAllowedEvents(learningprogramId,yearAndSemesterid);
        $('#calendar').fullCalendar( 'destroy' );
        var teacherid = $('#select-pickteacher').val();
        //fetch all events
        fetch_all_events(teacherid,learningprogramId);
        //calc teaching hours
        handlecalcteachinghours(teacherid,learningprogramId,yearAndSemesterid);
    });

function getNotAllowedEvents(learningprogramId,yearAndSemesterId)
{
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
                      addCalanderEvent(id, start, end, title);
                  });
            }

         },
         error: function(e){
           console.log(e.responseText);
         }
       });
}

function addCalanderEvent(id, start, end, title)
{
    var eventObject = {
        title: title,
        start: start,
        end: end,
        id: id,
        backgroundColor:'orange',
        editable:false
    };

    $('#calendar').fullCalendar('renderEvent', eventObject, true);
}

function calcNewTeacherMinHours(learningprogramId,teacherid,yearAndSemesterid)
{
    var data = {
        'learningprogramId':learningprogramId,
        'teacherid':teacherid,
        'yearAndSemesterid':yearAndSemesterid,
    };

     $.ajax({
         url: baseurl+'site/calcteacherteachingnewhours',
         data: data,
         type: 'POST',
         async :false,
         success: function(response){
             var re = parseInt(response);
             var finalDiff = teacherFullCalc - re;
             $('#calcteachinghours').html(finalDiff);
         },
         error: function(e){
           console.log(e.responseText);
         }
       });
}


function handlecalcteachinghours(teacherid,learningprogramId,yearAndSemesterid)
{
     var data = {
        'teacherid':teacherid,
        'learningprogramId':learningprogramId,
        'yearAndSemesterid':yearAndSemesterid
    };

    $.ajax({
     url: baseurl+'site/calcteacherteachinghours',
     data: data,
     type: 'POST',
     async :false,
     success: function(response){
        teacherFullCalc = response;
     },
     error: function(e){
       console.log(e.responseText);
     }
   });

 var calc=0;
 var events =  $('#calendar').fullCalendar('clientEvents');
 var yearStr = $('#yearAndSemester-select option:selected').text();
 $.each(events, function(idx, obj) {
         if(  obj.title != 'Free')
         {
              yearObj = obj.title.substr(obj.title.indexOf('-') + 1);
              if( yearObj == yearStr ){
                  var start  = obj.start._i;
                  var end  = obj.end._i;
                  var startDate = Date.parse(start);
                  var endDate = Date.parse(end);
                  var startDate = new Date(startDate);
                  var endDate = new Date(endDate);
                  var startHours = startDate.getHours();
                  var endHours = endDate.getHours();
                  var diff = endHours - startHours;
                  calc = diff + calc; 
              }
              
         }

  });

  var finalDiff = teacherFullCalc - calc;
  $('#calcteachinghours').html(finalDiff);

}

function fetch_all_events(teacherid,learningprogramId)
{
        /* initialize the calendar
		-----------------------------------------------------------------*/

		$('#calendar').fullCalendar({
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
                                  $('#calendar').fullCalendar('removeEvents', calEvent._id);
                                  deleteEvent(calEvent.id);
                                  var yearAndSemesterid = $('#yearAndSemester-select').val();
                                  calcNewTeacherMinHours(learningprogramId,teacherid,yearAndSemesterid);
                              }
			},
			eventReceive: function(event)
			{
			    $('#calendar').fullCalendar('updateEvent',event);
			     handleEventoDb(event,'new');
			},
            eventResize: function(event, delta, revertFunc)
            {
                   handleEventoDb(event,'update');
                   var yearAndSemesterid = $('#yearAndSemester-select').val();
                   calcNewTeacherMinHours(learningprogramId,teacherid,yearAndSemesterid);
            },
            eventDrop: function(event, delta, revertFunc)
            {
                   handleEventoDb(event,'update');
            },
            events: {
                url: baseurl+'site/insertimetableteachers',
                type: 'POST',
                async :false,
                data: {
                    'teacherid':teacherid,
                    'learningprogramId':learningprogramId,
                    'type':'fetch'
                },
                error: function() {
                    alert('there was an error while fetching events!');
                }
            }

		});
}

function handleEventoDb(event,type){

    if(type == 'new'){

        insertEventoDb(event);
    }

    if(type == 'update'){

        updateEventoDb(event);
    }
}

function deleteEvent(eventid){
    var data = {
        'eventid':eventid,
        'type':'delete'
    };

    $.ajax({
     url: baseurl+'site/insertimetableteachers',
     data: data,
     type: 'POST',
     async :false,
     success: function(response){

     },
     error: function(e){
       console.log(e.responseText);
     }
   });
}

function updateEventoDb(event){
    var zone     = get_time_zone_offset();
    var startime = event.start.format('YYYY-MM-DD[T]HH:mm');
    var endtime = (event.end == null) ? null : event.end.format('YYYY-MM-DD[T]HH:mm');
    var title    = event.title;

    data = {
        'eventid':event.id,
        'startime':startime,
        'endtime':endtime,
        'type':'update',
    };

    $.ajax({
     url: baseurl+'site/insertimetableteachers',
     data: data,
     type: 'POST',
     async :false,
     success: function(response){

     },
     error: function(e){
       console.log(e.responseText);
     }
   });
}

function insertEventoDb(event){
    var zone     = get_time_zone_offset();
    var startime = event.start.format('YYYY-MM-DD[T]HH:mm');
    var title    = event.title;
    
    data = {
        'title':title,
        'startime':startime,
        'type':'new',
        'teacherid':$('#select-pickteacher').val(),
        'learningprogramId':$('#select-learningprogram').val(),
        'yearAndSemesterId':$('#yearAndSemester-select').val()
    };

    $.ajax({
     url: baseurl+'site/insertimetableteachers',
     data: data,
     type: 'POST',
     async :false,
     success: function(eventid){
       event.id = eventid;
       $('#calendar').fullCalendar('updateEvent',event);
     },
     error: function(e){
       console.log(e.responseText);
     }
   });
}
function get_time_zone_offset() {
     var current_date = new Date();
     var gmt_offset = current_date.getTimezoneOffset()/60;
     return gmt_offset;
}
", View::POS_READY);
?>
