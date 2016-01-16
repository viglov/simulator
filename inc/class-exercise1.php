<?php

class Exercise {

    private $con;
    private $return;

    public function Exercise() {
        $this->con = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

        if ( !$this->con ) {
            echo "<br/>Error: Unable to connect to MySQL." . PHP_EOL;
            echo "<br/>Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "<br/>Debugging error: " . mysqli_connect_error() . PHP_EOL;
            $this->con->close();
        }
    }

    public function set_start() {
        
    }

    public function set_end() {
        
    }

    public function reset_log() {

        $sql = "TRUNCATE TABLE log";
        if ( !$sql_res = $this->con->query( $sql ) ) {
            $this->return['error'][] = _( 'Error updating record:' ) . $this->con->error;
        }
    }

    public function set_debug( $txt ) {
        $this->return['debug'][] = $txt;
    }

    public function get_return() {
        return $this->return;
    }
}
