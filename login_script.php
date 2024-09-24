<?php
$connessione = new mysqli();
$connessione->connect("localhost", "root", "", "Azienda");

if ($connessione->error) {
    $connessione->connect_error;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $connessione->real_escape_string($_POST["email"]);
    $password = $connessione->real_escape_string($_POST["password"]);

    if (strlen($email) == 0 && strlen($password) == 0) {
        header("Location: error.php");
        die();
    }

    try {
        $stmt = $connessione->prepare("SELECT Cognome, Nome, Email FROM Cliente WHERE Email = ? AND Password = ?");
        $stmt->bind_param("ss", $email, $password);

        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($congome, $nome, $email);
            $stmt->fetch();

            session_start();

            $_SESSION["cliente"] = array(
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