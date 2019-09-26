<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/CourseOccurrence.php';
require_once 'model/Course.php';
require_once 'model/StudentCourses.php';
require_once 'model/Presence.php';
date_default_timezone_set('UTC');

class ControllerCourse extends Controller {

    public function index() {
        
    }

    public function get_teacher_or_redirect() {
        $user = $this->get_user_or_false();
        if ($user === FALSE || $user->role != "teacher") {
            $this->redirect();
        } else {
            return $user;
        }
    }

    public function get_event() {
        $user = $this->get_teacher_or_redirect();
        $data = array();

        $result = CourseOccurrence::get_event_db($_POST['start'], $_POST['end'], $user->id);

        foreach ($result as $row) {
            $data[] = array(
                'id' => $row["id"],
                'title' => $row["title"],
                'start' => $row["start"] . " " . $row['starttime'],
                'end' => $row["start"] . " " . $row['endtime']
            );
        }
        echo json_encode($data);
    }

    public function presence_by_occurrence() {
        $user = $this->get_teacher_or_redirect();
        $liste_presence = " ";
        $success = "";
        $viewFullCalendar = "";
        $dateFullCalendar = "";
        if (isset($_POST['idoccurrence'])) {
            $occurrence = CourseOccurrence::get_course_occurrence_by_id($_POST['idoccurrence']);
            $course_selected = Course::get_course_by_id($occurrence->course);
            $liste_student = $course_selected->get_student_by_course();
            $liste_presence = Presence::get_presence_by_id($occurrence->id);
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
        $viewFullCalendar = $_POST["view"];
        $dateFullCalendar = $_POST["date"];
        return (new View("presence_by_occurrence"))->show(array("view" => $viewFullCalendar, "date" => $dateFullCalendar, "liste_presence" => $liste_presence, "courseoccurrence" => $occurrence, "user" => $user, "date" => $occurrence->date, "course" => $course_selected, "liste_student" => $liste_student, "success" => $success));
    }

    public function code_available_service() {
        $res = "true";
        if (isset($_POST["code"]) && $_POST["code"] !== "") {
            $course = Course::get_course_by_id($_POST["code"]);
            if ($course) {
                $res = "false";
            }
        }
        echo $res;
    }

    public function get_certifs_service() {
        $msg_json = Certificat::get_all_certificates_as_json();
        echo $msg_json;
    }

    public function set_presence() {
        $student = $_POST["student"];
        $id = $_POST["occurence"];
        $value = $_POST["present"];
        $presence = new Presence($student, $id, $value);
        $presence->delete();
        $presence->insert();
    }

}

?>