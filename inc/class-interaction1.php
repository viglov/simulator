<?php

class Interaction {

    private $con;
    private $exercises;

    public function Interaction() {
        $this->con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if (!$this->con) {
            echo "<br/>Error: Unable to connect to MySQL." . PHP_EOL;
            echo "<br/>Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "<br/>Debugging error: " . mysqli_connect_error() . PHP_EOL;
            $this->con->close();
        }
    }

    public function close() {
        $this->con->close();
    }

    public function get_log() {
        $return = '';
        $sql = 'SELECT id,object,status,date_time,reason FROM log';
        if ($sql_res = $this->con->prepare($sql)) {

            $sql_res->execute();
            $sql_res->bind_result($id, $obj, $stat, $date, $reas);
            while ($sql_res->fetch()) {
                $return[$id] = array($obj, $stat, $date, $reas);
            }
        }
        if (empty($return)) {
            $return = __('The Log is empty.');
        }
        return $return;
    }

    public function get_exercises($field = '') {
        $sql = 'SELECT ';

        switch ($field) {
            case 'menu':
                $sql .= 'id FROM exercise';
                break;
            case 'exp':
                $sql .= 'id,start,end FROM exercise';
                break;
            default :
                $sql .= '* FROM exercise';
                break;
        }
//        $sql .= ' WHERE id=?';

        if ($sql_res = $this->con->prepare($sql)) {

            $sql_res->execute();
            $sql_res->bind_result($id, $start, $end, $log);
            while ($sql_res->fetch()) {
//                $return[$id] = array($obj, $stat, $date, $reas);
            }
        }
        if (empty($return)) {
            $return = __('There is no Exercises.');
        }
        return $return;
    }

    public function get_exercises_menu() {
        return $this->get_exercises('menu');
    }

}
