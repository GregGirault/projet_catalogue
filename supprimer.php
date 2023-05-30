<?php
session_start();
require_once('connect.php');

if ($_GET && isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM `` WHERE `id` = :id";
    $query = $db->prepare($sql);
    $query->bindValue(":id", $id, PDO::PARAM_INT);
    $query->execute();
    $ = $query->fetch
}
?>