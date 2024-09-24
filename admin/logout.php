<?php
session_start();
if (isset($_SESSION["amministratore"])) {
    session_destroy();
    header("Location: index.php");
    die();
}
?>