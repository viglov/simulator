<?php

require_once '../config.php';
require_once 'functions.php';

if ( $_POST['action'] == 'stend' ) {

    require_once 'class-stend.php';

    if ( isset( $_POST['mode'] ) && $_POST['mode'] == 1 ) {
        $mode = true;
    } else {
        $mode = false;
    }

    if ( isset( $_POST['ex'] ) ) {
        require_once 'exersise.php';
        $ex = new Exersise();
    }

    if ( isset( $_POST['obj'] ) ) {

        $objs = $_POST['obj'];
    } elseif ( isset( $_POST['ex'] ) ) {

        $objs = $ex->get_start( $_POST['ex'] );
    } else {

        $objs = array();
    }

    $stend = new Stend( $mode, $objs );

    if ( isset( $_POST['name'] ) && isset( $_POST['com'] ) ) {

        $stend->update_obj( $_POST['name'], $_POST['com'] );
    } elseif ( isset( $_POST['val'] ) ) {

        $stend->reset( $_POST['val'] );
    }

    $return = $stend->get_return();

    if ( isset( $_POST['ex'] ) ) {

        $objs = $ex->get_end( $_POST['ex'] );
        $t = array();

        foreach ( $objs as $obj => $val ) {
            $result = array_diff_assoc( $val, $return['obj'][$obj] );
            if ( !empty( $result ) ) {
                $t[$obj] = $result;
            }
        }
        if ( empty( $t ) ) {
            $return['success'][] = 'Task completed';
        }

        $return['end'] = $objs;
        $return['steps'] = $ex->get_steps( $_POST['ex'] );
    }
}

echo json_encode( $return );
die();
