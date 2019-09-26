<?php

require_once "framework/Model.php";
require_once "CourseOccurrence.php";
require_once "Student.php";

class StudentCourses extends Model {

    public $idstudent;
    public $idcourse;

    public function __construct($idstudent, $idcourse) {
        $this->idstudent = $idstudent;
        $this->idcourse = $idcourse;
    }

    public static function get_student_courses($idstudent, $code) {
        $query = self::execute("SELECT * FROM studentcourses  where student = :id and course=:code", array("id" => $idstudent, "code" => $code));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new StudentCourses($row["student"], $row["course"]);
        }
    }

    public function delete() {
        self::execute("DELETE from studentcourses where student=:idstudent and course=:idcourse", array("idstudent" => $this->idstudent, "idcourse" => $this->idcourse));
    }

    public static function add($idstudent, $idcourse) {
        self::execute("INSERT INTO studentcourses VALUES (:idstudent,:idcourse)", array("idstudent" => $idstudent, "idcourse" => $idcourse));
    }

    public static function update_by_course($id, $oldcourse) {
        self::execute("UPDATE studentcourses SET course=:id where course=:oldcourse", array("id" => $id, "oldcourse" => $oldcourse));
    }

    public static function delete_by_course($idcourse) {
        self::execute("DELETE from studentcourses where course=:idcourse", array("idcourse" => $idcourse));
    }

//    public static function delete($idstudent, $idcourse) {
//        self::execute("DELETE from studentcourses where student=:idstudent and course=:idcourse", array("idstudent" => $idstudent, "idcourse" => $idcourse));
//    }

    public static function delete_by_student($idstudent) {
        self::execute("DELETE from studentcourses where student=:idstudent ", array("idstudent" => $idstudent));
    }

}

?>