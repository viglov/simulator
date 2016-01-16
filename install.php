<?php

require_once 'config.php';

/*
 * array(
 *      table_name => array(
 *          field_name => field_setings,
 *          ...
 *      ),
 *      ...
 * );
 */
$tables = array(
     'objects'     => array(
          'object'      => 'VARCHAR(20) CHARACTER SET utf8',
          'status'      => 'TINYINT(1) NOT NULL DEFAULT 0',
          'position'    => 'TINYINT(1) NOT NULL DEFAULT 0',
          'ext_on'      => 'TINYINT(1) NOT NULL DEFAULT 0',
          'ext_off'     => 'TINYINT(1) NOT NULL DEFAULT 0',
          'PRIMARY KEY' => '(object)'
     ),
     'log'         => array(
          'id'          => 'INT NOT NULL AUTO_INCREMENT',
          'object'      => 'VARCHAR(20) CHARACTER SET utf8',
          'date_time'   => 'DATETIME NOT NULL',
          'status'      => 'TINYINT(1) NOT NULL DEFAULT 0',
          'reason'      => 'VARCHAR(127) CHARACTER SET utf8',
          'PRIMARY KEY' => '(id)'
     ),
     'confirm'     => array(
          'id'          => 'INT NOT NULL AUTO_INCREMENT',
          'object'      => 'VARCHAR(20) CHARACTER SET utf8',
          'PRIMARY KEY' => '(id)'
     ),
     'protections' => array(
          'id'          => 'INT NOT NULL AUTO_INCREMENT',
          'object'      => 'VARCHAR(20) CHARACTER SET utf8',
          'pr_51'       => 'TINYINT(1) NOT NULL DEFAULT 0',
          'pr_51n'      => 'TINYINT(1) NOT NULL DEFAULT 0',
          'pr_50'       => 'TINYINT(1) NOT NULL DEFAULT 0',
          'pr_21'       => 'TINYINT(1) NOT NULL DEFAULT 0',
          'PRIMARY KEY' => '(id)'
     ),
     'plccom'      => array(
          'id'          => 'INT NOT NULL AUTO_INCREMENT',
          'BLC_RESET'   => 'VARCHAR(20) CHARACTER SET utf8',
          'PRIMARY KEY' => '(id)'
     ),
     'exercise'    => array(
          'id'          => 'INT NOT NULL AUTO_INCREMENT',
          'title'       => 'VARCHAR(20) CHARACTER SET utf8',
          'start'       => 'BLOB',
          'end'         => 'BLOB',
          'log'         => 'BLOB',
          'PRIMARY KEY' => '(id)'
     ),
     'connections' => array(
          'ip'          => 'VARCHAR(20) CHARACTER SET utf8',
          'first_call'  => 'DATETIME NOT NULL',
          'last_call'   => 'DATETIME NOT NULL',
          'PRIMARY KEY' => '(ip)'
     )
);

/*
 * array(
 *      table_name => array(
 *          array(
 *              field_name => value,
 *              ...
 *          ),
 *          ...
 *      ),
 *      ...
 * );
 */
$defoults = array(
     'objects' => array(
          array( 'object' => 'e02q2' ),
          array( 'object' => 'e02q25' ),
          array( 'object' => 'e02q9' ),
//        array('object' => 'e02l29'),
          array( 'object' => 'e04q1' ),
          array( 'object' => 'e04q15' ),
          array( 'object' => 'e04q9' ),
//        array('object' => 'e04l19'),
          array( 'object' => 'e10q3' ),
          array( 'object' => 'e10q35' ),
          array( 'object' => 'e10q9' ),
//        array('object' => 'e10l39'),
          array( 'object' => 'e07q7' ),
          array( 'object' => 'e07q75' ),
          array( 'object' => 'e07q9' ),
//        array('object' => 'e07l79'),
          array( 'object' => 'e06q0' ),
          array( 'object' => 'e06q1' ),
          array( 'object' => 'e06q2' ),
          array( 'object' => 'e06q10' ),
          array( 'object' => 'e06q3' ),
          array( 'object' => 'e06q51' ),
          array( 'object' => 'e06q52' ),
//        array('object' => 'e06l01251'),
//        array('object' => 'e06l010352'),
          array( 'object' => 'e08q0' ),
          array( 'object' => 'e08q1' ),
          array( 'object' => 'e08q2' ),
          array( 'object' => 'e08q3' ),
          array( 'object' => 'e08q9' ),
          array( 'object' => 'e08q51' ),
          array( 'object' => 'e08q52' ),
//        array('object' => 'e08l012351'),
//        array('object' => 'e08l0952'),
          array( 'object' => 'e01q0' ),
          array( 'object' => 'e01q1' ),
          array( 'object' => 'e01q2' ),
          array( 'object' => 'e01q7' ),
          array( 'object' => 'e01q8' ),
          array( 'object' => 'e01q9' ),
          array( 'object' => 'e01q51' ),
          array( 'object' => 'e01q52' ),
//        array('object' => 'e01l01251'),
//        array('object' => 'e01l0952'),
          array( 'object' => 'e01l789' ),
          array( 'object' => 'e03q0' ),
          array( 'object' => 'e03q1' ),
          array( 'object' => 'e03q2' ),
          array( 'object' => 'e03q7' ),
          array( 'object' => 'e03q8' ),
          array( 'object' => 'e03q9' ),
          array( 'object' => 'e03q51' ),
          array( 'object' => 'e03q52' ),
//        array('object' => 'e03l01251'),
//        array('object' => 'e03l0952'),
          array( 'object' => 'e03l789' ),
          array( 'object' => 'e05q0' ),
          array( 'object' => 'e05q1' ),
          array( 'object' => 'e05q2' ),
          array( 'object' => 'e05q7' ),
          array( 'object' => 'e05q8' ),
          array( 'object' => 'e05q9' ),
          array( 'object' => 'e05q51' ),
          array( 'object' => 'e05q52' ),
//        array('object' => 'e05l01251'),
//        array('object' => 'e05l0952'),
          array( 'object' => 'e05l789' ),
          array( 'object' => 'e09q0' ),
          array( 'object' => 'e09q1' ),
          array( 'object' => 'e09q3' ),
          array( 'object' => 'e09q7' ),
          array( 'object' => 'e09q8' ),
          array( 'object' => 'e09q9' ),
          array( 'object' => 'e09q51' ),
          array( 'object' => 'e09q52' ),
//        array('object' => 'e09l01351'),
//        array('object' => 'e09l0952'),
          array( 'object' => 'e09l789' ),
          array( 'object' => 'e11q0' ),
          array( 'object' => 'e11q1' ),
          array( 'object' => 'e11q3' ),
          array( 'object' => 'e11q7' ),
          array( 'object' => 'e11q8' ),
          array( 'object' => 'e11q9' ),
          array( 'object' => 'e11q51' ),
          array( 'object' => 'e11q52' ),
//        array('object' => 'e11l01351'),
//        array('object' => 'e11l0952'),
          array( 'object' => 'e11l789' ),
          array( 'object' => 'e12q0' ),
          array( 'object' => 'e12q1' ),
          array( 'object' => 'e12q3' ),
          array( 'object' => 'e12q7' ),
          array( 'object' => 'e12q8' ),
          array( 'object' => 'e12q9' ),
          array( 'object' => 'e12q51' ),
          array( 'object' => 'e12q52' ),
//        array('object' => 'e12l01351'),
//        array('object' => 'e12l0952'),
          array( 'object' => 'e12l789' ),
//        array('object' => 'bb0'),
//        array('object' => 'bb1'),
//        array('object' => 'bb2'),
//        array('object' => 'bb3')
     )
);

/*
 * Conect to MySql
 */
$con = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD );
if ( mysqli_connect_errno() ) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    return;
}

/*
 * Create database if not exist
 */
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;

//echo '<br/><br/>Query: ' . $sql;
if ( mysqli_query( $con, $sql ) ) {
    echo "<br/>Database '" . DB_NAME . "' created successfully";
} else {
    echo "<br/>Error creating database: " . mysqli_error( $con ) . "";
    return;
}

/*
 * Conect to the database
 */
$con = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
if ( !$con ) {
    echo "<br/>Error: Unable to connect to MySQL." . PHP_EOL;
    echo "<br/>Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "<br/>Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
echo "<br/><br/>Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
echo "<br/><br/>Host information: " . mysqli_get_host_info( $con ) . PHP_EOL;

echo "<br/><br/>Installing Tables:";

/*
 * Create tables defined in $tables if not exist
 */
foreach ( $tables as $table => $fields ) {
    echo "<br/>Installing Table '$table'...";
// Create table
    $sql = "CREATE TABLE IF NOT EXISTS $table(";
    $i = 0;
    foreach ( $fields as $f => $prop ) {
        if ( $i > 0 ) {
            $sql .= ',';
        }
        $i++;
        $sql .= $f . ' ' . $prop;
    }
    $sql .= ")";

//    Execute query
    if ( mysqli_query( $con, $sql ) ) {
        echo "<br/><br/>Table '$table' created successfully<br/>";
    } else {
        echo "<br/><br/>Error creating table: " . mysqli_error( $con ) . "<br/>";
        return;
    }
}

/*
 * Insert data defined in $defoults
 */
foreach ( $defoults as $table => $cont ) {
    echo "<br/>Installing Data on '{$table}'...";

    $sql = '';
    $i = 0;
    foreach ( $cont as $r ) {
        if ( $i > 0 ) {
            $sql .= ';';
        }
        $i++;
        $fields = $datas = '';
        $k = 0;
        foreach ( $r as $field => $data ) {
            if ( $k > 0 ) {
                $fields .= ',';
                $datas .= ',';
            }
            $k++;
            $fields .= $field;
            $datas .= "'$data'";
        }
        $sql .= "INSERT IGNORE INTO $table ($fields) VALUES ($datas)";
    }
    echo '<br/>Data Insert...';

//    Execute query
    if ( $con->multi_query( $sql ) ) {
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        echo "<br/><br/>Siccessful Installation";
        echo "<br/><br/><a href=\"http://" . substr( $url, 0, -12 ) . "\">Go to the System</a>";
    } else {
        echo "<br/><br/>Error: " . mysqli_error( $con ) . "<br/>";
    }
}

