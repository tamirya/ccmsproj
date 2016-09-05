<?php
namespace app\models;
use yii\base\Model;
use app\models\Teacher;
use app\models\Teachertocourse;
use app\models\Learnningprogram;
use yii\helpers\Html;
use yii\web\View;
use Yii;
use yii\web\Controller;

class Algorithm {
     private  $teacherToCourseArr=array();
     private  $teacherConstrainsArr=array();
     private  $learnningProgram=0;
     private  $year=0;
     private  $restrtCount=0;
     private  $curriculum=array();

    function  __construct($learnningProgram,$yearName)
    {
        $this->learnningProgram = $learnningProgram;
        $this->year = $yearName;
    }

    function setLearnningProgram($num)
    {
        $this->learnningProgram = $num;
    }

    function getLearnningProgram()
    {
        return $this->learnningProgram;
    }

    function execute()
    {
        /*
        $sql = 'SELECT ttc.`learnningProgram_id`,
               ttc.teacher_id,
               te.teacher_name,
               ttc.course_id,
               co.course_name,
               co.course_duration
        FROM `teachertocourse` ttc
            join `course` co on co.course_id = ttc.course_id
            join `teacher` te on te.teacher_id = ttc.teacher_id
        WHERE ttc.`learnningProgram_id` = '.$this->learnningProgram;
         * 
         */
        $sql="SELECT ttc.`learnningProgram_id`,
               ttc.teacher_id,
               te.teacher_name,
               ttc.course_id,
               co.course_name,
               co.course_duration
	FROM `teachertocourse` ttc
        join `coursetoyear` cty on ttc.`learnningProgram_id` = cty.`learnningProgram_id`
        join `yearandsemester` ye on ye.yearAndSemester_id = cty.yearAndSemester_id
        join `teacher` te on te.`teacher_id` = ttc.`teacher_id`
        join `course` co on co.`course_id` = cty.`course_id`
        WHERE ttc.`learnningProgram_id`=".$this->learnningProgram."  
              and ye.`yearAndSemester_name` = '".$this->year."'  
              and ttc.`course_id`= cty.`course_id`";
        
        $datasqlCommand = Yii::$app->db->createCommand($sql);
        $dataCommanDataReader = $datasqlCommand->queryAll();
        
        if( !empty($dataCommanDataReader) )
        {
            //####################
            // Insert Data To Arrays
            //####################

            foreach($dataCommanDataReader as $row)
            {
                $this->teacherToCourseArr[$row['teacher_id']][] = array(
                    'courseId'=>$row['course_id'],
                    'courseName'=>$row['course_name'],
                    'courseDuration'=>$row['course_duration'],
                );
                $teacherId=$row['teacher_id'];
                $isExsits = false;
                foreach(  $this->teacherConstrainsArr as $id=>$data )
                {
                    if( $id == $teacherId )
                    {
                        $isExsits=true;
                        break;
                    }
                }

                if( $isExsits==false  )
                {
                    $constrains = $this->getTeacherConstrains($teacherId);

                    foreach( $constrains as $constrain  )
                    {
                        $this->teacherConstrainsArr[$constrain['teacher_id']][] = array(
                            'startDate'=>$constrain['startdate'],
                            'endDate'=>$constrain['enddate'],
                            'startDateTimeStamp'=> strtotime($constrain['startdate']),
                            'endDateDateTimeStamp'=> strtotime($constrain['enddate']),
                        );
                    }
                }
            }
        }
    }

    private function getTeacherConstrains($teacherId)
    {
        $sql='SELECT * 
            FROM `teachersconstrains` tc
            join `yearandsemester` ye on ye.yearAndSemester_id = tc.yearAndSemester_id
            WHERE `learnningProgram_id`='.$this->learnningProgram.
            ' and `teacher_id`='.$teacherId.'
            and ye.`yearAndSemester_name` = "'.$this->year.'" 
            ORDER BY tc.`startdate` ASC';
    
        $datasqlCommand = Yii::$app->db->createCommand($sql);
        $dataCommanDataReader = $datasqlCommand->queryAll();
        
        return !empty($dataCommanDataReader) ? $dataCommanDataReader : array();
    }

    public function getCurriculum(){
        return $this->curriculum;
    }
    public function fillCurriculum()
    {
        $this->restrtCount=0;

        foreach(  $this->teacherToCourseArr as $teacherId=>$row  )
        {
            foreach( $row as $courseData  )
            {
                $this->insertCourseToCurriculum($teacherId,$courseData);
            }
        }
        /*
        if( !empty($this->curriculum)  ) {
            var_dump('$this->curriculum');
            var_dump($this->curriculum);
            var_dump('$this->teacherToCourseArr');
            var_dump($this->teacherToCourseArr);
            var_dump('$this->restrtCount final');
            var_dump($this->restrtCount);
            var_dump('$this->teacherConstrainsArr');
            var_dump($this->teacherConstrainsArr);
            var_dump('########################################');
            exit; 
        }*/
        
        return $this->restrtCount;
    }

    public function writeToJsonFile()
    {
        if( !empty($this->curriculum)  )
        {
            $learnningProgramId = $this->learnningProgram;
            $events = $this->curriculum;
            $fileName = 'json_data/events_'.$learnningProgramId.'_'.$this->year;
            $fp = fopen($fileName, 'w');
            fwrite($fp, json_encode($events));
            fclose($fp);
            return true;
        }
        return false;
    }

    private function insertCourseToCurriculum($teacherId,$courseData)
    {
        //get Constrains

        if( !isset($this->teacherConstrainsArr[$teacherId])  )
        {
            return;
        }
        $teacherConstrains = $this->teacherConstrainsArr[$teacherId];
        // check curriculum empty
        if( !empty($this->curriculum)  )
        {
            foreach( $teacherConstrains as $key=>$teacherConstrain  )
            {
                $isConstrainValid = false;
                // check for collissions in curriculms
                $isConstrainValid = $this->isConstrainValid($teacherConstrain,$courseData,$teacherId,$key);

                if($isConstrainValid === -99)
                {
                    /*
                    var_dump('$isConstrainValid -99');
                    var_dump($isConstrainValid);
                    */
                    continue;
                }

                if($isConstrainValid === false)
                {
                    /*
                    var_dump('$isConstrainValid false');
                    var_dump($isConstrainValid);
                    */
                   $this->restrtCount++;
                }

                if($isConstrainValid === true) {
                    /*
                    var_dump('$isConstrainValid true');
                    var_dump($isConstrainValid);
                    exit;
                    */

                    //Constrain Valid !!!
                    //add constrain to curriculm
                    $isInsertedToCurriculm = $this->insertConstrainToCurriculum($teacherConstrain,$courseData);

                    // check if constrain is inserted
                    if( $isInsertedToCurriculm  )
                    {
                        //remove constrain
                        unset($this->teacherConstrainsArr[$teacherId][$key]);
                        //remove course from array
                        $teacherToCourseArr = $this->teacherToCourseArr[$teacherId];
                        /*
                        var_dump($courseData);
                        var_dump('before');
                        var_dump($this->teacherToCourseArr);
                        */
                        foreach(  $teacherToCourseArr as $key=>$teacherToCourse )
                        {
                            if($teacherToCourse['courseId'] == $courseData['courseId']
                                and $teacherToCourse['courseName'] ==  $courseData['courseName']
                            )
                            {
                                unset($this->teacherToCourseArr[$teacherId][$key]);
                                break;
                            }
                        }
                        /*
                        var_dump('after');
                        var_dump($this->teacherToCourseArr);
                        */
                    }
                }
                return;
            }
        }else{
            /*
            var_dump('$this->curriculum');
            var_dump($this->curriculum);
            var_dump('$this->teacherToCourseArr');
            var_dump($this->teacherToCourseArr);
            var_dump('$this->restrtCount final');
            var_dump($this->restrtCount);
            var_dump('$this->teacherConstrainsArr');
            var_dump($this->teacherConstrainsArr);
            var_dump('########################################');
            exit; */

            //case is empty
            foreach( $teacherConstrains as $key=>$teacherConstrain  )
            {
                // if fit course duration
                $diff = $this->getTimeStampDiffHour($teacherConstrain);
                if( $diff == $courseData['courseDuration']  )
                {
                    $this->insertConstrainToCurriculum($teacherConstrain,$courseData);
                    unset($this->teacherConstrainsArr[$teacherId][$key]);
                    //remove course from array
                    $teacherToCourseArr = $this->teacherToCourseArr[$teacherId];

                    

                    foreach(  $teacherToCourseArr as $teacherToCourseKey=>$teacherToCourse )
                    {
                    
                        if( $teacherToCourse['courseId'] ==  $courseData['courseId'] )
                        {
                            unset($this->teacherToCourseArr[$teacherId][$teacherToCourseKey]);
                            break;    
                        }
                        
                    }
                    break;
                }

                if( $diff > $courseData['courseDuration']  )
                {
                    unset($this->teacherConstrainsArr[$teacherId][$key]);

                    //var_dump($teacherConstrains);
                    //var_dump($courseData);

                    // the constrain is too big
                    // we need to split it
                    //## first constrain
                        $firstConstrainStartTime = $teacherConstrain['startDateTimeStamp'];
                        $firstConstrainEndTime   = $this->addHourToTimeStamp($teacherConstrain['startDateTimeStamp'],$courseData['courseDuration']);
                    //##insert constrain
                        $eventData = $this->convertTimeStampToDateArr($firstConstrainStartTime,$firstConstrainEndTime);
                        //var_dump($eventData);
                        $this->insertTeacherConstrain($teacherId,$eventData);
                    //## second constrain
                    //second constrain
                    $secondConstrainStartTime = $firstConstrainEndTime;
                    $secondConstrainEndTime   = $teacherConstrain['endDateDateTimeStamp'];
                    $eventData = $this->convertTimeStampToDateArr($secondConstrainStartTime,$secondConstrainEndTime);
                    ///var_dump($eventData);
                    //exit;
                    $this->insertTeacherConstrain($teacherId,$eventData);
                    $this->restrtCount++;
                    break;
                }
            }
        }
    }

    private function isConstrainValid($teacherConstrain,$courseData,$teacherId,$key)
    {
        //###teacherConstrain
        //day
            $teacherConstrainDay = date("w",$teacherConstrain['startDateTimeStamp']);
        //start hour
            $teacherConstrainStartHour = date('H:i',$teacherConstrain['startDateTimeStamp']);
        //end hour
            $teacherConstrainEndtHour = date('H:i',$teacherConstrain['endDateDateTimeStamp']);
        //###END teacherConstrain
            $curriculumConstrains = $this->curriculum;

        foreach($curriculumConstrains as $curriculumConstrain)
        {
            //####curriculumConstrain
                $curriculumConstrainStartTimeStamp = strtotime($curriculumConstrain['start']);
                $curriculumConstrainEndTimeStamp = strtotime($curriculumConstrain['end']);
            //day
                $curriculumConstrainDay = date("w",$curriculumConstrainStartTimeStamp);
            //start hour
                $curriculumConstrainStartHour = date('H:i',$curriculumConstrainStartTimeStamp);
            //end hour
                $curriculumConstrainEndHour = date('H:i',$curriculumConstrainEndTimeStamp);
            //####END curriculumConstrain

            if(  $curriculumConstrainDay == $teacherConstrainDay )
            {
                /*
                * black
                 * S1
                * $curriculumConstrainStartHour
                 * E1
                * $curriculumConstrainEndHour
                * red
                 * S2
                * $teacherConstrainStartHour
                 * E2
                * $teacherConstrainEndtHour
                */
                //### state 1
                if( $curriculumConstrainStartHour   < $teacherConstrainStartHour
                    and $teacherConstrainStartHour  < $curriculumConstrainEndHour
                    and $curriculumConstrainEndHour < $teacherConstrainEndtHour
                ){
                    //var_dump('//###state 1');
                    //create new constrain => E1->E2
                    $eventData = $this->convertTimeStampToDateArr($curriculumConstrainEndTimeStamp,$teacherConstrain['endDateDateTimeStamp']);

                    //remove constrain
                    unset($this->teacherConstrainsArr[$teacherId][$key]);
                    //insert new constrain
                    $this->insertTeacherConstrain($teacherId,$eventData);
                    return  false;
                }

                //###state 2
                if( $curriculumConstrainStartHour ==  $teacherConstrainStartHour
                    and $curriculumConstrainEndHour == $teacherConstrainEndtHour
                ){
                    //var_dump('//###state 2');
                    //remove constrain
                    unset($this->teacherConstrainsArr[$teacherId][$key]);
                    return false;
                }
                //###state 3
                if( $curriculumConstrainStartHour ==  $teacherConstrainStartHour
                    and $curriculumConstrainEndHour <  $teacherConstrainEndtHour
                ){
                    //var_dump('//###state 3');
                    $eventData = $this->convertTimeStampToDateArr($curriculumConstrainEndTimeStamp,$teacherConstrain['endDateDateTimeStamp']);
                    //remove constrain
                    unset($this->teacherConstrainsArr[$teacherId][$key]);
                    //add new constrain => E1->E2
                    $this->insertTeacherConstrain($teacherId,$eventData);
                    return  false;
                }

                //###state 4
                if( $teacherConstrainStartHour <  $curriculumConstrainStartHour
                    and $curriculumConstrainStartHour < $teacherConstrainEndtHour
                    and $teacherConstrainEndtHour < $curriculumConstrainEndHour
                )
                {
                    //var_dump('//###state 4');
                    $eventData = $this->convertTimeStampToDateArr($teacherConstrain['startDateTimeStamp'],$curriculumConstrainStartTimeStamp);
                    //remove constrain
                    unset($this->teacherConstrainsArr[$teacherId][$key]);
                    //add new constrain => S2->S1
                    $this->insertTeacherConstrain($teacherId,$eventData);
                    return  false;
                }

                //###state 5
                if( $teacherConstrainStartHour <  $curriculumConstrainStartHour
                    and $curriculumConstrainEndHour < $teacherConstrainEndtHour
                )
                {
                    //var_dump('//###state 5');
                    //remove constrain
                    unset($this->teacherConstrainsArr[$teacherId][$key]);
                    // cut constrain 1 => S2->S1
                    $eventData1 = $this->convertTimeStampToDateArr($curriculumConstrainStartTimeStamp,$teacherConstrain['startDateTimeStamp']);
                    $eventData2 = $this->convertTimeStampToDateArr($curriculumConstrainEndTimeStamp,$teacherConstrain['endDateDateTimeStamp']);

                    //add new constrain => E1->E2
                    $this->insertTeacherConstrain($teacherId,$eventData1);
                    $this->insertTeacherConstrain($teacherId,$eventData2);
                    return  false;
                }
                
                //###state 6
                if( $curriculumConstrainStartHour == $teacherConstrainStartHour
                    and $teacherConstrainStartHour < $curriculumConstrainEndHour
                    and $curriculumConstrainStartHour<$teacherConstrainEndtHour
                    and $teacherConstrainEndtHour < $curriculumConstrainEndHour
                   )
                {
                    return  -99;
                }
                
                //###state 7
                if(  $curriculumConstrainStartHour < $teacherConstrainStartHour
                     and $teacherConstrainStartHour < $curriculumConstrainEndHour
                     and $curriculumConstrainStartHour < $teacherConstrainEndtHour
                     and $teacherConstrainEndtHour<$curriculumConstrainEndHour
                  )
                {
                    return -99;
                }
            }
        }

        // Constrain Valid !!!
        //check constrain
        $constrainDiff = $this->getTimeStampDiffHour($teacherConstrain);
        $courseDuration=$courseData['courseDuration'];
        if(  $constrainDiff > $courseDuration   )
        {
            //var_dump('$constrainDiff > $courseDuration ');
            //remove constrain
            unset($this->teacherConstrainsArr[$teacherId][$key]);
            //split to two new constrains
            // first constrain
                $firstConstrainStartTime = $teacherConstrain['startDateTimeStamp'];
                $firstConstrainEndTime   = $this->addHourToTimeStamp($teacherConstrain['startDateTimeStamp'],$courseDuration);
                //insert constrain
                $eventData = $this->convertTimeStampToDateArr($firstConstrainStartTime,$firstConstrainEndTime);
                $this->insertTeacherConstrain($teacherId,$eventData);
            //second constrain
                $secondConstrainStartTime = $firstConstrainEndTime;
                $secondConstrainEndTime   = $teacherConstrain['endDateDateTimeStamp'];
                $eventData = $this->convertTimeStampToDateArr($secondConstrainStartTime,$secondConstrainEndTime);
                $this->insertTeacherConstrain($teacherId,$eventData);
            return false;
        }

        if( $constrainDiff == $courseDuration )
        {
            return true;
        }
        // nothing to do with that
        return -99;
    }

    private function addHourToTimeStamp($timestamp,$addHour)
    {
        $addHourTimeStamp = $addHour*3600;
        return ($timestamp+$addHourTimeStamp);
    }
    private function convertTimeStampToDateArr( $start , $end )
    {
        //create new constrain
        $eventStartDate = date('Y-m-d',$start);
        $eventStartHour = date('H:i',$start);
        $eventStartDate = $eventStartDate.'T'.$eventStartHour;

        $eventEndDate = date('Y-m-d',$end);
        $eventEndHour = date('H:i',$end);
        $eventEndDate = $eventEndDate.'T'.$eventEndHour;

        $dataReturned = array(
            'eventStartDate' => $eventStartDate,
            'eventEndDate'=>$eventEndDate,
        );

        return $dataReturned;
    }

    private function insertTeacherConstrain($teacherId,$eventData)
    {
        $startdate = $eventData['eventStartDate'];
        $enddate   = $eventData['eventEndDate'];

        $this->teacherConstrainsArr[$teacherId][] = array(
            'startDate'=>$startdate,
            'endDate'=>$enddate,
            'startDateTimeStamp'=> strtotime($startdate),
            'endDateDateTimeStamp'=> strtotime($enddate),
        );
    }

    private function getTimeStampDiffHour($time)
    {

        return ($time['endDateDateTimeStamp'] - $time['startDateTimeStamp'])/3600;
    }

    private function insertConstrainToCurriculum($teacherConstrain,$courseData)
    {
        // if fit course duration
        $diff = $this->getTimeStampDiffHour($teacherConstrain);
        if( $diff == $courseData['courseDuration']  )
        {
            $e=array();
            $e['id']    = $courseData['courseId'];
            $e['title'] = $courseData['courseName'];
            $e['start'] = $teacherConstrain['startDate'];
            $e['end'] = $teacherConstrain['endDate'];
            $e['allDay'] = false;
            $e['editable'] = true;
            $this->curriculum[] = $e;
            return true;
        }
        return false;
    }

    public function isCourseInCurriculum($courseId)
    {
        foreach( $this->curriculum as $row  )
        {
            if( $row['id'] ==  $courseId )
            {
                return true;
            }
        }
        return false;
    }
}