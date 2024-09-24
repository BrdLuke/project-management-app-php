<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage</title>
    <link rel="stylesheet" href="styles.css">
    <?php
    $connessione = new mysqli();
    try {
        $connessione->connect("localhost", "root", "", "Azienda");
    } catch (Exception $e) {
        echo "Errore";
    }
    ?>
</head>

<body>

    <?php
    include_once ("./navbar.php");
    ?>

    <div class="container-progetti">
        <?php
        if (isset($_SESSION["cliente"])) {
            echo "<h1>Benvenuto, " . $_SESSION["cliente"]["nome"] . "</h1>";
            $emailClienteCorrente = $_SESSION["cliente"]["email"];

            $query = "SELECT Progetto.* FROM Progetto, Cliente WHERE Cliente.Email = Progetto.Cliente AND Cliente.Email = '$emailClienteCorrente'";

            $risultato = $connessione->query($query);

            if ($risultato->num_rows > 0) {
                while ($record = $risultato->fetch_assoc()) {
                    $nomeProgetto = $record["Nome"];
                    $descrizioneProgetto = $record["Descrizione"];
                    $amministratoreProgetto = $record["Amministratore"];
                    $statoProgetto = $record["Stato"];

                    echo "<section class='progetti'>";
                    echo "<div class='progetto-wrapper'>";
                    echo "<div class='progetto-body'>";
                    echo "<h2>$nomeProgetto</h2>";
                    echo "<p class='info-progetto'>";
                    if ($statoProgetto == "Avviato") {
                        echo "<b>Stato progetto</b>: <span class='box blue'></span></span> $statoProgetto<br>";
                    } else if ($statoProgetto == "In corso") {
                        echo "<b>Stato progetto</b>: <span class='box orange'></span></span> $statoProgetto<br>";
                    } else if ($statoProgetto == "Completato") {
                        echo "<b>Stato progetto</b>: <span class='box green'></span></span> $statoProgetto<br>";
                    }

                    $queryAmministratori = "SELECT DISTINCT Nome, Cognome FROM Amministratore WHERE CF = '$amministratoreProgetto'";
                    $risultatoAmministratori = $connessione->query($queryAmministratori);

                    if ($risultatoAmministratori->num_rows > 0) {
                        while ($record = $risultatoAmministratori->fetch_assoc()) {
                            $nomeAmministratore = $record["Nome"];
                            $cognomeAmministratore = $record["Cognome"];
                        }
                    }

                    echo "<b>Amministratore del progetto</b>: $cognomeAmministratore $nomeAmministratore<br>";
                    echo "<b>Descrizione del progetto</b>: $descrizioneProgetto <br>";
                    echo "</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</section>";
                }
            }

            echo "<form method='POST'>";
            echo "<button id='add_progetto' name='add_progetto'>Aggiungi progetto</button>";
            echo "</form>";
        } else {
            echo "<h1>Benvenuto</h1>";
            echo "<p>Sembra che non sia registrato/loggato, passa da <a href='./login.html'>qui</a>";
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["add_progetto"])) {
                header("Location: add_progetto.php");
            }
        }
        ?>
    </div>


    <?php
    $connessione->close();
    ?>
</body>

</html>