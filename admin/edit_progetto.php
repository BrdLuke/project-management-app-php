<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Stato Progetto - Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <?php
    include_once ("./navbar.php");

    $connessione = new mysqli();
    try {
        $connessione->connect("localhost", "root", "", "Azienda");
    } catch (Exception $e) {
        echo "Errore";
    }
    ?>

    <div class="container">
        <h1>Modifica Progetto</h1>
        <?php
        $idProgetto = $_GET["progetto"];
        if (isset($idProgetto) && $idProgetto != null && is_numeric($idProgetto)) {
            echo "Inserisci i dati per aggiornare il progetto";

            $queryProgetto = "SELECT DISTINCT Nome, Descrizione, Stato FROM Progetto WHERE ID = '$idProgetto'";
            $risultatoProgetto = $connessione->query($queryProgetto);

            if ($risultatoProgetto->num_rows == 1) {
                while ($recordProgetto = $risultatoProgetto->fetch_assoc()) {
                    $nomeProgetto = $recordProgetto["Nome"];
                    $descrizioneProgetto = $recordProgetto["Descrizione"];
                    $statoProgetto = $recordProgetto["Stato"];
                }
            } else {
                header("Location: progetti.php");
            }

        } else {
            header("Location: progetti.php");
        }
        ?>
        <br>
        <form method="POST">
            <div class="input">
                <p>Nome Progetto: </p>
                <?php
                echo "<input type='text' name='nome_progetto' id='nome' value='$nomeProgetto' readonly>";
                ?>
            </div>

            <div class="input">
                <p>Descrizione: </p>
                <?php
                echo "<input type='text' name='descrizione_progetto' id='descrizione_progetto' value='" . $descrizioneProgetto . "' readonly>";
                ?>
            </div>

            <div class="input">
                <p>Stato Progetto: </p>
                <select name="stato" id="stato">
                    <?php
                    $queryStato = "SELECT DISTINCT Tipo FROM Stato";
                    $risultato = $connessione->query($queryStato);

                    if ($risultato->num_rows > 0) {
                        while ($record = $risultato->fetch_assoc()) {
                            $stato = $record["Tipo"];
                            echo "<option name='amministratore' value='$stato' id='opzione'>$stato</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <button>Modifica dati progetto</button>
            <p style="font-style: italic;"><a href="./progetti.php">Ritorna ai progetti</a></p>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $statoProgetto = $_POST["stato"];
        
        try {
            $stmt = $connessione->prepare("UPDATE Progetto SET Stato = ? WHERE ID = ?");
            $stmt->bind_param("si", $statoProgetto, $idProgetto);

            $risultato = $stmt->execute();

            if ($risultato === TRUE) {
                header("Location: index.php");
            } else {
                header("Location: error.php");
            }
            $connessione->close();
        } catch (Exception $e) {
            echo "<p style='text-align: center; color: red; font-weight: bold; margin-top: 1rem'>Errore durante l'invio ... Riprovare";
            echo "</p>";
        }
    }

    ?>

</body>

</html>