<?php

define('DB_CONN',   'localhost/ORCLPDB');  
define('DB_SCHEMA', 'nasib');      
define('DB_CHARSET','AL32UTF8');

// The two Oracle users from our SQL file
define('ROLES', ['manager', 'trainer']);

function db_connect($user, $pass) {
    $conn = @oci_connect($user, $pass, DB_CONN, DB_CHARSET);
    if (!$conn) {
        $e = oci_error();
        throw new Exception($e['message'] ?? 'Cannot connect to Oracle');
    }
    return $conn;
}
