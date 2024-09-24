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
        $stmt = $connessione->prepare("SELECT CodD, Cognome, Nome, Email, Skills FROM Dipendente WHERE Email = ? AND Password = ?");
        $stmt->bind_param("ss", $email, $password);

        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($cf, $cognome, $nome, $email, $skills);
            $stmt->fetch();

            session_set_cookie_params(7200);
            session_start();

            $_SESSION["dipendente"] = array(
                "cf" => $cf,
                "cognome" => $cognome,
                "nome" => $nome,
                "email" => $email,
                "skills" => $skills
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