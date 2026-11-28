<?php
// ---- EDIT THESE VALUES ----
define('DB_CONN',   'localhost/ORCLPDB');  // host/service  (XE: localhost/XEPDB1 or localhost/XE)
define('DB_SCHEMA', 'nasib');         // the Oracle user that OWNS the gym tables
define('DB_CHARSET','AL32UTF8');

// The two Oracle users from your SQL file
define('ROLES', ['manager', 'trainer']);

function db_connect($user, $pass) {
    $conn = @oci_connect($user, $pass, DB_CONN, DB_CHARSET);
    if (!$conn) {
        $e = oci_error();
        throw new Exception($e['message'] ?? 'Cannot connect to Oracle');
    }
    return $conn;
}
