<?php
session_start();
if (isset($_SESSION["dipendente"])) {
    session_destroy();
    header("Location: index.php");
    die();
}
?>