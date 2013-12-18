<?php
$mysqli = new mysqli("localhost", "root", "123456qwe", "sanxing");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
$mysqli->query("SET NAMES utf8");
?>