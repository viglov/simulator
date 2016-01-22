<?php

require_once dirname( __FILE__ ) . '/config.php';

require_once dirname( __FILE__ ) . '/header.php';

$page = '';
if ( isset( $_GET['p'] ) ) {
    $page = $_GET['p'];
}

if ( $page == 'log' ) {
    require_once dirname( __FILE__ ) . '/pages/log.php';
} elseif ( $page == 'doc' ) {
    require_once dirname( __FILE__ ) . '/pages/doc.php';
} elseif ( $page == 'cr' ) {
    require_once dirname( __FILE__ ) . '/pages/credits.php';
} else {
    require_once dirname( __FILE__ ) . '/scheme.php';
    require_once dirname( __FILE__ ) . '/js.php';
}

require_once dirname( __FILE__ ) . '/footer.php';
