<?php

date_default_timezone_set('UTC');
require_once 'model/User.php';
require_once 'model/Course.php';
require_once 'model/Presence.php';
require_once 'model/Certificat.php';
require_once 'model/StudentCourses.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerAdmin extends Controller {

    const UPLOAD_ERR_OK = 0;

    //page d'accueil. 
    public function index() {
        $this->home();
    }

    public function get_admin_or_redirect() {
        $user = $this->get_user_or_false();
        if ($user === FALSE || $user->role != "admin") {
            $this->redirect();
        } else {
            return $user;
        }
    }

    public function home() {
        $user = $this->get_admin_or_redirect();
        $teachers = User::get_user();
        $courses = Course::get_course();
        $newcourse = true;
        $courseselected = "";
        $errors = [];
        if (isset($_POST["courseselected"]) && $_POST["courseselected"] !== "") {
            $courseselected = Course::get_course_by_id($_POST["courseselected"]);
            $newcourse = false;
        }
        (new View("admin"))->show(array("newcourse" => $newcourse, "user" => $user, "courses" => $courses, "teachers" => $teachers, "courseselected" => $courseselected, "errors" => $errors));
    }

    public function add_course() {
        $user = $this->get_admin_or_redirect();
        $errors = [];
        $teachers = User::get_user();
        $courses = Course::get_course();
        $newcourse = true;
        if (isset($_POST['code']) && !empty($_POST['code']) && $_POST['code'] != 0 && isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['dayofweek']) && isset($_POST['starttime']) && !empty($_POST['starttime']) && isset($_POST['endtime']) && !empty($_POST['endtime']) && isset($_POST['startdate']) && !empty($_POST['startdate']) && isset($_POST['finishdate']) && !empty($_POST['finishdate']) && isset($_POST['teacher']) && !empty($_POST['teacher'])) {
            $courseselected = new Course($_POST['code'], $_POST['title'], $_POST['dayofweek'], $_POST['starttime'], $_POST['endtime'], $_POST['startdate'], $_POST['finishdate'], $_POST['teacher']);
            $errors = $courseselected->validate_new_course();
        } else {
            $courseselected = new Course($_POST['code'], $_POST['title'], $_POST['dayofweek'], $_POST['starttime'], $_POST['endtime'], $_POST['startdate'], $_POST['finishdate'], $_POST['teacher']);
            $errors [] = "Tout les champs ne sont pas remplis";
        }
        if (empty($errors)) {
            $courseselected->update_db();
            $this->redirect();
            $courseselected = "";
        }
        (new View("admin"))->show(array("newcourse" => $newcourse, "user" => $user, "courses" => $courses, "teachers" => $teachers, "courseselected" => $courseselected, "errors" => $errors));
    }

    public function update_course() {
        $user = $this->get_admin_or_redirect();
        $errors = [];
        $teachers = User::get_user();
        $courses = Course::get_course();
        $newcourse = true;
        if (isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['starttime']) && !empty($_POST['starttime']) && isset($_POST['endtime']) && !empty($_POST['endtime']) && isset($_POST['teacher']) && !empty($_POST['teacher'])) {
            $courseselected = Course::get_course_by_id($_POST['code']);
            $courseselected->title = Tools::sanitize($_POST['title']);
            $courseselected->startTime = $_POST['starttime'];
            $courseselected->finishTime = $_POST['endtime'];
            $courseselected->teacher = $_POST['teacher'];
            $errors = $courseselected->validate_course();
            $newcourse = false;
        } else {
            $courseselected = Course::get_course_by_id($_POST['code']);
            $errors [] = "Tout les champs ne sont pas remplis";
            $newcourse = false;
        }
        if (empty($errors)) {
            $courseselected->update_db();
            $this->redirect();
            $courseselected = "";
        }
        (new View("admin"))->show(array("newcourse" => $newcourse, "user" => $user, "courses" => $courses, "teachers" => $teachers, "courseselected" => $courseselected, "errors" => $errors));
    }

    public function delete_course() {
        $user = $this->get_admin_or_redirect();
        if (isset($_POST['deletecourse']) && !empty($_POST['deletecourse'])) {
            $course = course::get_course_by_id($_POST['deletecourse']);
            $course->delete();
        }
        $this->redirect("Admin", "home");
    }

    public function view_delete_course() {
        $user = $this->get_admin_or_redirect();
        if (isset($_POST['deletecourse']) && !empty($_POST['deletecourse'])) {
            $course = course::get_course_by_id($_POST['deletecourse']);
        } else {
            $this->redirect("Admin", "home");
        }
        (new View("confirm_delete"))->show(array("course" => $course));
    }

    public function student_by_course() {
        $user = $this->get_admin_or_redirect();
        $teachers = User::get_user();
        $errors = [];
        if (isset($_POST["studentbycourse"]) && $_POST["studentbycourse"] !== "") {
            $course = Course::get_course_by_id($_POST["studentbycourse"]);
            $studentsin = $course->get_student_by_course();
            $studentsnotin = $course->get_student_not_course();
            $user->courseselected = $_POST["studentbycourse"];
        } else {
            $course = Course::get_course_by_id($user->courseselected);
            $studentsin = $course->get_student_by_course();
            $studentsnotin = $course->get_student_not_course();
        }
        (new View("studentsbycourse"))->show(array("course" => $course, "teachers" => $teachers, "studentsin" => $studentsin, "studentsnotin" => $studentsnotin, "errors" => $errors));
    }

    public function add_student_by_course() {
        if (isset($_POST['studentnotin']) && !empty($_POST['studentnotin'])) {
            $studentin = $_POST['studentnotin'];
            $idcourse = $_POST['studentbycourse'];
            foreach ($studentin as $row) {
                StudentCourses::add($row, $idcourse);
            }
        }
        if (isset($_POST['allstudent']) && !empty($_POST['allstudent'])) {
            $boolean = $_POST['allstudent'];
            $idcourse = Course::get_course_by_id($_POST['studentbycourse']);
            if ($boolean === "add") {
                $studentin = $idcourse->get_student_not_course();
                foreach ($studentin as $row) {
                    StudentCourses::add($row->id, $idcourse->id);
                }
            }
        }
        $this->redirect("Admin", "student_by_course");
    }

    public function delete_all_student_by_course() {
        $user = $this->get_admin_or_redirect();
        $errors = [];
        if (isset($_POST['allstudent']) && !empty($_POST['allstudent'])) {
            $boolean = $_POST['allstudent'];
            $idcourse = Course::get_course_by_id($_POST['studentbycourse']);
            if ($boolean === "delete") {
                $studentout = $idcourse->get_student_by_course();
                foreach ($studentout as $row) {
                    if ($row->check_presence_by_course($idcourse->id))
                        $errors [] = $row->lastName . " " . $row->firstName . " n'est pas supprimable";
                }
                if (empty($errors)) {
                    foreach ($studentout as $row) {
                        $studentcourse = StudentCourses::get_student_courses($row->id, $idcourse->id);
                        $studentcourse->delete();
                    }
                }
            }
        }
        $teachers = User::get_user();
        $course = Course::get_course_by_id($idcourse->id);
        $studentsin = $course->get_student_by_course();
        $studentsnotin = $course->get_student_not_course();
        (new View("studentsbycourse"))->show(array("course" => $course, "teachers" => $teachers, "studentsin" => $studentsin, "studentsnotin" => $studentsnotin, "errors" => $errors));
    }

    public function delete_student_by_course() {
        $user = $this->get_admin_or_redirect();
        $errors = [];
        if (isset($_POST['studentin']) && !empty($_POST['studentin'])) {
            $idcourse = Course::get_course_by_id($_POST['studentbycourse']);
            $studentout = $_POST['studentin'];
            foreach ($studentout as $row) {
                $student = Student::get_student_by_id($row);
                if ($student->check_presence_by_course($idcourse->id)) {
                    $errors [] = $student->lastName . " " . $student->firstName . " n'est pas supprimable";
                }
            }
            if (empty($errors))
                foreach ($studentout as $row) {
                    $studentcourse = StudentCourses::get_student_courses($row, $idcourse->id);
                    $studentcourse->delete();
                }
        }
        $teachers = User::get_user();
        $course = Course::get_course_by_id($idcourse->id);
        $studentsin = $course->get_student_by_course();
        $studentsnotin = $course->get_student_not_course();
        (new View("studentsbycourse"))->show(array("course" => $course, "teachers" => $teachers, "studentsin" => $studentsin, "studentsnotin" => $studentsnotin, "errors" => $errors));
    }

    public function gestion_certificat() {
        $user = $this->get_admin_or_redirect();
        $users = User::get_user();
        $etudiant = Student::get_student();
        $studentcertif = Certificat::get_certificat();
        $allstudent = "";
        $studentselected = "";
        if (isset($_POST['recharger'])) {
            $idstudent = $_POST['recharger'];
            if ($idstudent == "allStudent") {
                $studentcertif = Certificat::get_certificat();
            } else {
                $studentselected = Student::get_student_by_id($idstudent);
                $studentcertif = $studentselected->get_certificat_by_idstudent();
            }
        }
        (new View("certificat"))->show(array("studentselected" => $studentselected, "user" => $user, "users" => $users, "etudiant" => $etudiant, "studentcertif" => $studentcertif));
    }

    public function delete_certificat() {
        if (isset($_POST["effacer"])) {
            $certificat = Certificat::get_certificat_by_id($_POST["effacer"]);
            $certificat->delete();
        }
        $this->redirect(admin, gestion_certificat);
    }

    public function add_certificat() {
        if (isset($_POST["choixetudiant"]) && isset($_POST["dateDebut"]) && isset($_POST["dateFin"])) {
            $student = $_POST["choixetudiant"];
            $datedebut = $_POST["dateDebut"];
            $datefin = $_POST["dateFin"];
            $debutTimeTamp = strtotime($datedebut);
            $finTimeTamp = strtotime($datefin);
            if ($finTimeTamp - $debutTimeTamp > 0) {
                Certificat::insert_certificate($student, $datedebut, $datefin);
            }
            $this->redirect(admin, gestion_certificat);
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////AJOUT PERSONNEL
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function gestion_student() {
        $user = $this->get_admin_or_redirect();
        $students = Student::get_student();
        $studentselected = "";
        $errors = [];
        $success = "";
        if (isset($_POST["studentselected"]) && $_POST["studentselected"] !== "") {
            $studentselected = Student::get_student_by_id($_POST["studentselected"]);
        }
        (new View("gestionstudent"))->show(array("user" => $user, "students" => $students, "studentselected" => $studentselected, "errors" => $errors, "success" => $success));
    }

    public function insert_student() {
        $user = $this->get_admin_or_redirect();
        if (isset($_POST['firstname']) && !empty($_POST['firstname']) && isset($_POST['lastname']) && !empty($_POST['lastname']) && isset($_POST['sexe']) && !empty(($_POST['sexe']))) {
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $sexe = $_POST['sexe'];
            $errors = [];
            $studentselected = "";
            $success = "";
            $errors = Student::validate_unicity($firstname, $lastname);
            if (empty($errors)) {
                Student::add_student($firstname, $lastname, $sexe);
                $success = "Etudiant mise à jour";
            }
            $students = Student::get_student();
            (new View("gestionstudent"))->show(array("user" => $user, "students" => $students, "studentselected" => $studentselected, "errors" => $errors, "success" => $success));
        }
    }

    public function update_student() {
        $user = $this->get_admin_or_redirect();
        if (isset($_POST['firstname']) && !empty($_POST['firstname']) && isset($_POST['lastname']) && !empty($_POST['lastname']) && isset($_POST['sexe']) && !empty(($_POST['sexe']))) {
            $id = $_POST['oldstudent'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $sexe = $_POST['sexe'];
            $student = new Student($id, $firstname, $lastname, $sexe);
            $student->update();
            $this->redirect("admin", "gestion_student");
        }
    }

    public function delete_student() {
        $user = $this->get_admin_or_redirect();
        if (isset($_POST['deletestudent']) && !empty($_POST['deletestudent'])) {
            $student = Student::get_student_by_id($_POST['deletestudent']);
        }
        $student->delete();
        $this->redirect("Admin", "gestion_student");
    }

    public function gestion_user() {
        $user = $this->get_admin_or_redirect();
        $users = User::get_user();
        $utilisateurselected = "";
        $errors = [];
        if (isset($_POST["utilisateurselected"]) && $_POST["utilisateurselected"] !== "") {
            $utilisateurselected = user::get_user_by_id($_POST["utilisateurselected"]);
        }
        (new View("gestionuser"))->show(array("user" => $user, "users" => $users, "utilisateurselected" => $utilisateurselected, "errors" => $errors));
    }

    public function insert_user() {
        $user = $this->get_admin_or_redirect();
        if (isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['fullname']) && !empty($_POST['fullname']) && isset($_POST['role']) && !empty($_POST['role'] && isset($_POST['password']) && !empty(($_POST['password'])))) {
            $pseudo = $_POST['pseudo'];
            $fullname = $_POST['fullname'];
            $role = $_POST['role'];
            $password = Tools::my_hash($_POST['password']);
            $errors = [];
            $errors = User::validate_unicity($pseudo);
            if (empty($errors)) {
                User::adduser($pseudo, $fullname, $role, $password);
            }
            $users = User::get_user();
            $utilisateurselected = "";
            (new View("gestionuser"))->show(array("user" => $user, "users" => $users, "utilisateurselected" => $utilisateurselected, "errors" => $errors));
        }
    }

    public function delete_user() {
        $user = $this->get_admin_or_redirect();
        $errors = [];
        $users = User::get_user();
        $utilisateurselected = "";
        if (isset($_POST['deleteuser']) && !empty($_POST['deleteuser'])) {
            $userdeleted = User::get_user_by_id($_POST['deleteuser']);
            $courses = $userdeleted->get_course();
            if (empty($courses)) {
                $userdeleted->delete();
                $users = User::get_user();
            } else {
                $errors = $userdeleted->fullname . " est responsable d'un ou plusieurs cours";
            }
        }
        (new View("gestionuser"))->show(array("user" => $user, "users" => $users, "utilisateurselected" => $utilisateurselected, "errors" => $errors));
    }

    public function update_user() {
        $user = $this->get_admin_or_redirect();
        if (isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['fullname']) && !empty($_POST['fullname']) && isset($_POST['role']) && !empty($_POST['role'])) {
            $olduser = User::get_user_by_id($_POST['olduser']);
            $olduser->pseudo = $_POST['pseudo'];
            $olduser->fullname = $_POST['fullname'];
            $olduser->role = $_POST['role'];
            $olduser->update();
            $this->redirect("admin", "gestionuser");
        }
    }

    public function reset_password() {
        $user = $this->get_admin_or_redirect();
        if (isset($_POST['password']) && isset($_POST['id'])) {
            $usermodified = User::get_user_by_id($_POST['id']);
            $pass = "Password1,";
            $usermodified->hashed_password = Tools::my_hash($pass);
            $usermodified->update();
        }
        $this->redirect("admin", "gestionuser");
    }

}
