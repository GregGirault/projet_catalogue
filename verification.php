<?php
session_start();
if (isset($_POST['username']) && isset($_POST['password'])) {
    $db_host = 'localhost';
    $db_name = 'catalogue';
    $db_username = 'root';
    $db_password = '';
}
?>