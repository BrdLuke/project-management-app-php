<?php
$connessione = new mysqli();
$connessione->connect("localhost", "root", "", "Azienda");

if ($connessione->error) {
    $connessione->connect_error;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cf = $connessione->real_escape_string(strtoupper($_POST["cf"]));
    $email = $connessione->real_escape_string($_POST["email"]);
    $password = $connessione->real_escape_string($_POST["password"]);

    if (strlen($cf) == 0 && strlen($email) == 0 && strlen($password) == 0) {
        header("Location: error.php");
        die();
    }

    try {
        $stmt = $connessione->prepare("SELECT CF, Cognome, Nome, Email FROM Amministratore WHERE CF = ? AND Email = ? AND Password = ?");
        $stmt->bind_param("sss", $cf, $email, $password);

        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($cf, $cognome, $nome, $email);
            $stmt->fetch();

            session_set_cookie_params(7200);
            session_start();

            $_SESSION["amministratore"] = array(
                "cf" => $cf,
                "cognome" => $cognome,
                "nome" => $nome,
                "email" => $email
            );

            header("Location: index.php");
        } else {
            header("Location: error.php");
            die();
        }

        $connessione->close();
    } catch (Exception $e) {
        header("Location: error.php");
    }
}

?>