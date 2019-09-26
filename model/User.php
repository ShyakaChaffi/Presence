<?php

require_once "framework/Model.php";
require_once "Course.php";

class User extends Model {

    public $id;
    public $pseudo;
    public $hashed_password;
    public $fullname;
    public $role;

//    public $courses = [];
//    public $courseselected = null;

    public function __construct($id, $pseudo, $hashed_password, $fullName, $role) {
        $this->id = $id;
        $this->pseudo = $pseudo;
        $this->hashed_password = $hashed_password;
        $this->fullname = $fullName;
        $this->role = $role;
    }

    public function update() {
        $this->update_db();
    }

    private function update_db() {
        self::execute("UPDATE User SET password=:password, fullname=:fullname , pseudo=:pseudo, role=:role WHERE id=:id ", array("fullname" => $this->fullname, "password" => $this->hashed_password, "pseudo" => $this->pseudo, "role" => $this->role, "id" => $this->id));
    }

    public static function get_user_by_pseudo($pseudo) {
        $query = self::execute("SELECT * FROM User where pseudo = :pseudo", array("pseudo" => $pseudo));
        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["id"], $data["pseudo"], $data["password"], $data["fullname"], $data["role"]);
        }
    }

    public static function get_user_by_id($id) {
        $query = self::execute("SELECT * FROM User where id = :id", array("id" => $id));
        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["id"], $data["pseudo"], $data["password"], $data["fullname"], $data["role"]);
        }
    }

    public static function get_user() {
        $query = self::execute("SELECT * FROM User", array());
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new User($row["id"], $row["pseudo"], $row["password"], $row["fullname"], $row["role"]);
        }
        return $results;
    }

    private static function validate_password($password) {
        $errors = [];
        if (strlen($password) < 8 || strlen($password) > 16) {
            $errors[] = "Password length must be between 8 and 16.";
        } if (!((preg_match("/[A-Z]/", $password)) && preg_match("/\d/", $password) && preg_match("/['\";:,.\/?\\-]/", $password))) {
            $errors[] = "Password must contain one uppercase letter, one number and one punctuation mark.";
        }
        return $errors;
    }

    public static function validate_passwords($password, $password_confirm) {
        $errors = User::validate_password($password);
        if ($password != $password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
    }

    public static function validate_unicity($pseudo) {
        $errors = [];
        $user = self::get_user_by_pseudo($pseudo);
        if ($user) {
            $errors[] = "This user already exists.";
        }
        return $errors;
    }

    public function check_if_teacher_has_this_course($code) {
        $query = self::execute("SELECT * FROM Course join user on id=teacher where code = :code and teacher=:id", array("code" => $code, "id" => $this->id));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return true;
        }
    }

    //indique si un mot de passe correspond à son hash
    private static function check_password($clear_password, $hash) {
        return $hash === Tools::my_hash($clear_password);
    }

    //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    //ne s'occupe que de la validation "métier" des champs obligatoires (le pseudo)
    //les autres champs (mot de passe, description et image) sont gérés par d'autres
    //méthodes.
    public function validate() {
        $errors = array();
        if (!(isset($this->pseudo) && is_string($this->pseudo) && strlen($this->pseudo) > 0)) {
            $errors[] = "Pseudo is required.";
        } if (!(isset($this->pseudo) && is_string($this->pseudo) && strlen($this->pseudo) >= 3 && strlen($this->pseudo) <= 16)) {
            $errors[] = "Pseudo length must be between 3 and 16.";
        } if (!(isset($this->pseudo) && is_string($this->pseudo) && preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/", $this->pseudo))) {
            $errors[] = "Pseudo must start by a letter and must contain only letters and numbers.";
        }
        return $errors;
    }

    //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    public static function validate_login($pseudo, $password) {
        $errors = [];
        $user = User::get_user_by_pseudo($pseudo);
        if ($user) {
            if (!self::check_password($password, $user->hashed_password)) {
                $errors[] = "Wrong password. Please try again.";
            }
        } else {
            $errors[] = "Can't find a user with the pseudo '$pseudo'. Please sign up.";
        }
        return $errors;
    }

    public static function add_user($pseudo, $fullname, $role, $password) {
        self::execute("INSERT INTO user (pseudo, password, fullname, role) values (:pseudo, :password,:fullname,:role) ", array('pseudo' => $pseudo, 'password' => $password, 'role' => $role, 'fullname' => $fullname));
    }

    public function delete() {
        $this->delete_db();
    }

    private function delete_db() {
        self::execute("DELETE FROM user WHERE id = :id", array('id' => $this->id));
    }

    public function get_course() {
        $query = self::execute("SELECT * FROM Course join user on id=teacher where teacher = :teacher ORDER BY `starttime` ASC", array("teacher" => $this->id));
        $data = $query->fetchAll();
        $results = [];

        foreach ($data as $row) {
            $results[] = new Course($row["code"], $row["title"], $row["dayofweek"], $row["starttime"], $row["endtime"], $row["startdate"], $row["finishdate"], $row["teacher"]);
        }
        return $results;
    }

}

?>