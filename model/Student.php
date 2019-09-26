<?php

require_once "framework/Model.php";
require_once "Course.php";
require_once "Certificat.php";

class Student extends Model {

    public $id;
    public $firstName;
    public $lastName;
    public $sexe;

    public function __construct($id, $firstname, $lastname, $sexe) {
        $this->id = $id;
        $this->firstName = $firstname;
        $this->lastName = $lastname;
        $this->sexe = $sexe;
    }

    public function update() {
        $this->update_db();
    }

    private function update_db() {
        self::execute("UPDATE student SET firstname=:firstname, lastname=:lastname, sex=:sexe WHERE id=:id ", array('id' => $this->id, 'firstname' => $this->firstName, 'lastname' => $this->lastName, 'sexe' => $this->sexe));
    }

    public function check_presence_by_course($idcourse) {
        $query = self::execute("SELECT * FROM presence join student on student=student.id join courseoccurrence on courseoccurrence.id=courseoccurence  WHERE student.id=:id and course=:idcourse", array("id" => $this->id, "idcourse" => $idcourse));
        $data = $query->fetchAll();
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

    public function check_presence($idoccurrence) {
        $query = self::execute("SELECT * FROM presence WHERE student=:id and courseoccurence=:idcourse", array("id" => $this->id, "idcourse" => $idoccurrence));
        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return null;
        }
        return $data['present'];
    }

    public function get_certificat_by_idstudent() {
        $query = self::execute("SELECT * FROM certificate WHERE certificate.student=:id", array("id" => $this->id));
        $data = $query->fetchAll();
        $result = [];
        foreach ($data as $row) {
            $result[] = new Certificat($row["id"], $row["student"], $row["startdate"], $row["finishdate"]);
        }
        return $result;
    }

    public function check_certif($date) {
        $query = self::execute("SELECT * FROM certificate WHERE certificate.student=:id AND startdate<=:date AND finishdate>=:date ", array("id" => $this->id, "date" => $date));
        $data = $query->fetchAll();
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

    public static function get_student_by_id($id) {
        $query = self::execute("SELECT * FROM Student where id = :id", array("id" => $id));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Student($row["id"], $row["firstname"], $row["lastname"], $row["sex"]);
        }
    }

    public static function get_student_by_name($firstname, $lastname) {
        $query = self::execute("Select * FROM Student WHERE firstname=:firstname and lastname=:lastname", array("firstname" => $firstname, "lastname" => $lastname));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function get_student() {
        $query = self::execute("Select * FROM Student", array());
        $data = $query->fetchAll();
        $result = [];
        foreach ($data as $row) {
            $result[] = new Student($row["id"], $row["firstname"], $row["lastname"], $row["sex"]);
        }
        return $result;
    }

    public static function add_student($firstname, $lastname, $sexe) {
        self::execute("INSERT INTO student (lastname, firstname, sex) values (:lastname, :firstname,:sexe) ", array('lastname' => $lastname, 'firstname' => $firstname, 'sexe' => $sexe));
    }

    public function delete() {
        $this->delete_db();
    }

    private function delete_db() {
        $presences = Presence::get_presence_student($this->id);
        foreach ($presences as $pres) {
            $pres->delete();
        }
        StudentCourses::delete_by_student($this->id);
        self::execute("DELETE FROM student WHERE id = :id", array('id' => $this->id));
    }

    public static function validate_unicity($firstname, $lastname) {
        $errors = [];
        if (self::get_student_by_name($firstname, $lastname)) {
            $errors[] = "Etudiant existe déja.";
        }
        return $errors;
    }

    public static function get_student_course($id) {
        $query = self::execute("Select * FROM Student JOIN studentcourses on studentcourses.student=student.id ", array());
        $data = $query->fetchAll();
        $result = [];
        foreach ($data as $row) {
            $result[] = new Student($row["id"], $row["firstname"], $row["lastname"], $row["sex"]);
        }
        return $result;
    }

    public function get_presence($idoccurrence) {
        $query = self::execute("SELECT * FROM courseoccurrence join presence ON courseoccurrence.id=presence.courseoccurence WHERE id=:id and student=:student", array("id" => $idoccurrence, "student" => $this->id));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return $row['present'];
        }
    }

    public function get_certificat() {
        $query = self::execute("Select * FROM certificate where student=:id ", array("id" => $this->id));
        $data = $query->fetchAll();
        $result = [];
        foreach ($data as $row) {
            $result[] = new Certificat($row["id"], $row["student"], $row["startdate"], $row["finishdate"]);
        }
        return $result;
    }

    public function check_certificat($dateocc) {
        $certificat = $this->get_certificat();
        foreach ($certificat as $c) {
            if ($c->startdate <= $dateocc && $c->finishdate >= $dateocc) {
                return true;
            }
        }
        return false;
    }

}
