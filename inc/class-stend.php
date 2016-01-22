<?php

class Stend {

    private $init_line = array(
         'e01l789', 'e01l0952', 'e01l01251',
         'e02l29',
         'e03l789', 'e03l0952', 'e03l01251',
         'e04l19',
         'e05l789', 'e05l0952', 'e05l01251',
         'e06l01251', 'e06l010352',
         'e07l79',
         'e08l012351', 'e08l0952',
         'e09l789', 'e09l0952', 'e09l01351',
         'e10l39',
         'e11l789', 'e11l0952', 'e11l01351',
         'e12l789', 'e12l0952', 'e12l01351',
         'bb0', 'bb1', 'bb2', 'bb3'
    );
    private $init_q = array(
         'e01q0', 'e01q1', 'e01q2', 'e01q7', 'e01q8', 'e01q9', 'e01q51', 'e01q52',
         'e03q0', 'e03q1', 'e03q2', 'e03q7', 'e03q8', 'e03q9', 'e03q51', 'e03q52',
         'e05q0', 'e05q1', 'e05q2', 'e05q7', 'e05q8', 'e05q9', 'e05q51', 'e05q52',
         'e09q0', 'e09q1', 'e09q3', 'e09q7', 'e09q8', 'e09q9', 'e09q51', 'e09q52',
         'e11q0', 'e11q1', 'e11q3', 'e11q7', 'e11q8', 'e11q9', 'e11q51', 'e11q52',
         'e12q0', 'e12q1', 'e12q3', 'e12q7', 'e12q8', 'e12q9', 'e12q51', 'e12q52',
         'e06q0', 'e06q1', 'e06q2', 'e06q3', 'e06q10', 'e06q51', 'e06q52',
         'e08q0', 'e08q1', 'e08q2', 'e08q3', 'e08q9', 'e08q51', 'e08q52',
         'e02q2', 'e02q9', 'e02q25',
         'e04q1', 'e04q9', 'e04q15',
         'e07q7', 'e07q9', 'e07q75',
         'e10q3', 'e10q9', 'e10q35',
    );
    private $source_lines = array(
         'e01l789', 'e03l789', 'e05l789',
         'e09l789', 'e11l789', 'e12l789'
    );
    private $q_earthing = array(
         'e01q51', 'e01q52', 'e01q8',
         'e03q51', 'e03q52', 'e03q8',
         'e05q51', 'e05q52', 'e05q8',
         'e09q51', 'e09q52', 'e09q8',
         'e11q51', 'e11q52', 'e11q8',
         'e12q51', 'e12q52', 'e12q8',
         'e06q51', 'e06q52',
         'e08q51', 'e08q52',
         'e02q9', 'e02q25',
         'e04q9', 'e04q15',
         'e07q9', 'e07q75',
         'e10q9', 'e10q35',
    );
    private $sql_con;
    private $return;
    private $mode;
    private $client_obj_status;
    private $obj_status;
    private $command;
    private $message;

    public function Stend( $mode = false, $object = array() ) {
        $this->sql_con = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

        if ( !$this->sql_con ) {
            echo "<br/>Error: Unable to connect to MySQL." . PHP_EOL;
            echo "<br/>Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "<br/>Debugging error: " . mysqli_connect_error() . PHP_EOL;
            $this->return['error'][] = "<br/>Error: Unable to connect to MySQL." . PHP_EOL . "<br/>Debugging errno: " . mysqli_connect_errno() . PHP_EOL . "<br/>Debugging error: " . mysqli_connect_error() . PHP_EOL;
            echo json_encode( $this->return );
            $this->sql_con->close();
            die();
        }
        $this->mode = $mode;
        $this->client_obj_status = $object;
        $this->return['obj'] = array();

        if ( $this->mode ) {
            $this->setIP();
        } else {
            foreach ( $this->client_obj_status as $obj => $vars ) {

                $this->return['obj'][$obj]['status'] = (int) $vars['status'];
                $this->return['obj'][$obj]['position'] = (int) $vars['position'];
                if ( isset( $vars['source'] ) ) {
                    $this->return['obj'][$obj]['source'] = 1;
                }
            }
        }
    }

    private function setIP() {
        $date = date( "Y-m-d H:i:s", time() );
        $ip = $this->getIP();

        $this->return['debug']['ip'] = $ip;

        $sql = "DELETE FROM connections WHERE ((TIMESTAMPDIFF(SECOND, last_call, '$date') > 60) OR (TIMESTAMPDIFF(SECOND, first_call, last_call) > 300)); ";
        if ( !$sql_res = $this->sql_con->query( $sql ) ) {
            $this->return['error'][] = _( 'Error updating record:' ) . $this->sql_con->error;
        }

        $sql = "INSERT INTO connections (ip, first_call, last_call) VALUES('{$ip}', '{$date}', '{$date}') ON DUPLICATE KEY UPDATE last_call = VALUES(last_call);";
        if ( !$sql_res = $this->sql_con->query( $sql ) ) {
            $this->return['error'][] = _( 'Error updating record:' ) . $this->sql_con->error;
        }

        $sql = "SELECT ip FROM connections ORDER BY first_call ASC;";
        if ( $sql_res = $this->sql_con->prepare( $sql ) ) {
            $sql_res->execute();
            $sql_res->bind_result( $ips );
            while ( $sql_res->fetch() ) {
                $as[] = $ips;
            }
            $this->return['position'] = array_search( $ip, $as );
        }
    }

    private function getIP() {
        foreach ( array(
   'HTTP_CLIENT_IP',
   'HTTP_X_FORWARDED_FOR',
   'HTTP_X_FORWARDED',
   'HTTP_X_CLUSTER_CLIENT_IP',
   'HTTP_FORWARDED_FOR',
   'HTTP_FORWARDED',
   'REMOTE_ADDR'
        ) as $key ) {
            if ( array_key_exists( $key, $_SERVER ) === true ) {
                foreach ( explode( ',', $_SERVER[$key] ) as $ip ) {
                    if ( filter_var( $ip, FILTER_VALIDATE_IP ) !== false ) {
                        return $ip;
                    }
                }
            }
        }
    }

    public function get_return( $reset = true ) {
        if ( $reset ) {
            $this->reload_objects();
            if ( isset( $this->message['success'] ) ) {
                foreach ( $this->message['success'] as $m ) {
                    $this->return['success'][] = $m;
                }
            }
        }
        return $this->return;
    }

    public function set_message( $message = '', $type = 'success' ) {
        $this->message[$type][] = $message;
    }

    public function update_obj( $obj_name, $obj_com ) {
        $this->return['debug'][] = 'enter update_obj(): obj - ' . $obj_name . ', com - ' . $obj_com;

        $this->obj_status = $this->get_status( $obj_name );
        $this->command = $obj_com;

        if ( $this->approve_command( $obj_name ) ) {

            if ( $this->mode ) {
                $sql = "UPDATE objects SET status = '{$this->command}',position = '{$this->command}' WHERE object = '{$obj_name}';";
                if ( !$sql_res = $this->sql_con->query( $sql ) ) {
                    $this->return['error'][] = _( 'Error updating record:' ) . $this->sql_con->error;
                }
                $date = date( "Y-m-d H:i:s", time() );
                $sql = "INSERT INTO log (object, date_time, status, reason) VALUES ('$obj_name', '$date', '$this->command', 'OC')";
                if ( !$sql_res = $this->sql_con->query( $sql ) ) {
                    $this->return['error'][] = _( 'Error updating record:' ) . $this->sql_con->error;
                }
            } else {
                $this->return['obj'][$obj_name]['status'] = $this->command;
                $this->return['obj'][$obj_name]['position'] = $this->command;

                $this->return['obj'][$obj_name]['source'] = $this->is_source( $obj_name, $this->command );
            }
        } else {
            if ( $this->mode ) {
                $sql = "UPDATE objects SET position = '{$this->command}' WHERE object = '{$obj_name}'";
                if ( !$sql_res = $this->sql_con->query( $sql ) ) {
                    $this->return['error'][] = _( 'Error updating record:' ) . $this->sql_con->error;
                }
            } else {
                $this->return['obj'][$obj_name]['position'] = $this->command;
            }

            echo json_encode( $this->return );
            $this->sql_con->close();
            die();
        }
    }

    public function reset( $r ) {

        if ( $r == 'stend' ) {

            if ( $this->mode ) {
                $sql = "UPDATE objects SET status = '0',position = '0'";
                if ( !$sql_res = $this->sql_con->query( $sql ) ) {
                    $this->return['error'][] = _( 'Error updating record:' ) . $this->sql_con->error;
                }
            } else {
                $this->return['obj'] = array();
            }
        } elseif ( $r == 'conflict' ) {

            if ( $this->mode ) {
                $sql = "UPDATE objects SET position = CASE status WHEN 0 THEN 0 WHEN 1 THEN 1 ELSE position END WHERE (position = '1' AND status IN (0)) OR position = '0' AND status IN (1)";
                if ( !$sql_res = $this->sql_con->query( $sql ) ) {
                    $this->return['error'][] = _( 'Error updating record:' ) . $this->sql_con->error;
                }
            } else {
                $this->return['obj'] = array();
            }
        }

        return $this->return;
    }

    private function is_source( $name, $status ) {
        if ( $status && in_array( $name, $this->source_lines ) ) {
            return 1;
        }
        return 0;
    }

    private function get_status( $q ) {
        if ( $this->mode ) {

            $sql = "SELECT status FROM objects WHERE object = '$q'";
            if ( $result = $this->sql_con->query( $sql ) ) {
                $status = $result->fetch_assoc();

                if ( $status['status'] == 1 ) {

                    return true;
                } elseif ( $status['status'] == 0 ) {

                    return false;
                } else {

                    return NULL;
                }
            } else {

                return false;
            }
        } else {
            if ( array_key_exists( $q, $this->client_obj_status ) ) {

                if ( $this->client_obj_status[$q]['status'] ) return true;
                else return false;
            } else {
                return FALSE;
            }
        }
    }

    private function busbar_couple( $q, $s = true ) {
        switch ( $q ) {
            case 'e01q1':
            case 'e01q2':
            case 'e03q1':
            case 'e03q2':
            case 'e05q1':
            case 'e05q2':
            case 'e06q1':
            case 'e06q2':
                $r = 1;
                break;
            case 'e06q10':
            case 'e06q3':
            case 'e09q1':
            case 'e09q3':
            case 'e11q1':
            case 'e11q3':
            case 'e12q1':
            case 'e12q3':
                $r = 2;
                break;
            case 'e08q1':
                if ( $s ) {
                    $r = 1;
                } else {
                    $r = 2;
                }
                break;
            case 'e08q2':
                if ( $s ) {
                    $r = 1;
                } else {
                    $r = 3;
                }
                break;
            case 'e08q3':
                if ( $s ) {
                    $r = 2;
                } else {
                    $r = 3;
                }
                break;
            default :
                $r = FALSE;
                break;
        }
        return $r;
    }

    private function breaker_couple( $q, $i = true ) {
        switch ( $q ) {
            case 'e01q1':
                $r = 'e01q2';
                break;
            case 'e01q2':
                $r = 'e01q1';
                break;
            case 'e03q1':
                $r = 'e03q2';
                break;
            case 'e03q2':
                $r = 'e03q1';
                break;
            case 'e05q1':
                $r = 'e05q2';
                break;
            case 'e05q2':
                $r = 'e05q1';
                break;
            case 'e06q1':
                if ( $i ) $r = 'e06q2';
                else $r = 'e06q10';
                break;
            case 'e06q2':
                $r = 'e06q1';
                break;
            case 'e06q3':
                $r = 'e06q10';
                break;
            case 'e06q10':
                if ( $i ) $r = 'e06q3';
                else $r = 'e06q1';
                break;
            case 'e08q1':
                if ( $i ) $r = 'e08q2';
                else $r = 'e08q3';
                break;
            case 'e08q2':
                if ( $i ) $r = 'e08q1';
                else $r = 'e08q3';
                break;
            case 'e08q3':
                if ( $i ) $r = 'e08q1';
                else $r = 'e08q2';
                break;
            case 'e09q1':
                $r = 'e09q3';
                break;
            case 'e09q3':
                $r = 'e09q1';
                break;
            case 'e11q1':
                $r = 'e11q3';
                break;
            case 'e11q3':
                $r = 'e11q1';
                break;
            case 'e12q1':
                $r = 'e12q3';
                break;
            case 'e12q3':
                $r = 'e12q1';
                break;
            default :
                $r = FALSE;
                break;
        }
        return $r;
    }

    private function q_conection( $q ) {
        switch ( $q ) {
            case 'e01q0':
                return array( 'e01l01251', 'e01l0952' );
            case 'e01q1':
                return array( 'e01l01251', 'bb1' );
            case 'e01q2':
                return array( 'e01l01251', 'bb2' );
            case 'e01q7':
                return array( 'e01l789', 'bb0' );
            case 'e01q8':
                return array( 'e01l789' );
            case 'e01q9':
                return array( 'e01l789', 'e01l0952' );
            case 'e01q51':
                return array( 'e01l01251' );
            case 'e01q52':
                return array( 'e01l0952' );

            case 'e02q2':
                return array( 'bb2', 'e02l29' );
            case 'e02q9':
                return array( 'e02l29' );
            case 'e02q25':
                return array( 'bb2' );

            case 'e03q0':
                return array( 'e03l01251', 'e03l0952' );
            case 'e03q1':
                return array( 'e03l01251', 'bb1' );
            case 'e03q2':
                return array( 'e03l01251', 'bb2' );
            case 'e03q7':
                return array( 'e03l789', 'bb0' );
            case 'e03q8':
                return array( 'e03l789' );
            case 'e03q9':
                return array( 'e03l789', 'e03l0952' );
            case 'e03q51':
                return array( 'e03l01251' );
            case 'e03q52':
                return array( 'e03l0952' );

            case 'e04q1':
                return array( 'bb1', 'e04l19' );
            case 'e04q9':
                return array( 'e04l19' );
            case 'e04q15':
                return array( 'bb1' );

            case 'e05q0':
                return array( 'e05l01251', 'e05l0952' );
            case 'e05q1':
                return array( 'e05l01251', 'bb1' );
            case 'e05q2':
                return array( 'e05l01251', 'bb2' );
            case 'e05q7':
                return array( 'e05l789', 'bb0' );
            case 'e05q8':
                return array( 'e05l789' );
            case 'e05q9':
                return array( 'e05l789', 'e05l0952' );
            case 'e05q51':
                return array( 'e05l01251' );
            case 'e05q52':
                return array( 'e05l0952' );

            case 'e06q0':
                return array( 'e06l01251', 'e06l010352' );
            case 'e06q1':
                return array( 'bb1', 'e06l01251' );
            case 'e06q10':
                return array( 'bb1', 'e06l010352' );
            case 'e06q2':
                return array( 'bb2', 'e06l01251' );
            case 'e06q3':
                return array( 'bb3', 'e06l010352' );
            case 'e06q51':
                return array( 'e06l01251' );
            case 'e06q52':
                return array( 'e06l010352' );

            case 'e07q7':
                return array( 'bb0', 'e07l79' );
            case 'e07q9':
                return array( 'e07l79' );
            case 'e07q75':
                return array( 'bb0' );

            case 'e08q0':
                return array( 'e08l012351', 'e08l0952' );
            case 'e08q1':
                return array( 'bb1', 'e08l012351' );
            case 'e08q2':
                return array( 'bb2', 'e08l012351' );
            case 'e08q3':
                return array( 'bb3', 'e08l012351' );
            case 'e08q9':
                return array( 'e08l0952', 'bb0' );
            case 'e08q51':
                return array( 'e08l012351' );
            case 'e08q52':
                return array( 'e08l0952' );

            case 'e09q0':
                return array( 'e09l01351', 'e09l0952' );
            case 'e09q1':
                return array( 'e09l01351', 'bb1' );
            case 'e09q3':
                return array( 'e09l01351', 'bb3' );
            case 'e09q7':
                return array( 'e09l789', 'bb0' );
            case 'e09q8':
                return array( 'e09l789' );
            case 'e09q9':
                return array( 'e09l789', 'e09l0952' );
            case 'e09q51':
                return array( 'e09l01351' );
            case 'e09q52':
                return array( 'e09l0952' );

            case 'e10q3':
                return array( 'bb3', 'e10l39' );
            case 'e10q9':
                return array( 'e10l39' );
            case 'e10q35':
                return array( 'bb3' );

            case 'e11q0':
                return array( 'e11l01351', 'e11l0952' );
            case 'e11q1':
                return array( 'e11l01351', 'bb1' );
            case 'e11q3':
                return array( 'e11l01351', 'bb3' );
            case 'e11q7':
                return array( 'e11l789', 'bb0' );
            case 'e11q8':
                return array( 'e11l789' );
            case 'e11q9':
                return array( 'e11l789', 'e11l0952' );
            case 'e11q51':
                return array( 'e11l01351' );
            case 'e11q52':
                return array( 'e11l0952' );

            case 'e12q0':
                return array( 'e12l01351', 'e12l0952' );
            case 'e12q1':
                return array( 'e12l01351', 'bb1' );
            case 'e12q3':
                return array( 'e12l01351', 'bb3' );
            case 'e12q7':
                return array( 'e12l789', 'bb0' );
            case 'e12q8':
                return array( 'e12l789' );
            case 'e12q9':
                return array( 'e12l789', 'e12l0952' );
            case 'e12q51':
                return array( 'e12l01351' );
            case 'e12q52':
                return array( 'e12l0952' );

            default:
                return false;
        }
    }

    private function l_conection( $line ) {
        switch ( $line ) {
            case 'e01l789':
                return array( 'e01q7', 'e01q9' );
            case 'e01l0952':
                return array( 'e01q0', 'e01q9' );
            case 'e01l01251':
                return array( 'e01q0', 'e01q1', 'e01q2' );

            case 'e03l789':
                return array( 'e03q7', 'e03q9' );
            case 'e03l0952':
                return array( 'e03q0', 'e03q9' );
            case 'e03l01251':
                return array( 'e03q0', 'e03q1', 'e03q2' );

            case 'e05l789':
                return array( 'e05q7', 'e05q9' );
            case 'e05l0952':
                return array( 'e05q0', 'e05q9' );
            case 'e05l01251':
                return array( 'e05q0', 'e05q1', 'e05q2' );

            case 'e06l01251':
                return array( 'e06q0', 'e06q1', 'e06q2' );
            case 'e06l010352':
                return array( 'e06q0', 'e06q10', 'e06q3' );

            case 'e08l012351':
                return array( 'e08q0', 'e08q1', 'e08q2', 'e08q3' );
            case 'e08l0952':
                return array( 'e08q0', 'e08q9' );

            case 'e09l789':
                return array( 'e09q7', 'e09q9' );
            case 'e09l0952':
                return array( 'e09q0', 'e09q9' );
            case 'e09l01351':
                return array( 'e09q0', 'e09q1', 'e09q3' );

            case 'e11l789':
                return array( 'e11q7', 'e11q9' );
            case 'e11l0952':
                return array( 'e11q0', 'e11q9' );
            case 'e11l01351':
                return array( 'e11q0', 'e11q1', 'e11q3' );

            case 'e12l789':
                return array( 'e12q7', 'e12q9' );
            case 'e12l0952':
                return array( 'e12q0', 'e12q9' );
            case 'e12l01351':
                return array( 'e12q0', 'e12q1', 'e12q3' );

            case 'bb0':
                return array( 'e01q7', 'e03q7', 'e05q7', 'e07q7', 'e08q9', 'e09q7', 'e11q7', 'e12q7' );
            case 'bb1':
                return array( 'e01q1', 'e03q1', 'e04q1', 'e05q1', 'e06q1', 'e06q10', 'e08q1', 'e09q1', 'e11q1', 'e12q1' );
            case 'bb2':
                return array( 'e01q2', 'e02q2', 'e03q2', 'e05q2', 'e06q2', 'e08q2' );
            case 'bb3':
                return array( 'e06q3', 'e08q3', 'e09q3', 'e10q3', 'e11q3', 'e12q3' );

            default:
                return false;
        }
    }

    private function q_check_status( $q_array = array(), $status = true ) {

        foreach ( $q_array as $q ) {

            $q_status = $this->get_status( $q );
            if ( $status && $q_status ) {

                $this->return['error'][] = '<span class="q-text">' . $q . '</span> is ON';

                return true;
            } elseif ( !$status && !$q_status ) {
                $this->return['error'][] = '<span class="q-text">' . $q . '</span> is OFF';

                return true;
            }
        }
        return false;
    }

    private function q_check_status_arr( $q_array = array() ) {
        foreach ( $q_array as $qs ) {
            $index = true;
            $message = '';
            foreach ( $qs as $elem ) {
                $message .= $elem . ', ';
                if ( !$this->get_status( $elem ) ) {
                    $index = false;
                    break;
                }
            }
            if ( $index ) {
                $this->return['error'][] = '<span class="q-text">' . $message . '</span> are ON';
                return true;
            }
        }
        return false;
    }

    private function off_line_status( $q ) {
        switch ( $q ) {
            case 'e01q1':
            case 'e01q2':
                $r = $this->q_check_status( array( 'e01q0', 'e01q51', 'e01q52' ) );
                break;
            case 'e01q51':
            case 'e01q52':
                $r = $this->q_check_status( array( 'e01q1', 'e01q2', 'e01q9' ) );
                break;
            case 'e01q7':
                $r = $this->q_check_status( array( 'e01q8' ) );
                break;
            case 'e01q8':
                $r = $this->q_check_status( array( 'e01q9', 'e01q7' ) );
                break;
            case 'e01q9':
                $r = $this->q_check_status( array( 'e01q0', 'e01q51', 'e01q52', 'e01q8' ) );
                break;
            case 'e03q1':
            case 'e03q2':
                $r = $this->q_check_status( array( 'e03q0', 'e03q51', 'e03q52' ) );
                break;
            case 'e03q51':
            case 'e03q52':
                $r = $this->q_check_status( array( 'e03q1', 'e03q2', 'e03q9' ) );
                break;
            case 'e03q7':
                $r = $this->q_check_status( array( 'e03q8' ) );
                break;
            case 'e03q8':
                $r = $this->q_check_status( array( 'e03q9', 'e03q7' ) );
                break;
            case 'e03q9':
                $r = $this->q_check_status( array( 'e03q0', 'e03q51', 'e03q52', 'e03q8' ) );
                break;
            case 'e05q1':
            case 'e05q2':
                $r = $this->q_check_status( array( 'e05q0', 'e05q51', 'e05q52' ) );
                break;
            case 'e05q7':
                $r = $this->q_check_status( array( 'e05q8' ) );
                break;
            case 'e05q51':
            case 'e05q52':
                $r = $this->q_check_status( array( 'e05q1', 'e05q2', 'e05q9' ) );
                break;
            case 'e05q8':
                $r = $this->q_check_status( array( 'e05q9', 'e05q7' ) );
                break;
            case 'e05q9':
                $r = $this->q_check_status( array( 'e05q0', 'e05q51', 'e05q52', 'e05q8' ) );
                break;
            case 'e06q1':
            case 'e06q2':
            case 'e06q3':
            case 'e06q10':
                $r = $this->q_check_status( array( 'e06q0', 'e06q51', 'e06q52' ) );
                break;
            case 'e06q51':
            case 'e06q52':
                $r = $this->q_check_status( array( 'e06q1', 'e06q2', 'e06q3', 'e06q10' ) );
                break;
            case 'e08q51':
            case 'e08q52':
                $r = $this->q_check_status( array( 'e08q1', 'e08q2', 'e08q3', 'e08q9' ) );
                break;
            case 'e08q1':
            case 'e08q2':
            case 'e08q3':
                $r = $this->q_check_status( array( 'e08q0', 'e08q51', 'e08q52' ) );
                break;
            case 'e09q1':
            case 'e09q3':
                $r = $this->q_check_status( array( 'e09q0', 'e09q51', 'e09q52' ) );
                break;
            case 'e09q51':
            case 'e09q52':
                $r = $this->q_check_status( array( 'e09q1', 'e09q3', 'e09q9' ) );
                break;
            case 'e09q7':
                $r = $this->q_check_status( array( 'e09q8' ) );
                break;
            case 'e09q8':
                $r = $this->q_check_status( array( 'e09q9', 'e09q7' ) );
                break;
            case 'e09q9':
                $r = $this->q_check_status( array( 'e09q0', 'e09q51', 'e09q52', 'e09q8' ) );
                break;
            case 'e11q1':
            case 'e11q3':
                $r = $this->q_check_status( array( 'e11q0', 'e11q51', 'e11q52' ) );
                break;
            case 'e11q51':
            case 'e11q52':
                $r = $this->q_check_status( array( 'e11q1', 'e11q3', 'e11q9' ) );
                break;
            case 'e11q7':
                $r = $this->q_check_status( array( 'e11q8' ) );
                break;
            case 'e11q8':
                $r = $this->q_check_status( array( 'e11q9', 'e11q7' ) );
                break;
            case 'e11q9':
                $r = $this->q_check_status( array( 'e11q0', 'e11q51', 'e11q52', 'e11q8' ) );
                break;
            case 'e12q1':
            case 'e12q3':
                $r = $this->q_check_status( array( 'e12q0', 'e12q51', 'e12q52' ) );
                break;
            case 'e12q51':
            case 'e12q52':
                $r = $this->q_check_status( array( 'e12q1', 'e12q3', 'e12q9' ) );
                break;
            case 'e12q7':
                $r = $this->q_check_status( array( 'e12q8' ) );
                break;
            case 'e12q8':
                $r = $this->q_check_status( array( 'e12q9', 'e12q7' ) );
                break;
            case 'e12q9':
                $r = $this->q_check_status( array( 'e12q0', 'e12q51', 'e12q52', 'e12q8' ) );
                break;
            default :
                $r = FALSE;
                break;
        }
        return $r;
    }

    private function interlock_1_4( $q ) {
        switch ( $q ) {
            case 'e01q1':
            case 'e03q1':
            case 'e05q1':
            case 'e09q1':
            case 'e11q1':
            case 'e12q1':
            case 'e04q1':
            case 'e06q1':
            case 'e06q10':
            case 'e08q1':
                $r = $this->q_check_status( array( 'e04q15' ) );
                break;
            case 'e01q2':
            case 'e03q2':
            case 'e05q2':
            case 'e02q2':
            case 'e06q2':
            case 'e08q2':
                $r = $this->q_check_status( array( 'e02q25' ) );
                break;
            case 'e09q3':
            case 'e11q3':
            case 'e12q3':
            case 'e06q3':
            case 'e08q3':
            case 'e10q3':
                $r = $this->q_check_status( array( 'e10q35' ) );
                break;
            case 'e01q7':
            case 'e03q7':
            case 'e05q7':
            case 'e09q7':
            case 'e11q7':
            case 'e12q7':
            case 'e07q7':
            case 'e08q9':
                $r = $this->q_check_status( array( 'e07q75' ) );
                break;
            default :
                $r = false;
                break;
        }

        return $r;
    }

    private function interlock_5() {
        $array = array(
             array( 'e01q1', 'e01q2' ), array( 'e03q1', 'e03q2' ), array( 'e05q1', 'e05q2' ),
             array( 'e08q1', 'e08q2' ), array( 'e08q1', 'e08q3' ), array( 'e08q2', 'e08q3' ),
             array( 'e09q1', 'e09q3' ), array( 'e11q1', 'e11q3' ), array( 'e12q1', 'e12q3' )
        );

        if ( $this->q_check_status_arr( $array ) ) {
            return true;
        }
        return false;
    }

    private function interlock_6_7( $q, $s = true ) {

        $shini = $this->busbar_couple( $q, $s );
        switch ( $shini ) {
            case 1:
                if ( $this->q_check_status( array( 'e06q10', 'e06q2' ), false ) ) {
                    return true;
                }
                break;
            case 2:
                if ( $this->q_check_status( array( 'e06q1', 'e06q3' ), false ) ) {
                    return true;
                }
                break;
            case 3:
                if ( $this->q_check_status( array( 'e06q2', 'e06q3' ), false ) ) {
                    return true;
                }
                break;
            default:
                return false;
        }
        if ( $this->q_check_status( array( 'e06q0' ), false ) ) {
            return true;
        }
        return false;
    }

    private function interlock_8() {
        $array = array( 'e01q1', 'e03q1', 'e05q1', 'e06q1', 'e06q10', 'e08q1', 'e09q1', 'e11q1', 'e12q1' );
        if ( $this->q_check_status( $array ) ) {
            return true;
        }
        return false;
    }

    private function interlock_9() {
        $array = array( 'e01q2', 'e03q2', 'e05q2', 'e06q2', 'e08q2' );
        if ( $this->q_check_status( $array ) ) {
            return true;
        }
        return false;
    }

    private function interlock_10() {
        $array = array( 'e06q3', 'e08q3', 'e09q3', 'e11q3', 'e12q3' );
        if ( $this->q_check_status( $array ) ) {
            return true;
        }
        return false;
    }

    private function interlock_11() {
        return ($this->interlock_12() || $this->interlock_13());
    }

    private function interlock_12() {
        $array = array( 'e01q7', 'e03q7', 'e05q7', 'e09q7', 'e11q7', 'e12q7' );
        if ( $this->q_check_status( $array ) ) {
            return true;
        }
        return false;
    }

    private function interlock_13() {
        $array = array( 'e08q9' );
        if ( $this->q_check_status( $array ) ) {
            return true;
        }
        return false;
    }

    private function algorithm_e01q1( $q ) {
        $status = $this->get_status( $this->breaker_couple( $q ) );

        if ( $status ) {

            if ( $this->interlock_6_7( $q ) ) {
                return FALSE;
            }
        } else {

            $status = $this->off_line_status( $q );

            if ( $status ) {

                return FALSE;
            }
        }
        return !$this->interlock_1_4( $q );
    }

    private function algorithm_q51q52( $q ) {
        return !$this->off_line_status( $q );
    }

    private function algorithm_e01q7( $q ) {
        if ( $this->off_line_status( $q ) || $this->interlock_1_4( $q ) ) {
            return FALSE;
        }
        if ( $this->command ) {
            return (!$this->interlock_12() && !$this->interlock_13());
        } else {
            return !$this->interlock_13();
        }
    }

    private function algorithm_e06q1( $q, $i = true ) {
        if ( $this->q_check_status( array( $this->breaker_couple( $q ) ) ) ) {
            return FALSE;
        } else {
            if ( $this->off_line_status( $q ) ) {
                return FALSE;
            }
        }
        return !($this->interlock_1_4( $q ) || $this->q_check_status( array( $this->breaker_couple( $q, $i ) ) ));
    }

    private function algorithm_e06q0( $q ) {
        if ( $this->command ) {
            return true;
        } else {
            return !$this->interlock_5();
        }
    }

    private function algorithm_e08q1( $q ) {

        if ( $this->interlock_1_4( $q ) ) {

            return false;
        } elseif ( $this->get_status( $this->breaker_couple( $q ) ) ) {

            if ( $this->interlock_6_7( $q ) ) {

                return FALSE;
            }
        } elseif ( $this->get_status( $this->breaker_couple( $q, false ) ) ) {

            if ( $this->interlock_6_7( $q, false ) ) {

                return FALSE;
            }
        } else {

            if ( $this->off_line_status( $q ) ) {

                return FALSE;
            }
        }
        return true;
    }

    private function interlock_e2_e10( $q ) {
        switch ( $q ) {
            case 'e02q2':
                $q_inter = 'e02q9';
                break;
            case 'e04q1':
                $q_inter = 'e04q9';
                break;
            case 'e10q3':
                $q_inter = 'e10q9';
                break;
            case 'e07q7':
                $q_inter = 'e07q9';
                break;
            default:
                return false;
        }
        if ( $this->q_check_status( array( $q_inter ) ) ) {
            return true;
        }
        return false;
    }

    private function reload_objects() {

        if ( $this->mode ) {

            $this->return['debug'][] = 'mode: ' . $this->mode;

            $sql = 'SELECT object,status,position FROM objects';
            if ( $sql_res = $this->sql_con->prepare( $sql ) ) {

                $sql_res->execute();
                $sql_res->bind_result( $name, $status, $position );
                while ( $sql_res->fetch() ) {
                    $this->return['obj'][$name] = array(
                         'status'   => $status,
                         'position' => $position
                    );
                    $this->return['obj'][$name]['source'] = $this->is_source( $name, $status );
                }

                $sql_res->close();
            } else {
                $this->return['error'][] = $sql_res;
                $this->return['obj'] = false;
            }
        } else {
            foreach ( $this->init_q as $q ) {
                if ( !isset( $this->return['obj'][$q] ) ) {

                    $this->return['obj'][$q]['status'] = 0;
                    $this->return['obj'][$q]['position'] = 0;
                }
            }
        }

        foreach ( $this->init_line as $l ) {

            if ( !array_key_exists( $l, $this->return['obj'] ) ) {
                $this->return['obj'][$l]['status'] = 0;
                $this->return['obj'][$l]['position'] = 0;
            }
        }

        foreach ( $this->source_lines as $line ) {

            if ( isset( $this->return['obj'][$line] ) ) {
                if ( $this->return['obj'][$line]['status'] ) {
                    $this->set_line_status( $line );
                }
            }
        }

        foreach ( $this->q_earthing as $q ) {
            if ( $this->return['obj'][$q]['status'] ) {
                $this->chek_q_status( $q, false, true );
            }
        }
    }
    /*
     * Fatal error: Maximum function nesting level of '1500' reached, aborting!
     * E05 - sourse; bb1 - 1; E09 - 1; E09Q7 - 1; E06 - 1 (bb2 = bb3); E08 - 1 (Q2 & Q3 - 1)
     */

    private function set_line_status( $line, $q = false, $earthing = false ) {

        if ( $q !== false && isset( $this->return['obj'][$line] ) && $this->return['obj'][$line]['status'] ) {
            return;
        } elseif ( !isset( $this->return['obj'][$line] ) ||
                  !$this->return['obj'][$line]['status'] && isset( $this->return['obj'][$q] ) && $this->return['obj'][$q]['status'] ) {
            if ( $earthing ) {
                $this->return['obj'][$line]['status'] = 'e';
                $this->return['obj'][$line]['position'] = 'e';
            } else {
                $this->return['obj'][$line]['status'] = $this->return['obj'][$q]['status'];
            }
        }

        $line_conection = $this->l_conection( $line );

        if ( $line_conection === false ) {
            return;
        }

        if ( $q !== false ) {
            $key = array_search( $q, $line_conection );
            if ( $key !== false ) {
                unset( $line_conection[$key] );
                if ( empty( $line_conection ) ) {
                    return;
                }
            }
        }

        foreach ( $line_conection as $line_conection_q ) {

            if ( !$this->return['obj'][$line_conection_q]['status'] ) {

                continue;
            }

            $q_conections = $this->q_conection( $line_conection_q );
            if ( $q_conections === false ) {

                break;
            }

            $key = array_search( $line, $q_conections );
            if ( $key !== false ) {
                unset( $q_conections[$key] );
                if ( empty( $q_conections ) ) {
                    break;
                }
            }

            foreach ( $q_conections as $q_conection_line ) {

                $this->set_line_status( $q_conection_line, $line_conection_q, $earthing );
            }
        }
    }

    private function chek_q_status( $q, $line = false, $earthing = false ) {

        if ( !$this->return['obj'][$q]['status'] ) {

            return;
        }

        $q_conections = $this->q_conection( $q );
        if ( $q_conections === false ) {

            return;
        }
        if ( $line !== false ) {
            $key = array_search( $line, $q_conections );
            if ( $key !== false ) {
                unset( $q_conections[$key] );
                if ( empty( $q_conections ) ) {
                    return;
                }
            }
        }

        foreach ( $q_conections as $q_conection_line ) {

            $this->set_line_status( $q_conection_line, $q, $earthing );
        }
    }

    private function approve_command( $q ) {

        if ( $this->command != $this->get_status( $q ) ) {
            switch ( $q ) {
                case 'e02q2':
                case 'e04q1':
                case 'e10q3':
                case 'e07q7':
                    $ret = !($this->interlock_e2_e10( $q ) || $this->interlock_1_4( $q ));
                    break;
                case 'e02q25':
                    $ret = !$this->interlock_9();
                    break;
                case 'e04q15':
                    $ret = !$this->interlock_8();
                    break;
                case 'e10q35':
                    $ret = !$this->interlock_10();
                    break;
                case 'e07q75':
                    $ret = !$this->interlock_11();
                    break;
                case 'e02q9':
                    $ret = !$this->q_check_status( array( 'e02q2' ) );
                    break;
                case 'e04q9':
                    $ret = !$this->q_check_status( array( 'e04q1' ) );
                    break;
                case 'e10q9':
                    $ret = !$this->q_check_status( array( 'e10q3' ) );
                    break;
                case 'e07q9':
                    $ret = !$this->q_check_status( array( 'e07q7' ) );
                    break;
                case 'e06q0':
                    $ret = $this->algorithm_e06q0( $q );
                    break;
                case 'e06q1':
                case 'e06q10':
                    $ret = $this->algorithm_e06q1( $q, false );
                    break;
                case 'e06q2':
                case 'e06q3':
                    $ret = $this->algorithm_e06q1( $q );
                    break;
                case 'e06q51':
                case 'e06q52':
                    $ret = !$this->off_line_status( $q );
                    break;
                case 'e08q1':
                case 'e08q2':
                case 'e08q3':
                    $ret = $this->algorithm_e08q1( $q );
                    break;
                case 'e08q9':
                    $ret = !($this->q_check_status( array( 'e08q0', 'e08q51', 'e08q52' ) ) || $this->interlock_1_4( $q ));
                    break;
                case 'e01q7':
                case 'e03q7':
                case 'e05q7':
                case 'e09q7':
                case 'e11q7':
                case 'e12q7':
                    $ret = $this->algorithm_e01q7( $q );
                    break;
                case 'e01q8':
                case 'e01q9':
                case 'e03q8':
                case 'e03q9':
                case 'e05q8':
                case 'e05q9':
                case 'e09q8':
                case 'e09q9':
                case 'e11q8':
                case 'e11q9':
                case 'e12q8':
                case 'e12q9':
                    $ret = !$this->off_line_status( $q );
                    break;
                case 'e01q0':
                case 'e03q0':
                case 'e05q0':
                case 'e08q0':
                case 'e09q0':
                case 'e11q0':
                case 'e12q0':
                    $ret = true;
                    break;
                case 'e01q1':
                case 'e01q2':
                case 'e03q1':
                case 'e03q2':
                case 'e05q1':
                case 'e05q2':
//                case 'e06q2':
//                case 'e06q3':
                case 'e09q1':
                case 'e09q3':
                case 'e11q1':
                case 'e11q3':
                case 'e12q1':
                case 'e12q3':
                    $ret = $this->algorithm_e01q1( $q );
                    break;
                case 'e01q51':
                case 'e01q52':
                case 'e03q51':
                case 'e03q52':
                case 'e05q51':
                case 'e05q52':
                case 'e08q51':
                case 'e08q52':
                case 'e09q51':
                case 'e09q52':
                case 'e11q51':
                case 'e11q52':
                case 'e12q51':
                case 'e12q52':
                    $ret = $this->algorithm_q51q52( $q );
                    break;

                default:
                    $ret = true;
                    break;
            }
        } else {
            $ret = true;
        }
        return $ret;
    }
}
