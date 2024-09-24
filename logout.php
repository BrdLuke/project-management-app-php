<?php
session_start();
if (isset($_SESSION["cliente"])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    die();
}
?>