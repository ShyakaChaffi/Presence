<?php

date_default_timezone_set('UTC');
require_once 'model/User.php';
require_once 'model/Course.php';
require_once 'model/StudentCourses.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/Presence.php';

class ControllerUser extends Controller {

    const UPLOAD_ERR_OK = 0;

    //page d'accueil. 
    public function index() {
        $this->home();
    }

    public function pseudo_available_service() {
        $res = "false";
        if (isset($_POST["pseudo"]) && $_POST["pseudo"] !== "") {
            $member = User::get_user_by_pseudo($_POST["pseudo"]);
            if ($member) {
                $res = "true";
            }
        }
        echo $res;
    }

    public function get_teacher_or_redirect() {
        $user = $this->get_user_or_false();
        if ($user === FALSE || $user->role != "teacher") {
            $this->redirect();
        } else {
            return $user;
        }
    }

    public function home() {
        $viewFullCalendar = "agendaWeek";
        $dateFullCalendar = "";
        $user = $this->get_teacher_or_redirect();
        $todaydate = date("Y-m-d");
        if (isset($_POST["nextdate"]) && $_POST["nextdate"] !== "") {
            $todaydate = $_POST["nextdate"];
        }
        list($year, $month, $day) = explode('-', $todaydate);
        $firstdayofweek = date("l", mktime(0, 0, 0, $month, $day, $year));
        if ($firstdayofweek != "Monday") {
            $firstdayofweek = date("Y-m-d", strtotime("last Monday"));
            $lastdayofweek = date("Y-m-d", strtotime(date("Y-m-d", strtotime($firstdayofweek)) . " +5 day"));
        } else {
            $firstdayofweek = $todaydate;
            $lastdayofweek = date("Y-m-d", strtotime(date("Y-m-d", strtotime($firstdayofweek)) . " +5 day"));
        }
        $jourprec = "";
        $courses = $user->get_course();
        $occurrences = [];
        foreach ($courses as $course) {
            $occurrences = array_merge($occurrences, $course->get_course_occurrence_between_dates($firstdayofweek, $lastdayofweek));
        }
        if (isset($_POST["view"]) && $_POST["date"]) {
            $viewFullCalendar = $_POST["view"];
            $dateFullCalendar = $_POST["date"];
        }
        (new View("home"))->show(array("view" => $viewFullCalendar, "date" => $dateFullCalendar, "occurrences" => $occurrences, "user" => $user, "courses" => $courses, "firstdayofweek" => $firstdayofweek, "lastdayofweek" => $lastdayofweek, "todaydate" => $todaydate, "jourprec" => $jourprec));
    }

    public function presence_by_occurrence() {
        $user = $this->get_teacher_or_redirect();
        if (!isset($_POST['courseselected']) && !$user->check_if_teacher_has_this_course($_POST['courseselected']))
            $this->redirect("User", "home");
        $liste_presence = " ";
        $success = "";

        if (isset($_POST['courseselected']) && isset($_POST['date'])) {//dans le post on ne recoit que l'ID du cours renvoyer parla page home
            $course_selected = Course::get_course_by_id($_POST['courseselected']);
            $date = $_POST['date'];
            $liste_student = $course_selected->get_student_by_course();
            $courseoccurrence = CourseOccurrence::get_course_occurence_by_date($course_selected->id, $date);
            $liste_presence = Presence::get_presence_by_id($courseoccurrence->id);
        }
        if (isset($_POST['student']) && isset($_POST['presence'])) {
            foreach ($_POST['presence'] as $student => $presence) {
                $presence = new Presence(str_replace("'", " ", $student), $courseoccurrence->id, $presence);
                $presence->delete();
                $presence->insert();
                $liste_presence = Presence::get_presence_by_id($courseoccurrence->id);
                $success = "Presence mise à jour";
            }
        }
        return (new View("presence_by_occurrence"))->show(array("liste_presence" => $liste_presence, "courseoccurrence" => $courseoccurrence, "user" => $user, "date" => $date, "course" => $course_selected, "liste_student" => $liste_student, "success" => $success));
    }

    public function historique() {
        $member = $this->get_teacher_or_redirect();
        $user = User::get_user_by_pseudo($member->pseudo);
        $course = $user->get_course();
        if (isset($_POST['historique'])) {
            $course = Course::get_course_by_id($_POST['historique']);
            $student = $course->get_student_by_course();
            $presences = $course->get_presence_by_course();
            $courseoccurrence = $course->get_course_occurrence_by_course();
        }
        (new View("historiquePresence"))->show(array("presences" => $presences, "user" => $user, "student" => $student, /* "courses" => $courses, */ "course" => $course, "courseoccurrence" => $courseoccurrence));
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////AJOUT PERSONNEL
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function edit_password() {
        $member = $this->get_teacher_or_redirect();
        $user = User::get_user_by_pseudo($member->pseudo);
        $oldPassword = '';
        $newPassword = '';
        $newPassword_confirm = '';
        $errors = [];
        $success = '';
        if (isset($_POST['oldPassword']) && isset($_POST['newPassword']) && isset($_POST['newPassword_confirm'])) {
            $oldPassword = Tools::sanitize($_POST['oldPassword']);
            $newPassword = Tools::sanitize($_POST['newPassword']);
            $newPassword_confirm = Tools::sanitize($_POST['newPassword_confirm']);
            $errors = array_merge($errors, User::validate_login($user->pseudo, $oldPassword));
            $errors = array_merge($errors, User::validate_passwords($newPassword, $newPassword_confirm));
            if (count($errors) == 0) {
                $member->hashed_password = Tools::my_hash($newPassword);
                $member->update(); //sauve l'utilisateur
                $success = "Your password has been updated !";
            }
        }
        (new View("edit_password"))->show(array("oldPassword" => $oldPassword, "newPassword" => $newPassword, "newPassword_confirm" => $newPassword_confirm, "errors" => $errors, "success" => $success));
    }

    public function student_presence() {
        $member = $this->get_teacher_or_redirect();
        $user = User::get_user_by_pseudo($member->pseudo);
        $courses = $user->get_course();
        $studentsin = null;
        $presences = null;
        $studentselected = null;
        $courseselected = null;
        $courseoccurrence = null;
        if (isset($_POST['courseselected'])) {
            $courseselected = Course::get_course_by_id($_POST['courseselected']);
            $studentsin = $courseselected->get_student_by_course();
            if (isset($_POST['studentselected'])) {
                $studentselected = Student::get_student_by_id($_POST['studentselected']);
                $courseoccurrence = $courseselected->get_course_occurrence_by_course();
                $presences = Presence::get_presence_by_id_student_and_course($_POST['studentselected'], $courseselected->id);
                if (isset($_POST['presence'])) {
                    foreach ($_POST['presence'] as $occurence => $presence) {
                        $presence = new Presence($studentselected->id, str_replace("'", " ", $occurence), $presence);
                        $presence->delete();
                        $presence->insert();
                        $presences = Presence::get_presence_by_id_student($_POST['studentselected']);
                    }
                }
            }
        }


        (new View("student_presence"))->show(array("courses" => $courses, "studentsin" => $studentsin, "courseselected" => $courseselected, "studentselected" => $studentselected, "presences" => $presences, "courseoccurrence" => $courseoccurrence));
    }

}
