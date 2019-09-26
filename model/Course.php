<?php

require_once "framework/Model.php";
require_once "CourseOccurrence.php";
require_once "Student.php";

class Course extends Model {

    public $id;
    public $title;
    public $dayOfWeek;
    public $startTime;
    public $finishTime;
    public $startDate;
    public $finishDate;
    public $teacher;

    public function __construct($id, $title, $dayOfWeek, $startTime, $finishTime, $startDate, $finishDate, $teacher) {
        $this->id = $id;
        $this->title = $title;
        $this->dayOfWeek = $dayOfWeek;
        $this->startTime = $startTime;
        $this->finishTime = $finishTime;
        $this->startDate = $startDate;
        $this->finishDate = $finishDate;
        $this->teacher = $teacher;
    }

    public function get_teacher_name() {
        $query = self::execute("SELECT * FROM User where id = :id", array("id" => $this->teacher));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return $row["fullname"];
        }
    }

    public function update_db() {
        if (self::get_course_by_id($this->id))
            $this->update();
        else
            $this->insert();
        return $this;
    }

    private function update() {
        self::execute("UPDATE Course SET code=:code, title=:title, dayofweek=:dayofweek, starttime=:starttime, endtime=:endtime, startdate=:startdate, finishdate=:finishdate, teacher=:teacher WHERE code=:id ", array('id' => $this->id, 'code' => $this->id, 'title' => $this->title, 'dayofweek' => $this->dayOfWeek, 'starttime' => $this->startTime, 'endtime' => $this->finishTime, 'startdate' => $this->startDate, 'finishdate' => $this->finishDate, 'teacher' => $this->teacher));
    }

    private function insert() {
        $courseoccurrences = CourseOccurrence::find_Occurrences($this->startDate, $this->finishDate, $this->dayOfWeek, $this->id);
        self::execute("INSERT INTO Course values (:code, :title,:starttime, :endtime, :startdate, :finishdate, :teacher,:dayofweek) ", array('code' => $this->id, 'title' => $this->title, 'dayofweek' => $this->dayOfWeek, 'starttime' => $this->startTime, 'endtime' => $this->finishTime, 'startdate' => $this->startDate, 'finishdate' => $this->finishDate, 'teacher' => $this->teacher));
        foreach ($courseoccurrences as $occ) {
            $occ->insert();
        }
    }

    public function delete() {
        $this->delete_db();
        return true;
    }

    private function delete_db() {
        StudentCourses::delete_by_course($this->id);
        $occurrences = $this->get_course_occurrence_by_course();
        $presences = Presence::get_presence_by_id_course($this->id);
        foreach ($presences as $pres) {
            $pres->delete();
        }
        foreach ($occurrences as $occurrence) {
            $occurrence->delete();
        }
        self::execute("DELETE FROM course WHERE code = :code", array('code' => $this->id));
        return true;
    }

    public static function get_course_by_id($code) {
        $query = self::execute("SELECT * FROM Course where code = :code", array("code" => $code));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Course($row["code"], $row["title"], $row["dayofweek"], $row["starttime"], $row["endtime"], $row["startdate"], $row["finishdate"], $row["teacher"]);
        }
    }

    public static function get_course() {
        $query = self::execute("SELECT * FROM course ORDER BY `course`.`teacher` ASC, `course`.`dayofweek` ASC, `course`.`starttime` ASC", array());
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new Course($row["code"], $row["title"], $row["dayofweek"], $row["starttime"], $row["endtime"], $row["startdate"], $row["finishdate"], $row["teacher"]);
        }
        return $results;
    }

    public function get_student_by_course() {
        $query = self::execute("SELECT * FROM course join studentcourses on course=code join student on id=student where code=:code", array("code" => $this->id));
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new Student($row["id"], $row["lastname"], $row["firstname"], $row["sex"]);
        }
        return $results;
    }

    public function get_student_not_course() {
        $query = self::execute("SELECT DISTINCT id, lastname, firstname, sex FROM student left outer join studentcourses on id=student where id not in (select student from studentcourses where course=:code)", array("code" => $this->id));
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new Student($row["id"], $row["lastname"], $row["firstname"], $row["sex"]);
        }
        return $results;
    }

    public function get_jour() {
        $jour = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        return $jour[$this->dayOfWeek];
    }

    public function validate_new_course() {
        $errors = [];
        if ($this->startDate > $this->finishDate)
            $errors[] = "Les dates de début et fin ne sont pas juste";
        if ($this->startTime > $this->finishTime)
            $errors[] = "Les heures de début et fin ne sont pas juste";
        if ($this->check_id()) {
            $errors[] = "Code du cours existe déja";
        }
        return $errors;
    }

    public function validate_course() {
        $errors = [];
        if ($this->startTime > $this->finishTime)
            $errors[] = "Les heures de début et fin ne sont pas juste";
        return $errors;
    }

    public function check_id() {
        $query = self::execute("SELECT * FROM Course where code = :id", array("id" => $this->id));
        $data = $query->fetch();
        if ($query->rowCount() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function get_course_occurrence_by_course() {
        $query = self::execute("SELECT * FROM courseoccurrence where course=:course", array("course" => $this->id));
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new CourseOccurrence($row["id"], $row["date"], $row["course"]);
        }
        return $results;
    }

    public function get_course_occurrence_between_dates($datedebut, $datefin) {
        $query = self::execute("SELECT * FROM courseoccurrence where course=:course and (date>=:datedebut and date<=:datefin)", array("course" => $this->id, "datedebut" => $datedebut, "datefin" => $datefin));
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new CourseOccurrence($row["id"], $row["date"], $row["course"]);
        }
        return $results;
    }

    public static function check_date($datedebut, $datefin) {
        if ($datedebut < $datefin) {
            return false;
        }
        return true;
    }

    public function get_presence_by_course() {
        $query = self::execute("select distinct * from course join courseoccurrence on course=code join presence on courseoccurence=id where course = :id and (present=1 or present=0)", array("id" => $this->id));
        $data = $query->fetchAll();
        $results = [];
        if ($query->rowCount() == 0) {
            return false;
        } else {
            foreach ($data as $row) {
                $results[] = new Presence($row["student"], $row["courseoccurence"], $row["present"]);
            }
            return $results;
        }
    }

}
