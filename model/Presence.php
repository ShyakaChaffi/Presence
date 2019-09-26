<?php

class Presence extends Model {

    public $student;
    public $id;
    public $present;

    public function __construct($idstudent, $idcourseoccurence, $present) {
        $this->student = $idstudent;
        $this->id = $idcourseoccurence;
        $this->present = $present;
    }

    public function insert() {
        $this->insert_db();
    }

    public function insert_db() {
        self::execute("INSERT INTO presence values (:idstudent,:idcourseoccurence, :presence) ", array('idstudent' => $this->student, 'idcourseoccurence' => $this->id, 'presence' => $this->present));
    }

    public function delete() {
        $this->delete_db();
    }

    private function delete_db() {
        self::execute("DELETE FROM presence WHERE student=:idstudent and courseoccurence=:id", array('idstudent' => $this->student, 'id' => $this->id));
    }

    public static function get_presence_by_id($id) {
        $query = self::execute("SELECT student.id as idstudent, present, courseoccurrence.id as idoccurrence, date FROM presence join student on student=student.id join courseoccurrence on courseoccurrence.id=courseoccurence  WHERE courseoccurence=:id", array("id" => $id));
        $data = $query->fetchAll();
        /* $results = []; */
        if ($query->rowCount() == 0) {
            return $results = " ";
        }
        return $data;
    }

    public static function get_presence_by_id_student($id) {
        $query = self::execute("SELECT student.id as idstudent, present, courseoccurrence.id as idoccurrence, date FROM presence join student on student=student.id join courseoccurrence on courseoccurrence.id=courseoccurence  WHERE student.id=:id", array("id" => $id));
        $data = $query->fetchAll();
        if ($query->rowCount() == 0) {
            return false;
        }
        return $data;
    }

    public static function check_presence_by_id_student($id, $idcourse) {
        $query = self::execute("SELECT * FROM presence join student on student=student.id join courseoccurrence on courseoccurrence.id=courseoccurence  WHERE student.id=:id and course=:idcourse", array("id" => $id, "idcourse" => $idcourse));
        $data = $query->fetchAll();
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

    public static function get_presence_by_id_student_and_course($id, $idcourse) {
        $query = self::execute("select distinct student as idstudent, present, courseoccurrence.id as idoccurrence, date  from courseoccurrence left join presence on id=courseoccurence where course=:idcourse and (student =:id or student is null)", array("id" => $id, "idcourse" => $idcourse));
        $data = $query->fetchAll();
        if ($query->rowCount() == 0) {
            return false;
        }
        return $data;
    }

    public static function get_presence_course($id) {
        $query = self::execute("SELECT * FROM Presence where courseoccurence = :id", array("id" => $id));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Presence($row["student"], $row["courseoccurence"], $row["present"]);
        }
    }

    public static function get_presence_student($id) {
        $query = self::execute("SELECT * FROM presence where student=:id", array('id' => $id));
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new Presence($row["student"], $row["courseoccurence"], $row["present"]);
        }
        return $results;
    }

    public static function get_presence_by_id_course($id) {
        $query = self::execute("select distinct * from course join courseoccurrence on course=code join presence on courseoccurence=id where course = :id and (present=1 or present=0)", array("id" => $id));
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

    public static function get_presence_join_student() {
        $query = self::execute("select student.lastname, student.firstname, presence.present,presence.courseoccurence,presence.student
from student join presence ON student.id=presence.student", array());
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

    public static function get_presence_student_occurence($id) {
        $query = self::execute("SELECT * FROM courseoccurrence left join presence ON courseoccurrence.id=presence.courseoccurence left JOIN student on student.id=presence.student  WHERE courseoccurrence. course=:id", array("id" => $id));
        $data = $query->fetchAll();
        $results = [];
        if ($query->rowCount() == 0) {
            return false;
        } else {
            foreach ($data as $row) {
                $results [] = $row;
            }
            return $results;
        }
    }

}
