<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progetti</title>
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
        <h1>Progetti richiesti</h1>
        <?php
        if (isset($_SESSION["cliente"])) {
            $emailClienteCorrente = $_SESSION["cliente"]["email"];

            $query = "SELECT Progetto.ID AS IDProgetto, Progetto.Nome AS NomeProgetto, Progetto.Descrizione AS DescrizioneProgetto, Progetto.Stato AS StatoProgetto, Amministratore.Nome AS NomeAmministratore, Amministratore.Cognome AS CognomeAmministratore  FROM Progetto, Cliente, Amministratore WHERE Cliente.Email = Progetto.Cliente AND Amministratore.CF = Progetto.Amministratore AND Cliente.Email = '$emailClienteCorrente'";

            $risultato = $connessione->query($query);

            if ($risultato->num_rows > 0) {
                while ($record = $risultato->fetch_assoc()) {
                    $idProgetto = $record["IDProgetto"];
                    $nomeProgetto = $record["NomeProgetto"];
                    $descrizioneProgetto = $record["DescrizioneProgetto"];
                    $statoProgetto = $record["StatoProgetto"];

                    $nomeAmministratore = $record["NomeAmministratore"];
                    $cognomeAmministratore = $record["CognomeAmministratore"];

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

                    echo "<b>Amministratore del progetto</b>: $cognomeAmministratore $nomeAmministratore<br>";
                    echo "<b>Descrizione del progetto</b>: $descrizioneProgetto <br>";
                    echo "</p>";

                    echo "<form method='POST' class='progetto_buttons'>";
                    echo "<input value='$idProgetto' name='idProgetto' hidden></input>";
                    echo "<button name='edit_progetto' id='edit_progetto'>Modifica progetto</button>";
                    echo "<button name='delete_progetto' id='delete_progetto'>Elimina progetto</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                    echo "</section>";
                }
            } else {
                echo "Nessun progetto richiesto. <br> Vai alla sezione dedicata (Home) per aggiungere un progetto.";
            }
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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $idProgetto = $_POST["idProgetto"];

        if (isset($_POST["edit_progetto"])) {
            header("Location: edit_progetto.php?progetto=$idProgetto");
        } else if (isset($_POST["delete_progetto"])) {
            header("Location: delete_progetto.php?progetto=$idProgetto");
        } else {
            echo "Errore";
        }
    }
    $connessione->close();
    ?>
</body>

</html>