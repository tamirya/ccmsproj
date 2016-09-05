<?php
namespace app\models;
use app\models\Learnningprogram;
use app\models\Yearandsemester;
use yii\base\Model;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use Yii;

$this->title ='Learning Program';
$Learnningprograms=Learnningprogram::find()->asArray()->all();
$yearandsemesters=Yearandsemester::find()->asArray()->orderBy('yearAndSemester_name')->all();

if(count($Learnningprograms)>=1){?>
    <div id="divpicklearnningProgram">
    <!---- present the Learning programs--->
    <div class="row" id="divpicklearnningProgram" >
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <select class="form-control" id="learnningprogram-select">
            <?php foreach($Learnningprograms as $Learnningprogram ){ ?>
            <option value="<?= $Learnningprogram['learnningProgram_id'] ?>" ><?= $Learnningprogram['learnningProgram_name'] ?></option>
            <?php }?>
        </select>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <select class="form-control" id="yearAndSemester-select">
            <?php foreach($yearandsemesters as $yearandsemester ){ ?>
            <option value="<?= $yearandsemester['yearAndSemester_id'] ?>" ><?= $yearandsemester['yearAndSemester_name'] ?></option>
            <?php }?>
        </select>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <button class="btn btn-primary" id="picklearnningProgram" >Pick</button>
        </div>
    </div>

    <div class="container" id="divcontainercalender" >
        <div class="row" id="divlearningprogramtitle">
            <h2 style="margin-top:15px;margin-left: 20px"></h2>
        </div>

        <!--- Details About The year and semester ---->
        <div class="row" id="detaisyearandsemeter">

        </div>

        <div class="row" id="divcalendertitle">
             <h3 style="margin-top:15px;margin-bottom: 25px;margin-left: 20px" >Choose The Days And Hours</h3>
        </div>
        <div class="row" id="divcalendar" >
            <div class="col-md-2">
                <div id='external-events'>
                    <h4>Events</h4>
                    <div class='fc-event'>Study</div>
                    <div class='fc-event' style="background-color: orange;" >Free</div>
                </div>
            </div>
            <div class="col-md-10">
                <div id='calendar'></div>
            </div>

        </div>
    </div>

<?php }else{?>
    <!----No Learning programs  ---->
    <div class="alert alert-danger alert-dismissable">
        <i class="fa fa-ban"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <b>Alert!</b> No Learning programs were found....please create one.
    </div>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createlearningprogramform">Create New Learning Program</button>

    <!-- Modal -->
    <div class="modal fade" id="createlearningprogramform" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Create New Learning Program</h4>
                </div>
                <div class="modal-body">
                   <!-- form createlearningprogramform -->
                    <?php $form = ActiveForm::begin(
                        [
                            'id' => 'addlearningprogram-form',
                            //'action' => ['saveLearningProgram'],
                        ]);
                    ?>
                    <div class="body">

                        <?= $form->field($learningprogrammodel, 'learningProgramName'); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <?= Html::submitButton('Save', ['class' => 'btn bg-olive btn-primary', 'name' => 'save-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php }

$inlineScript = 'var baseurl = ' . Yii::$app->homeUrl . ';';
$this->registerJs($inlineScript, View::POS_HEAD);

$this->registerJs("
    $('#picklearnningProgram').click(function(){
        $('#calendar').fullCalendar( 'destroy' );
        var learningprogramtitle = $('#learnningprogram-select').find(':selected').text();
        $('#divlearningprogramtitle h2').text(learningprogramtitle);
        $('#divcontainercalender').slideDown(1000);

        var learnningprogramid = $('#learnningprogram-select').val();
        var yearandsemesterid = $('#yearAndSemester-select').val();
        //fetch details year and semseter
        //update_learnningprogram_to_semester(learnningprogramid,yearandsemesterid);
        console.log(yearandsemesterid);
        //fetch all events
        fetch_all_events(learnningprogramid,yearandsemesterid);
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

$('#divcontainercalender').hide();


function update_learnningprogram_to_semester(learnningprogramid,yearandsemesterid)
{
    var data = {
        'learnningprogramid':learnningprogramid,

    };

    $.ajax({
     url: baseurl+'site/updatelearnningprogramtosemester',
     data: data,
     type: 'POST',
     success: function(response){

     },
     error: function(e){
       console.log(e.responseText);
     }
   });

}


function fetch_all_events(learnningprogramid,yearandsemesterid)
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
				  }
			},
			eventReceive: function(event)
			{
			    if(  event.title == 'Free')
			     {
			        event.backgroundColor = 'orange';
			     }
			    $('#calendar').fullCalendar('updateEvent',event);
			     handleEventoDb(event,'new');
			},
            eventResize: function(event, delta, revertFunc)
            {
                   handleEventoDb(event,'update');
            },
            eventDrop: function(event, delta, revertFunc)
            {
                   handleEventoDb(event,'update');
            },
            events: {
                url: baseurl+'site/insertimetableevnt',
                type: 'POST',
                data: {
                    'learnningprogramid':learnningprogramid,
                    'yearandsemesterid':yearandsemesterid,
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
     url: baseurl+'site/insertimetableevnt',
     data: data,
     type: 'POST',
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
    /*
    var startimeDate = new Date(startime);
    var endtimeDate = (event.end == null) ? null :new Date(endtime);

    var d = new Date();
    var startimehour = startimeDate.getHours()+zone;
    var endtimehour  = (event.end == null) ? null :endtimeDate.getHours()+zone;

    startimeDate.setHours( startimehour );

    if(event.end != null){
        endtimeDate.setHours( endtimehour );
    }*/

    data = {
        'eventid':event.id,
        'startime':startime,
        'endtime':endtime,
        'type':'update',

    };

    $.ajax({
     url: baseurl+'site/insertimetableevnt',
     data: data,
     type: 'POST',
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
        'learnningprogramid':$('#learnningprogram-select').val(),
        'yearandsemesterid' : $('#yearAndSemester-select').val()
    };

    $.ajax({
     url: baseurl+'site/insertimetableevnt',
     data: data,
     type: 'POST',
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