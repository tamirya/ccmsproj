<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignupForm;
use app\models\LearningProgramForm;
use app\models\Yearandsemester;
use app\models\Coursetoyear;
use app\models\Teachertocourse;
use app\models\Course;
use app\models\Timetable;
use app\models\Teachersconstrains;
use app\models\Learningprogramtoyear;

class SiteController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

     
    public function actionIndex() {
       
         if (Yii::$app->user->isGuest) {
            return $this->redirect('site/login');
        }else{
            return $this->render('index');
        }
         
    }
    
    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }
        
        return $this->render('signup', [
                    'model' => $model,
        ]);
    }
    

    public function actionLogin() {

        if (!\Yii::$app->user->isGuest) {

            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            //return $this->redirect('picklearning');
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);

        }
    }

    public function actionConnectocourses(){

        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('connectocourses');
    }

    public function actionCurriculums(){

        if (Yii::$app->user->isGuest || !isset( $_GET['id']) ) {
            return $this->goHome();
        }

        return $this->render('curriculums');
    }

    public function actionPicklearning(){
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $learningprogrammodel = new LearningProgramForm();
        if ($learningprogrammodel->load(Yii::$app->request->post()) && $learningprogrammodel->saveLearningProgram()) {
            return $this->goBack();
        }else{
            return $this->render('picklearningprogram',[
                'learningprogrammodel'=>$learningprogrammodel,
            ]);
        }
    }

    public function actionManageteachertocourse(){
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $courseid  = empty( $_POST['courseId'] )?null:$_POST['courseId'];
        $teacherid = empty( $_POST['teacherId'])?null:$_POST['teacherId'];
        $learningprogramId = empty( $_POST['learningprogramId'])?null:$_POST['learningprogramId'];
        $action    = empty( $_POST['action'])?null:$_POST['action'];

        if( empty($courseid) || empty($teacherid) || empty($action) ){
            echo 'Something Went Wrong';
            return;
        }
        // $action == 1 => SAVE
        if($action == 1){
            $obj = new Teachertocourse();
            $obj->teacher_id =$teacherid;
            $obj->course_id =$courseid;
            $obj->user_id =Yii::$app->getUser()->id;
            $obj->learnningProgram_id	 =$learningprogramId;
            $obj->save();
            echo 'actionAddcoursetoyear save=>'.$courseid.' '.$teacherid;
        }else{
            // $action == 2 => DELETE
            Teachertocourse::deleteAll([
                'teacher_id' => $teacherid,
                'course_id'=>$courseid,
                'learnningProgram_id'=>$learningprogramId
            ]);
            echo 'actionAddcoursetoyear delete=>'.$courseid.' '.$teacherid;
        }
    }

    public function actionInsertimetableevnt()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (Yii::$app->request->isAjax)
        {
            $data = Yii::$app->request->post();
            if($data['type']=='new'){

                $obj = new Timetable();
                $obj->title = $data['title'];
                $obj->startdate = $data['startime'];
                $obj->learnningProgram_id = $data['learnningprogramid'];
                $obj->yearAndSemester_id = $data['yearandsemesterid'];
                $obj->save();
                return $obj->timetableid;
            }
            if($data['type']=='update'){
                $timetablevent = Timetable::findOne($data['eventid']);
                $timetablevent->startdate = $data['startime'];
                $timetablevent->enddate = $data['endtime'];
                $timetablevent->update();
                return $timetablevent->timetableid;
            }
        }
        if($data['type']=='delete'){
            Timetable::deleteAll([
                'timetableid' => $data['eventid'],
            ]);
        }

        if($data['type']=='fetch'){
            $learnningprogramid = $data['learnningprogramid'];
            $yearandsemesterid = $data['yearandsemesterid'];
            
            
            $events = array();
            $allevents = Timetable::find()
                        ->where(['learnningProgram_id' => $learnningprogramid,'yearAndSemester_id'=>$yearandsemesterid])
                        ->asArray()
                        ->all();
            foreach($allevents as $event){
                $e= array();
                $e['id']    = $event['timetableid'];
                if($event['title'] == 'Free'){
                    $e['backgroundColor'] = 'orange';
                }
                $e['title'] = $event['title'];
                $e['start'] = $event['startdate'];
                $e['end'] = $event['enddate'];
                $e['allDay'] = false;
                $e['editable'] = true;
                array_push($events,$e);
            }
            echo json_encode($events);
        }
    }

    public function actionInsertimetableteachers()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (Yii::$app->request->isAjax)
        {
            $data = Yii::$app->request->post();
            if($data['type']=='new'){

                $obj = new Teachersconstrains();
                $obj->title = $data['title'];
                $obj->startdate = $data['startime'];
                $obj->teacher_id = $data['teacherid'];
                $obj->learnningProgram_id = $data['learningprogramId'];
                $obj->yearAndSemester_id = $data['yearAndSemesterId'];
                $obj->save();
                return $obj->teachersconstrains_id;
            }
            if($data['type']=='update'){
                $timetablevent = Teachersconstrains::findOne($data['eventid']);
                $timetablevent->startdate = $data['startime'];
                $timetablevent->enddate = $data['endtime'];
                $timetablevent->update();
                return $timetablevent->teachersconstrains_id;
            }
        }
        if($data['type']=='delete'){
            Teachersconstrains::deleteAll([
                'teachersconstrains_id' => $data['eventid'],
            ]);
        }

        if($data['type']=='fetch'){
            $teacherid = $data['teacherid'];
            $learningprogramId = $data['learningprogramId'];

            $events = array();
            $allevents = Teachersconstrains::find()
                ->where(['teacher_id' => $teacherid,'learnningProgram_id'=>$learningprogramId ])
                ->asArray()
                ->all();
            foreach($allevents as $event){
                $yearName = Yearandsemester::find()->where(['yearAndSemester_id'=>$event['yearAndSemester_id']])->asArray()->all();
                $yearName = $yearName[0]['yearAndSemester_name'];
                $e= array();
                $e['id']    = $event['teachersconstrains_id'];
                $e['title'] = $event['title'].'-'.$yearName;
                $e['start'] = $event['startdate'];
                $e['end'] = $event['enddate'];
                $e['allDay'] = false;
                $e['editable'] = false;
                array_push($events,$e);
            }
            //return json_encode($events);
            echo json_encode($events);
        }
    }

    public function actionGetevents()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if (Yii::$app->request->isAjax)
        {
            $data = Yii::$app->request->post();
            if( !empty($data['learnningprogramId']) )
            {
                $events = array();
                $learnningprogramId = $data['learnningprogramId'];
                $yearAndSemesterName = $data['yearAndSemesterName'];
                $fileName = 'json_data/events_'.$learnningprogramId.'_'.$yearAndSemesterName;
                
                // Read and parse our events JSON file into an array of event data arrays.
                $json = file_get_contents($fileName);
                $input_arrays = json_decode($json, true);
                foreach ( $input_arrays as $e  )
                {
                    array_push($events,$e);
                }

                echo json_encode($events);
            }
        }
    }

    public  function actionNotallowedevents()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (Yii::$app->request->isAjax)
        {
            $data = Yii::$app->request->post();
            $learningprogramId = $data['learningprogramId'];
            $yearAndSemesterid = $data['yearAndSemesterId'];
            $sql = "SELECT * FROM `timetable` WHERE `title`='Free' and `learnningProgram_id` =".$learningprogramId." and `yearAndSemester_id` = ".$yearAndSemesterid;
            $results = Yii::$app->db->createCommand($sql);
            $resultsDataReader = $results->queryAll();
            $events = array();

            foreach($resultsDataReader as $event){

                $e= array();
                $e['id']    = $event['timetableid'];
                $e['title'] = $event['title'];
                $e['start'] = $event['startdate'];
                $e['end'] = $event['enddate'];
                $e['allDay'] = false;
                $e['editable'] = true;
                $e['backgroundColor'] = 'orange';
                array_push($events,$e);
            }
            echo json_encode($events);
        }
    }

    public  function actionUpdatelearnningprogramtosemester()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (Yii::$app->request->isAjax)
        {
            $data = Yii::$app->request->post();

            if( empty($data['learnningprogramid']) || empty($data['yearandsemesterid']) ){
                return null;
            }

            $learnningprogramid = $data['learnningprogramid'];
            $yearandsemesterid = $data['yearandsemesterid'];

            Learningprogramtoyear::deleteAll([
                'learnningProgram_id' => $learnningprogramid,
            ]);

            $obj = new Learningprogramtoyear();
            $obj->learnningProgram_id = $data['learnningprogramid'];
            $obj->yearAndSemester_id = $data['yearandsemesterid'];
            $obj->user_id = Yii::$app->getUser()->id;
            $obj->save();
            return $obj->learningprogramtoyear_id;
        }
    }

    //
    public function actionCalcteacherteachingnewhours()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (Yii::$app->request->isAjax)
        {
            $data = Yii::$app->request->post();
            $teacherid = $data['teacherid'];
            $learningprogramId= $data['learningprogramId'];
            $yearAndSemesterid = $data['yearAndSemesterid'];

            
            $teacherConstrains = Teachersconstrains::find()
                ->where(['teacher_id' => $teacherid ,'learnningProgram_id'=>$learningprogramId,
                        'yearAndSemester_id' => $yearAndSemesterid ])
                ->asArray()
                ->all();
            

            /*    
            $userId  = Yii::$app->getUser()->id;
            $sql = "SELECT cty.`course_id`
                    FROM `teachertocourse` as ttc
                    join `coursetoyear` as cty on ttc.`learnningProgram_id` = cty.`learnningProgram_id`
                    WHERE ttc.`learnningProgram_id` = ".$learningprogramId." 
                    and   ttc.`teacher_id`=".$teacherid." 
                    and   cty.yearAndSemester_id = ".$yearAndSemesterid." 
                    and   ttc.`course_id`= cty.`course_id`
                    and   ttc.`user_id` = ".$userId." 
                    and   ttc.`user_id` = cty.`user_id`";

             $dataSqlCommand = Yii::$app->db->createCommand($sql);
             $dataCommanDataReader = $dataSqlCommand->queryAll();

            var_dump($sql);
            return; */

            $calc=0;
            foreach(  $teacherConstrains as $teacherConstrain  ){
                $startDate =  strtotime($teacherConstrain['startdate']);
                $enddate   = strtotime($teacherConstrain['enddate']);
                $diff= ($enddate - $startDate) / 3600 ;
                $calc =  $calc + $diff;
            }
            echo $calc;
        }
    }

    public function actionCalcteacherteachinghours()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (Yii::$app->request->isAjax)
        {
            $totalhours = 0;
            $delta = 2;
            $data = Yii::$app->request->post();
            $teacherid = $data['teacherid'];
            $learningprogramId= $data['learningprogramId'];
            $yearAndSemesterid= $data['yearAndSemesterid'];
            /// calc the deltaTime
            /*
            $allcourses = Teachertocourse::find()
                ->where(['teacher_id' => $teacherid ,'learnningProgram_id'=>$learningprogramId ])
                ->asArray()
                ->all();
            */

            $userId  = Yii::$app->getUser()->id;
            $sql = "SELECT cty.`course_id`
                    FROM `teachertocourse` as ttc
                    join `coursetoyear` as cty on ttc.`learnningProgram_id` = cty.`learnningProgram_id`
                    WHERE ttc.`learnningProgram_id` = ".$learningprogramId." 
                    and   ttc.`teacher_id`=".$teacherid." 
                    and   cty.yearAndSemester_id = ".$yearAndSemesterid." 
                    and   ttc.`course_id`= cty.`course_id`
                    and   ttc.`user_id` = ".$userId." 
                    and   ttc.`user_id` = cty.`user_id`";

             $dataSqlCommand = Yii::$app->db->createCommand($sql);
             $dataCommanDataReader = $dataSqlCommand->queryAll();

            foreach($dataCommanDataReader as $allcourse)
            {
                $coure = Course::find()->where(['course_id' => $allcourse['course_id']]) ->asArray()->all();
                $coure = reset($coure);
                $totalhours += $coure['course_duration'];
            }
            $deltaTime =  $totalhours*$delta;
            echo $deltaTime;
        }
    }


    public function actionManagecoursetoyear()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $courseid          = empty( $_POST['courseId'] )?null:$_POST['courseId'];
        $yearandsemesterid = empty( $_POST['yearSemesterId'])?null:$_POST['yearSemesterId'];
        $learningprogramid = empty( $_POST['learningprogramId'])?null:$_POST['learningprogramId'];
        $action            = empty( $_POST['action'])?null:$_POST['action'];

        if( empty($courseid) || empty($yearandsemesterid) || empty($action) ){
            echo 'Something Went Wrong';
            return;
        }
        // $action == 1 => SAVE
        if($action == 1){
            $obj = new Coursetoyear();
            $obj->yearAndSemester_id =$yearandsemesterid;
            $obj->course_id =$courseid;
            $obj->user_id =Yii::$app->getUser()->id;
            $obj->learnningProgram_id = $learningprogramid;
            $obj->save();
            echo 'actionAddcoursetoyear save=>'.$courseid.' '.$yearandsemesterid;
        }else{
            try{
                // $action == 2 => DELETE
                Coursetoyear::deleteAll([
                    'yearAndSemester_id' => $yearandsemesterid,
                    'course_id'=>$courseid,
                    'learnningProgram_id'=>$learningprogramid
                ]);
            }catch (Exception $e){
                var_dump($e);
            }
            echo 'actionAddcoursetoyear delete=>'.$courseid.' '.$yearandsemesterid.' '.$learningprogramid;
        }
    }

    public function actionConnecteachertocourses(){
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('connecteachertocourses');
    }
    public function actionTeacherconstrains(){
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('teacherconstrains');
    }

    public function actionLogout() {
        Yii::$app->user->logout();
       return $this->redirect('login');
    }

    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    public function actionAbout() {
        return $this->render('about');
    }
}
