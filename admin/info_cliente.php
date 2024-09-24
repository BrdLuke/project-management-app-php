<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informazioni Cliente - Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <?php
    session_start();

    include ("./navbar.php");

    if (!isset($_SESSION["amministratore"])) {
        echo "<div style='text-align: center; margin-top: 5rem;'>";
        echo "<h1 style='font-size: 4rem;'>Benvenuto</h1>";
        echo "<p>Sembra che non sia registrato/loggato, passa da <a href='./login.html'>qui</a>";
        echo "</div>";
    } else {

        $connessione = new mysqli();
        try {
            $connessione->connect("localhost", "root", "", "Azienda");
        } catch (Exception $e) {
            echo "Errore";
        }

        $emailCliente = $_GET["email"];
        if (isset($emailCliente) && $emailCliente != null) {
            echo "<div style='text-align: center; margin-top: 5rem;'>";
            echo "<h1 style='font-size: 4rem;'>Informazioni Cliente</h1>";

            $stmt = $connessione->prepare("SELECT Cliente.Nome, Cliente.Cognome, Cliente.Email, Progetto.Nome, Progetto.Descrizione FROM Cliente INNER JOIN Progetto ON Cliente.Email = Progetto.Cliente WHERE Email = ?");
            $stmt->bind_param("s", $emailCliente);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($nomeCliente, $cognomeCliente, $emailCliente, $progettoNome, $progettoDescrizione);
                $stmt->fetch();

                echo "<table>";
                echo "<tr><th>Nome cliente</th><td>$nomeCliente</td></tr>";
                echo "<tr><th>Cognome cliente</th><td>$cognomeCliente</td></tr>";
                echo "<tr><th>Email cliente</th><td>$emailCliente</td></tr>";
                echo "<tr><th>Progetto richiesto</th><td>$progettoNome</td></tr>";
                echo "<tr><th>Descrizione progetto</th><td>$progettoDescrizione</td></tr>";
                echo "</table>";
            } else {
                header("Location: index.php");
            }

            echo "</div>";
        } else {
            echo header("Location: index.php");
        }
        $connessione->close();
    }
    ?>

    <a style="display:block; margin-left:auto; margin-right: auto; text-align: center;" href="./index.php">Ritorna
        home</a>
</body>

</html>