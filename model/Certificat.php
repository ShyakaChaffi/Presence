<?php

require_once "framework/Model.php";
require_once "CourseOccurrence.php";
require_once "Student.php";

class Certificat extends Model {

    public $id;
    public $student;
    public $startdate;
    public $finishdate;

    public function __construct($id, $studentid, $startdate, $finishdate) {

        $this->id = $id;
        $this->student = $studentid;
        $this->startdate = $startdate;
        $this->finishdate = $finishdate;
    }

    public static function get_certificat_by_id($id) {
        $query = self::execute("SELECT * FROM Certificate where id = :id", array("id" => $id));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Certificat($row["id"], $row["student"], $row["startdate"], $row["finishdate"]);
        }
    }

    public static function insert_certificate($studentid, $startdate, $finishdate) {
        self::execute("INSERT INTO certificate (student,startdate,finishdate) VALUES (:studentid,:startdate,:finishdate)", array("studentid" => $studentid, "startdate" => $startdate, "finishdate" => $finishdate));
    }

    public static function get_certificat() {
        $query = self::execute("SELECT * FROM Certificate", array());
        $data = $query->fetchAll();
        $result = [];
        foreach ($data as $row) {
            $result[] = new Certificat($row["id"], $row["student"], $row["startdate"], $row["finishdate"]);
        }
        return $result;
    }

    public static function get_certificat_by_order() {
        $query = self::execute("SELECT * FROM certificate ORDER BY certificate.student ASC", array());
        $data = $query->fetchAll();
        return $data;
    }

    public function get_name_student() {
        $query = self::execute("SELECT lastname, firstname FROM certificate join student on student=student.id where student=:id", array("id" => $this->student));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return $row['firstname'] . " " . $row['lastname'];
        }
    }

    public function delete() {
        self::execute("DELETE FROM certificate WHERE id=:id", array("id" => $this->id));
    }

    public static function get_all_certificates_as_json() {
        $str = "";
        $certificates = Certificat::get_certificat_by_order();
        foreach ($certificates as $certificate) {
            $student = Student::get_student_by_id($certificate['student']);
            $certif_id = json_encode($certificate['id']);
            $studentid = json_encode($student->id);
            $firstname = json_encode($student->firstName);
            $lastname = json_encode($student->lastName);
            $startDate = json_encode(date('d-m-Y', strtotime($certificate['startdate'])));
            $finishDate = json_encode(date('d-m-Y', strtotime($certificate['finishdate'])));
            $str .= "{\"id\":$certif_id,\"student\":$studentid,\"firstname\":$firstname,\"lastname\":$lastname,\"startdate\":$startDate,\"finishdate\":$finishDate},";
        }
        if ($str !== "") {
            $str = substr($str, 0, strlen($str) - 1);
        }
        return "[$str]";
    }

}
