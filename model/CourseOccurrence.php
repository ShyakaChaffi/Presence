<?php

require_once "framework/Model.php";

class CourseOccurrence extends Model {

    public $id;
    public $date;
    public $course;

    public function __construct($id, $date, $course) {
        $this->id = $id;
        $this->date = $date;
        $this->course = $course;
    }

    public static function get_event_db($startdate, $datefin, $teacher) {
        $query = self::execute("select id, title, endtime , starttime , date as start from course join courseoccurrence on course = code where teacher = :id   and (date>=:datedebut and date<=:datefin) ORDER BY id", array('id' => $teacher, 'datedebut' => $startdate, 'datefin' => $datefin));
        $result = $query->fetchAll();
        return $result;
    }

    public function insert() {
        $this->insert_db();
    }

    private function insert_db() {
        self::execute("INSERT INTO Courseoccurrence (date,course) values (:date, :course) ", array('date' => $this->date, 'course' => $this->course));
    }

    public function delete() {
        $this->delete_db();
    }

    private function delete_db() {

        self::execute("DELETE FROM courseoccurrence WHERE course=:course", array('course' => $this->course));
    }

    public function get_jour() {
        $query = self::execute("SELECT * FROM Course where code = :id", array("id" => $this->course));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return $row["dayofweek"];
        }
    }

    public static function get_course_occurrence() {
        $query = self::execute("SELECT * FROM courseoccurrence", array());
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new CourseOccurrence($row["id"], $row["date"], $row["course"]);
        }
        return $results;
    }

    public static function get_course_occurrence_by_id($id) {
        $query = self::execute("SELECT * FROM courseoccurrence where id=:id", array('id' => $id));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new CourseOccurrence($row["id"], $row["date"], $row["course"]);
        }
    }

    public static function get_course_occurence_by_date($course, $date) {
        $query = self::execute("SELECT * FROM courseoccurrence where course=:course and date=:date", array("course" => $course, "date" => $date));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new CourseOccurrence($row["id"], $row["date"], $row["course"]);
        }
    }

    public function get_name_course() {
        $query = self::execute("SELECT * FROM Course where code= :id", array("id" => $this->course));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return $row["title"];
        }
    }

    public static function find_occurrences($startDate, $endDate, $dayweek, $id) {
// variables
        $tabOccurrences = array();
        $nbtotal = 0;
// test pour le jour de recurrence
        $dayofweek = self::get_Day_Of_Week($dayweek);
// recherche du jour de la date de début d'intervalle + la date du jour de la 1ère occurrence
        list($year, $month, $day) = explode('-', $startDate);
        $dayFirstDate = date("l", mktime(0, 0, 0, $month, $day, $year));
        $firstDateOccurrence = strtotime('next ' . $dayofweek, mktime(0, 0, 0, $month, $day, $year));
        $firstDayOccurrence = date('l', $firstDateOccurrence);
// verifie si le jour de la premiere occurrence est à prendre en compte ou non
        if ($dayFirstDate == $firstDayOccurrence) {
            $nextDate = strtotime('-7 day', $firstDateOccurrence);
        } else {
            $nextDate = strtotime(date('d-m-Y', $firstDateOccurrence));
        }

// affiche les réccurrences possibles dans l'intervalle
        while ($nextDate <= strtotime($endDate)) {
            array_push($tabOccurrences, new CourseOccurrence(0, date('Y-m-d', $nextDate), $id));
            $nextDate = strtotime('+7 day', $nextDate);
        }
// retourne le tableau des dates
        return $tabOccurrences;
    }

    public static function get_day_of_week($dayweek) {
        switch ($dayweek) {
            case "0": $dayofweek = 'Monday';
                break;
            case "1": $dayofweek = 'Tuesday';
                break;
            case "2": $dayofweek = 'Wednesday';
                break;
            case "3": $dayofweek = 'Thursday';
                break;
            case "4": $dayofweek = 'Friday';
                break;
            case "5": $dayofweek = 'Saturday';
                break;
        }
        return $dayofweek;
    }

    public static function get_day_of_week_in_french($dayweek) {
        switch ($dayweek) {
            case "0": $dayofweek = 'Lundi';
                break;
            case "1": $dayofweek = 'Mardi';
                break;
            case "2": $dayofweek = 'Mercredi';
                break;
            case "3": $dayofweek = 'Jeudi';
                break;
            case "4": $dayofweek = 'Vendredi';
                break;
            case "5": $dayofweek = 'Samedi';
                break;
        }
        return $dayofweek;
    }

}

?>