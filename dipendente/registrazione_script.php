<?php
$connessione = new mysqli();
$connessione->connect("localhost", "root", "", "Azienda");

if ($connessione->error) {
    $connessione->connect_error;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cf = $connessione->real_escape_string(strtoupper($_POST["cf"]));
    $cognome = $connessione->real_escape_string(ucfirst($_POST["cognome"]));
    $nome = $connessione->real_escape_string(ucfirst($_POST["nome"]));
    $numeroTelefono = $connessione->real_escape_string($_POST["num_tel"]);
    $email = $connessione->real_escape_string($_POST["email"]);
    $skills = $connessione->real_escape_string($_POST["skills"]);
    $password = $connessione->real_escape_string($_POST["password"]);

    if (ctype_digit($numeroTelefono) === FALSE && strlen($cf) == 0 && strlen($cognome) == 0 && strlen($nome) == 0 && strlen($numeroTelefono) == 0 && strlen($email) == 0 && strlen($password) == 0) {
        header("Location: error.php");
        die();
    } else {
        try {
            $stmt = $connessione->prepare("INSERT INTO Dipendente(CodD, Cognome, Nome, NumeroTelefono, Email, Skills, Password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $cf, $cognome, $nome, $numeroTelefono, $email, $skills, $password);

            $risultato = $stmt->execute();

            if ($risultato === TRUE) {
                header("Location: index.php");
            } else {
                header("Location: error.php");
                die();
            }

            session_start();
            $_SESSION["dipendente"] = array(
                "cf" => $cf,
                "cognome" => $cognome,
                "nome" => $nome,
                "email" => $email,
                "skills" => $skills
            );

            $connessione->close();
        } catch (Exception $e) {
            header("Location: error.php");
        }

    }
}

?>