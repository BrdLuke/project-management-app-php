<?php
$connessione = new mysqli();
$connessione->connect("localhost", "root", "", "Azienda");

if ($connessione->error) {
    $connessione->connect_error;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cognome = $connessione->real_escape_string(ucfirst($_POST["cognome"]));
    $nome = $connessione->real_escape_string(ucfirst($_POST["nome"]));
    $email = $connessione->real_escape_string($_POST["email"]);
    $password = $connessione->real_escape_string($_POST["password"]);

    if (strlen($cognome) == 0 && strlen($nome) == 0 && strlen($email) == 0 && strlen($password) == 0) {
        header("Location: error.php");
        die();
    } else {
        try {
            $stmt = $connessione->prepare("INSERT INTO Cliente(Cognome, Nome, Email, Password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $cognome, $nome, $email, $password);

            $risultato = $stmt->execute();

            if ($risultato === TRUE) {
                header("Location: index.php");
            } else {
                header("Location: error.php");
                die();
            }

            session_start();

            $_SESSION["cliente"] = array(
                "cognome" => $cognome,
                "nome" => $nome,
                "email" => $email
            );

            $connessione->close();
        } catch (Exception $e) {
            header("Location: error.php");
        }

    }
}

?>